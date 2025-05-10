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

class Seo extends Controller {

    public function index() {

        if(is_logged_in()) {
            redirect('dashboard');
        }

        if(!settings()->plan_guest->status) {
            Alerts::add_info(l('global.info_message.plan_feature_no_access'));
            redirect('dashboard');
        }

        if(!$this->user->plan_settings->audits_per_month_limit) {
            Alerts::add_info(l('global.info_message.plan_feature_no_access'));
            redirect('dashboard');
        }

        /* Main View */
        $data = [];

        $view = new \Altum\View('seo/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
