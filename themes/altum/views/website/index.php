<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <?php if(settings()->main->breadcrumbs_is_enabled): ?>
        <?php if(is_logged_in()): ?>
            <nav aria-label="breadcrumb">
                <ol class="custom-breadcrumbs small">
                    <li>
                        <a href="<?= url('websites') ?>"><?= l('websites.breadcrumb') ?></a><i class="fas fa-fw fa-angle-right"></i>
                    </li>
                    <li class="active" aria-current="page"><?= l('website.breadcrumb') ?></li>
                </ol>
            </nav>
        <?php endif ?>
    <?php endif ?>

    <?php
    $website_score_class_name = match (true) {
        $data->website->score >= 80 => 'success',
        $data->website->score >= 50 => 'warning',
        $data->website->score >= 0 => 'danger',
    };

    $website_score_circle_attributes = [
        'progress' => $data->website->score,
        'size' => 125,
        'circleColor' => 'var(--gray-200)',
        'progressColor' => 'var(--' . $website_score_class_name . ')',
        'circleWidth' => '12px',
        'progressWidth' => '12px',
        'progressShape' => 'round',
        'textColor' => 'var(--' . $website_score_class_name . ')',
        'textSize' => [
            'fontSize' => 30
        ],
        'valueToggle' => true,
        'percentageToggle' => false
    ];
    ?>

    <div class="card mb-2">
        <div class="d-flex flex-row">
            <div class="d-flex align-items-center justify-content-center">
                <div class="audit-score-circle">
                    <?= get_audit_score_circle($website_score_circle_attributes) ?>
                </div>
            </div>

            <div class="card-body text-truncate d-flex justify-content-between align-items-center">
                <div class="text-truncate">
                    <div class="d-flex align-items-center">
                        <img referrerpolicy="no-referrer" src="<?= get_favicon_url_from_domain($data->website->host) ?>" class="img-fluid icon-favicon-small mr-1" loading="lazy" />

                        <span class="small text-muted text-truncate" data-toggle="tooltip" title="<?= $data->website->host ?>">
                            <?= string_truncate($data->website->host, 32) ?>
                        </span>
                    </div>

                    <h1 class="h4 text-truncate mb-0">
                        <?= sprintf(l('website.header'), $data->website->host) ?>

                        <a href="<?= $data->website->scheme . '://' . $data->website->host ?>" class="small" target="_blank" rel="noreferrer">
                            <i class="fas fa-fw fa-xs fa-external-link-alt text-muted ml-1"></i>
                        </a>
                    </h1>

                    <p class="small text-muted white-space-normal m-0">
                        <?= sprintf(l('websites.dynamic_description'), $data->website->score, $data->website->total_issues, $data->website->total_audits) ?>
                    </p>
                </div>

                <div class="d-flex">
                    <button type="button" class="btn btn-link text-secondary <?= $this->user->plan_settings->export->pdf ? null : 'disabled' ?>" onclick="window.print()" data-toggle="tooltip" title="<?= sprintf(l('global.export_to'), 'PDF') ?>" data-tooltip-hide-on-click>
                        <i class="fas fa-fw fa-file-pdf"></i>
                    </button>

                    <?= include_view(THEME_PATH . 'views/websites/website_dropdown_button.php', ['id' => $data->website->website_id, 'resource_name' => $data->website->host]) ?>
                </div>
            </div>
        </div>

        <?php if(count($data->audits)): ?>
        <div class="card-footer bg-white">
            <?php
            $major_issues_percentage = number_format($data->website->major_issues * 100 / $data->website->total_tests, '2', '.', '');
            $moderate_issues_percentage = number_format($data->website->moderate_issues * 100 / $data->website->total_tests, '2', '.', '');
            $minor_issues_percentage = number_format($data->website->minor_issues * 100 / $data->website->total_tests, '2', '.', '');
            $passed_tests_percentage = number_format($data->website->passed_tests * 100 / $data->website->total_tests, '2', '.', '');
            ?>

            <?php
            $score_bar_tooltip = '<div class=\'text-left\'>';
            $score_bar_tooltip .= '<div><i class=\'fas fa-fw fa-sm fa-exclamation-circle text-danger mr-1\'></i> ' . sprintf(l('audits.major_issues_x'), nr($data->website->major_issues)) . '</div>';
            $score_bar_tooltip .= '<div><i class=\'fas fa-fw fa-sm fa-exclamation-triangle text-warning mr-1\'></i> ' . sprintf(l('audits.moderate_issues_x'), nr($data->website->moderate_issues)) . '</div>';
            $score_bar_tooltip .= '<div><i class=\'fas fa-fw fa-sm fa-circle text-muted mr-1\'></i> ' . sprintf(l('audits.minor_issues_x'), nr($data->website->minor_issues)) . '</div>';
            $score_bar_tooltip .= '<div><i class=\'fas fa-fw fa-sm fa-check-circle text-success mr-1\'></i> ' . sprintf(l('audits.passed_tests_x'), nr($data->website->passed_tests)) . '</div>';
            $score_bar_tooltip .= '</div>';
            ?>

            <div class="d-flex flex-column flex-md-row flex-lg-column flex-xl-row audit-checks-bar-wrapper" data-toggle="tooltip" data-html="true" title="<?= $score_bar_tooltip ?>">
                <?php if($data->website->major_issues): ?>
                    <div class="audit-checks-bar-item bg-danger my-1 my-md-0 my-lg-1 my-xl-0" style="width: <?= $major_issues_percentage ?>%;"></div>
                <?php endif ?>

                <?php if($data->website->moderate_issues): ?>
                    <div class="audit-checks-bar-item bg-warning my-1 my-md-0 my-lg-1 my-xl-0" style="width: <?= $moderate_issues_percentage ?>%;"></div>
                <?php endif ?>

                <?php if($data->website->minor_issues): ?>
                    <div class="audit-checks-bar-item bg-gray-600 my-1 my-md-0 my-lg-1 my-xl-0" style="width: <?= $minor_issues_percentage ?>%;"></div>
                <?php endif ?>

                <?php if($data->website->passed_tests): ?>
                    <div class="audit-checks-bar-item bg-success my-1 my-md-0 my-lg-1 my-xl-0" style="width: <?= $passed_tests_percentage ?>%;"></div>
                <?php endif ?>
            </div>
        </div>
        <?php endif ?>
    </div>

    <div class="row">
        <div class="col-12 col-md-6 p-3 text-truncate">
            <div class="card d-flex flex-row h-100 overflow-hidden position-relative">
                <div class="px-3 d-flex flex-column justify-content-center">
                    <div class="p-2 rounded-2x card-widget-icon d-flex align-items-center justify-content-center bg-audit">
                        <i class="fas fa-fw fa-sm fa-user-check text-audit"></i>
                    </div>
                </div>

                <div class="card-body text-truncate">
                    <a href="<?= url('audits?website_id=' . $data->website->website_id) ?>" class="text-reset text-decoration-none stretched-link">
                        <?= sprintf(l('websites.total_audits_x'), nr($data->website->total_audits)) ?>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 p-3 text-truncate">
            <div class="card d-flex flex-row h-100 overflow-hidden position-relative">
                <div class="px-3 d-flex flex-column justify-content-center">
                    <div class="p-2 rounded-2x card-widget-icon d-flex align-items-center justify-content-center bg-gray-50">
                        <i class="fas fa-fw fa-sm fa-archive text-muted"></i>
                    </div>
                </div>

                <div class="card-body text-truncate">
                    <a href="<?= url('archived-audits?website_id=' . $data->website->website_id) ?>" class="text-reset text-decoration-none stretched-link">
                        <?= sprintf(l('websites.total_archived_audits_x'), nr($data->website->total_archived_audits)) ?>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-n2">
        <div class="col-12 col-md-4 p-3 text-truncate">
            <div class="card d-flex flex-row h-100 overflow-hidden" data-toggle="tooltip" data-html="true" title="<?= l('websites.last_audit_datetime') . ($data->website->last_audit_datetime ? '<br />' . \Altum\Date::get($data->website->last_audit_datetime, 2) . '<br /><small>' . \Altum\Date::get($data->website->last_audit_datetime, 3) . '</small>' . '<br /><small>(' . \Altum\Date::get_timeago($data->website->last_audit_datetime) . ')</small>' : '<br />-') ?>">
                <div class="px-3 d-flex flex-column justify-content-center">
                    <div class="p-2 rounded-2x card-widget-icon d-flex align-items-center justify-content-center bg-gray-50">
                        <i class="fas fa-fw fa-sm fa-calendar-check text-muted"></i>
                    </div>
                </div>

                <div class="card-body text-truncate">
                    <?= $data->website->last_audit_datetime ? \Altum\Date::get_timeago($data->website->last_audit_datetime) : '-' ?>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4 p-3 text-truncate">
            <div class="card d-flex flex-row h-100 overflow-hidden" data-toggle="tooltip" data-html="true" title="<?= sprintf(l('global.datetime_tooltip'), '<br />' . \Altum\Date::get($data->website->datetime, 2) . '<br /><small>' . \Altum\Date::get($data->website->datetime, 3) . '</small>' . '<br /><small>(' . \Altum\Date::get_timeago($data->website->datetime) . ')</small>') ?>">
                <div class="px-3 d-flex flex-column justify-content-center">
                    <div class="p-2 rounded-2x card-widget-icon d-flex align-items-center justify-content-center bg-gray-50">
                        <i class="fas fa-fw fa-sm fa-clock text-muted"></i>
                    </div>
                </div>

                <div class="card-body text-truncate">
                    <?= $data->website->datetime ? \Altum\Date::get_timeago($data->website->datetime) : '-' ?>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4 p-3 text-truncate">
            <div class="card d-flex flex-row h-100 overflow-hidden" data-toggle="tooltip" data-html="true" title="<?= sprintf(l('global.last_datetime_tooltip'), ($data->website->last_datetime ? '<br />' . \Altum\Date::get($data->website->last_datetime, 2) . '<br /><small>' . \Altum\Date::get($data->website->last_datetime, 3) . '</small>' . '<br /><small>(' . \Altum\Date::get_timeago($data->website->last_datetime) . ')</small>' : '<br />-')) ?>">
                <div class="px-3 d-flex flex-column justify-content-center">
                    <div class="p-2 rounded-2x card-widget-icon d-flex align-items-center justify-content-center bg-gray-50">
                        <i class="fas fa-fw fa-sm fa-clock-rotate-left text-muted"></i>
                    </div>
                </div>

                <div class="card-body text-truncate">
                    <?= $data->website->last_datetime ? \Altum\Date::get_timeago($data->website->last_datetime) : '-' ?>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <div class="d-flex align-items-center mb-3">
            <h2 class="small font-weight-bold text-uppercase text-muted mb-0 mr-3"><i class="fas fa-fw fa-sm fa-bolt mr-1 text-audit"></i> <?= l('audits.audit') ?></h2>

            <div class="flex-fill">
                <hr class="border-gray-100" />
            </div>

            <div class="ml-3">
                <a href="<?= url('audits?website_id=' . $data->website->website_id) ?>" class="btn btn-sm bg-audit text-audit" data-toggle="tooltip" title="<?= l('global.view_all') ?>"><i class="fas fa-fw fa-bolt fa-sm"></i></a>
            </div>
        </div>

        <?php if(count($data->audits)): ?>
            <div class="table-responsive table-custom-container">
                <table class="table table-custom">
                    <thead>
                    <tr>
                        <th data-bulk-table class="d-none">
                            <div class="custom-control custom-checkbox">
                                <input id="bulk_select_all" type="checkbox" class="custom-control-input" />
                                <label class="custom-control-label" for="bulk_select_all"></label>
                            </div>
                        </th>
                        <th><?= l('audits.audit') ?></th>
                        <th><?= l('audits.score') ?></th>
                        <th><?= l('audits.total_issues') ?></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php foreach($data->audits as $row): ?>

                        <tr>
                            <td data-bulk-table class="d-none">
                                <div class="custom-control custom-checkbox">
                                    <input id="selected_audit_id_<?= $row->audit_id ?>" type="checkbox" class="custom-control-input" name="selected[]" value="<?= $row->audit_id ?>" />
                                    <label class="custom-control-label" for="selected_audit_id_<?= $row->audit_id ?>"></label>
                                </div>
                            </td>

                            <td class="text-nowrap">
                                <div>
                                    <a href="<?= url('audit/' . $row->audit_id) ?>">
                                        <?= string_truncate(remove_url_protocol_from_url($row->url), 32) ?>
                                    </a>
                                </div>

                                <div class="small">
                                    <img referrerpolicy="no-referrer" src="<?= get_favicon_url_from_domain($row->host) ?>" class="img-fluid icon-favicon-small mr-1" loading="lazy" />

                                    <a href="<?= url('website/' . $row->website_id) ?>" class="text-muted"><?= $row->host ?></a>

                                    <a href="<?= $row->url ?>" target="_blank" rel="noreferrer"><i class="fas fa-fw fa-xs fa-external-link-alt text-muted ml-1"></i></a>
                                </div>
                            </td>

                            <td class="text-nowrap text-muted">
                                <div class="d-flex align-items-center">
                                    <div class="mr-3">
                                        <span class="font-weight-bold small"><?= nr($row->score) ?>%</span>
                                    </div>

                                    <?php $rounded_score = round($row->score / 10); ?>

                                    <?php for($i = 0; $i <= 9; $i++): ?>
                                        <?php
                                        if($i <= $rounded_score - 1) {
                                            $audit_badge_bg_class_name = match (true) {
                                                $rounded_score >= 8 => 'success',
                                                $rounded_score >= 5 => 'warning',
                                                $rounded_score >= 0 => 'danger',
                                            };
                                        } else {
                                            $audit_badge_bg_class_name = 'gray-200';
                                        }
                                        ?>

                                        <div class="audit-badge bg-<?= $audit_badge_bg_class_name ?> mr-1"></div>
                                    <?php endfor ?>
                                </div>
                            </td>

                            <?php
                            $audit_badge_bg_class_name = 'success';
                            if($row->minor_issues > 0) $audit_badge_bg_class_name = 'light';
                            if($row->moderate_issues > 0) $audit_badge_bg_class_name = 'warning';
                            if($row->major_issues > 0) $audit_badge_bg_class_name = 'danger';

                            $badge_tooltip = '<div class=\'text-left\'>';
                            $badge_tooltip .= '<div><i class=\'fas fa-fw fa-sm fa-exclamation-circle text-danger mr-1\'></i> ' . sprintf(l('audits.major_issues_x'), nr($row->major_issues)) . '</div>';
                            $badge_tooltip .= '<div><i class=\'fas fa-fw fa-sm fa-exclamation-triangle text-warning mr-1\'></i> ' . sprintf(l('audits.moderate_issues_x'), nr($row->moderate_issues)) . '</div>';
                            $badge_tooltip .= '<div><i class=\'fas fa-fw fa-sm fa-circle text-muted mr-1\'></i> ' . sprintf(l('audits.minor_issues_x'), nr($row->minor_issues)) . '</div>';
                            $badge_tooltip .= '<div><i class=\'fas fa-fw fa-sm fa-check-circle text-success mr-1\'></i> ' . sprintf(l('audits.passed_tests_x'), nr($row->passed_tests)) . '</div>';
                            $badge_tooltip .= '</div>';
                            ?>

                            <td class="text-nowrap text-muted">
                                <a href="<?= url('audit/' . $row->audit_id) ?>" class="badge badge-<?= $audit_badge_bg_class_name ?>" data-html="true" data-toggle="tooltip" title="<?= $badge_tooltip ?>">
                                    <?= sprintf(l('audits.total_issues_x'), nr($row->total_issues)) ?>
                                </a>
                            </td>

                            <td class="text-nowrap text-muted">
                                <a href="<?= url('archived-audits?audit_id=' . $row->audit_id) ?>" class="mr-2" data-toggle="tooltip" title="<?= l('archived_audits.menu') ?>">
                                    <i class="fas fa-fw fa-archive text-muted"></i>
                                </a>
                            </td>

                            <td class="text-nowrap text-muted">
                                <span class="mr-2" data-toggle="tooltip" data-html="true" title="<?= l('audits.last_refresh_datetime') . '<br />' . ($row->last_refresh_datetime ? (\Altum\Date::get($row->last_refresh_datetime, 2) . '<br /><small>' . \Altum\Date::get($row->last_refresh_datetime, 3) . '</small>' . '<br /><small>(' . \Altum\Date::get_timeago($row->last_refresh_datetime) . ')</small>') : '-') ?>">
                                    <i class="fas fa-fw fa-calendar-check text-muted"></i>
                                </span>

                                <span class="mr-2" data-toggle="tooltip" data-html="true" title="<?= l('audits.next_refresh_datetime') . '<br />' . ($row->next_refresh_datetime ? (\Altum\Date::get($row->next_refresh_datetime, 2) . '<br /><small>' . \Altum\Date::get($row->next_refresh_datetime, 3) . '</small>' . '<br /><small>(' . \Altum\Date::get_time_until($row->next_refresh_datetime) . ')</small>') : '-') ?>">
                                    <i class="fas fa-fw fa-retweet text-muted"></i>
                                </span>

                                <span class="mr-2" data-toggle="tooltip" data-html="true" title="<?= sprintf(l('global.datetime_tooltip'), '<br />' . \Altum\Date::get($row->datetime, 2) . '<br /><small>' . \Altum\Date::get($row->datetime, 3) . '</small>' . '<br /><small>(' . \Altum\Date::get_timeago($row->datetime) . ')</small>') ?>">
                                    <i class="fas fa-fw fa-calendar text-muted"></i>
                                </span>

                                <span class="mr-2" data-toggle="tooltip" data-html="true" title="<?= sprintf(l('global.last_datetime_tooltip'), ($row->last_datetime ? '<br />' . \Altum\Date::get($row->last_datetime, 2) . '<br /><small>' . \Altum\Date::get($row->last_datetime, 3) . '</small>' . '<br /><small>(' . \Altum\Date::get_timeago($row->last_datetime) . ')</small>' : '<br />-')) ?>">
                                    <i class="fas fa-fw fa-history text-muted"></i>
                                </span>

                                <?php if($row->settings->password): ?>
                                    <span class="mr-2" data-toggle="tooltip" title="<?= l('global.password') . ': ' . l('global.yes') ?>">
                                        <i class="fas fa-fw fa-lock text-muted"></i>
                                    </span>
                                <?php else: ?>
                                    <span class="mr-2" data-toggle="tooltip" title="<?= l('global.password') . ': ' . l('global.no') ?>">
                                        <i class="fas fa-fw fa-lock-open text-muted"></i>
                                    </span>
                                <?php endif ?>

                                <?php if($row->settings->is_public): ?>
                                    <span class="mr-2" data-toggle="tooltip" title="<?= l('audits.input.is_public') . ': ' . l('global.yes') ?>">
                                        <i class="fas fa-fw fa-eye text-muted"></i>
                                    </span>
                                <?php else: ?>
                                    <span class="mr-2" data-toggle="tooltip" title="<?= l('audits.input.is_public') . ': ' . l('global.no') ?>">
                                        <i class="fas fa-fw fa-eye-slash text-muted"></i>
                                    </span>
                                <?php endif ?>
                            </td>

                            <td>
                                <div class="d-flex justify-content-end">
                                    <?= include_view(THEME_PATH . 'views/audits/audit_dropdown_button.php', ['id' => $row->audit_id, 'resource_name' => remove_url_protocol_from_url($row->url), 'url' => $row->url]) ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach ?>

                    </tbody>
                </table>
            </div>
        <?php else: ?>

            <?= include_view(THEME_PATH . 'views/partials/no_data.php', [
                'filters_get' => $data->filters->get ?? [],
                'name' => 'audits',
                'has_secondary_text' => true,
            ]); ?>

        <?php endif ?>
    </div>
</div>
