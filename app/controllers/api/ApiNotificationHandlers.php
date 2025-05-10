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

class ApiNotificationHandlers extends Controller {
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
                } else {
                    $this->post();
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
        $filters->set_default_order_by('notification_handler_id', $this->api_user->preferences->default_order_type ?? settings()->main->default_order_type);
        $filters->set_default_results_per_page($this->api_user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);
        $filters->process();

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `notification_handlers` WHERE `user_id` = {$this->api_user->user_id}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('api/notification-handlers?' . $filters->get_get() . '&page=%d')));

        /* Get the data */
        $data = [];
        $data_result = database()->query("
            SELECT
                *
            FROM
                `notification_handlers`
            WHERE
                `user_id` = {$this->api_user->user_id}
                {$filters->get_sql_where()}
                {$filters->get_sql_order_by()}
                  
            {$paginator->get_sql_limit()}
        ");
        while($row = $data_result->fetch_object()) {

            /* Prepare the data */
            $row = [
                'id' => (int) $row->notification_handler_id,
                'user_id' => (int) $row->user_id,
                'type' => $row->type,
                'name' => $row->name,
                'settings' => json_decode($row->settings),
                'is_enabled' => (bool) $row->is_enabled,
                'last_datetime' => $row->last_datetime,
                'datetime' => $row->datetime
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

        $notification_handler_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        /* Try to get details about the resource id */
        $notification_handler = db()->where('notification_handler_id', $notification_handler_id)->where('user_id', $this->api_user->user_id)->getOne('notification_handlers');

        /* We haven't found the resource */
        if(!$notification_handler) {
            $this->return_404();
        }

        /* Prepare the data */
        $data = [
            'id' => (int) $notification_handler->notification_handler_id,
            'user_id' => (int) $notification_handler->user_id,
            'type' => $notification_handler->type,
            'name' => $notification_handler->name,
            'settings' => json_decode($notification_handler->settings),
            'is_enabled' => (bool) $notification_handler->is_enabled,
            'last_datetime' => $notification_handler->last_datetime,
            'datetime' => $notification_handler->datetime
        ];

        Response::jsonapi_success($data);

    }

    private function post() {

        /* Check for any errors */
        $required_fields = ['type', 'name'];
        foreach($required_fields as $field) {
            if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                $this->response_error(l('global.error_message.empty_fields'), 401);
                break 1;
            }
        }

        $_POST['type'] = array_key_exists($_POST['type'], require APP_PATH . 'includes/notification_handlers.php') ? input_clean($_POST['type']) : null;
        $_POST['name'] = input_clean($_POST['name']);

        /* Check for the plan limit */
        $total_rows = db()->where('user_id', $this->api_user->user_id)->where('type', $_POST['type'])->getValue('notification_handlers', 'count(`notification_handler_id`)');

        if($this->api_user->plan_settings->{'notification_handlers_' . $_POST['type'] . '_limit'} != -1 && $total_rows >= $this->api_user->plan_settings->{'notification_handlers_' . $_POST['type'] . '_limit'}) {
            $this->response_error(l('global.info_message.plan_feature_limit'), 401);
        }

        $settings = [];
        switch($_POST['type']) {
            case 'telegram':
                $settings['telegram'] = mb_substr(input_clean($_POST['telegram']), 0, 512);
                $settings['telegram_chat_id'] = mb_substr(input_clean($_POST['telegram_chat_id']), 0, 512);
                break;

            case 'whatsapp':
                $settings['whatsapp'] = (int) input_clean($_POST['whatsapp'], 32);
                break;

            case 'twilio':
            case 'twilio_call':
                $settings[$_POST['type']] = input_clean($_POST[$_POST['type']], 32);
                break;

            case 'x':
                $settings['x_consumer_key'] = input_clean($_POST['x_consumer_key'], 512);
                $settings['x_consumer_secret'] = input_clean($_POST['x_consumer_secret'], 512);
                $settings['x_access_token'] = input_clean($_POST['x_access_token'], 512);
                $settings['x_access_token_secret'] = input_clean($_POST['x_access_token_secret'], 512);
                break;

            default:
                $settings[$_POST['type']] = mb_substr(input_clean($_POST[$_POST['type']]), 0, 512);
                break;
        }
        $settings = json_encode($settings);

        /* Database query */
        $notification_handler_id = db()->insert('notification_handlers', [
            'user_id' => $this->api_user->user_id,
            'type' => $_POST['type'],
            'name' => $_POST['name'],
            'settings' => $settings,
            'datetime' => get_date(),
        ]);

        /* Clear the cache */
        cache()->deleteItem('notification_handlers?user_id=' . $this->api_user->user_id);

        /* Prepare the data */
        $data = [
            'id' => $notification_handler_id,
            'user_id' => (int) $this->api_user->user_id,
            'type' => $_POST['type'],
            'name' => $_POST['name'],
            'settings' => $settings,
            'is_enabled' => (bool) 1,
            'last_datetime' => null,
            'datetime' => get_date(),
        ];

        Response::jsonapi_success($data, null, 201);

    }

    private function patch() {

        $notification_handler_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        /* Try to get details about the resource id */
        $notification_handler = db()->where('notification_handler_id', $notification_handler_id)->where('user_id', $this->api_user->user_id)->getOne('notification_handlers');

        /* We haven't found the resource */
        if(!$notification_handler) {
            $this->return_404();
        }

        $notification_handler->settings = json_decode($notification_handler->settings ?? '');

        $_POST['type'] = in_array($_POST['type'] ?? $notification_handler->type, require APP_PATH . 'includes/notification_handlers.php') ? input_clean($_POST['type']) : null;
        $_POST['name'] = input_clean($_POST['name'] ?? $notification_handler->name);
        $_POST['is_enabled'] = isset($_POST['is_enabled']) ? (int) $_POST['is_enabled'] : $notification_handler->is_enabled;

        $settings = [];
        switch($_POST['type']) {
            case 'telegram':
                $settings['telegram'] = mb_substr(input_clean($_POST['telegram'] ?? $notification_handler->settings->telegram), 0, 512);
                $settings['telegram_chat_id'] = mb_substr(input_clean($_POST['telegram_chat_id'] ?? $notification_handler->settings->telegram_chat_id), 0, 512);
                break;

            case 'whatsapp':
                $settings['whatsapp'] = (int) input_clean($_POST['whatsapp'] ?? $notification_handler->settings->whatsapp, 32);
                break;

            case 'twilio':
            case 'twilio_call':
                $settings[$_POST['type']] = input_clean($_POST[$_POST['type']] ?? $notification_handler->settings->{$_POST['type']}, 32);
                break;

            case 'x':
                $settings['x_consumer_key'] = input_clean($_POST['x_consumer_key'] ?? $notification_handler->settings->x_consumer_key, 512);
                $settings['x_consumer_secret'] = input_clean($_POST['x_consumer_secret'] ?? $notification_handler->settings->x_consumer_secret, 512);
                $settings['x_access_token'] = input_clean($_POST['x_access_token'] ?? $notification_handler->settings->x_access_token, 512);
                $settings['x_access_token_secret'] = input_clean($_POST['x_access_token_secret'] ?? $notification_handler->settings->x_access_token_secret, 512);
                break;

            default:
                $settings[$_POST['type']] = mb_substr(input_clean($_POST[$_POST['type']] ?? $notification_handler->settings->{$_POST['type']}), 0, 512);
                break;
        }
        $settings = json_encode($settings);

        /* Database query */
        db()->where('notification_handler_id', $notification_handler->notification_handler_id)->update('notification_handlers', [
            'type' => $_POST['type'],
            'name' => $_POST['name'],
            'settings' => $settings,
            'is_enabled' => $_POST['is_enabled'],
            'last_datetime' => get_date(),
        ]);

        /* Clear the cache */
        cache()->deleteItem('notification_handlers?user_id=' . $this->api_user->user_id);

        /* Prepare the data */
        $data = [
            'id' => $notification_handler->notification_handler_id,
            'user_id' => (int) $this->api_user->user_id,
            'type' => $_POST['type'],
            'name' => $_POST['name'],
            'settings' => $settings,
            'is_enabled' => $notification_handler->is_enabled,
            'last_datetime' => get_date(),
            'datetime' => $notification_handler->datetime,
        ];

        Response::jsonapi_success($data, null, 200);

    }

    private function delete() {

        $notification_handler_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        /* Try to get details about the resource id */
        $notification_handler = db()->where('notification_handler_id', $notification_handler_id)->where('user_id', $this->api_user->user_id)->getOne('notification_handlers');

        /* We haven't found the resource */
        if(!$notification_handler) {
            $this->return_404();
        }

        /* Delete the resource */
        db()->where('notification_handler_id', $notification_handler_id)->delete('notification_handlers');

        /* Clear the cache */
        cache()->deleteItem('notification_handlers?user_id=' . $this->api_user->user_id);

        http_response_code(200);
        die();

    }
}
