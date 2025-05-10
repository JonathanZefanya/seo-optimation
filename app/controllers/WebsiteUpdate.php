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

namespace Altum\controllers;

use Altum\Alerts;

defined('ALTUMCODE') || die();

class WebsiteUpdate extends Controller {

    public function index() {

        \Altum\Authentication::guard();

        /* Team checks */
        if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('update.websites')) {
            Alerts::add_info(l('global.info_message.team_no_access'));
            redirect('websites');
        }

        $website_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        if(!$website = db()->where('website_id', $website_id)->where('user_id', $this->user->user_id)->getOne('websites')) {
            redirect('websites');
        }

        foreach(['notifications', 'settings'] as $key) $website->{$key} = json_decode($website->{$key} ?? '');

        /* Get available notification handlers */
        $notification_handlers = (new \Altum\Models\NotificationHandlers())->get_notification_handlers_by_user_id($this->user->user_id);

        /* Get available custom domains */
        $domains = (new \Altum\Models\Domain())->get_available_domains_by_user_id($this->user->user_id);

        if(!empty($_POST)) {
            $_POST['domain_id'] = isset($_POST['domain_id']) && array_key_exists($_POST['domain_id'], $domains) ? (int) $_POST['domain_id'] : null;
            $is_public = (int) isset($_POST['is_public']);
            $_POST['audit_check_interval'] = isset($_POST['audit_check_interval']) && in_array($_POST['audit_check_interval'], $this->user->plan_settings->audits_check_intervals ?? []) ? (int) $_POST['audit_check_interval'] : null;
            $password = !empty($_POST['password']) ?
                ($_POST['password'] != $website->settings->password ? password_hash($_POST['password'], PASSWORD_DEFAULT) : $website->settings->password)
                : null;

            /* Notification handlers */
            $_POST['notifications'] = array_map(
                function($notification_handler_id) {
                    return (int) $notification_handler_id;
                },
                array_filter($_POST['notifications'] ?? [], function($notification_handler_id) use($notification_handlers) {
                    return array_key_exists($notification_handler_id, $notification_handlers);
                })
            );
            if($this->user->plan_settings->active_notification_handlers_per_resource_limit != -1) {
                $_POST['notifications'] = array_slice($_POST['notifications'], 0, $this->user->plan_settings->active_notification_handlers_per_resource_limit);
            }

            //ALTUMCODE:DEMO if(DEMO) if($this->user->user_id == 1) Alerts::add_error('Please create an account on the demo to test out this function.');

            /* Check for any errors */
            $required_fields = [];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                /* Settings */
                $settings = [
                    'is_public' => $is_public,
                    'password' => $password,
                    'audit_check_interval' => $_POST['audit_check_interval'],
                ];

                /* Notification handlers */
                $notifications = json_encode($_POST['notifications']);

                /* Database query */
                db()->where('website_id', $website->website_id)->update('websites', [
                    'domain_id' => $_POST['domain_id'],
                    'settings' => json_encode($settings),
                    'notifications' => $notifications,
                    'last_datetime' => get_date(),
                ]);

                /* Update all audits within the website */
                db()->where('website_id', $website->website_id)->update('audits', [
                    'domain_id' => $_POST['domain_id'],
                    'settings' => json_encode($settings),
                    'notifications' => $notifications,
                    'last_datetime' => get_date(),
                ]);

                /* Set a nice success message */
                Alerts::add_success(sprintf(l('global.success_message.update1'), '<strong>' . $_POST['name'] . '</strong>'));

                redirect('website-update/' . $website_id);
            }
        }

        /* Prepare the view */
        $data = [
            'website' => $website,
            'domains' => $domains,
            'notification_handlers' => $notification_handlers,
        ];

        $view = new \Altum\View('website-update/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
