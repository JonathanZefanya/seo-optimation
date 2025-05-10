<?php defined('ALTUMCODE') || die() ?>

<p><?= sprintf(l('audits.email_report.p1', $data->row->language), $data->row->url) ?></p>

<div>
    <table>
        <tbody>
            <tr>
                <th style="text-align: left;"><?= l('audits.host', $data->row->language) ?></th>
                <td class="word-break-all">
                    <span>
                        <?= $data->row->host ?>
                    </span>
                </td>
            </tr>

            <tr>
                <th style="text-align: left;"><?= l('global.url', $data->row->language) ?></th>
                <td class="word-break-all">
                    <span>
                        <?= $data->row->url ?>
                    </span>
                </td>
            </tr>

            <tr>
                <th style="text-align: left;"><?= l('audits.response_time', $data->row->language) ?></th>
                <td class="word-break-all">
                    <span>
                        <?= display_response_time($data->data['response_time']) ?>
                    </span>
                </td>
            </tr>

            <tr>
                <th style="text-align: left;">&nbsp;</th>
                <td class="word-break-all">
                    <span>
                        &nbsp;
                    </span>
                </td>
            </tr>

            <?php
            $audit_score_color = match (true) {
                $data->audit['score'] >= 80 => '#63c669',
                $data->audit['score'] >= 50 => '#fec30e',
                $data->audit['score'] >= 0 => '#da2846',
            };
            ?>
            <tr>
                <th style="text-align: left;"><?= l('audits.score', $data->row->language) ?></th>
                <td class="word-break-all">
                    <span style="color: <?= $audit_score_color ?>">
                        <?= $data->audit['score'] . '%' ?>

                        <?php
                        $one = match (true) {
                            $data->audit['score'] > $data->row->score => '📈',
                            $data->audit['score'] == $data->row->score => '📈',
                            $data->audit['score'] < $data->row->score => '📉',
                        };
                        ?>
                    </span>
                </td>
            </tr>

            <tr>
                <th style="text-align: left;"><?= l('audits.passed_tests', $data->row->language) ?></th>
                <td class="word-break-all">
                    <span>
                        <?= nr($data->audit['passed_tests']) . '/' . nr($data->audit['total_tests']) ?>
                    </span>
                </td>
            </tr>

            <?php
            $audit_total_issues_color = '#63c669';
            if($data->audit['found_minor_issues'] > 0) $audit_total_issues_color = '#747474';
            if($data->audit['found_moderate_issues'] > 0) $audit_total_issues_color = '#fec30e';
            if($data->audit['found_major_issues'] > 0) $audit_total_issues_color = '#da2846';
            ?>

            <tr>
                <th style="text-align: left;"><?= l('audits.total_issues', $data->row->language) ?></th>
                <td class="word-break-all">
                    <span style="color: <?= $audit_total_issues_color ?>">
                        <?= nr($data->audit['total_issues']) ?>
                    </span>
                </td>
            </tr>

            <tr>
                <th style="text-align: left;"><small>↳ <?= l('audits.major_issues', $data->row->language) ?></small></th>
                <td class="word-break-all">
                    <span>
                        <?= nr($data->audit['found_major_issues']) ?>
                    </span>
                </td>
            </tr>

            <tr>
                <th style="text-align: left;"><small>↳ <?= l('audits.moderate_issues', $data->row->language) ?></small></th>
                <td class="word-break-all">
                    <span>
                        <?= nr($data->audit['found_moderate_issues']) ?>
                    </span>
                </td>
            </tr>

            <tr>
                <th style="text-align: left;"><small>↳ <?= l('audits.minor_issues', $data->row->language) ?></small></th>
                <td class="word-break-all">
                    <span>
                        <?= nr($data->audit['found_minor_issues']) ?>
                    </span>
                </td>
            </tr>

            <tr>
                <th style="text-align: left;">&nbsp;</th>
                <td class="word-break-all">
                    <span>
                        &nbsp;
                    </span>
                </td>
            </tr>

            <tr>
                <th style="text-align: left;"><?= l('audits.total_refreshes', $data->row->language) ?></th>
                <td class="word-break-all">
                    <span>
                        <?= nr($data->row->total_refreshes + 1) ?>
                    </span>
                </td>
            </tr>

            <tr>
                <th style="text-align: left;"><?= l('audits.next_refresh_datetime', $data->row->language) ?></th>
                <td class="word-break-all">
                    <span>
                        <?php $next_refresh_datetime = (new \DateTime())->modify('+' . $data->row->settings->audit_check_interval . ' seconds')->format('Y-m-d H:i:s') ?>
                        <?= \Altum\Date::get_time_until($next_refresh_datetime) ?>
                    </span>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<div style="margin-top: 30px">
    <table border="0" cellpadding="0" cellspacing="0" class="btn btn-primary">
        <tbody>
        <tr>
            <td align="center">
                <table border="0" cellpadding="0" cellspacing="0">
                    <tbody>
                    <tr>
                        <td>
                            <a href="<?= url('audit/' . $data->row->audit_id) ?>">
                                <?= l('audits.email_report.button', $data->row->language) ?>
                            </a>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        </tbody>
    </table>
</div>

<hr />
<table width="100%" cellpadding="0" cellspacing="0" role="presentation">
    <tr>
        <td class="note align-center">
            <small><?= sprintf(l('audits.email_report.notice', $data->row->language), '<a href="' . url('audit-update/' . $data->row->audit_id) . '">', '</a>') ?></small>
        </td>
    </tr>
</table>
