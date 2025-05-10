<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <div class="mb-3 d-flex justify-content-between">
        <div>
            <h1 class="h4 mb-0 text-truncate"><i class="fas fa-fw fa-xs fa-table-cells mr-1"></i> <?= l('dashboard.header') ?></h1>
        </div>
    </div>

    <div class="mt-4 mb-3">
        <div class="card">
            <div class="card-body">

                <form id="audit_create_form" action="<?= url('audit-create') ?>" method="post" role="form">
                    <input type="hidden" name="token" value="<?= \Altum\Csrf::get('token') ?>" />

                    <div class="notification-container"></div>

                    <div class="row">
                        <div class="col-12 col-lg mb-3 mb-lg-0">
                            <div data-type="single" <?= ($_SESSION['audit_form']['type'] ?? 'single') != 'single' ? 'class="d-none"' : null ?>>
                                <input id="url" type="text" name="url" class="form-control <?= \Altum\Alerts::has_field_errors('url') ? 'is-invalid' : null ?>" value="<?= $_SESSION['audit_form']['url'] ?? null ?>" placeholder="<?= l('global.url_placeholder') ?>" aria-label="<?= l('global.url') ?>" maxlength=2048" required="required" data-url-autofocus />
                            </div>

                            <div data-type="sitemap" <?= ($_SESSION['audit_form']['type'] ?? 'single') != 'sitemap' ? 'class="d-none"' : null ?>>
                                <input id="url" type="text" name="url" class="form-control <?= \Altum\Alerts::has_field_errors('url') ? 'is-invalid' : null ?>" value="<?= $_SESSION['audit_form']['url'] ?? null ?>" placeholder="<?= l('audits.input.sitemap_url_placeholder') ?>" aria-label="<?= l('global.url') ?>" maxlength=2048" required="required"  />
                            </div>

                            <div data-type="bulk" class="d-none" <?= ($_SESSION['audit_form']['type'] ?? 'single') != 'bulk' ? 'class="d-none"' : null ?>>
                                <textarea id="urls" type="urls" name="urls" class="form-control <?= \Altum\Alerts::has_field_errors('url') ? 'is-invalid' : null ?>" placeholder="<?= l('audits.input.bulk_url_placeholder1') . "\n" . l('audits.input.bulk_url_placeholder2') ?>" aria-label="<?= l('global.url') ?>" required="required"><?= $_SESSION['audit_form']['urls'] ?? null ?></textarea>
                                <small class="form-text text-muted"><?= l('audits.input.bulk_url_help') ?></small>
                            </div>

                            <div data-type="html" class="d-none" <?= ($_SESSION['audit_form']['type'] ?? 'single') != 'html' ? 'class="d-none"' : null ?>>
                                <input id="url" type="text" name="url" class="form-control <?= \Altum\Alerts::has_field_errors('url') ? 'is-invalid' : null ?>" value="<?= $_SESSION['audit_form']['url'] ?? null ?>" placeholder="<?= l('global.url_placeholder') ?>" aria-label="<?= l('global.url') ?>" maxlength=2048" required="required"  />
                                <small class="form-text text-muted"><?= l('audits.input.html_url_help') ?></small>
                            </div>

                            <div data-type="html" class="d-none" <?= ($_SESSION['audit_form']['type'] ?? 'html') != 'sitemap' ? 'class="d-none"' : null ?>>
                                <textarea id="html" type="html" name="html" class="mt-3 form-control <?= \Altum\Alerts::has_field_errors('url') ? 'is-invalid' : null ?>" placeholder="<?= l('audits.input.html_placeholder') ?>" aria-label="<?= l('audits.input.html') ?>" required="required"><?= $_SESSION['audit_form']['html'] ?? null ?></textarea>
                                <small class="form-text text-muted"><?= l('audits.input.html_help') ?></small>
                            </div>
                            <?= \Altum\Alerts::output_field_error('url') ?>
                        </div>

                        <div class="col-12 col-lg-auto">
                            <div class="row no-gutters">
                                <div class="col-auto mr-1">
                                    <button type="button" class="btn btn-block btn-outline-primary" data-tooltip title="<?= l('audits.settings') ?>" data-toggle="collapse" data-target="#audit_settings" aria-expanded="false" aria-controls="audit_settings">
                                        <i class="fas fa-fw fa-sm fa-wrench"></i>
                                    </button>
                                </div>

                                <div class="col">
                                    <button type="submit" name="submit" class="btn btn-block btn-primary" data-is-ajax>
                                        <i class="fas fa-fw fa-sm fa-bolt mr-1"></i>
                                        <?= l('audits.submit') ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="collapse mt-4 <?= ($_SESSION['audit_form']['type'] ?? 'single') != 'single' ? 'show' : null ?>" id="audit_settings">

                        <div class="form-group">
                            <label for="type"><i class="fas fa-fw fa-sm fa-fingerprint text-muted mr-1"></i> <?= l('global.type') ?></label>
                            <div class="row btn-group-toggle" data-toggle="buttons">
                                <div class="col-12 col-lg-6">
                                    <label class="btn btn-light btn-block text-truncate <?= ($_SESSION['audit_form']['type'] ?? 'single') == 'single' ? 'active"' : null?>">
                                        <input type="radio" name="type" value="single" class="custom-control-input" <?= ($_SESSION['audit_form']['type'] ?? 'single') == 'single' ? 'checked="checked"' : null?> required="required" />
                                        <i class="fas fa-link fa-fw fa-sm mr-1"></i> <?= l('audits.input.type.single') ?>
                                    </label>
                                </div>

                                <div class="col-12 col-lg-6">
                                    <label class="btn btn-light btn-block text-truncate <?= ($_SESSION['audit_form']['type'] ?? 'single') == 'sitemap' ? 'active"' : null?>">
                                        <input type="radio" name="type" value="sitemap" class="custom-control-input" <?= ($_SESSION['audit_form']['type'] ?? 'single') == 'sitemap' ? 'checked="checked"' : null?> required="required" />
                                        <i class="fas fa-network-wired fa-fw fa-sm mr-1"></i> <?= l('audits.input.type.sitemap') ?>
                                    </label>
                                </div>

                                <div class="col-12 col-lg-6">
                                    <label class="btn btn-light btn-block text-truncate <?= ($_SESSION['audit_form']['type'] ?? 'single') == 'bulk' ? 'active"' : null?>">
                                        <input type="radio" name="type" value="bulk" class="custom-control-input" <?= ($_SESSION['audit_form']['type'] ?? 'single') == 'bulk' ? 'checked="checked"' : null?> required="required" />
                                        <i class="fas fa-layer-group fa-fw fa-sm mr-1"></i> <?= l('audits.input.type.bulk') ?>
                                    </label>
                                </div>

                                <div class="col-12 col-lg-6">
                                    <label class="btn btn-light btn-block text-truncate <?= ($_SESSION['audit_form']['type'] ?? 'single') == 'html' ? 'active"' : null?>">
                                        <input type="radio" name="type" value="html" class="custom-control-input" <?= ($_SESSION['audit_form']['type'] ?? 'single') == 'html' ? 'checked="checked"' : null?> required="required" />
                                        <i class="fas fa-code fa-fw fa-sm mr-1"></i> <?= l('audits.input.type.html') ?>
                                    </label>
                                </div>
                            </div>
                            <small id="type_single_help" data-type="single" class="form-text text-muted"><?= l('audits.input.type.single_help') ?></small>
                            <small id="type_sitemap_help" data-type="sitemap" class="form-text text-muted"><?= l('audits.input.type.sitemap_help') ?></small>
                            <small id="type_bulk_help" data-type="bulk" class="form-text text-muted"><?= l('audits.input.type.bulk_help') ?></small>
                        </div>

                        <div class="form-group custom-control custom-switch" data-tracking-type="normal">
                            <input
                                    type="checkbox"
                                    class="custom-control-input"
                                    name="is_public"
                                    id="is_public"
                                <?= ($_SESSION['audit_form']['is_public'] ?? false) ? 'checked="checked"' : null ?>
                            >
                            <label class="custom-control-label" for="is_public"><i class="fas fa-fw fa-sm fa-eye text-muted mr-1"></i> <?= l('audits.input.is_public') ?></label>
                            <small class="form-text text-muted"><?= l('audits.input.is_public_help') ?></small>
                        </div>

                        <div class="form-group" data-password-toggle-view data-password-toggle-view-show="<?= l('global.show') ?>" data-password-toggle-view-hide="<?= l('global.hide') ?>" data-is-public="on">
                            <label for="password"><i class="fas fa-fw fa-sm fa-lock text-muted mr-1"></i> <?= l('global.password') ?></label>
                            <input id="password" type="password" class="form-control" name="password" value="<?= $_SESSION['audit_form']['password'] ?? null ?>" />
                            <small class="form-text text-muted"><?= l('audits.input.password_help') ?></small>
                        </div>

                        <?php if(count($data->domains) && settings()->audits->domains_is_enabled): ?>
                            <div class="form-group">
                                <label for="domain_id"><i class="fas fa-fw fa-sm fa-globe text-muted mr-1"></i> <?= l('audits.input.domain_id') ?></label>
                                <select id="domain_id" name="domain_id" class="custom-select">
                                    <option value="" <?= empty($_SESSION['audit_form']['domain_id']) ? 'selected="selected"' : null ?>><?= parse_url(SITE_URL, PHP_URL_HOST) ?></option>
                                    <?php foreach($data->domains as $row): ?>
                                        <option value="<?= $row->domain_id ?>" <?= ($_SESSION['audit_form']['domain_id'] ?? null) == $row->domain_id ? 'selected="selected"' : null ?>><?= $row->host ?></option>
                                    <?php endforeach ?>
                                </select>
                                <small class="form-text text-muted"><?= l('audits.input.domain_id_help') ?></small>
                            </div>
                        <?php endif ?>

                        <div class="form-group">
                            <label for="audit_check_interval"><i class="fas fa-fw fa-sm fa-sync text-muted mr-1"></i> <?= l('audits.input.audit_check_interval') ?></label>
                            <select id="audit_check_interval" name="audit_check_interval" class="custom-select">
                                <option value="" <?= empty($_SESSION['audit_form']['audit_check_interval']) ? 'selected="selected"' : null ?>><?= l('global.none') ?></option>
                                <?php foreach(require APP_PATH . 'includes/audits_check_intervals.php' as $key => $value): ?>
                                    <option value="<?= $key ?>" <?= ($_SESSION['audit_form']['audit_check_interval'] ?? null) == $key ? 'selected="selected"' : null ?> <?= !in_array($key, $this->user->plan_settings->audits_check_intervals ?? []) ? 'disabled="disabled"' : null ?>><?= $value ?></option>
                                <?php endforeach ?>
                            </select>
                            <small class="form-text text-muted"><?= l('audits.input.audit_check_interval_help') ?></small>
                        </div>

                        <div <?= $this->user->plan_id != 'guest' ? null : 'data-toggle="tooltip" title="' . l('global.info_message.plan_feature_no_access') . '"' ?> data-audit-check-interval>
                            <div class="form-group <?= $this->user->plan_settings != 'guest' ? null : 'container-disabled' ?>">
                                <div class="d-flex flex-column flex-xl-row justify-content-between">
                                    <label><i class="fas fa-fw fa-sm fa-bell text-muted mr-1"></i> <?= l('audits.input.notification_handlers') ?></label>
                                    <a href="<?= url('notification-handler-create') ?>" target="_blank" class="small mb-2"><i class="fas fa-fw fa-sm fa-plus mr-1"></i> <?= l('notification_handlers.create') ?></a>
                                </div>
                                <div class="mb-2"><small class="form-text text-muted"><?= l('audits.input.notification_handlers_help') ?></small></div>

                                <div class="row">
                                    <?php foreach($data->notification_handlers as $notification_handler): ?>
                                        <div class="col-12 col-lg-6">
                                            <div class="custom-control custom-checkbox my-2">
                                                <input id="notifications_<?= $notification_handler->notification_handler_id ?>" name="notifications[]" value="<?= $notification_handler->notification_handler_id ?>" type="checkbox" class="custom-control-input" <?= in_array($notification_handler->notification_handler_id, $_SESSION['audit_form']['notifications'] ?? []) ? 'checked="checked"' : null ?>>
                                                <label class="custom-control-label" for="notifications_<?= $notification_handler->notification_handler_id ?>">
                                                    <span class="mr-1"><?= $notification_handler->name ?></span>
                                                    <small class="badge badge-light badge-pill"><?= l('notification_handlers.input.type_' . $notification_handler->type) ?></small>
                                                </label>
                                            </div>
                                        </div>
                                    <?php endforeach ?>
                                </div>
                            </div>
                        </div>

                        <small class="text-muted"><i class="fas fa-fw fa-sm fa-info-circle mr-1"></i> <?= sprintf(l('audits.request_help'), '<strong>' . (settings()->audits->domains_custom_main_ip ?: $_SERVER['SERVER_ADDR']) . '</strong>', '<strong>' . settings()->audits->user_agent . '</strong>') ?></small>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <div class="mb-4">
        <div class="row m-n2">
            <div class="col-12 col-sm-6 p-2 position-relative text-truncate">
                <div class="card d-flex flex-row h-100 overflow-hidden" data-toggle="tooltip" data-html="true" title="<?= get_plan_feature_limit_info($data->usage->audit_audits_current_month, $this->user->plan_settings->audits_per_month_limit) ?>">
                    <div class="px-3 d-flex flex-column justify-content-center">
                        <a href="<?= url('audits') ?>" class="stretched-link">
                            <div class="p-2 rounded-2x card-widget-icon d-flex align-items-center justify-content-center bg-audit">
                                <i class="fas fa-fw fa-sm fa-fire text-audit"></i>
                            </div>
                        </a>
                    </div>

                    <div class="card-body text-truncate">
                        <?= sprintf(l('dashboard.total_audits'), '<span class="h6">' . nr($data->usage->audit_audits_current_month) . '</span>') ?>

                        <div class="progress" style="height: .25rem;">
                            <div class="progress-bar <?= $this->user->plan_settings->audits_per_month_limit == -1 ? 'bg-success' : null ?>" role="progressbar" style="width: <?= $this->user->plan_settings->audits_per_month_limit == -1 ? 0 : ($data->usage->audit_audits_current_month / $this->user->plan_settings->audits_per_month_limit * 100) ?>%" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 p-2 position-relative text-truncate">
                <div class="card d-flex flex-row h-100 overflow-hidden" data-toggle="tooltip" data-html="true" title="<?= get_plan_feature_limit_info($data->total_websites, $this->user->plan_settings->websites_limit) ?>">
                    <div class="px-3 d-flex flex-column justify-content-center">
                        <a href="<?= url('websites') ?>" class="stretched-link">
                            <div class="p-2 rounded-2x card-widget-icon d-flex align-items-center justify-content-center bg-website">
                                <i class="fas fa-fw fa-sm fa-pager text-website"></i>
                            </div>
                        </a>
                    </div>

                    <div class="card-body text-truncate">
                        <?= sprintf(l('dashboard.total_websites'), '<span class="h6">' . nr($data->total_websites) . '</span>') ?>

                        <div class="progress" style="height: .25rem;">
                            <div class="progress-bar <?= $this->user->plan_settings->websites_limit == -1 ? 'bg-success' : null ?>" role="progressbar" style="width: <?= $this->user->plan_settings->websites_limit == -1 ? 0 : ($data->total_websites / $this->user->plan_settings->websites_limit * 100) ?>%" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4 mb-5">
        <div class="d-flex align-items-center mb-3">
            <h2 class="small font-weight-bold text-uppercase text-muted mb-0 mr-3"><i class="fas fa-fw fa-sm fa-bolt mr-1 text-audit"></i> <?= l('dashboard.audits_header') ?></h2>

            <div class="flex-fill">
                <hr class="border-gray-100" />
            </div>

            <div class="ml-3">
                <a href="<?= url('audits') ?>" class="btn btn-sm bg-audit text-audit" data-toggle="tooltip" title="<?= l('global.view_all') ?>"><i class="fas fa-fw fa-bolt fa-sm"></i></a>
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

    <div class="mt-4">
        <div class="d-flex align-items-center mb-3">
            <h2 class="small font-weight-bold text-uppercase text-muted mb-0 mr-3"><i class="fas fa-fw fa-sm fa-pager mr-1 text-website"></i> <?= l('dashboard.websites_header') ?></h2>

            <div class="flex-fill">
                <hr class="border-gray-100" />
            </div>

            <div class="ml-3">
                <a href="<?= url('websites') ?>" class="btn btn-sm bg-website text-website" data-toggle="tooltip" title="<?= l('global.view_all') ?>"><i class="fas fa-fw fa-pager fa-sm"></i></a>
            </div>
        </div>

        <?php if(count($data->websites)): ?>
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
                                <div>
                                    <a href="<?= url('website/' . $row->website_id) ?>"><?= $row->host ?></a>
                                </div>

                                <div class="small">
                                    <img referrerpolicy="no-referrer" src="<?= get_favicon_url_from_domain($row->host) ?>" class="img-fluid icon-favicon-small mr-1" loading="lazy" />

                                    <span class="text-muted"><?= $row->host ?></span>

                                    <a href="<?= 'https://' . $row->host ?>" target="_blank" rel="noreferrer"><i class="fas fa-fw fa-xs fa-external-link-alt text-muted ml-1"></i></a>
                                </div>
                            </td>

                            <td class="text-nowrap">
                                <a href="<?= url('audits?website_id=' . $row->website_id) ?>" class="badge text-audit bg-audit mr-2">
                                    <i class="fas fa-fw fa-sm fa-bolt mr-1"></i> <?= nr($row->total_audits) ?>
                                </a>

                                <a href="<?= url('archived-audits?website_id=' . $row->website_id) ?>" class="badge badge-light">
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
                                <a href="<?= url('audits?website_id=' . $row->website_id) ?>" class="badge badge-<?= $audit_badge_bg_class_name ?>" data-html="true" data-toggle="tooltip" title="<?= $badge_tooltip ?>">
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
                                    <?= include_view(THEME_PATH . 'views/websites/website_dropdown_button.php', ['id' => $row->website_id, 'resource_name' => $row->host]) ?>
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
                'name' => 'websites',
                'has_secondary_text' => true,
            ]); ?>

        <?php endif ?>
    </div>
</div>

<?php ob_start() ?>
<script>
    'use strict';

    /* Handle URL input */
    document.querySelector('[data-url-autofocus]').focus();

    document.querySelector('#audit_create_form').addEventListener('submit', event => {
        event.currentTarget.querySelectorAll('input[name="url"]').forEach(url_input => {
            const lowercase_input = url_input.value.toLowerCase();
            url_input.value = lowercase_input.startsWith('http://') || lowercase_input.startsWith('https://')
                ? url_input.value
                : `https://${url_input.value}`;
        });

        if(document.querySelector('#audit_create_form').checkValidity()) {
            pause_submit_button(event.currentTarget);
        }
    });


    type_handler('input[name="type"]', 'data-type');
    document.querySelector('input[name="type"]') && document.querySelectorAll('input[name="type"]').forEach(element => element.addEventListener('change', () => { type_handler('input[name="type"]', 'data-type'); }));

    type_handler('[name="is_public"]', 'data-is-public');
    document.querySelector('[name="is_public"]') && document.querySelectorAll('[name="is_public"]').forEach(element => element.addEventListener('change', () => { type_handler('[name="is_public"]', 'data-is-public'); }));

    let audit_check_interval_process = () => {
        let audit_check_interval = document.querySelector('select[name="audit_check_interval"]').value;
        if(audit_check_interval) {
            document.querySelector('[data-audit-check-interval]').classList.remove('d-none');
        } else {
            document.querySelector('[data-audit-check-interval]').classList.add('d-none');
        }
    }
    document.querySelector('select[name="audit_check_interval"]').addEventListener('change', audit_check_interval_process);
    audit_check_interval_process();
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>

<?php ob_start() ?>
<script>
    'use strict';

    let active_notification_handlers_per_resource_limit = <?= (int) $this->user->plan_settings->active_notification_handlers_per_resource_limit ?>;

    if(active_notification_handlers_per_resource_limit != -1) {
        let process_notification_handlers = () => {
            let selected = document.querySelectorAll('[name="notifications[]"]:checked').length;

            if(selected >= active_notification_handlers_per_resource_limit) {
                document.querySelectorAll('[name="notifications[]"]:not(:checked)').forEach(element => element.setAttribute('disabled', 'disabled'));
            } else {
                document.querySelectorAll('[name="notifications[]"]:not(:checked)').forEach(element => element.removeAttribute('disabled'));
            }
        }

        document.querySelectorAll('[name="notifications[]"]').forEach(element => element.addEventListener('change', process_notification_handlers));

        process_notification_handlers();
    }
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
