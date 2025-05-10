<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <div class="row mb-4">
        <div class="col-12 col-lg d-flex align-items-center mb-3 mb-lg-0 text-truncate">
            <h1 class="h4 m-0 text-truncate"><i class="fas fa-fw fa-xs fa-archive mr-1"></i> <?= l('archived_audits.header') ?></h1>

            <div class="ml-2">
                <span data-toggle="tooltip" title="<?= l('archived_audits.subheader') ?>">
                    <i class="fas fa-fw fa-info-circle text-muted"></i>
                </span>
            </div>
        </div>

        <div class="col-12 col-lg-auto d-flex d-print-none">
            <div>
                <?php if(($this->user->plan_settings->audits_per_month_limit != -1 && $data->audits_current_month >= $this->user->plan_settings->audits_per_month_limit)): ?>
                    <button type="button" class="btn btn-primary disabled" data-toggle="tooltip" title="<?= l('global.info_message.plan_feature_limit') ?>">
                        <i class="fas fa-fw fa-plus-circle fa-sm mr-1"></i> <?= l('audits.create') ?>
                    </button>
                <?php else: ?>
                    <a href="<?= url('dashboard') ?>" class="btn btn-primary" data-toggle="tooltip" data-html="true" title="<?= get_plan_feature_limit_info($data->audits_current_month, $this->user->plan_settings->audits_per_month_limit, isset($data->filters) ? !$data->filters->has_applied_filters : true) ?>">
                        <i class="fas fa-fw fa-plus-circle fa-sm mr-1"></i> <?= l('audits.create') ?>
                    </a>
                <?php endif ?>
            </div>

            <div class="ml-3">
                <div class="dropdown">
                    <button type="button" class="btn btn-light dropdown-toggle-simple <?= count($data->archived_audits) ? null : 'disabled' ?>" data-toggle="dropdown" data-boundary="viewport" data-tooltip title="<?= l('global.export') ?>" data-tooltip-hide-on-click>
                        <i class="fas fa-fw fa-sm fa-download"></i>
                    </button>

                    <div class="dropdown-menu dropdown-menu-right d-print-none">
                        <a href="<?= url('archived-audits?' . $data->filters->get_get() . '&export=csv')  ?>" target="_blank" class="dropdown-item <?= $this->user->plan_settings->export->csv ? null : 'disabled' ?>">
                            <i class="fas fa-fw fa-sm fa-file-csv mr-2"></i> <?= sprintf(l('global.export_to'), 'CSV') ?>
                        </a>
                        <a href="<?= url('archived-audits?' . $data->filters->get_get() . '&export=json') ?>" target="_blank" class="dropdown-item <?= $this->user->plan_settings->export->json ? null : 'disabled' ?>">
                            <i class="fas fa-fw fa-sm fa-file-code mr-2"></i> <?= sprintf(l('global.export_to'), 'JSON') ?>
                        </a>
                        <a href="#" onclick="window.print();return false;" class="dropdown-item <?= $this->user->plan_settings->export->pdf ? null : 'disabled' ?>">
                            <i class="fas fa-fw fa-sm fa-file-pdf mr-2"></i> <?= sprintf(l('global.export_to'), 'PDF') ?>
                        </a>
                    </div>
                </div>
            </div>

            <div class="ml-3">
                <div class="dropdown">
                    <button type="button" class="btn <?= $data->filters->has_applied_filters ? 'btn-dark' : 'btn-light' ?> filters-button dropdown-toggle-simple <?= count($data->archived_audits) || $data->filters->has_applied_filters ? null : 'disabled' ?>" data-toggle="dropdown" data-boundary="viewport" data-tooltip title="<?= l('global.filters.header') ?>" data-tooltip-hide-on-click>
                        <i class="fas fa-fw fa-sm fa-filter"></i>
                    </button>

                    <div class="dropdown-menu dropdown-menu-right filters-dropdown">
                        <div class="dropdown-header d-flex justify-content-between">
                            <span class="h6 m-0"><?= l('global.filters.header') ?></span>

                            <?php if($data->filters->has_applied_filters): ?>
                                <a href="<?= url(\Altum\Router::$original_request) ?>" class="text-muted"><?= l('global.filters.reset') ?></a>
                            <?php endif ?>
                        </div>

                        <div class="dropdown-divider"></div>

                        <form action="" method="get" role="form">
                            <div class="form-group px-4">
                                <label for="filters_search" class="small"><?= l('global.filters.search') ?></label>
                                <input type="search" name="search" id="filters_search" class="form-control form-control-sm" value="<?= $data->filters->search ?>" />
                            </div>

                            <div class="form-group px-4">
                                <label for="filters_search_by" class="small"><?= l('global.filters.search_by') ?></label>
                                <select name="search_by" id="filters_search_by" class="custom-select custom-select-sm">
                                    <option value="url" <?= $data->filters->search_by == 'url' ? 'selected="selected"' : null ?>><?= l('global.url') ?></option>
                                    <option value="host" <?= $data->filters->search_by == 'host' ? 'selected="selected"' : null ?>><?= l('audits.host') ?></option>
                                    <option value="title" <?= $data->filters->search_by == 'title' ? 'selected="selected"' : null ?>><?= l('audits.page_title') ?></option>
                                </select>
                            </div>

                            <div class="form-group px-4">
                                <label for="filters_order_by" class="small"><?= l('global.filters.order_by') ?></label>
                                <select name="order_by" id="filters_order_by" class="custom-select custom-select-sm">
                                    <option value="archived_audit_id" <?= $data->filters->order_by == 'archived_audit_id' ? 'selected="selected"' : null ?>><?= l('global.id') ?></option>
                                    <option value="datetime" <?= $data->filters->order_by == 'datetime' ? 'selected="selected"' : null ?>><?= l('global.filters.order_by_datetime') ?></option>
                                    <option value="url" <?= $data->filters->order_by == 'url' ? 'selected="selected"' : null ?>><?= l('global.url') ?></option>
                                    <option value="host" <?= $data->filters->order_by == 'host' ? 'selected="selected"' : null ?>><?= l('audits.host') ?></option>
                                    <option value="title" <?= $data->filters->order_by == 'title' ? 'selected="selected"' : null ?>><?= l('audits.page_title') ?></option>
                                    <option value="score" <?= $data->filters->order_by == 'score' ? 'selected="selected"' : null ?>><?= l('audits.score') ?></option>
                                    <option value="total_issues" <?= $data->filters->order_by == 'total_issues' ? 'selected="selected"' : null ?>><?= l('audits.total_issues') ?></option>
                                </select>
                            </div>

                            <div class="form-group px-4">
                                <label for="filters_order_type" class="small"><?= l('global.filters.order_type') ?></label>
                                <select name="order_type" id="filters_order_type" class="custom-select custom-select-sm">
                                    <option value="ASC" <?= $data->filters->order_type == 'ASC' ? 'selected="selected"' : null ?>><?= l('global.filters.order_type_asc') ?></option>
                                    <option value="DESC" <?= $data->filters->order_type == 'DESC' ? 'selected="selected"' : null ?>><?= l('global.filters.order_type_desc') ?></option>
                                </select>
                            </div>

                            <div class="form-group px-4">
                                <label for="filters_results_per_page" class="small"><?= l('global.filters.results_per_page') ?></label>
                                <select name="results_per_page" id="filters_results_per_page" class="custom-select custom-select-sm">
                                    <?php foreach($data->filters->allowed_results_per_page as $key): ?>
                                        <option value="<?= $key ?>" <?= $data->filters->results_per_page == $key ? 'selected="selected"' : null ?>><?= $key ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>

                            <div class="form-group px-4 mt-4">
                                <button type="submit" name="submit" class="btn btn-sm btn-primary btn-block"><?= l('global.submit') ?></button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>

            <div class="ml-3">
                <button id="bulk_enable" type="button" class="btn btn-light <?= count($data->archived_audits) ? null : 'disabled' ?>" data-toggle="tooltip" title="<?= l('global.bulk_actions') ?>"><i class="fas fa-fw fa-sm fa-list"></i></button>

                <div id="bulk_group" class="btn-group d-none" role="group">
                    <div class="btn-group dropdown" role="group">
                        <button id="bulk_actions" type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" data-boundary="viewport" aria-haspopup="true" aria-expanded="false">
                            <?= l('global.bulk_actions') ?> <span id="bulk_counter" class="d-none"></span>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="bulk_actions">
                            <a href="#" class="dropdown-item" data-toggle="modal" data-target="#bulk_delete_modal"><i class="fas fa-fw fa-sm fa-trash-alt mr-2"></i> <?= l('global.delete') ?></a>
                        </div>
                    </div>

                    <button id="bulk_disable" type="button" class="btn btn-secondary" data-toggle="tooltip" title="<?= l('global.close') ?>"><i class="fas fa-fw fa-times"></i></button>
                </div>
            </div>
        </div>
    </div>

    <?php if(count($data->archived_audits)): ?>
        <?php if($data->archived_audits_chart): ?>
        <div class="card mb-4">
            <div class="card-body">
                <div class="chart-container <?= !$data->archived_audits_chart['is_empty'] ? null : 'd-none' ?>">
                    <canvas id="archived_audits_chart"></canvas>
                </div>
                <?= !$data->archived_audits_chart['is_empty'] ? null : include_view(THEME_PATH . 'views/partials/no_chart_data.php', ['has_wrapper' => false]); ?>

                <?php if(!$data->archived_audits_chart['is_empty'] && settings()->main->chart_cache ?? 12): ?>
                    <small class="text-muted"><i class="fas fa-fw fa-sm fa-info-circle mr-1"></i> <?= sprintf(l('global.chart_help'), settings()->main->chart_cache ?? 12, settings()->main->chart_days ?? 30) ?></small>
                <?php endif ?>
            </div>
        </div>

<?php require THEME_PATH . 'views/partials/js_chart_defaults.php' ?>

    <?php ob_start() ?>
        <script>
            if(document.getElementById('archived_audits_chart')) {
                let css = window.getComputedStyle(document.body);
                let audits_color = css.getPropertyValue('--primary');
                let audits_color_gradient = null;

                /* Chart */
                let archived_audits_chart = document.getElementById('archived_audits_chart').getContext('2d');

                /* Colors */
                audits_color_gradient = archived_audits_chart.createLinearGradient(0, 0, 0, 250);
                audits_color_gradient.addColorStop(0, set_hex_opacity(audits_color, 0.6));
                audits_color_gradient.addColorStop(1, set_hex_opacity(audits_color, 0.1));

                new Chart(archived_audits_chart, {
                    type: 'line',
                    data: {
                        labels: <?= $data->archived_audits_chart['labels'] ?? '[]' ?>,
                        datasets: [
                            {
                                label: <?= json_encode(l('audits.audits')) ?>,
                                data: <?= $data->archived_audits_chart['total'] ?? '[]' ?>,
                                backgroundColor: audits_color_gradient,
                                borderColor: audits_color,
                                fill: true
                            }
                        ]
                    },
                    options: chart_options
                });
            }
        </script>
    <?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
    <?php endif ?>

        <div class="card mb-3">
            <div class="card-body">
                <?php
                $major_issues_percentage = number_format($data->audits_stats['major_issues'] * 100 / $data->audits_stats['total_tests'], '2', '.', '');
                $moderate_issues_percentage = number_format($data->audits_stats['moderate_issues'] * 100 / $data->audits_stats['total_tests'], '2', '.', '');
                $minor_issues_percentage = number_format($data->audits_stats['minor_issues'] * 100 / $data->audits_stats['total_tests'], '2', '.', '');
                $passed_tests_percentage = number_format($data->audits_stats['passed_tests'] * 100 / $data->audits_stats['total_tests'], '2', '.', '');

                $score_bar_tooltip = '<div class=\'text-left\'>';
                $score_bar_tooltip .= '<div><i class=\'fas fa-fw fa-sm fa-exclamation-circle text-danger mr-1\'></i> ' . sprintf(l('audits.major_issues_x'), nr($data->audits_stats['major_issues'])) . '</div>';
                $score_bar_tooltip .= '<div><i class=\'fas fa-fw fa-sm fa-exclamation-triangle text-warning mr-1\'></i> ' . sprintf(l('audits.moderate_issues_x'), nr($data->audits_stats['moderate_issues'])) . '</div>';
                $score_bar_tooltip .= '<div><i class=\'fas fa-fw fa-sm fa-circle text-muted mr-1\'></i> ' . sprintf(l('audits.minor_issues_x'), nr($data->audits_stats['minor_issues'])) . '</div>';
                $score_bar_tooltip .= '<div><i class=\'fas fa-fw fa-sm fa-check-circle text-success mr-1\'></i> ' . sprintf(l('audits.passed_tests_x'), nr($data->audits_stats['passed_tests'])) . '</div>';
                $score_bar_tooltip .= '</div>';
                ?>

                <div class="d-flex flex-column flex-md-row flex-lg-column flex-xl-row audit-checks-bar-wrapper" data-toggle="tooltip" data-html="true" title="<?= $score_bar_tooltip ?>">
                    <?php if($data->audits_stats['major_issues']): ?>
                        <div class="audit-checks-bar-item bg-danger my-1 my-md-0 my-lg-1 my-xl-0" style="width: <?= $major_issues_percentage ?>%;"></div>
                    <?php endif ?>

                    <?php if($data->audits_stats['moderate_issues']): ?>
                        <div class="audit-checks-bar-item bg-warning my-1 my-md-0 my-lg-1 my-xl-0" style="width: <?= $moderate_issues_percentage ?>%;"></div>
                    <?php endif ?>

                    <?php if($data->audits_stats['minor_issues']): ?>
                        <div class="audit-checks-bar-item bg-gray-600 my-1 my-md-0 my-lg-1 my-xl-0" style="width: <?= $minor_issues_percentage ?>%;"></div>
                    <?php endif ?>

                    <?php if($data->audits_stats['passed_tests']): ?>
                        <div class="audit-checks-bar-item bg-success my-1 my-md-0 my-lg-1 my-xl-0" style="width: <?= $passed_tests_percentage ?>%;"></div>
                    <?php endif ?>
                </div>
            </div>
        </div>

        <form id="table" action="<?= SITE_URL . 'audits/bulk' ?>" method="post" role="form">
            <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" />
            <input type="hidden" name="type" value="" data-bulk-type />
            <input type="hidden" name="original_request" value="<?= base64_encode(\Altum\Router::$original_request) ?>" />
            <input type="hidden" name="original_request_query" value="<?= base64_encode(\Altum\Router::$original_request_query) ?>" />

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
                    </tr>
                    </thead>
                    <tbody>

                    <?php foreach($data->archived_audits as $row): ?>

                        <tr>
                            <td data-bulk-table class="d-none">
                                <div class="custom-control custom-checkbox">
                                    <input id="selected_archived_audit_id_<?= $row->archived_audit_id ?>" type="checkbox" class="custom-control-input" name="selected[]" value="<?= $row->archived_audit_id ?>" />
                                    <label class="custom-control-label" for="selected_archived_audit_id_<?= $row->archived_audit_id ?>"></label>
                                </div>
                            </td>

                            <td class="text-nowrap">
                                <div>
                                    <a href="<?= url('archived-audit/' . $row->archived_audit_id) ?>">
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
                                <span class="mr-2" data-toggle="tooltip" data-html="true" title="<?= sprintf(l('global.datetime_tooltip'), '<br />' . \Altum\Date::get($row->datetime, 2) . '<br /><small>' . \Altum\Date::get($row->datetime, 3) . '</small>' . '<br /><small>(' . \Altum\Date::get_timeago($row->datetime) . ')</small>') ?>">
                                    <i class="fas fa-fw fa-calendar text-muted"></i>
                                </span>
                            </td>

                            <td>
                                <div class="d-flex justify-content-end">
                                    <?= include_view(THEME_PATH . 'views/archived-audits/archived_audit_dropdown_button.php', ['id' => $row->archived_audit_id, 'audit_id' => $row->audit_id, 'resource_name' => $row->host, 'url' => $row->url]) ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach ?>

                    </tbody>
                </table>
            </div>
        </form>

        <div class="mt-3"><?= $data->pagination ?></div>
    <?php else: ?>
        <?= include_view(THEME_PATH . 'views/partials/no_data.php', [
            'filters_get' => $data->filters->get ?? [],
            'name' => 'archived_audits',
            'has_secondary_text' => true,
        ]); ?>
    <?php endif ?>
</div>

<?php require THEME_PATH . 'views/partials/js_bulk.php' ?>
<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/partials/bulk_delete_modal.php'), 'modals'); ?>
