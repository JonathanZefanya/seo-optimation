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

return [
    'dns_lookup' => [
        'icon' => 'fas fa-network-wired',
        'similar' => [
            'ip_lookup',
            'whois_lookup',
            'ping',
            'reverse_ip_lookup',
        ]
    ],

    'ip_lookup' => [
        'icon' => 'fas fa-search-location',
        'similar' => [
            'dns_lookup',
            'whois_lookup',
            'ping',
            'reverse_ip_lookup',
        ]
    ],

    'ssl_lookup' => [
        'icon' => 'fas fa-lock',
        'similar' => [
            'whois_lookup',
            'http_headers_lookup',
            'http2_checker',
        ]
    ],

    'whois_lookup' => [
        'icon' => 'fas fa-fingerprint',
        'similar' => [
            'dns_lookup',
            'ip_lookup',
            'ping',
            'reverse_ip_lookup',
        ]
    ],

    'ping' => [
        'icon' => 'fas fa-server',
        'similar' => [
            'dns_lookup',
            'ip_lookup',
            'whois_lookup',
            'reverse_ip_lookup',
        ]
    ],

    'meta_tags_checker' => [
        'icon' => 'fas fa-external-link-alt',
        'similar' => [
            'http_headers_lookup',
            'url_redirect_checker',
            'website_hosting_checker',
        ]
    ],

    'website_hosting_checker' => [
        'icon' => 'fas fa-server',
        'similar' => [
            'ping',
            'http_headers_lookup',
            'meta_tags_checker',
        ]
    ],

    'http_headers_lookup' => [
        'icon' => 'fas fa-asterisk',
        'similar' => [
            'ssl_lookup',
            'http2_checker',
            'meta_tags_checker',
        ]
    ],

    'http2_checker' => [
        'icon' => 'fas fa-satellite',
        'similar' => [
            'ssl_lookup',
            'http_headers_lookup',
        ]
    ],

    'google_cache_checker' => [
        'icon' => 'fas fa-history',
        'similar' => [
            'url_redirect_checker',
        ]
    ],

    'url_redirect_checker' => [
        'icon' => 'fas fa-directions',
        'similar' => [
            'meta_tags_checker',
            'google_cache_checker',
        ]
    ],

    'reverse_ip_lookup' => [
        'icon' => 'fas fa-book',
        'similar' => [
            'dns_lookup',
            'ip_lookup',
            'whois_lookup',
            'ping',
        ],
    ],

    'brotli_checker' => [
        'icon' => 'fas fa-compress-alt',
        'similar' => [
            'ssl_lookup',
            'http_headers_lookup',
            'http2_checker',
        ]
    ],
];

