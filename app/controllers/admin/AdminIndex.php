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

defined('ALTUMCODE') || die();

class AdminIndex extends Controller {

    public function index() {

        $tools = db()->getOne('tools_usage', 'sum(`total_views`) as `total_views`, sum(`total_submissions`) as `total_submissions`');
        $websites = db()->getValue('websites', 'count(`website_id`)');
        $audits = db()->getValue('audits', 'count(`audit_id`)');
        $archived_audits = db()->getValue('archived_audits', 'count(`archived_audit_id`)');
        $users = db()->getValue('users', 'count(`user_id`)');

        /* Widgets stats: current month */
        extract(\Altum\Cache::cache_function_result('admin_dashboard_current_month', null, function() {
            return [
                'websites_current_month' => db()->where('datetime', date('Y-m-01'), '>=')->getValue('websites', 'count(*)'),
                'audits_current_month' => db()->where('datetime', date('Y-m-01'), '>=')->getValue('audits', 'count(*)'),
                'archived_audits_current_month' => db()->where('datetime', date('Y-m-01'), '>=')->getValue('archived_audits', 'count(*)'),
                'users_current_month' => db()->where('datetime', date('Y-m-01'), '>=')->getValue('users', 'count(*)'),
                'payments_current_month' => in_array(settings()->license->type, ['Extended License', 'extended']) ? db()->where('datetime', date('Y-m-01'), '>=')->getValue('payments', 'count(*)') : 0,
                'payments_amount_current_month' => in_array(settings()->license->type, ['Extended License', 'extended']) ? db()->where('datetime', date('Y-m-01'), '>=')->getValue('payments', 'sum(`total_amount_default_currency`)') : 0,
            ];
        }, 86400));

        /* Get currently active users */
        $fifteen_minutes_ago_datetime = (new \DateTime())->modify('-15 minutes')->format('Y-m-d H:i:s');
        $active_users = db()->where('last_activity', $fifteen_minutes_ago_datetime, '>=')->getValue('users', 'COUNT(*)');

        if(in_array(settings()->license->type, ['Extended License', 'extended'])) {
            $payments = db()->getValue('payments', 'count(`id`)');
            $payments_total_amount = db()->getValue('payments', 'sum(`total_amount_default_currency`)');
        } else {
            $payments = $payments_total_amount = 0;
        }

        if(settings()->internal_notifications->admins_is_enabled) {
            $internal_notifications = db()->where('for_who', 'admin')->orderBy('internal_notification_id', 'DESC')->get('internal_notifications', 5);

            $should_set_all_read = false;
            foreach($internal_notifications as $notification) {
                if(!$notification->is_read) $should_set_all_read = true;
            }

            if($should_set_all_read) {
                db()->where('for_who', 'admin')->update('internal_notifications', [
                    'is_read' => 1,
                    'read_datetime' => get_date(),
                ]);
            }
        }

        /* Requested plan details */
        $plans = (new \Altum\Models\Plan())->get_plans();

        /* Main View */
        $data = [
            'tools' => $tools,
            'websites' => $websites,
            'audits' => $audits,
            'archived_audits' => $archived_audits,
            'payments_total_amount' => $payments_total_amount,
            'users' => $users,
            'payments' => $payments,

            'websites_current_month' => $websites_current_month,
            'audits_current_month' => $audits_current_month,
            'archived_audits_current_month' => $archived_audits_current_month,
            'users_current_month' => $users_current_month,
            'payments_current_month' => $payments_current_month,
            'payments_amount_current_month' => $payments_amount_current_month,

            'plans' => $plans,
            'active_users' => $active_users,
            'internal_notifications' => $internal_notifications ?? [],
        ];

        $view = new \Altum\View('admin/index/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
