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
use Altum\Response;
use Altum\Traits\Apiable;
use vipnytt\SitemapParser;

defined('ALTUMCODE') || die();

class ApiArchivedAudits extends Controller {
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

            case 'DELETE':
                $this->delete();
                break;
        }

        $this->return_404();
    }

    private function get_all() {

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters([], [], []));
        $filters->set_default_order_by($this->api_user->preferences->archived_audits_default_order_by, $this->api_user->preferences->default_order_type ?? settings()->main->default_order_type);
        $filters->set_default_results_per_page($this->api_user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);
        $filters->process();

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `archived_audits` WHERE `user_id` = {$this->api_user->user_id}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('api/archived-audits?' . $filters->get_get() . '&page=%d')));

        /* Get the data */
        $data = [];
        $data_result = database()->query("
            SELECT
                *
            FROM
                `archived_audits`
            WHERE
                `user_id` = {$this->api_user->user_id}
                {$filters->get_sql_where()}
                {$filters->get_sql_order_by()}
                  
            {$paginator->get_sql_limit()}
        ");


        while($row = $data_result->fetch_object()) {

            /* Prepare the data */
            $row = [
                'id' => (int) $row->archived_audit_id,
                'audit_id' => (int) $row->audit_id,
                'website_id' => (int) $row->website_id,
                'domain_id' => (int) $row->domain_id,
                'user_id' => (int) $row->user_id,
                'uploader_id' => $row->uploader_id,
                'host' => $row->host,
                'url' => $row->url,
                'ttfb' => (float) $row->ttfb,
                'response_time' => (float) $row->response_time,
                'average_download_speed' => (float) $row->average_download_speed,
                'page_size' => (float) $row->page_size,
                'is_https' => (bool) $row->is_https,
                'is_ssl_valid' => (bool) $row->is_ssl_valid,
                'http_protocol' => (int) $row->http_protocol,
                'title' => $row->title,
                'meta_description' => $row->meta_description,
                'meta_keywords' => $row->meta_keywords,
                'data' => json_decode($row->data),
                'issues' => json_decode($row->issues),
                'score' => (int) $row->score,
                'total_tests' => (int) $row->total_tests,
                'passed_tests' => (int) $row->passed_tests,
                'total_issues' => (int) $row->total_issues,
                'major_issues' => (int) $row->major_issues,
                'moderate_issues' => (int) $row->moderate_issues,
                'minor_issues' => (int) $row->minor_issues,
                'expiration_datetime' => $row->expiration_datetime,
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

        $archived_audit_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        /* Try to get details about the resource id */
        $archived_audit = db()->where('archived_audit_id', $archived_audit_id)->where('user_id', $this->api_user->user_id)->getOne('archived_audits');

        /* We haven't found the resource */
        if(!$archived_audit) {
            $this->return_404();
        }

        /* Prepare the data */
        $data = [
            'id' => (int) $archived_audit->archived_audit_id,
            'audit_id' => (int) $archived_audit->audit_id,
            'website_id' => (int) $archived_audit->website_id,
            'domain_id' => (int) $archived_audit->domain_id,
            'user_id' => (int) $archived_audit->user_id,
            'uploader_id' => $archived_audit->uploader_id,
            'host' => $archived_audit->host,
            'url' => $archived_audit->url,
            'ttfb' => (float) $archived_audit->ttfb,
            'response_time' => (float) $archived_audit->response_time,
            'average_download_speed' => (float) $archived_audit->average_download_speed,
            'page_size' => (float) $archived_audit->page_size,
            'is_https' => (bool) $archived_audit->is_https,
            'is_ssl_valid' => (bool) $archived_audit->is_ssl_valid,
            'http_protocol' => (int) $archived_audit->http_protocol,
            'title' => $archived_audit->title,
            'meta_description' => $archived_audit->meta_description,
            'meta_keywords' => $archived_audit->meta_keywords,
            'data' => json_decode($archived_audit->data),
            'issues' => json_decode($archived_audit->issues),
            'score' => (int) $archived_audit->score,
            'total_tests' => (int) $archived_audit->total_tests,
            'passed_tests' => (int) $archived_audit->passed_tests,
            'total_issues' => (int) $archived_audit->total_issues,
            'major_issues' => (int) $archived_audit->major_issues,
            'moderate_issues' => (int) $archived_audit->moderate_issues,
            'minor_issues' => (int) $archived_audit->minor_issues,
            'expiration_datetime' => $archived_audit->expiration_datetime,
            'datetime' => $archived_audit->datetime,
        ];

        Response::jsonapi_success($data);

    }


    private function delete() {

        $archived_audit_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        /* Try to get details about the resource id */
        $archived_audit = db()->where('archived_audit_id', $archived_audit_id)->where('user_id', $this->api_user->user_id)->getOne('archived_audits');

        /* We haven't found the resource */
        if(!$archived_audit) {
            $this->return_404();
        }

        /* Delete the resource */
        db()->where('archived_audit_id', $archived_audit_id)->delete('archived_audits');

        (new \Altum\Models\Website())->refresh_stats($archived_audit->website_id);

        http_response_code(200);
        die();

    }
}
