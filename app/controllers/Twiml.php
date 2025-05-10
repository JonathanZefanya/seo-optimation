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

defined('ALTUMCODE') || die();

class Twiml extends Controller {

    public function index() {

        if(!settings()->notification_handlers->twilio_call_is_enabled) {
            redirect();
        }

        $language_key = isset($this->params[0]) ? str_replace('-', '_', input_clean($this->params[0])) : null;

        if(!$language_key) {
            redirect();
        }

        $available_language_keys = [
            't_transfer.download.simple_notification',
        ];

        if(!in_array($language_key, $available_language_keys)) {
            redirect();
        }

        /* Process parameters */
        $parameters = [];
        foreach($_GET as $key => $value) {
            if(string_starts_with('param', $key)) {
                $parameters[] = input_clean($value);
            }
        }

        header('Content-Type: text/xml');

        echo '<?xml version="1.0" encoding="UTF-8"?>';
        echo '<Response>';
        echo '<Say>' . sprintf(l($language_key), ...$parameters) . '</Say>';
        echo '</Response>';

        die();
    }

}
