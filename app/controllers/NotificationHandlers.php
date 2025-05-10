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

use Altum\Alerts;

defined('ALTUMCODE') || die();

class NotificationHandlers extends Controller {

    public function index() {

        \Altum\Authentication::guard();

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters(['type'], ['name'], ['notification_handler_id', 'datetime', 'last_datetime', 'name']));
        $filters->set_default_order_by($this->user->preferences->notification_handlers_default_order_by, $this->user->preferences->default_order_type ?? settings()->main->default_order_type);
        $filters->set_default_results_per_page($this->user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `notification_handlers` WHERE `user_id` = {$this->user->user_id} {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('notification-handlers?' . $filters->get_get() . '&page=%d')));

        /* Get the notification handlers list for the user */
        $notification_handlers = [];
        $notification_handlers_result = database()->query("SELECT * FROM `notification_handlers` WHERE `user_id` = {$this->user->user_id} {$filters->get_sql_where()} {$filters->get_sql_order_by()} {$paginator->get_sql_limit()}");
        while($row = $notification_handlers_result->fetch_object()) $notification_handlers[] = $row;

        /* Export handler */
        process_export_csv($notification_handlers, 'include', ['notification_handler_id', 'user_id', 'type', 'name', 'is_enabled', 'last_datetime', 'datetime'], sprintf(l('notification_handlers.title')));
        process_export_json($notification_handlers, 'include', ['notification_handler_id', 'user_id', 'type', 'name', 'settings', 'is_enabled', 'last_datetime', 'datetime'], sprintf(l('notification_handlers.title')));

        /* Prepare the pagination view */
        $pagination = (new \Altum\View('partials/pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Prepare the view */
        $data = [
            'notification_handlers' => $notification_handlers,
            'total_notification_handlers' => $total_rows,
            'pagination' => $pagination,
            'filters' => $filters,
        ];

        $view = new \Altum\View('notification-handlers/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function bulk() {

        \Altum\Authentication::guard();

        /* Check for any errors */
        if(empty($_POST)) {
            redirect('notification-handlers');
        }

        if(empty($_POST['selected'])) {
            redirect('notification-handlers');
        }

        if(!isset($_POST['type'])) {
            redirect('notification-handlers');
        }

        //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

        if(!\Altum\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            set_time_limit(0);

            switch($_POST['type']) {
                case 'delete':

                    /* Team checks */
                    if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('delete.notification_handlers')) {
                        Alerts::add_info(l('global.info_message.team_no_access'));
                        redirect('notification-handlers');

                    }

                    foreach($_POST['selected'] as $notification_handler_id) {
                        db()->where('notification_handler_id', $notification_handler_id)->where('user_id', $this->user->user_id)->delete('notification_handlers');
                    }

                    cache()->deleteItem('notification_handlers?user_id=' . $this->user->user_id);

                    break;
            }

            /* Set a nice success message */
            Alerts::add_success(l('bulk_delete_modal.success_message'));

        }

        redirect('notification-handlers');

    }

    public function delete() {

        \Altum\Authentication::guard();

        /* Team checks */
        if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('delete.notification_handlers')) {
            Alerts::add_info(l('global.info_message.team_no_access'));
            redirect('notification-handlers');
        }

        if(empty($_POST)) {
            redirect('notification-handlers');
        }

        $notification_handler_id = (int) query_clean($_POST['notification_handler_id']);

        //ALTUMCODE:DEMO if(DEMO) if($this->user->user_id == 1) Alerts::add_error('Please create an account on the demo to test out this function.');

        if(!\Altum\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            redirect('notification-handlers');
        }

        if(!$notification_handler = db()->where('notification_handler_id', $notification_handler_id)->where('user_id', $this->user->user_id)->getOne('notification_handlers', ['notification_handler_id', 'name'])) {
            redirect('notification-handlers');
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Delete the resource */
            db()->where('notification_handler_id', $notification_handler_id)->delete('notification_handlers');

            /* Clear the cache */
            cache()->deleteItem('notification_handlers?user_id=' . $this->user->user_id);

            /* Set a nice success message */
            Alerts::add_success(sprintf(l('global.success_message.delete1'), '<strong>' . $notification_handler->name . '</strong>'));

            redirect('notification-handlers');
        }

        redirect('notification-handlers');
    }
}
