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

namespace Altum\models;

defined('ALTUMCODE') || die();

class Tools extends Model {

    public function get_tools_usage() {

        $cache_instance = cache()->getItem('tools_usage');

        /* Set cache if not existing */
        if(!$cache_instance->get()) {

            $result = database()->query("SELECT * FROM `tools_usage` ORDER BY `total_views` DESC");
            $data = [];

            while($row = $result->fetch_object()) {
                $row->data = json_decode($row->data ?? '');
                $data[$row->tool_id] = $row;
            }

            cache()->save($cache_instance->set($data)->expiresAfter(3600));

        } else {

            /* Get cache */
            $data = $cache_instance->get('tools_usage');

        }

        return $data;
    }

}
