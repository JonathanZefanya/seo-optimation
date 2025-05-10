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

namespace Altum\Models;

use Altum\Language;

defined('ALTUMCODE') || die();

class Page extends Model {

    public function get_pages($position) {

        $data = [];

        $cache_instance = cache()->getItem('pages_' . $position);

        /* Set cache if not existing */
        if(is_null($cache_instance->get())) {
            $result = database()->query("SELECT `url`, `title`, `type`, `open_in_new_tab`, `language`, `icon` FROM `pages` WHERE `position` = '{$position}' AND `is_published` = 1 ORDER BY `order`");

            while($row = $result->fetch_object()) {
                $data[] = $row;
            }

            cache()->save($cache_instance->set($data)->expiresAfter(CACHE_DEFAULT_SECONDS)->addTag('pages'));

        } else {

            /* Get cache */
            $data = $cache_instance->get();

        }

        foreach($data as $key => $value) {
            /* Make sure the language of the page still exists */
            if($value->language && !isset(\Altum\Language::$active_languages[$value->language])) {
                unset($data[$key]);
                continue;
            }

            if($value->type == 'internal') {
                $value->target = '_self';
                $value->url = SITE_URL . ($value->language ? \Altum\Language::$active_languages[$value->language] . '/' : null) . 'page/' . $value->url;
            } else {
                $value->target = $value->open_in_new_tab ? '_blank' : '_self';
            }

            /* Check language */
            if($value->language && $value->language != Language::$name) {
                unset($data[$key]);
            }
        }

        return $data;
    }

}
