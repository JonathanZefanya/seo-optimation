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

defined('ALTUMCODE') || die();

$access = [
    'read' => [
        'read.all' => l('global.all')
    ],

    'create' => [
        'create.websites' => l('websites.title'),
        'create.audits' => l('audits.title'),
        'create.notification_handlers' => l('notification_handlers.title'),
    ],

    'update' => [
        'update.websites' => l('websites.title'),
        'update.audits' => l('audits.title'),
        'update.notification_handlers' => l('notification_handlers.title'),
    ],

    'delete' => [
        'delete.websites' => l('websites.title'),
        'delete.audits' => l('audits.title'),
        'delete.notification_handlers' => l('notification_handlers.title'),
    ],
];

if(settings()->audits->domains_is_enabled) {
    $access['create']['create.domains'] = l('domains.title');
    $access['update']['update.domains'] = l('domains.title');
    $access['delete']['delete.domains'] = l('domains.title');
}

return $access;
