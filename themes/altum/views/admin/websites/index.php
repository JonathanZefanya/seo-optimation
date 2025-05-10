<?php defined('ALTUMCODE') || die() ?>

<div class="d-flex flex-column flex-md-row justify-content-between mb-4">
    <h1 class="h3 mb-3 mb-md-0"><i class="fas fa-fw fa-xs fa-pager text-primary-900 mr-2"></i> <?= l('admin_websites.header') ?></h1>

    <div class="d-flex position-relative d-print-none">
        <div class="">
            <div class="dropdown">
                <button type="button" class="btn btn-gray-300 dropdown-toggle-simple <?= count($data->websites) ? null : 'disabled' ?>" data-toggle="dropdown" data-boundary="viewport" data-tooltip title="<?= l('global.export') ?>" data-tooltip-hide-on-click>
                    <i class="fas fa-fw fa-sm fa-download"></i>
                </button>

                <div class="dropdown-menu dropdown-menu-right d-print-none">
                    <a href="<?= url('admin/websites?' . $data->filters->get_get() . '&export=csv')  ?>" target="_blank" class="dropdown-item <?= $this->user->plan_settings->export->csv ? null : 'disabled' ?>">
                        <i class="fas fa-fw fa-sm fa-file-csv mr-2"></i> <?= sprintf(l('global.export_to'), 'CSV') ?>
                    </a>
                    <a href="<?= url('admin/websites?' . $data->filters->get_get() . '&export=json') ?>" target="_blank" class="dropdown-item <?= $this->user->plan_settings->export->json ? null : 'disabled' ?>">
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
                <button type="button" class="btn <?= $data->filters->has_applied_filters ? 'btn-dark' : 'btn-gray-300' ?> filters-button dropdown-toggle-simple <?= count($data->websites) || $data->filters->has_applied_filters ? null : 'disabled' ?>" data-toggle="dropdown" data-boundary="viewport" data-tooltip title="<?= l('global.filters.header') ?>" data-tooltip-hide-on-click>
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
                            <label for="search_by" class="small"><?= l('global.filters.search_by') ?></label>
                            <select name="search_by" id="search_by" class="custom-select custom-select-sm">
                                <option value="host" <?= $data->filters->order_by == 'host' ? 'selected="selected"' : null ?>><?= l('websites.host') ?></option>
                            </select>
                        </div>

                        <div class="form-group px-4">
                            <label for="order_by" class="small"><?= l('global.filters.order_by') ?></label>
                            <select name="order_by" id="order_by" class="custom-select custom-select-sm">
                                <option value="website_id" <?= $data->filters->order_by == 'website_id' ? 'selected="selected"' : null ?>><?= l('global.id') ?></option>
                                <option value="datetime" <?= $data->filters->order_by == 'datetime' ? 'selected="selected"' : null ?>><?= l('global.filters.order_by_datetime') ?></option>
                                <option value="last_datetime" <?= $data->filters->order_by == 'last_datetime' ? 'selected="selected"' : null ?>><?= l('global.filters.order_by_last_datetime') ?></option>
                                <option value="last_audit_datetime" <?= $data->filters->order_by == 'last_audit_datetime' ? 'selected="selected"' : null ?>><?= l('websites.last_audit_datetime') ?></option>
                                <option value="host" <?= $data->filters->order_by == 'host' ? 'selected="selected"' : null ?>><?= l('audits.host') ?></option>
                                <option value="score" <?= $data->filters->order_by == 'score' ? 'selected="selected"' : null ?>><?= l('audits.score') ?></option>
                                <option value="total_audits" <?= $data->filters->order_by == 'total_audits' ? 'selected="selected"' : null ?>><?= l('websites.total_audits') ?></option>
                                <option value="total_archived_audits" <?= $data->filters->order_by == 'total_archived_audits' ? 'selected="selected"' : null ?>><?= l('websites.total_archived_audits') ?></option>
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
            <button id="bulk_enable" type="button" class="btn btn-gray-300" data-toggle="tooltip" title="<?= l('global.bulk_actions') ?>"><i class="fas fa-fw fa-sm fa-list"></i></button>

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

<?= \Altum\Alerts::output_alerts() ?>

<?php if(count($data->websites)): ?>
    <form id="table" action="<?= SITE_URL . 'admin/websites/bulk' ?>" method="post" role="form">
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
                    <th><?= l('global.user') ?></th>
                    <th><?= l('global.name') ?></th>
                    <th><?= l('audits.audits') ?></th>
                    <th><?= l('audits.score') ?></th>
                    <th><?= l('audits.total_issues') ?></th>
                    <th></th>
                    <th></th>
                </tr>
                </thead>
                <tbody>

                <?php foreach($data->websites as $row): ?>

                    <tr>
                        <td data-bulk-table class="d-none">
                            <div class="custom-control custom-checkbox">
                                <input id="selected_website_id_<?= $row->website_id ?>" type="checkbox" class="custom-control-input" name="selected[]" value="<?= $row->website_id ?>" />
                                <label class="custom-control-label" for="selected_website_id_<?= $row->website_id ?>"></label>
                            </div>
                        </td>

                        <td class="text-nowrap">
                            <div class="d-flex">
                                <a href="<?= url('admin/user-view/' . $row->user_id) ?>">
                                    <img src="<?= get_gravatar($row->user_email) ?>" referrerpolicy="no-referrer" loading="lazy" class="user-avatar rounded-circle mr-3" alt="" />
                                </a>

                                <div class="d-flex flex-column">
                                    <div>
                                        <a href="<?= url('admin/user-view/' . $row->user_id) ?>"><?= $row->user_name ?></a>
                                    </div>

                                    <span class="text-muted small"><?= $row->user_email ?></span>
                                </div>
                            </div>
                        </td>

                        <td class="text-nowrap">
                            <div>
                                <?= $row->host ?>
                            </div>

                            <div class="small">
                                <img referrerpolicy="no-referrer" src="<?= get_favicon_url_from_domain($row->host) ?>" class="img-fluid icon-favicon-small mr-1" loading="lazy" />

                                <span class="text-muted"><?= $row->host ?></span>

                                <a href="<?= 'https://' . $row->host ?>" target="_blank" rel="noreferrer"><i class="fas fa-fw fa-xs fa-external-link-alt text-muted ml-1"></i></a>
                            </div>
                        </td>

                        <td class="text-nowrap">
                            <a href="<?= url('admin/audits?website_id=' . $row->website_id) ?>" class="badge text-audit bg-audit mr-2">
                                <i class="fas fa-fw fa-sm fa-bolt mr-1"></i> <?= nr($row->total_audits) ?>
                            </a>

                            <a href="<?= url('admin/archived-audits?website_id=' . $row->website_id) ?>" class="badge badge-light">
                                <i class="fas fa-fw fa-sm fa-archive mr-1"></i> <?= nr($row->total_archived_audits) ?>
                            </a>
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
                            <a href="<?= url('admin-audit/' . $row->audit_id) ?>" class="badge badge-<?= $audit_badge_bg_class_name ?>" data-html="true" data-toggle="tooltip" title="<?= $badge_tooltip ?>">
                                <?= sprintf(l('audits.total_issues_x'), nr($row->total_issues)) ?>
                            </a>
                        </td>

                        <td class="text-nowrap text-muted">
                            <span class="mr-2" data-toggle="tooltip" data-html="true" title="<?= l('websites.last_audit_datetime') . '<br />' . \Altum\Date::get($row->last_audit_datetime, 2) . '<br /><small>' . \Altum\Date::get($row->last_audit_datetime, 3) . '</small>' . '<br /><small>(' . \Altum\Date::get_timeago($row->last_audit_datetime) . ')</small>' ?>">
                                <i class="fas fa-fw fa-calendar-check text-muted"></i>
                            </span>

                            <span class="mr-2" data-toggle="tooltip" data-html="true" title="<?= sprintf(l('global.datetime_tooltip'), '<br />' . \Altum\Date::get($row->datetime, 2) . '<br /><small>' . \Altum\Date::get($row->datetime, 3) . '</small>' . '<br /><small>(' . \Altum\Date::get_timeago($row->datetime) . ')</small>') ?>">
                                <i class="fas fa-fw fa-calendar text-muted"></i>
                            </span>

                            <span class="mr-2" data-toggle="tooltip" data-html="true" title="<?= sprintf(l('global.last_datetime_tooltip'), ($row->last_datetime ? '<br />' . \Altum\Date::get($row->last_datetime, 2) . '<br /><small>' . \Altum\Date::get($row->last_datetime, 3) . '</small>' . '<br /><small>(' . \Altum\Date::get_timeago($row->last_datetime) . ')</small>' : '<br />-')) ?>">
                                <i class="fas fa-fw fa-history text-muted"></i>
                            </span>
                        </td>

                        <td>
                            <div class="d-flex justify-content-end">
                                <?= include_view(THEME_PATH . 'views/admin/websites/admin_website_dropdown_button.php', ['id' => $row->website_id, 'resource_name' => $row->host,]) ?>
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
        'name' => 'websites',
        'has_secondary_text' => true,
    ]); ?>
<?php endif ?>

<?php require THEME_PATH . 'views/partials/js_bulk.php' ?>
<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/partials/bulk_delete_modal.php'), 'modals'); ?>
