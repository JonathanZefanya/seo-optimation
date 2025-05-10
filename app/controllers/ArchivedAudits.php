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

class ArchivedAudits extends Controller {

    public function index() {

        \Altum\Authentication::guard();

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters(['user_id', 'website_id', 'domain_id', 'audit_id'], ['url', 'host', 'title'], ['audit_id', 'archived_audit_id', 'datetime', 'expiration_datetime', 'url', 'host', 'score', 'total_issues', 'title']));
        $filters->set_default_order_by($this->user->preferences->archived_audits_default_order_by, $this->user->preferences->default_order_type ?? settings()->main->default_order_type);
        $filters->set_default_results_per_page($this->user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `archived_audits` WHERE `user_id` = {$this->user->user_id} {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('audits?' . $filters->get_get() . '&page=%d')));

        /* Generate stats */
        $audits_stats = [
            'total_tests' => 0,
            'major_issues' => 0,
            'moderate_issues' => 0,
            'minor_issues' => 0,
            'passed_tests' => 0,
        ];

        /* Get the audits list for the user */
        $archived_audits = [];
        $archived_audits_result = database()->query("SELECT * FROM `archived_audits` WHERE `user_id` = {$this->user->user_id} {$filters->get_sql_where()} {$filters->get_sql_order_by()} {$paginator->get_sql_limit()}");
        while($row = $archived_audits_result->fetch_object()) {
            foreach(['data', 'issues', 'settings'] as $key) $row->{$key} = json_decode($row->{$key} ?? '');

            $audits_stats['total_tests'] += $row->total_tests;
            $audits_stats['major_issues'] += $row->major_issues;
            $audits_stats['moderate_issues'] += $row->moderate_issues;
            $audits_stats['minor_issues'] += $row->minor_issues;
            $audits_stats['passed_tests'] += $row->passed_tests;

            $archived_audits[] = $row;
        }

        /* Export handler */
        process_export_csv($archived_audits, 'include', ['archived_audit_id', 'audit_id' ,'user_id', 'domain_id', 'uploader_id', 'host', 'url', 'ttfb', 'response_time', 'average_download_speed', 'page_size', 'http_requests', 'is_https', 'is_ssl_valid', 'http_protocol', 'title', 'meta_description', 'meta_keywords', 'score', 'total_issues', 'major_issues', 'moderate_issues', 'minor_issues', 'refresh_error', 'expiration_datetime', 'datetime'], sprintf(l('archived_audits.title')));
        process_export_json($archived_audits, 'include', ['archived_audit_id', 'audit_id' ,'user_id', 'domain_id', 'uploader_id', 'host', 'url', 'ttfb', 'response_time', 'average_download_speed', 'page_size', 'http_requests', 'is_https', 'is_ssl_valid', 'http_protocol', 'title', 'meta_description', 'meta_keywords', 'data', 'issues', 'score', 'total_issues', 'major_issues', 'moderate_issues', 'minor_issues', 'refresh_error', 'expiration_datetime', 'datetime'], sprintf(l('archived_audits.title')));

        /* Prepare the pagination view */
        $pagination = (new \Altum\View('partials/pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Available */
        $audits_current_month = db()->where('user_id', $this->user->user_id)->getValue('users', '`audit_audits_current_month`');

        /* Get statistics */
        if(count($archived_audits) && !$filters->has_applied_filters) {
            $start_date_query = (new \DateTime())->modify('-' . (settings()->main->chat_days ?? 30) . ' day')->format('Y-m-d');
            $end_date_query = (new \DateTime())->modify('+1 day')->format('Y-m-d');

            $convert_tz_sql = get_convert_tz_sql('`datetime`', $this->user->timezone);

            $archived_audits_result_query = "
                SELECT
                    COUNT(*) AS `total`,
                    DATE_FORMAT({$convert_tz_sql}, '%Y-%m-%d') AS `formatted_date`
                FROM
                    `archived_audits`
                WHERE   
                    `user_id` = {$this->user->user_id} 
                    AND ({$convert_tz_sql} BETWEEN '{$start_date_query}' AND '{$end_date_query}')
                GROUP BY
                    `formatted_date`
                ORDER BY
                    `formatted_date`
            ";

            $archived_audits_chart = \Altum\Cache::cache_function_result('archived_audits_chart?user_id=' . $this->user->user_id, null, function() use ($archived_audits_result_query) {
                $archived_audits_chart= [];

                $archived_audits_result = database()->query($archived_audits_result_query);

                /* Generate the raw chart data and save logs for later usage */
                while($row = $archived_audits_result->fetch_object()) {
                    $label = \Altum\Date::get($row->formatted_date, 5, \Altum\Date::$default_timezone);
                    $archived_audits_chart[$label]['total'] = $row->total;
                }

                return $archived_audits_chart;
            }, 60 * 60 * settings()->main->chart_cache ?? 12);

            $archived_audits_chart = get_chart_data($archived_audits_chart);
        }

        /* Prepare the view */
        $data = [
            'archived_audits' => $archived_audits,
            'archived_audits_chart' => $archived_audits_chart ?? null,
            'total_archived_audits' => $total_rows,
            'pagination' => $pagination,
            'filters' => $filters,
            'audits_current_month' => $audits_current_month,
            'audits_stats' => $audits_stats,
        ];

        $view = new \Altum\View('archived-audits/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function bulk() {

        \Altum\Authentication::guard();

        /* Check for any errors */
        if(empty($_POST)) {
            redirect('archived-audits');
        }

        if(empty($_POST['selected'])) {
            redirect('archived-audits');
        }

        if(!isset($_POST['type'])) {
            redirect('archived-audits');
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
                    if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('delete.audits')) {
                        Alerts::add_info(l('global.info_message.team_no_access'));
                        redirect('archived-audits');
                    }

                    $websites_ids = [];

                    foreach($_POST['selected'] as $archived_audit_id) {
                        if($archived_audit = db()->where('archived_audit_id', $archived_audit_id)->where('user_id', $this->user->user_id)->getOne('archived_audits', ['archived_audit_id', 'website_id'])) {

                            db()->where('archived_audit_id', $archived_audit_id)->where('user_id', $this->user->user_id)->delete('archived_audits');
                            if(!in_array($archived_audit->website_id, $websites_ids)) {
                                $websites_ids[] = $archived_audit->website_id;
                            }

                        }
                    }

                    foreach($websites_ids as $website_id) {
                        (new \Altum\Models\Website())->refresh_stats($website_id);
                    }

                    break;
            }

            /* Set a nice success message */
            Alerts::add_success(l('bulk_delete_modal.success_message'));

        }

        redirect('archived-audits');
    }

    public function delete() {

        $error_redirect = is_logged_in() ? 'archived-audits' : 'seo';

        \Altum\Authentication::guard();

        /* Team checks */
        if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('delete.audits')) {
            Alerts::add_info(l('global.info_message.team_no_access'));
            redirect($error_redirect);
        }

        if(empty($_POST)) {
            redirect($error_redirect);
        }

        $archived_audit_id = (int) query_clean($_POST['archived_audit_id']);

        //ALTUMCODE:DEMO if(DEMO) if($this->user->user_id == 1) Alerts::add_error('Please create an account on the demo to test out this function.');

        if(!\Altum\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!$archived_audit = db()->where('archived_audit_id', $archived_audit_id)->getOne('audits', ['archived_audit_id', 'website_id', 'url'])) {
            redirect($error_redirect);
        }

        if($archived_audit->user_id != $this->user->user_id && $archived_audit->uploader_id != md5(get_ip())) {
            redirect($error_redirect);
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Delete the resource */
            db()->where('archived_audit_id', $archived_audit->archived_audit_id)->delete('archived_audits');

            (new \Altum\Models\Website())->refresh_stats($archived_audit->website_id);

            /* Set a nice success message */
            Alerts::add_success(sprintf(l('global.success_message.delete1'), '<strong>' . remove_url_protocol_from_url($archived_audit->url) . '</strong>'));

            redirect($error_redirect);
        }

        redirect($error_redirect);
    }
}
