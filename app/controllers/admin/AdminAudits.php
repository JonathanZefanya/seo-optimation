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

class AdminAudits extends Controller {

    public function index() {

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters(['user_id', 'website_id', 'domain_id',], ['url', 'host', 'title'], ['audit_id', 'last_datetime', 'datetime', 'last_refresh_datetime', 'next_refresh_datetime', 'url', 'host', 'score', 'total_issues', 'title']));
        $filters->set_default_order_by($this->user->preferences->audits_default_order_by, $this->user->preferences->default_order_type ?? settings()->main->default_order_type);
        $filters->set_default_results_per_page($this->user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `audits` WHERE 1 = 1 {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('admin/audits?' . $filters->get_get() . '&page=%d')));

        /* Get the audits list for the user */
        $audits = [];
        $audits_result = database()->query("
            SELECT
                `audits`.*, `users`.`name` AS `user_name`, `users`.`email` AS `user_email`
            FROM
                `audits`
            LEFT JOIN
                `users` ON `audits`.`user_id` = `users`.`user_id`
            WHERE
                1 = 1
                {$filters->get_sql_where('audits')}
                {$filters->get_sql_order_by('audits')}
            
            {$paginator->get_sql_limit()}
        ");
        while($row = $audits_result->fetch_object()) {
            $row->settings = json_decode($row->settings ?? '');
            $audits[] = $row;
        }

        /* Export handler */
        process_export_csv($audits, 'include', ['audit_id', 'user_id', 'domain_id', 'uploader_id', 'host', 'url', 'ttfb', 'response_time', 'average_download_speed', 'page_size', 'http_requests', 'is_https', 'is_ssl_valid', 'http_protocol', 'title', 'meta_description', 'meta_keywords', 'score', 'total_issues', 'major_issues', 'moderate_issues', 'minor_issues', 'refresh_error', 'total_refreshes', 'next_refresh_datetime', 'last_refresh_datetime', 'expiration_datetime', 'last_datetime', 'datetime'], sprintf(l('audits.title')));
        process_export_json($audits, 'include', ['audit_id', 'user_id', 'domain_id', 'uploader_id', 'host', 'url', 'ttfb', 'response_time', 'average_download_speed', 'page_size', 'http_requests', 'is_https', 'is_ssl_valid', 'http_protocol', 'title', 'meta_description', 'meta_keywords', 'data', 'issues', 'settings', 'notifications', 'score', 'total_issues', 'major_issues', 'moderate_issues', 'minor_issues', 'refresh_error', 'total_refreshes', 'next_refresh_datetime', 'last_refresh_datetime', 'expiration_datetime', 'last_datetime', 'datetime'], sprintf(l('audits.title')));

        /* Prepare the pagination view */
        $pagination = (new \Altum\View('partials/pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Prepare the view */
        $data = [
            'audits' => $audits,
            'pagination' => $pagination,
            'filters' => $filters,
        ];

        $view = new \Altum\View('admin/audits/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function bulk() {

        /* Check for any errors */
        if(empty($_POST)) {
            redirect('admin/audits');
        }

        if(empty($_POST['selected'])) {
            redirect('admin/audits');
        }

        if(!isset($_POST['type'])) {
            redirect('admin/audits');
        }

        //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

        if(!\Altum\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            set_time_limit(0);

            switch($_POST['type']) {
                case 'delete':

                    $websites_ids = [];
                    $users_ids = [];

                    foreach($_POST['selected'] as $audit_id) {
                        if($audit = db()->where('audit_id', $audit_id)->getOne('audits', ['audit_id', 'website_id', 'user_id'])) {

                            db()->where('audit_id', $audit_id)->delete('audits');

                            if(!in_array($audit->website_id, $websites_ids)) {
                                $websites_ids[] = $audit->website_id;
                            }

                            if(!in_array($audit->user_id, $users_ids)) {
                                $users_ids[] = $audit->user_id;
                            }
                        }
                    }

                    foreach($websites_ids as $website_id) {
                        (new \Altum\Models\Website())->refresh_stats($website_id);
                    }

                    foreach($users_ids as $user_id) {
                        /* Clear the cache */
                        cache()->deleteItem('audits_total?user_id=' . $user_id);
                        cache()->deleteItem('audits_dashboard?user_id=' . $user_id);
                    }

                    break;
            }

            /* Set a nice success message */
            Alerts::add_success(l('bulk_delete_modal.success_message'));

        }

        redirect('admin/audits');
    }

    public function delete() {

        $audit_id = (isset($this->params[0])) ? (int) $this->params[0] : null;

        //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

        if(!\Altum\Csrf::check('global_token')) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!$audit = db()->where('audit_id', $audit_id)->getOne('audits', ['audit_id', 'website_id', 'url', 'user_id'])) {
            redirect('admin/audits');
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {
            db()->where('audit_id', $audit_id)->delete('audits');

            (new \Altum\Models\Website())->refresh_stats($audit->website_id);

            /* Clear the cache */
            cache()->deleteItem('audits_total?user_id=' . $audit->user_id);
            cache()->deleteItem('audits_dashboard?user_id=' . $audit->user_id);

            /* Set a nice success message */
            Alerts::add_success(sprintf(l('global.success_message.delete1'), '<strong>' . remove_url_protocol_from_url($audit->url) . '</strong>'));

        }

        redirect('admin/audits');
    }
}
