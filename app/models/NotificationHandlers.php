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

defined('ALTUMCODE') || die();

class NotificationHandlers extends Model {

    public function get_notification_handlers_by_user_id($user_id) {
        if(!$user_id) return [];

        /* Get the user notification handlers */
        $notification_handlers = [];

        /* Try to check if the user posts exists via the cache */
        $cache_instance = cache()->getItem('notification_handlers?user_id=' . $user_id);

        /* Set cache if not existing */
        if(is_null($cache_instance->get())) {

            /* Get data from the database */
            $notification_handlers_result = database()->query("SELECT * FROM `notification_handlers` WHERE `user_id` = {$user_id}");
            while($row = $notification_handlers_result->fetch_object()) {
                $row->settings = json_decode($row->settings ?? '');
                $notification_handlers[$row->notification_handler_id] = $row;
            }

            cache()->save(
                $cache_instance->set($notification_handlers)->expiresAfter(CACHE_DEFAULT_SECONDS)->addTag('user_id=' . $user_id)
            );

        } else {

            /* Get cache */
            $notification_handlers = $cache_instance->get();

        }

        return $notification_handlers;

    }

}
