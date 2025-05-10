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
use Altum\Meta;
use Altum\Title;

defined('ALTUMCODE') || die();

class Website extends Controller {
    public $website;

    public function index()
    {

        $website_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        if(!$website = db()->where('website_id', $website_id)->getOne('websites')) {
            redirect();
        }
        foreach(['website', 'settings'] as $key) $website->{$key} = json_decode($website->{$key} ?? '');

        /* Public website */
        if(!$website->settings->is_public) {

            /* Make sure the current user has access */
//            if(($website->uploader_id != md5(get_ip())) && (!$website->user_id || ($website->user_id != $this->user->user_id))) {
//                redirect();
//            }
            if(!$website->user_id || ($website->user_id != $this->user->user_id)) {
                redirect();
            }

        }

        /* Audit */
        $website->full_url = (isset(\Altum\Router::$data['domain']) ? \Altum\Router::$data['domain']->url : url()) . 'website/' . $website_id;

        /* Meta */
        Meta::set_canonical_url($website->full_url);

        /* Check if the user has access to the page */
        $has_access = !$website->settings->password || ($website->settings->password && isset($_COOKIE['password_' . $website->website_id]) && $_COOKIE['password_' . $website->website_id] == $website->settings->password);

        /* Do not let the user have password protection if the plan doesn't allow it */
        if(!$this->user->plan_settings->password_protection_is_enabled) {
            $has_access = true;
        }

        /* Check if the password form is submitted */
        if(!$has_access && !empty($_POST)) {

            /* Check for any errors */
            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!password_verify($_POST['password'], $website->settings->password)) {
                Alerts::add_field_error('password', l('audits.password.error_message'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                /* Set a cookie */
                setcookie('password_' . $website->website_id, $website->settings->password, time() + 60 * 60 * 24 * 30);

                header('Location: ' . $_SERVER['REQUEST_URI']);
                die();

            }

        }

        /* Display the password form */
        if(!$has_access) {

            /* Set a custom title */
            Title::set(l('websites.password.title'));

            /* Main View */
            $data = [
                'website' => $website,
            ];

            $view = new \Altum\View('website/password', (array)$this);
            $this->add_view_content('content', $view->run($data));

        }

        /* Show website */
        else {

            /* Get the last audits */
            $audits = db()->where('website_id', $website->website_id)->orderBy('last_refresh_datetime', 'DESC')->orderBy('audit_id', 'DESC')->get('audits', $this->user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);
            foreach($audits as $row) $row->settings = json_decode($row->settings ?? '');



            /* Set a custom title */
            Title::set(sprintf(l('website.title'), $website->host));

            $data = [
                'website' => $website,
                'audits' => $audits,
            ];

            $view = new \Altum\View('website/index', (array)$this);
            $this->add_view_content('content', $view->run($data));

        }
    }

}
