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

class Websites extends Controller {

    public function index() {

        \Altum\Authentication::guard();

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters(['user_id', 'domain_id'], ['host',], ['website_id', 'last_datetime', 'datetime', 'last_audit_datetime', 'host', 'total_audits', 'total_archived_audits', 'total_issues', 'score']));
        $filters->set_default_order_by($this->user->preferences->websites_default_order_by, $this->user->preferences->default_order_type ?? settings()->main->default_order_type);
        $filters->set_default_results_per_page($this->user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `websites` WHERE `user_id` = {$this->user->user_id} {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('websites?' . $filters->get_get() . '&page=%d')));

        /* Generate stats */
        $websites_stats = [
            'total_tests' => 0,
            'major_issues' => 0,
            'moderate_issues' => 0,
            'minor_issues' => 0,
            'passed_tests' => 0,
        ];

        /* Get the websites list for the user */
        $websites = [];
        $websites_result = database()->query("SELECT * FROM `websites` WHERE `user_id` = {$this->user->user_id} {$filters->get_sql_where()} {$filters->get_sql_order_by()} {$paginator->get_sql_limit()}");
        while($row = $websites_result->fetch_object()) {
            $websites_stats['total_tests'] += $row->total_tests;
            $websites_stats['major_issues'] += $row->major_issues;
            $websites_stats['moderate_issues'] += $row->moderate_issues;
            $websites_stats['minor_issues'] += $row->minor_issues;
            $websites_stats['passed_tests'] += $row->passed_tests;

            $websites[] = $row;
        }

        /* Export handler */
        process_export_csv($websites, 'include', ['website_id', 'user_id', 'domain_id', 'scheme', 'host', 'score', 'total_audits', 'total_archived_audits', 'total_issues', 'major_issues', 'moderate_issues', 'minor_issues', 'last_audit_datetime', 'datetime', 'last_datetime'], sprintf(l('websites.title')));
        process_export_json($websites, 'include', ['website_id', 'user_id', 'domain_id', 'scheme', 'host', 'score', 'total_audits', 'total_archived_audits', 'total_issues', 'major_issues', 'moderate_issues', 'minor_issues', 'last_audit_datetime', 'datetime', 'last_datetime'], sprintf(l('websites.title')));

        /* Prepare the pagination view */
        $pagination = (new \Altum\View('partials/pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Prepare the view */
        $data = [
            'websites' => $websites,
            'total_websites' => $total_rows,
            'pagination' => $pagination,
            'filters' => $filters,
            'websites_stats' => $websites_stats,
        ];

        $view = new \Altum\View('websites/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function bulk() {

        \Altum\Authentication::guard();

        /* Check for any errors */
        if(empty($_POST)) {
            redirect('websites');
        }

        if(empty($_POST['selected'])) {
            redirect('websites');
        }

        if(!isset($_POST['type'])) {
            redirect('websites');
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
                    if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('delete.websites')) {
                        Alerts::add_info(l('global.info_message.team_no_access'));
                        redirect('websites');
                    }

                    foreach($_POST['selected'] as $website_id) {
                        db()->where('website_id', $website_id)->where('user_id', $this->user->user_id)->delete('websites');
                    }

                    /* Clear the cache */
                    cache()->deleteItem('audits_total?user_id=' . $this->user->user_id);
                    cache()->deleteItem('audits_dashboard?user_id=' . $this->user->user_id);
                    cache()->deleteItem('websites_dashboard?user_id=' . $this->user->user_id);

                    break;
            }

            /* Set a nice success message */
            Alerts::add_success(l('bulk_delete_modal.success_message'));

        }

        redirect('websites');
    }

    public function delete() {

        \Altum\Authentication::guard();

        /* Team checks */
        if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('delete.websites')) {
            Alerts::add_info(l('global.info_message.team_no_access'));
            redirect('websites');
        }

        if(empty($_POST)) {
            redirect('websites');
        }

        $website_id = (int) query_clean($_POST['website_id']);

        //ALTUMCODE:DEMO if(DEMO) if($this->user->user_id == 1) Alerts::add_error('Please create an account on the demo to test out this function.');

        if(!\Altum\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!$website = db()->where('website_id', $website_id)->where('user_id', $this->user->user_id)->getOne('websites', ['website_id', 'host'])) {
            redirect('websites');
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Database query */
            db()->where('website_id', $website_id)->delete('websites');

            /* Set a nice success message */
            Alerts::add_success(sprintf(l('global.success_message.delete1'), '<strong>' . $website->host . '</strong>'));

            /* Clear the cache */
            cache()->deleteItem('audits_total?user_id=' . $this->user->user_id);
            cache()->deleteItem('audits_dashboard?user_id=' . $this->user->user_id);
            cache()->deleteItem('websites_dashboard?user_id=' . $this->user->user_id);

            redirect('websites');
        }

        redirect('websites');
    }
}
