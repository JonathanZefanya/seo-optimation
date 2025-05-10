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

class AdminArchivedAudits extends Controller {

    public function index() {

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters(['user_id', 'website_id', 'domain_id', 'audit_id'], ['url', 'host', 'title'], ['audit_id', 'archived_audit_id', 'datetime', 'expiration_datetime', 'url', 'host', 'score', 'total_issues', 'title']));
        $filters->set_default_order_by($this->user->preferences->archived_audits_default_order_by, $this->user->preferences->default_order_type ?? settings()->main->default_order_type);
        $filters->set_default_results_per_page($this->user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `archived_audits` WHERE 1 = 1 {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('admin/audits?' . $filters->get_get() . '&page=%d')));

        /* Get the audits list for the user */
        $archived_audits = [];
        $archived_audits_result = database()->query("
            SELECT
                `archived_audits`.*, `users`.`name` AS `user_name`, `users`.`email` AS `user_email`
            FROM
                `archived_audits`
            LEFT JOIN
                `users` ON `archived_audits`.`user_id` = `users`.`user_id`
            WHERE
                1 = 1
                {$filters->get_sql_where('archived_audits')}
                {$filters->get_sql_order_by('archived_audits')}
            
            {$paginator->get_sql_limit()}
        ");
        while($row = $archived_audits_result->fetch_object()) {
            $archived_audits[] = $row;
        }

        /* Export handler */
        process_export_csv($archived_audits, 'include', ['archived_audit_id', 'audit_id' ,'user_id', 'domain_id', 'uploader_id', 'host', 'url', 'ttfb', 'response_time', 'average_download_speed', 'page_size', 'http_requests', 'is_https', 'is_ssl_valid', 'http_protocol', 'title', 'meta_description', 'meta_keywords', 'score', 'total_issues', 'major_issues', 'moderate_issues', 'minor_issues', 'refresh_error', 'expiration_datetime', 'datetime'], sprintf(l('archived_audits.title')));
        process_export_json($archived_audits, 'include', ['archived_audit_id', 'audit_id' ,'user_id', 'domain_id', 'uploader_id', 'host', 'url', 'ttfb', 'response_time', 'average_download_speed', 'page_size', 'http_requests', 'is_https', 'is_ssl_valid', 'http_protocol', 'title', 'meta_description', 'meta_keywords', 'data', 'issues', 'score', 'total_issues', 'major_issues', 'moderate_issues', 'minor_issues', 'refresh_error', 'expiration_datetime', 'datetime'], sprintf(l('archived_audits.title')));

        /* Prepare the pagination view */
        $pagination = (new \Altum\View('partials/pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Prepare the view */
        $data = [
            'archived_audits' => $archived_audits,
            'pagination' => $pagination,
            'filters' => $filters,
        ];

        $view = new \Altum\View('admin/archived-audits/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function bulk() {

        /* Check for any errors */
        if(empty($_POST)) {
            redirect('admin/archived-audits');
        }

        if(empty($_POST['selected'])) {
            redirect('admin/archived-audits');
        }

        if(!isset($_POST['type'])) {
            redirect('admin/archived-audits');
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

                    foreach($_POST['selected'] as $archived_audit_id) {
                        if($archived_audit = db()->where('archived_audit_id', $archived_audit_id)->getOne('archived_audits', ['archived_audit_id', 'website_id'])) {

                            db()->where('archived_audit_id', $archived_audit_id)->delete('archived_audits');
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

        redirect('admin/archived-audits');
    }

    public function delete() {

        $archived_audit_id = (isset($this->params[0])) ? (int) $this->params[0] : null;

        //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

        if(!\Altum\Csrf::check('global_token')) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!$archived_audit = db()->where('archived_audit_id', $archived_audit_id)->getOne('archived_audits', ['archived_audit_id', 'website_id', 'url'])) {
            redirect('admin/archived-audits');
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {
            db()->where('archived_audit_id', $archived_audit_id)->delete('archived_audits');

            (new \Altum\Models\Website())->refresh_stats($archived_audit->website_id);

            /* Set a nice success message */
            Alerts::add_success(sprintf(l('global.success_message.delete1'), '<strong>' . remove_url_protocol_from_url($archived_audit->url) . '</strong>'));

        }

        redirect('admin/archived-audits');
    }
}
