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

class Website extends Model {

    public function refresh_stats($website_id) {

        $audits = db()->where('website_id', $website_id)->getOne('audits', '
            ROUND(AVG(`score`)) as `score`,
            COUNT(*) as `total_audits`,
            SUM(`total_tests`) as `total_tests`,
            SUM(`passed_tests`) as `passed_tests`,
            SUM(`total_issues`) as `total_issues`,
            SUM(`major_issues`) as `major_issues`,
            SUM(`moderate_issues`) as `moderate_issues`,
            SUM(`minor_issues`) as `minor_issues`
        ');

        $total_archived_audits = db()->where('website_id', $website_id)->getValue('archived_audits', 'count(*)');

        db()->where('website_id', $website_id)->update('websites', [
            'score' => $audits->score,
            'total_audits' => $audits->total_audits,
            'total_archived_audits' => $total_archived_audits,
            'total_tests' => $audits->total_tests,
            'total_issues' => $audits->total_issues,
            'passed_tests' => $audits->passed_tests,
            'major_issues' => $audits->major_issues,
            'moderate_issues' => $audits->moderate_issues,
            'minor_issues' => $audits->minor_issues,
            'last_audit_datetime' => get_date(),
        ]);

    }

}
