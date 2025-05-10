<?php
/*
 * Copyright (c) 2025 AltumCode (https://altumcode.com/)
 *
 * This software is licensed exclusively by AltumCode and is sold only via https://altumcode.com/.
 * Unauthorized distribution, modification, or use of this software without a valid license is not permitted and may be subject to applicable legal actions.
 *
 * 🌍 View all other existing AltumCode projects via https://altumcode.com/
 * 📧 Get in touch for support or general queries via https://altumcode.com/contact
 * 📤 Download the latest version via https://altumcode.com/downloads
 *
 * 🐦 X/Twitter: https://x.com/AltumCode
 * 📘 Facebook: https://facebook.com/altumcode
 * 📸 Instagram: https://instagram.com/altumcode
 */

namespace Altum\Controllers;

use Altum\Logger;
use Altum\Models\User;

defined('ALTUMCODE') || die();

class Cron extends Controller {

    public function index() {
        die();
    }

    private function initiate() {
        /* Initiation */
        set_time_limit(0);

        /* Make sure the key is correct */
        if(!isset($_GET['key']) || (isset($_GET['key']) && $_GET['key'] != settings()->cron->key)) {
            die();
        }

        /* Send webhook notification if needed */
        if(settings()->webhooks->cron_start) {
            $backtrace = debug_backtrace();
            \Unirest\Request::post(settings()->webhooks->cron_start, [], [
                'type' => $backtrace[1]['function'] ?? null,
            ]);
        }
    }

    private function close() {
        /* Send webhook notification if needed */
        if(settings()->webhooks->cron_end) {
            $backtrace = debug_backtrace();
            \Unirest\Request::post(settings()->webhooks->cron_end, [], [
                'type' => $backtrace[1]['function'] ?? null,
            ]);
        }
    }

    private function update_cron_execution_datetimes($key) {
        $date = get_date();

        /* Database query */
        database()->query("UPDATE `settings` SET `value` = JSON_SET(`value`, '$.{$key}', '{$date}') WHERE `key` = 'cron'");
    }

    public function reset() {

        $this->initiate();

        $this->users_plan_expiry_checker();

        $this->users_deletion_reminder();

        $this->auto_delete_inactive_users();

        $this->auto_delete_unconfirmed_users();

        $this->users_plan_expiry_reminder();

        $this->audits_cleanup();

        $this->update_cron_execution_datetimes('reset_datetime');

        /* Make sure the reset date month is different than the current one to avoid double resetting */
        $reset_date = settings()->cron->reset_date ? (new \DateTime(settings()->cron->reset_date))->format('m') : null;
        $current_date = (new \DateTime())->format('m');

        if($reset_date != $current_date) {
            $this->logs_cleanup();

            $this->users_logs_cleanup();

            $this->internal_notifications_cleanup();

            $this->users_audit_reset();

            $this->update_cron_execution_datetimes('reset_date');

            /* Clear the cache */
            cache()->deleteItem('settings');
        }

        $this->close();
    }

    private function users_plan_expiry_checker() {
        if(!settings()->payment->user_plan_expiry_checker_is_enabled) {
            return;
        }

        $date = get_date();

        /* Get potential monitors from users that have almost all the conditions to get an email report right now */
        $result = database()->query("
            SELECT `user_id`
            FROM `users`
            WHERE 
                `plan_id` <> 'free'
				AND `plan_expiration_date` < '{$date}' 
        ");

        /* Go through each result */
        while($user = $result->fetch_object()) {

            /* Switch the user to the default plan */
            db()->where('user_id', $user->user_id)->update('users', [
                'plan_id' => 'free',
                'plan_settings' => json_encode(settings()->plan_free->settings),
                'payment_subscription_id' => ''
            ]);

            /* Clear the cache */
            cache()->deleteItemsByTag('user_id=' .  \Altum\Authentication::$user_id);

            if(DEBUG) {
                echo sprintf('Plan expired for user_id %s', $user->user_id);
            }
        }

    }

    private function users_deletion_reminder() {
        if(!settings()->users->auto_delete_inactive_users) {
            return;
        }

        /* Determine when to send the email reminder */
        $days_until_deletion = settings()->users->user_deletion_reminder;
        $days = settings()->users->auto_delete_inactive_users - $days_until_deletion;
        $past_date = (new \DateTime())->modify('-' . $days . ' days')->format('Y-m-d H:i:s');

        /* Get the users that need to be reminded */
        $result = database()->query("
            SELECT `user_id`, `name`, `email`, `language`, `anti_phishing_code` FROM `users` WHERE `plan_id` = 'free' AND `last_activity` < '{$past_date}' AND `user_deletion_reminder` = 0 AND `type` = 0 LIMIT 25
        ");

        /* Go through each result */
        while($user = $result->fetch_object()) {

            /* Prepare the email */
            $email_template = get_email_template(
                [
                    '{{DAYS_UNTIL_DELETION}}' => $days_until_deletion,
                ],
                l('global.emails.user_deletion_reminder.subject', $user->language),
                [
                    '{{DAYS_UNTIL_DELETION}}' => $days_until_deletion,
                    '{{LOGIN_LINK}}' => url('login'),
                    '{{NAME}}' => $user->name,
                ],
                l('global.emails.user_deletion_reminder.body', $user->language)
            );

            if(settings()->users->user_deletion_reminder) {
                send_mail($user->email, $email_template->subject, $email_template->body, ['anti_phishing_code' => $user->anti_phishing_code, 'language' => $user->language]);
            }

            /* Update user */
            db()->where('user_id', $user->user_id)->update('users', ['user_deletion_reminder' => 1]);

            if(DEBUG) {
                if(settings()->users->user_deletion_reminder) echo sprintf('User deletion reminder email sent for user_id %s', $user->user_id);
            }
        }

    }

    private function auto_delete_inactive_users() {
        if(!settings()->users->auto_delete_inactive_users) {
            return;
        }

        /* Determine what users to delete */
        $days = settings()->users->auto_delete_inactive_users;
        $past_date = (new \DateTime())->modify('-' . $days . ' days')->format('Y-m-d H:i:s');

        /* Get the users that need to be reminded */
        $result = database()->query("
            SELECT `user_id`, `name`, `email`, `language`, `anti_phishing_code` FROM `users` WHERE `plan_id` = 'free' AND `last_activity` < '{$past_date}' AND `user_deletion_reminder` = 1 AND `type` = 0 LIMIT 25
        ");

        /* Go through each result */
        while($user = $result->fetch_object()) {

            /* Prepare the email */
            $email_template = get_email_template(
                [],
                l('global.emails.auto_delete_inactive_users.subject', $user->language),
                [
                    '{{INACTIVITY_DAYS}}' => settings()->users->auto_delete_inactive_users,
                    '{{REGISTER_LINK}}' => url('register'),
                    '{{NAME}}' => $user->name,
                ],
                l('global.emails.auto_delete_inactive_users.body', $user->language)
            );

            send_mail($user->email, $email_template->subject, $email_template->body, ['anti_phishing_code' => $user->anti_phishing_code, 'language' => $user->language]);

            /* Delete user */
            (new User())->delete($user->user_id);

            if(DEBUG) {
                echo sprintf('User deletion for inactivity user_id %s', $user->user_id);
            }
        }

    }

    private function auto_delete_unconfirmed_users() {
        if(!settings()->users->auto_delete_unconfirmed_users) {
            return;
        }

        /* Determine what users to delete */
        $days = settings()->users->auto_delete_unconfirmed_users;
        $past_date = (new \DateTime())->modify('-' . $days . ' days')->format('Y-m-d H:i:s');

        /* Get the users that need to be reminded */
        $result = database()->query("SELECT `user_id` FROM `users` WHERE `status` = '0' AND `datetime` < '{$past_date}' LIMIT 100");

        /* Go through each result */
        while($user = $result->fetch_object()) {

            /* Delete user */
            (new User())->delete($user->user_id);

            if(DEBUG) {
                echo sprintf('User deleted for unconfirmed account user_id %s', $user->user_id);
            }
        }
    }

    private function logs_cleanup() {
        /* Clear files caches */
        clearstatcache();

        $current_month = (new \DateTime())->format('m');

        $deleted_count = 0;

        /* Get the data */
        foreach(glob(UPLOADS_PATH . 'logs/' . '*.log') as $file_path) {
            $file_last_modified = filemtime($file_path);

            if((new \DateTime())->setTimestamp($file_last_modified)->format('m') != $current_month) {
                unlink($file_path);
                $deleted_count++;
            }
        }

        if(DEBUG) {
            echo sprintf('logs_cleanup: Deleted %s file logs.', $deleted_count);
        }
    }

    private function users_logs_cleanup() {
        /* Delete old users logs */
        $ninety_days_ago_datetime = (new \DateTime())->modify('-90 days')->format('Y-m-d H:i:s');
        db()->where('datetime', $ninety_days_ago_datetime, '<')->delete('users_logs');
    }

    private function internal_notifications_cleanup() {
        /* Delete old users notifications */
        $ninety_days_ago_datetime = (new \DateTime())->modify('-30 days')->format('Y-m-d H:i:s');
        db()->where('datetime', $ninety_days_ago_datetime, '<')->delete('internal_notifications');
    }

    private function audits_cleanup() {
        $current_date = (new \DateTime())->format('Y-m-d H:i:s');

        /* Delete the expired audits */
        $result = database()->query("
            SELECT `audit_id` 
            FROM `audits` 
            WHERE `expiration_datetime` < '{$current_date}'
        ");

        /* Go through each result */
        while($audit = $result->fetch_object()) {
            /* Delete the resource */
            db()->where('audit_id', $audit->audit_id)->delete('audits');

            if(DEBUG) {
                echo 'audits cleanup done';
            }
        }

        /* Delete the expired old audits */
        $result = database()->query("
            SELECT `archived_audit_id` 
            FROM `archived_audits` 
            WHERE `expiration_datetime` < '{$current_date}'
        ");

        /* Go through each result */
        while($audit = $result->fetch_object()) {
            /* Delete the resource */
            db()->where('archived_audit_id', $audit->archived_audit_id)->delete('archived_audits');

            if(DEBUG) {
                echo 'archived_audits cleanup done';
            }
        }
    }

    private function users_audit_reset() {
        db()->update('users', [
            'audit_audits_current_month' => 0,
        ]);

        cache()->clear();
    }

    private function users_plan_expiry_reminder() {
        if(!settings()->payment->user_plan_expiry_reminder) {
            return;
        }

        /* Determine when to send the email reminder */
        $days = settings()->payment->user_plan_expiry_reminder;
        $future_date = (new \DateTime())->modify('+' . $days . ' days')->format('Y-m-d H:i:s');

        /* Get potential monitors from users that have almost all the conditions to get an email report right now */
        $result = database()->query("
            SELECT
                `user_id`,
                `name`,
                `email`,
                `plan_id`,
                `plan_expiration_date`,
                `language`,
                `anti_phishing_code`
            FROM 
                `users`
            WHERE 
                `status` = 1
                AND `plan_id` <> 'free'
                AND `plan_expiry_reminder` = '0'
                AND (`payment_subscription_id` IS NULL OR `payment_subscription_id` = '')
				AND '{$future_date}' > `plan_expiration_date`
            LIMIT 25
        ");

        /* Go through each result */
        while($user = $result->fetch_object()) {

            /* Determine the exact days until expiration */
            $days_until_expiration = (new \DateTime($user->plan_expiration_date))->diff((new \DateTime()))->days;

            /* Prepare the email */
            $email_template = get_email_template(
                [
                    '{{DAYS_UNTIL_EXPIRATION}}' => $days_until_expiration,
                ],
                l('global.emails.user_plan_expiry_reminder.subject', $user->language),
                [
                    '{{DAYS_UNTIL_EXPIRATION}}' => $days_until_expiration,
                    '{{USER_PLAN_RENEW_LINK}}' => url('pay/' . $user->plan_id),
                    '{{NAME}}' => $user->name,
                    '{{PLAN_NAME}}' => (new \Altum\Models\Plan())->get_plan_by_id($user->plan_id)->name,
                ],
                l('global.emails.user_plan_expiry_reminder.body', $user->language)
            );

            send_mail($user->email, $email_template->subject, $email_template->body, ['anti_phishing_code' => $user->anti_phishing_code, 'language' => $user->language]);

            /* Update user */
            db()->where('user_id', $user->user_id)->update('users', ['plan_expiry_reminder' => 1]);

            if(DEBUG) {
                echo sprintf('Email sent for user_id %s', $user->user_id);
            }
        }

    }

    public function broadcasts() {

        $this->initiate();

        /* Update cron job last run date */
        $this->update_cron_execution_datetimes('broadcasts_datetime');

        /* Process a maximum of 30 emails per cron job run */
        $i = 1;
        while(($broadcast = db()->where('status', 'processing')->getOne('broadcasts')) && $i <= 30) {
            $broadcast->users_ids = json_decode($broadcast->users_ids ?? '[]');
            $broadcast->sent_users_ids = json_decode($broadcast->sent_users_ids ?? '[]');
            $broadcast->settings = json_decode($broadcast->settings ?? '[]');

            $users_ids_to_be_processed = array_diff($broadcast->users_ids, $broadcast->sent_users_ids);

            /* Get first user that needs to be processed */
            if(count($users_ids_to_be_processed)) {
                $user_id = reset($users_ids_to_be_processed);
                $user = db()->where('user_id', $user_id)->getOne('users', ['user_id', 'name', 'email', 'language', 'anti_phishing_code', 'continent_code', 'country', 'city_name', 'device_type', 'os_name', 'browser_name', 'browser_language',]);

                /* Prepare the email */
                $vars = [
                    '{{USER:NAME}}' => $user->name,
                    '{{USER:EMAIL}}' => $user->email,
                    '{{USER:CONTINENT_NAME}}' => get_continent_from_continent_code($user->continent_code),
                    '{{USER:COUNTRY_NAME}}' => get_country_from_country_code($user->country),
                    '{{USER:CITY_NAME}}' => $user->city_name,
                    '{{USER:DEVICE_TYPE}}' => l('global.device.' . $user->device_type),
                    '{{USER:OS_NAME}}' => $user->os_name,
                    '{{USER:BROWSER_NAME}}' => $user->browser_name,
                    '{{USER:BROWSER_LANGUAGE}}' => get_language_from_locale($user->browser_language),
                ];

                $email_template = get_email_template(
                    $vars,
                    htmlspecialchars_decode($broadcast->subject),
                    $vars,
                    convert_editorjs_json_to_html($broadcast->content)
                );

                $broadcast->sent_users_ids[] = $user_id;

                /* Add the tracking pixel */
                if(settings()->main->broadcasts_statistics_is_enabled) {
                    $tracking_id = base64_encode('broadcast_id=' . $broadcast->broadcast_id . '&user_id=' . $user->user_id);
                    $email_template->body .= '<img src="' . SITE_URL . 'broadcast?id=' . $tracking_id . '" style="display: none;" />';

                    /* Replace all links with trackable links */
                    $email_template->body = preg_replace('/<a href=\"(.+)\"/', '<a href="' . SITE_URL . 'broadcast?id=' . $tracking_id . '&url=$1"', $email_template->body);
                }
                /* Send the email */
                send_mail($user->email, $email_template->subject, $email_template->body, ['is_broadcast' => true, 'is_system_email' => $broadcast->settings->is_system_email, 'anti_phishing_code' => $user->anti_phishing_code, 'language' => $user->language]);

                /* Update the broadcast */
                db()->where('broadcast_id', $broadcast->broadcast_id)->update('broadcasts', [
                    'sent_emails' => db()->inc(),
                    'sent_users_ids' => json_encode($broadcast->sent_users_ids),
                    'status' => count($users_ids_to_be_processed) == 1 ? 'sent' : 'processing',
                    'last_sent_email_datetime' => get_date(),
                ]);

                Logger::users($user->user_id, 'broadcast.' . $broadcast->broadcast_id . '.sent');

                if(DEBUG) {
                    echo '<br />' . "broadcast_id - {$broadcast->broadcast_id} | user_id - {$user_id} sent email." . '<br />';
                }
            }

            /* If there are no users to be processed, mark as sent */
            else {
                db()->where('broadcast_id', $broadcast->broadcast_id)->update('broadcasts', [
                    'status' => 'sent'
                ]);
            }

            $i++;
        }

        $this->close();
    }

    public function push_notifications() {
        if(\Altum\Plugin::is_active('push-notifications')) {

            $this->initiate();

            /* Update cron job last run date */
            $this->update_cron_execution_datetimes('push_notifications_datetime');

            require_once \Altum\Plugin::get('push-notifications')->path . 'controllers/Cron.php';

            $this->close();
        }
    }

    public function audits() {

        $this->initiate();

        $date = get_date();

        /* Update cron job last run date */
        $this->update_cron_execution_datetimes('audits_datetime');

        /* Determine how many checks to do */
        $query_limit = 30;

        $result = database()->query("
            SELECT
                `audits`.*,
                `users`.`email`,
                `users`.`plan_settings`,
                `users`.`language`,
                `users`.`timezone`,
                `users`.`anti_phishing_code`
            FROM 
                `audits`
            LEFT JOIN 
                `users` ON `audits`.`user_id` = `users`.`user_id` 
            WHERE 
                `audits`.`next_refresh_datetime` IS NOT NULL
                AND `audits`.`next_refresh_datetime` <= '{$date}' 
                AND `users`.`status` = 1
            ORDER BY `audits`.`next_refresh_datetime`
            LIMIT {$query_limit}
        ");

        while($row = $result->fetch_object()) {
            $row->plan_settings = json_decode($row->plan_settings);
            $row->settings = json_decode($row->settings ?? '');
            $row->notifications = json_decode($row->notifications ?? '');

            /* Next refresh date */
            $next_refresh_datetime = $row->settings->audit_check_interval ? (new \DateTime())->modify('+' . $row->settings->audit_check_interval . ' seconds')->format('Y-m-d H:i:s') : null;

            $notification_handlers = [];

            /* Get available notification handlers */
            if(count($row->notifications)) $notification_handlers = (new \Altum\Models\NotificationHandlers())->get_notification_handlers_by_user_id($row->user_id);

            if(DEBUG) printf("Starting to refresh %s audit...\n", $row->url);

            $response_error = false;

            /* Send the main request */
            try {
                $response = \Altum\Helpers\Audit::process_request($row->url);
            } catch(\Exception $exception) {
                error_log($exception->getMessage());
                $response_error = $exception->getMessage();
            }

            if($response_error && settings()->audits->double_check_is_enabled) {
                sleep(settings()->audits->double_check_wait ?? 3);

                /* Send the second request */
                try {
                    $response = \Altum\Helpers\Audit::process_request($row->url);
                } catch(\Exception $exception) {
                    error_log($exception->getMessage());
                    $response_error = $exception->getMessage();
                }
            }

            /* Set the error and skip */
            if($response_error) {
                /* Update the main audit */
                db()->where('audit_id', $row->audit_id)->update('audits', [
                    'next_refresh_datetime' => $next_refresh_datetime,
                    'refresh_error' => $exception->getMessage(),
                ]);

                continue;
            }

            /* Single URL processing */
            $data = \Altum\Helpers\Audit::process_request_response($row->url, $response);
            $data_not_found = \Altum\Helpers\Audit::process_not_found($data['parsed_url']);
            $data_robots = \Altum\Helpers\Audit::process_robots($data['parsed_url']);

            /* Merge data */
            $data = array_merge($data, $data_not_found, $data_robots);

            /* Process data */
            $audit_data = \Altum\Helpers\Audit::process_audit_data($data);
            $issues = [
                'major' => $audit_data['major_issues'],
                'moderate' => $audit_data['moderate_issues'],
                'minor' => $audit_data['minor_issues'],
                'potential_major_issues' => $audit_data['potential_major_issues'],
                'potential_moderate_issues' => $audit_data['potential_moderate_issues'],
                'potential_minor_issues' => $audit_data['potential_minor_issues'],
                'total_tests' => $audit_data['total_tests'],
                'passed_tests' => $audit_data['passed_tests'],
            ];

            /* Score */
            $score = $audit_data['score'];

            /* Processing the notification handlers */
            foreach($notification_handlers as $notification_handler) {
            if(!$notification_handler->is_enabled) continue;
            if(!in_array($notification_handler->notification_handler_id, $row->notifications)) continue;

            switch($notification_handler->type) {
                case 'email':

                    /* Prepare the email subject */
                    $subject = sprintf(l('audits.email_report.subject', $row->language), $row->host, $score);

                    /* Email body */
                    $body = (new \Altum\View('audits/audit_email_report', (array) $this))->run([
                        'row' => $row,
                        'issues' => $issues,
                        'audit' => $audit_data,
                        'data' => $data,
                    ]);

                    /* Send the email */
                    send_mail($notification_handler->settings->email, $subject, $body, ['anti_phishing_code' => $row->anti_phishing_code, 'language' => $row->language]);
                    break;

                case 'webhook':

                    try {
                        \Unirest\Request::post($notification_handler->settings->webhook, [], [
                            'website_id' => $row->website_id,
                            'audit_id' => $row->audit_id,
                            'url' => $row->url,
                            'score' => $score,
                            'response_time' => $data['response_time'],
                            'total_tests' => $audit_data['total_tests'],
                            'passed_tests' => $audit_data['passed_tests'],
                            'total_issues' => $audit_data['total_issues'],
                            'major_issues' => $audit_data['found_major_issues'],
                            'moderate_issues' => $audit_data['found_moderate_issues'],
                            'minor_issues' => $audit_data['found_minor_issues'],
                            'total_refreshes' => $row->total_refreshes + 1,
                            'next_refresh_datetime' => $next_refresh_datetime,
                            'audit_view_url' => url('audit/' . $row->audit_id),
                        ]);
                    } catch (\Exception $exception) {
                        error_log($exception->getMessage());
                    }

                    break;

                case 'slack':

                    try {
                        \Unirest\Request::post(
                            $notification_handler->settings->slack,
                            ['Accept' => 'application/json'],
                            \Unirest\Request\Body::json([
                                'text' => sprintf(
                                    l('audits.simple_notification', $row->language),
                                    remove_url_protocol_from_url($row->url),
                                    $score,
                                    $audit_data['total_issues'],
                                    "\r\n\r\n",
                                    url('audit/' . $row->audit_id)
                                ),
                                'username' => settings()->main->title,
                                'icon_emoji' => ':large_red_square:'
                            ])
                        );
                    } catch (\Exception $exception) {
                        error_log($exception->getMessage());
                    }

                    break;

                case 'discord':

                    try {
                        \Unirest\Request::post(
                            $notification_handler->settings->discord,
                            [
                                'Accept' => 'application/json',
                                'Content-Type' => 'application/json',
                            ],
                            \Unirest\Request\Body::json([
                                'embeds' => [
                                    [
                                        'title' => sprintf(
                                            l('audits.simple_notification', $row->language),
                                            remove_url_protocol_from_url($row->url),
                                            $score,
                                            $audit_data['total_issues'],
                                            "\r\n\r\n",
                                            url('audit/' . $row->audit_id)
                                        ),
                                        'color' => '14431557',
                                    ]
                                ],
                            ])
                        );
                    } catch (\Exception $exception) {
                        error_log($exception->getMessage());
                    }

                    break;

                case 'telegram':

                    try {
                        \Unirest\Request::get(
                            sprintf(
                                'https://api.telegram.org/bot%s/sendMessage?chat_id=%s&text=%s',
                                $notification_handler->settings->telegram,
                                $notification_handler->settings->telegram_chat_id,
                                sprintf(
                                    l('audits.simple_notification', $row->language),
                                    remove_url_protocol_from_url($row->url),
                                    $score,
                                    $audit_data['total_issues'],
                                    urlencode("\r\n\r\n"),
                                    url('audit/' . $row->audit_id)
                                )
                            )
                        );
                    } catch (\Exception $exception) {
                        error_log($exception->getMessage());
                    }

                    break;

                case 'microsoft_teams':

                    try {
                        \Unirest\Request::post(
                            $notification_handler->settings->microsoft_teams,
                            ['Content-Type' => 'application/json'],
                            \Unirest\Request\Body::json([
                                'text' => sprintf(
                                    l('audits.simple_notification', $row->language),
                                    remove_url_protocol_from_url($row->url),
                                    $score,
                                    $audit_data['total_issues'],
                                    "\r\n\r\n",
                                    url('audit/' . $row->audit_id)
                                ),
                            ])
                        );
                    } catch (\Exception $exception) {
                        error_log($exception->getMessage());
                    }

                    break;

                case 'x':

                    $twitter = new \Abraham\TwitterOAuth\TwitterOAuth(
                        $notification_handler->settings->x_consumer_key,
                        $notification_handler->settings->x_consumer_secret,
                        $notification_handler->settings->x_access_token,
                        $notification_handler->settings->x_access_token_secret
                    );

                    $twitter->setApiVersion('2');

                    try {
                        $response = $twitter->post('tweets', ['text' => sprintf(
                            l('audits.simple_notification', $row->language),
                            remove_url_protocol_from_url($row->url),
                            $score,
                            $audit_data['total_issues'],
                            "\r\n\r\n",
                            url('audit/' . $row->audit_id)
                        )]);
                    } catch (\Exception $exception) {
                        /* :* */
                    }

                    break;

                case 'twilio':

                    try {
                        \Unirest\Request::auth(settings()->notification_handlers->twilio_sid, settings()->notification_handlers->twilio_token);

                        \Unirest\Request::post(
                            sprintf('https://api.twilio.com/2010-04-01/Accounts/%s/Messages.json', settings()->notification_handlers->twilio_sid),
                            [],
                            [
                                'From' => settings()->notification_handlers->twilio_number,
                                'To' => $notification_handler->settings->twilio,
                                'Body' => sprintf(
                                    l('audits.simple_notification', $row->language),
                                    remove_url_protocol_from_url($row->url),
                                    $score,
                                    $audit_data['total_issues'],
                                    "\r\n\r\n",
                                    url('audit/' . $row->audit_id)
                                ),
                            ]
                        );
                    } catch (\Exception $exception) {
                        error_log($exception->getMessage());
                    }

                    \Unirest\Request::auth('', '');

                    break;

                case 'twilio_call':

                    try {
                        \Unirest\Request::auth(settings()->notification_handlers->twilio_sid, settings()->notification_handlers->twilio_token);

                        \Unirest\Request::post(
                            sprintf('https://api.twilio.com/2010-04-01/Accounts/%s/Calls.json', settings()->notification_handlers->twilio_sid),
                            [],
                            [
                                'From' => settings()->notification_handlers->twilio_number,
                                'To' => $notification_handler->settings->twilio_call,
                                'Url' => SITE_URL . 'twiml/audits.simple_notification?param1=' . urlencode($row->name) . '&param2=' . urlencode($row->target . ($row->port ? ':' . $row->port : null)) . '&param3=' . $audit_data['total_issues'] . '&param4=&param4=' . urlencode(url('audit/' . $row->audit_id)),
                            ]
                        );
                    } catch (\Exception $exception) {
                        error_log($exception->getMessage());
                    }

                    \Unirest\Request::auth('', '');

                    break;

                case 'whatsapp':

                    try {
                        \Unirest\Request::post(
                            'https://graph.facebook.com/v18.0/' . settings()->notification_handlers->whatsapp_number_id . '/messages',
                            [
                                'Authorization' => 'Bearer ' . settings()->notification_handlers->whatsapp_access_token,
                                'Content-Type' => 'application/json'
                            ],
                            \Unirest\Request\Body::json([
                                'messaging_product' => 'whatsapp',
                                'to' => $notification_handler->settings->whatsapp,
                                'type' => 'template',
                                'template' => [
                                    'name' => 'audit_refresh',
                                    'language' => [
                                        'code' => \Altum\Language::$default_code
                                    ],
                                    'components' => [[
                                        'type' => 'body',
                                        'parameters' => [
                                            [
                                                'type' => 'text',
                                                'text' => $row->name
                                            ],
                                            [
                                                'type' => 'text',
                                                'text' => remove_url_protocol_from_url($row->url)
                                            ],
                                            [
                                                'type' => 'text',
                                                'text' => $audit_data['total_issues']
                                            ],
                                            [
                                                'type' => 'text',
                                                'text' => url('audit/' . $row->audit_id)
                                            ],
                                        ]
                                    ]]

                                ]
                            ])
                        );
                    } catch (\Exception $exception) {
                        error_log($exception->getMessage());
                    }

                    break;

                case 'push_subscriber_id':
                    $push_subscriber = db()->where('push_subscriber_id', $notification_handler->settings->push_subscriber_id)->getOne('push_subscribers');
                    if(!$push_subscriber) {
                        db()->where('notification_handler_id', $notification_handler->notification_handler_id)->update('notification_handlers', ['is_enabled' => 0]);
                    };

                    /* Prepare the web push */
                    $push_notification = \Altum\Helpers\PushNotifications::send([
                        'title' => sprintf(l('audits.push_notification.title', $row->language), $score),
                        'description' => sprintf(l('audits.push_notification.description', $row->language), remove_url_protocol_from_url($row->url)),
                        'url' => url('audit/' . $row->audit_id),
                    ], $push_subscriber);

                    /* Unsubscribe if push failed */
                    if(!$push_notification) {
                        db()->where('push_subscriber_id', $push_subscriber->push_subscriber_id)->delete('push_subscribers');
                        db()->where('notification_handler_id', $notification_handler->notification_handler_id)->update('notification_handlers', ['is_enabled' => 0]);
                    }

                    break;
            }
        }

            /* Insert a log of the current update as old */
            db()->insert('archived_audits', [
                'audit_id' => $row->audit_id,
                'user_id' => $row->user_id,
                'domain_id' => $row->domain_id,
                'uploader_id' => $row->uploader_id,
                'website_id' => $row->website_id,
                'host' => $row->host,
                'url' => $row->url,
                'ttfb' => $row->ttfb,
                'response_time' => $row->response_time,
                'average_download_speed' => $row->average_download_speed,
                'page_size' => $row->page_size,
                'http_requests' => $row->http_requests,
                'is_https' => $row->is_https,
                'is_ssl_valid' => $row->is_ssl_valid,
                'http_protocol' => $row->http_protocol,
                'title' => $row->title,
                'meta_description' => $row->meta_description,
                'meta_keywords' => $row->meta_keywords,
                'data' => $row->data,
                'issues' => $row->issues,
                'score' => $row->score,
                'total_tests' => $row->total_tests,
                'passed_tests' => $row->passed_tests,
                'total_issues' => $row->total_issues,
                'major_issues' => $row->major_issues,
                'moderate_issues' => $row->moderate_issues,
                'minor_issues' => $row->minor_issues,
                'expiration_datetime' => $row->expiration_datetime,
                'datetime' => $row->last_refresh_datetime ?: $row->datetime,
            ]);

            /* Prepare expiration date */
            $expiration_datetime = (new \DateTime())->modify('+' . ($row->plan_settings->audits_retention ?? 90) . ' days')->format('Y-m-d H:i:s');

            /* Update the main audit */
            db()->where('audit_id', $row->audit_id)->update('audits', [
                'ttfb' => $data['ttfb'],
                'response_time' => $data['response_time'],
                'average_download_speed' => $data['average_download_speed'],
                'page_size' => $data['page_size'],
                'http_requests' => $data['http_requests'],
                'is_https' => $data['is_https'],
                'is_ssl_valid' => $data['is_ssl_valid'],
                'http_protocol' => $data['http_protocol'],
                'title' => $data['title'],
                'meta_description' => $data['meta_description'],
                'meta_keywords' => $data['meta_keywords'],
                'data' => json_encode($data),
                'issues' => json_encode($issues),
                'score' => $score,
                'total_tests' => $audit_data['total_tests'],
                'passed_tests' => $audit_data['passed_tests'],
                'total_issues' => $audit_data['total_issues'],
                'major_issues' => $audit_data['found_major_issues'],
                'moderate_issues' => $audit_data['found_moderate_issues'],
                'minor_issues' => $audit_data['found_minor_issues'],
                'total_refreshes' => db()->inc(),
                'next_refresh_datetime' => $next_refresh_datetime,
                'last_refresh_datetime' => get_date(),
                'expiration_datetime' => $expiration_datetime,
            ]);

            (new \Altum\Models\Website())->refresh_stats($row->website_id);

            /* Database query */
            db()->where('user_id', $row->user_id)->update('users', [
                'audit_audits_current_month' => db()->inc()
            ]);

            /* Clear the cache */
            cache()->deleteItem('audits_total?user_id=' . $row->user_id);
        }

        $this->close();
    }

}
