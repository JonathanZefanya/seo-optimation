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

use Altum\Response;
use Altum\Traits\Apiable;

defined('ALTUMCODE') || die();

class ApiWebsites extends Controller {
    use Apiable;

    public function index() {

        $this->verify_request();

        /* Decide what to continue with */
        switch($_SERVER['REQUEST_METHOD']) {
            case 'GET':

                /* Detect if we only need an object, or the whole list */
                if(isset($this->params[0])) {
                    $this->get();
                } else {
                    $this->get_all();
                }

                break;

            case 'POST':

                /* Detect what method to use */
                if(isset($this->params[0])) {
                    $this->patch();
                }

                break;

            case 'DELETE':
                $this->delete();
                break;
        }

        $this->return_404();
    }

    private function get_all() {

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters([], [], []));
        $filters->set_default_order_by($this->api_user->preferences->websites_default_order_by, $this->api_user->preferences->default_order_type ?? settings()->main->default_order_type);
        $filters->set_default_results_per_page($this->api_user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);
        $filters->process();

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `websites` WHERE `user_id` = {$this->api_user->user_id}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('api/websites?' . $filters->get_get() . '&page=%d')));

        /* Get the data */
        $data = [];
        $data_result = database()->query("
            SELECT
                *
            FROM
                `websites`
            WHERE
                `user_id` = {$this->api_user->user_id}
                {$filters->get_sql_where()}
                {$filters->get_sql_order_by()}
                  
            {$paginator->get_sql_limit()}
        ");


        while($row = $data_result->fetch_object()) {

            /* Prepare the data */
            $row = [
                'id' => (int) $row->website_id,
                'user_id' => (int) $row->user_id,
                'domain_id' => (int) $row->domain_id,
                'scheme' => $row->scheme,
                'host' => $row->host,
                'score' => (int) $row->score,
                'total_audits' => (int) $row->total_audits,
                'total_archived_audits' => (int) $row->total_archived_audits,
                'total_issues' => (int) $row->total_issues,
                'major_issues' => (int) $row->major_issues,
                'moderate_issues' => (int) $row->moderate_issues,
                'minor_issues' => (int) $row->minor_issues,
                'last_audit_datetime' => $row->last_audit_datetime,
                'last_datetime' => $row->last_datetime,
                'datetime' => $row->datetime,
            ];

            $data[] = $row;
        }

        /* Prepare the data */
        $meta = [
            'page' => $_GET['page'] ?? 1,
            'total_pages' => $paginator->getNumPages(),
            'results_per_page' => $filters->get_results_per_page(),
            'total_results' => (int) $total_rows,
        ];

        /* Prepare the pagination links */
        $others = ['links' => [
            'first' => $paginator->getPageUrl(1),
            'last' => $paginator->getNumPages() ? $paginator->getPageUrl($paginator->getNumPages()) : null,
            'next' => $paginator->getNextUrl(),
            'prev' => $paginator->getPrevUrl(),
            'self' => $paginator->getPageUrl($_GET['page'] ?? 1)
        ]];

        Response::jsonapi_success($data, $meta, 200, $others);
    }

    private function get() {

        $website_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        /* Try to get details about the resource id */
        $website = db()->where('website_id', $website_id)->where('user_id', $this->api_user->user_id)->getOne('websites');

        /* We haven't found the resource */
        if(!$website) {
            $this->return_404();
        }

        /* Prepare the data */
        $data = [
            'id' => (int) $website->website_id,
            'user_id' => (int) $website->user_id,
            'domain_id' => (int) $website->domain_id,
            'scheme' => $website->scheme,
            'host' => $website->host,
            'score' => (int) $website->score,
            'total_audits' => (int) $website->total_audits,
            'total_archived_audits' => (int) $website->total_archived_audits,
            'total_issues' => (int) $website->total_issues,
            'major_issues' => (int) $website->major_issues,
            'moderate_issues' => (int) $website->moderate_issues,
            'minor_issues' => (int) $website->minor_issues,
            'last_audit_datetime' => $website->last_audit_datetime,
            'last_datetime' => $website->last_datetime,
            'datetime' => $website->datetime,
        ];

        Response::jsonapi_success($data);

    }

    private function patch() {

        $website_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        /* Try to get details about the resource id */
        $website = db()->where('website_id', $website_id)->where('user_id', $this->api_user->user_id)->getOne('websites');

        /* We haven't found the resource */
        if(!$website) {
            $this->return_404();
        }

        foreach(['notifications', 'settings'] as $key) $website->{$key} = json_decode($website->{$key} ?? '');

        /* Check for any errors */
        $required_fields = [];
        foreach($required_fields as $field) {
            if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                $this->response_error(l('global.error_message.empty_fields'), 401);
                break 1;
            }
        }

        /* Get available notification handlers */
        $notification_handlers = (new \Altum\Models\NotificationHandlers())->get_notification_handlers_by_user_id($this->api_user->user_id);

        /* Get available custom domains */
        $domains = (new \Altum\Models\Domain())->get_available_domains_by_user_id($this->api_user->user_id);

        /* Filter some the variables */
        $_POST['domain_id'] = isset($_POST['domain_id']) && array_key_exists($_POST['domain_id'], $domains) ? (int) $_POST['domain_id'] : $website->domain_id   ;
        $is_public = (int) (bool) ($_POST['is_public'] ?? $website->is_public);

        $_POST['audit_check_interval'] = isset($_POST['audit_check_interval']) && in_array($_POST['audit_check_interval'], array_merge([''], $this->api_user->plan_settings->audits_check_intervals ?? [])) ? (int) $_POST['audit_check_interval'] : $website->settings->audit_check_interval;
        $password = !empty($_POST['password']) ?
            ($_POST['password'] != $website->settings->password ? password_hash($_POST['password'], PASSWORD_DEFAULT) : $website->settings->password)
            : $website->settings->password;

        /* Notification handlers */
        $_POST['notifications'] = array_map(
            function($notification_handler_id) {
                return (int) $notification_handler_id;
            },
            array_filter($_POST['notifications'] ?? $website->notifications, function($notification_handler_id) use($notification_handlers) {
                return array_key_exists($notification_handler_id, $notification_handlers);
            })
        );
        if($this->api_user->plan_settings->active_notification_handlers_per_resource_limit != -1) {
            $_POST['notifications'] = array_slice($_POST['notifications'], 0, $this->api_user->plan_settings->active_notification_handlers_per_resource_limit);
        }

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

        /* Prepare the data */
        $data = [
            'id' => $website->website_id
        ];

        Response::jsonapi_success($data, null, 200);

    }

    private function delete() {

        $website_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        /* Try to get details about the resource id */
        $website = db()->where('website_id', $website_id)->where('user_id', $this->api_user->user_id)->getOne('websites');

        /* We haven't found the resource */
        if(!$website) {
            $this->return_404();
        }

        /* Delete the resource */
        db()->where('website_id', $website_id)->delete('websites');

        /* Clear the cache */
        cache()->deleteItem('audits_total?user_id=' . $this->api_user->user_id);
        cache()->deleteItem('audits_dashboard?user_id=' . $this->api_user->user_id);
        cache()->deleteItem('websites_dashboard?user_id=' . $this->api_user->user_id);

        http_response_code(200);
        die();

    }
}
