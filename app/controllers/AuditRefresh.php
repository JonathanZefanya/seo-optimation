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

class AuditRefresh extends Controller {

    public function index() {

        set_time_limit(0);

        if(empty($_POST)) redirect();

        $error_redirect = is_logged_in() ? 'archived-audits' : 'seo';

        /* Team checks */
        if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('create.audits')) {
            Alerts::add_info(l('global.info_message.team_no_access'));
            redirect($error_redirect);
        }

        /* Check for the plan limit */
        $audits_current_month = db()->where('user_id', $this->user->user_id)->getValue('users', '`audit_audits_current_month`');

        if($this->user->plan_settings->audits_per_month_limit != -1 && $audits_current_month >= $this->user->plan_settings->audits_per_month_limit) {
            Alerts::add_info(l('global.info_message.plan_feature_limit'));
            redirect($error_redirect);
        }

        //ALTUMCODE:DEMO if(DEMO) if($this->user->user_id == 1) Alerts::add_error('Please create an account on the demo to test out this function.');

        /* Check for any errors */
        $required_fields = ['audit_id'];
        foreach($required_fields as $field) {
            if(!isset($_POST[$field]) ||(isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                Alerts::add_field_error($field, l('global.error_message.empty_field'));
            }
        }

        if(!\Altum\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!$audit = db()->where('audit_id', $_POST['audit_id'])->getOne('audits')) {
            redirect($error_redirect);
        }
        foreach(['settings'] as $key) $audit->{$key} = json_decode($audit->{$key} ?? '');


        if($audit->user_id != $this->user->user_id && $audit->uploader_id != md5(get_ip())) {
            redirect($error_redirect);
        }

        /* Send the main request */
        try {
            $response = \Altum\Helpers\Audit::process_request($audit->url);
        } catch(\Exception $exception) {
            Alerts::add_error($exception->getMessage());
        }

        /* Single URL processing */
        $data = \Altum\Helpers\Audit::process_request_response($audit->url, $response);
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

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Insert a log of the current update as old */
            db()->insert('archived_audits', [
                'audit_id' => $audit->audit_id,
                'user_id' => $audit->user_id,
                'domain_id' => $audit->domain_id,
                'uploader_id' => $audit->uploader_id,
                'website_id' => $audit->website_id,
                'host' => $audit->host,
                'url' => $audit->url,
                'ttfb' => $audit->ttfb,
                'response_time' => $audit->response_time,
                'average_download_speed' => $audit->average_download_speed,
                'page_size' => $audit->page_size,
                'http_requests' => $audit->http_requests,
                'is_https' => $audit->is_https,
                'is_ssl_valid' => $audit->is_ssl_valid,
                'http_protocol' => $audit->http_protocol,
                'title' => $audit->title,
                'meta_description' => $audit->meta_description,
                'meta_keywords' => $audit->meta_keywords,
                'data' => $audit->data,
                'issues' => $audit->issues,
                'score' => $audit->score,
                'total_tests' => $audit->total_tests,
                'passed_tests' => $audit->passed_tests,
                'total_issues' => $audit->total_issues,
                'major_issues' => $audit->major_issues,
                'moderate_issues' => $audit->moderate_issues,
                'minor_issues' => $audit->minor_issues,
                'expiration_datetime' => $audit->expiration_datetime,
                'datetime' => $audit->last_refresh_datetime ?: $audit->datetime,
            ]);

            /* Prepare expiration date */
            $expiration_datetime = (new \DateTime())->modify('+' . ($this->user->plan_settings->audits_retention ?? 90) . ' days')->format('Y-m-d H:i:s');

            /* Next refresh date */
            $next_refresh_datetime = $audit->settings->audit_check_interval ? (new \DateTime())->modify('+' . $audit->settings->audit_check_interval . ' seconds')->format('Y-m-d H:i:s') : null;

            /* Update the main audit */
            db()->where('audit_id', $audit->audit_id)->update('audits', [
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

            (new \Altum\Models\Website())->refresh_stats($audit->website_id);

            /* Database query */
            db()->where('user_id', $this->user->user_id)->update('users', [
                'audit_audits_current_month' => db()->inc()
            ]);

            /* Set a nice success message */
            Alerts::add_success(sprintf(l('audits.success_message.processed_refresh'), '<strong>' . remove_url_protocol_from_url($audit->url) . '</strong>'));
            redirect('audit/' . $audit->audit_id);
        }

        redirect($error_redirect);
    }

}
