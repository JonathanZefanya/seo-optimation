<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <?= $this->views['account_header_menu'] ?>

    <div class="d-flex align-items-center mb-3">
        <h1 class="h4 m-0"><?= l('account_preferences.header') ?></h1>

        <div class="ml-2">
            <span data-toggle="tooltip" title="<?= l('account_preferences.subheader') ?>">
                <i class="fas fa-fw fa-info-circle text-muted"></i>
            </span>
        </div>
    </div>

    <div class="card">
        <div class="card-body">

            <form action="" method="post" role="form" enctype="multipart/form-data">
                <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" />

                <?php if(settings()->main->white_labeling_is_enabled): ?>
                <div <?= $this->user->plan_settings->white_labeling_is_enabled ? null : 'data-toggle="tooltip" title="' . l('global.info_message.plan_feature_no_access') . '"' ?>>
                    <div class="<?= $this->user->plan_settings->white_labeling_is_enabled ? null : 'container-disabled' ?>">
                        <div class="form-group">
                            <label for="white_label_title"><i class="fas fa-fw fa-sm fa-heading text-muted mr-1"></i> <?= l('account_preferences.input.white_label_title') ?></label>
                            <input type="text" id="white_label_title" name="white_label_title" class="form-control <?= \Altum\Alerts::has_field_errors('white_label_title') ? 'is-invalid' : null ?>" value="<?= $this->user->preferences->white_label_title ?>" maxlength="32" />
                            <?= \Altum\Alerts::output_field_error('white_label_title') ?>
                        </div>

                        <div class="form-group" data-file-image-input-wrapper data-file-input-wrapper-size-limit="<?= get_max_upload() ?>" data-file-input-wrapper-size-limit-error="<?= sprintf(l('global.error_message.file_size_limit'), get_max_upload()) ?>">
                            <label for="white_label_logo_light"><i class="fas fa-fw fa-sm fa-sun text-muted mr-1"></i> <?= l('account_preferences.input.white_label_logo_light') ?></label>
                            <?= include_view(THEME_PATH . 'views/partials/file_image_input.php', ['uploads_file_key' => 'users', 'file_key' => 'white_label_logo_light', 'already_existing_image' => $this->user->preferences->white_label_logo_light]) ?>
                            <small class="form-text text-muted"><?= sprintf(l('global.accessibility.whitelisted_file_extensions'), \Altum\Uploads::get_whitelisted_file_extensions_accept('users')) . ' ' . sprintf(l('global.accessibility.file_size_limit'), get_max_upload()) ?></small>
                        </div>

                        <div class="form-group" data-file-image-input-wrapper data-file-input-wrapper-size-limit="<?= get_max_upload() ?>" data-file-input-wrapper-size-limit-error="<?= sprintf(l('global.error_message.file_size_limit'), get_max_upload()) ?>">
                            <label for="white_label_logo_dark"><i class="fas fa-fw fa-sm fa-moon text-muted mr-1"></i> <?= l('account_preferences.input.white_label_logo_dark') ?></label>
                            <?= include_view(THEME_PATH . 'views/partials/file_image_input.php', ['uploads_file_key' => 'users', 'file_key' => 'white_label_logo_dark', 'already_existing_image' => $this->user->preferences->white_label_logo_dark]) ?>
                            <small class="form-text text-muted"><?= sprintf(l('global.accessibility.whitelisted_file_extensions'), \Altum\Uploads::get_whitelisted_file_extensions_accept('users')) . ' ' . sprintf(l('global.accessibility.file_size_limit'), get_max_upload()) ?></small>
                        </div>

                        <div class="form-group" data-file-image-input-wrapper data-file-input-wrapper-size-limit="<?= get_max_upload() ?>" data-file-input-wrapper-size-limit-error="<?= sprintf(l('global.error_message.file_size_limit'), get_max_upload()) ?>">
                            <label for="white_label_favicon"><i class="fas fa-fw fa-sm fa-icons text-muted mr-1"></i> <?= l('account_preferences.input.white_label_favicon') ?></label>
                            <?= include_view(THEME_PATH . 'views/partials/file_image_input.php', ['uploads_file_key' => 'users', 'file_key' => 'white_label_favicon', 'already_existing_image' => $this->user->preferences->white_label_favicon]) ?>
                            <small class="form-text text-muted"><?= sprintf(l('global.accessibility.whitelisted_file_extensions'), \Altum\Uploads::get_whitelisted_file_extensions_accept('users')) . ' ' . sprintf(l('global.accessibility.file_size_limit'), get_max_upload()) ?></small>
                        </div>
                    </div>
                </div>
                <?php endif ?>


                <div class="form-group">
                    <label for="default_results_per_page"><i class="fas fa-fw fa-sm fa-list-ol text-muted mr-1"></i> <?= l('account_preferences.input.default_results_per_page') ?></label>
                    <select id="default_results_per_page" name="default_results_per_page" class="custom-select <?= \Altum\Alerts::has_field_errors('default_results_per_page') ? 'is-invalid' : null ?>">
                        <?php foreach([10, 25, 50, 100, 250, 500, 1000] as $key): ?>
                            <option value="<?= $key ?>" <?= ($this->user->preferences->default_results_per_page ?? settings()->main->default_results_per_page) == $key ? 'selected="selected"' : null ?>><?= $key ?></option>
                        <?php endforeach ?>
                    </select>
                    <?= \Altum\Alerts::output_field_error('default_results_per_page') ?>
                </div>

                <div class="form-group">
                    <label for="default_order_type"><i class="fas fa-fw fa-sm fa-sort text-muted mr-1"></i> <?= l('account_preferences.input.default_order_type') ?></label>
                    <select id="default_order_type" name="default_order_type" class="custom-select <?= \Altum\Alerts::has_field_errors('default_order_type') ? 'is-invalid' : null ?>">
                        <option value="ASC" <?= ($this->user->preferences->default_order_type ?? settings()->main->default_order_type) == 'ASC' ? 'selected="selected"' : null ?>><?= l('global.filters.order_type_asc') ?></option>
                        <option value="DESC" <?= ($this->user->preferences->default_order_type ?? settings()->main->default_order_type) == 'DESC' ? 'selected="selected"' : null ?>><?= l('global.filters.order_type_desc') ?></option>
                    </select>
                    <?= \Altum\Alerts::output_field_error('default_order_type') ?>
                </div>

                <div class="form-group">
                    <label for="websites_default_order_by"><i class="fas fa-fw fa-sm fa-pager text-muted mr-1"></i> <?= sprintf(l('account_preferences.input.default_order_by_x'), l('websites.title')) ?></label>
                    <select id="websites_default_order_by" name="websites_default_order_by" class="custom-select <?= \Altum\Alerts::has_field_errors('websites_default_order_by') ? 'is-invalid' : null ?>">
                        <option value="website_id" <?= $this->user->preferences->websites_default_order_by == 'website_id' ? 'selected="selected"' : null ?>><?= l('global.id') ?></option>
                        <option value="datetime" <?= $this->user->preferences->websites_default_order_by == 'datetime' ? 'selected="selected"' : null ?>><?= l('global.filters.order_by_datetime') ?></option>
                        <option value="last_datetime" <?= $this->user->preferences->websites_default_order_by == 'last_datetime' ? 'selected="selected"' : null ?>><?= l('global.filters.order_by_last_datetime') ?></option>
                        <option value="last_audit_datetime" <?= $this->user->preferences->websites_default_order_by == 'last_audit_datetime' ? 'selected="selected"' : null ?>><?= l('websites.last_audit_datetime') ?></option>
                        <option value="host" <?= $this->user->preferences->websites_default_order_by == 'host' ? 'selected="selected"' : null ?>><?= l('audits.host') ?></option>
                        <option value="score" <?= $this->user->preferences->websites_default_order_by == 'score' ? 'selected="selected"' : null ?>><?= l('audits.score') ?></option>
                        <option value="total_audits" <?= $this->user->preferences->websites_default_order_by == 'total_audits' ? 'selected="selected"' : null ?>><?= l('websites.total_audits') ?></option>
                        <option value="total_archived_audits" <?= $this->user->preferences->websites_default_order_by == 'total_archived_audits' ? 'selected="selected"' : null ?>><?= l('websites.total_archived_audits') ?></option>
                        <option value="total_issues" <?= $this->user->preferences->websites_default_order_by == 'total_issues' ? 'selected="selected"' : null ?>><?= l('audits.total_issues') ?></option>
                    </select>
                    <?= \Altum\Alerts::output_field_error('websites_default_order_by') ?>
                </div>

                <div class="form-group">
                    <label for="audits_default_order_by"><i class="fas fa-fw fa-sm fa-bolt text-muted mr-1"></i> <?= sprintf(l('account_preferences.input.default_order_by_x'), l('audits.title')) ?></label>
                    <select id="audits_default_order_by" name="audits_default_order_by" class="custom-select <?= \Altum\Alerts::has_field_errors('audits_default_order_by') ? 'is-invalid' : null ?>">
                        <option value="audit_id" <?= $this->user->preferences->audits_default_order_by == 'audit_id' ? 'selected="selected"' : null ?>><?= l('global.id') ?></option>
                        <option value="datetime" <?= $this->user->preferences->audits_default_order_by == 'datetime' ? 'selected="selected"' : null ?>><?= l('global.filters.order_by_datetime') ?></option>
                        <option value="last_datetime" <?= $this->user->preferences->audits_default_order_by == 'last_datetime' ? 'selected="selected"' : null ?>><?= l('global.filters.order_by_last_datetime') ?></option>
                        <option value="next_refresh_datetime" <?= $this->user->preferences->audits_default_order_by == 'next_refresh_datetime' ? 'selected="selected"' : null ?>><?= l('audits.next_refresh_datetime') ?></option>
                        <option value="last_refresh_datetime" <?= $this->user->preferences->audits_default_order_by == 'last_refresh_datetime' ? 'selected="selected"' : null ?>><?= l('audits.last_refresh_datetime') ?></option>
                        <option value="url" <?= $this->user->preferences->audits_default_order_by == 'url' ? 'selected="selected"' : null ?>><?= l('global.url') ?></option>
                        <option value="host" <?= $this->user->preferences->audits_default_order_by == 'host' ? 'selected="selected"' : null ?>><?= l('audits.host') ?></option>
                        <option value="title" <?= $this->user->preferences->audits_default_order_by == 'title' ? 'selected="selected"' : null ?>><?= l('audits.page_title') ?></option>
                        <option value="score" <?= $this->user->preferences->audits_default_order_by == 'score' ? 'selected="selected"' : null ?>><?= l('audits.score') ?></option>
                        <option value="total_issues" <?= $this->user->preferences->audits_default_order_by == 'total_issues' ? 'selected="selected"' : null ?>><?= l('audits.total_issues') ?></option>
                        <option value="total_refreshes" <?= $this->user->preferences->audits_default_order_by == 'total_refreshes' ? 'selected="selected"' : null ?>><?= l('audits.total_refreshes') ?></option>
                    </select>
                    <?= \Altum\Alerts::output_field_error('audits_default_order_by') ?>
                </div>

                <div class="form-group">
                    <label for="archived_audits_default_order_by"><i class="fas fa-fw fa-sm fa-archive text-muted mr-1"></i> <?= sprintf(l('account_preferences.input.default_order_by_x'), l('archived_audits.title')) ?></label>
                    <select id="archived_audits_default_order_by" name="archived_audits_default_order_by" class="custom-select <?= \Altum\Alerts::has_field_errors('archived_audits_default_order_by') ? 'is-invalid' : null ?>">
                        <option value="archived_audit_id" <?= $this->user->preferences->archived_audits_default_order_by == 'archived_audit_id' ? 'selected="selected"' : null ?>><?= l('global.id') ?></option>
                        <option value="datetime" <?= $this->user->preferences->archived_audits_default_order_by == 'datetime' ? 'selected="selected"' : null ?>><?= l('global.filters.order_by_datetime') ?></option>
                        <option value="url" <?= $this->user->preferences->archived_audits_default_order_by == 'url' ? 'selected="selected"' : null ?>><?= l('global.url') ?></option>
                        <option value="host" <?= $this->user->preferences->archived_audits_default_order_by == 'host' ? 'selected="selected"' : null ?>><?= l('audits.host') ?></option>
                        <option value="title" <?= $this->user->preferences->archived_audits_default_order_by == 'title' ? 'selected="selected"' : null ?>><?= l('audits.page_title') ?></option>
                        <option value="score" <?= $this->user->preferences->archived_audits_default_order_by == 'score' ? 'selected="selected"' : null ?>><?= l('audits.score') ?></option>
                        <option value="total_issues" <?= $this->user->preferences->archived_audits_default_order_by == 'total_issues' ? 'selected="selected"' : null ?>><?= l('audits.total_issues') ?></option>
                    </select>
                    <?= \Altum\Alerts::output_field_error('audits_default_order_by') ?>
                </div>

                <div class="form-group">
                    <label for="notification_handlers_default_order_by"><i class="fas fa-fw fa-sm fa-bell text-muted mr-1"></i> <?= sprintf(l('account_preferences.input.default_order_by_x'), l('notification_handlers.title')) ?></label>
                    <select id="notification_handlers_default_order_by" name="notification_handlers_default_order_by" class="custom-select <?= \Altum\Alerts::has_field_errors('notification_handlers_default_order_by') ? 'is-invalid' : null ?>">
                        <option value="notification_handler_id" <?= $this->user->preferences->notification_handlers_default_order_by == 'notification_handler_id' ? 'selected="selected"' : null ?>><?= l('global.id') ?></option>
                        <option value="datetime" <?= $this->user->preferences->notification_handlers_default_order_by == 'datetime' ? 'selected="selected"' : null ?>><?= l('global.filters.order_by_datetime') ?></option>
                        <option value="last_datetime" <?= $this->user->preferences->notification_handlers_default_order_by == 'last_datetime' ? 'selected="selected"' : null ?>><?= l('global.filters.order_by_last_datetime') ?></option>
                        <option value="name" <?= $this->user->preferences->notification_handlers_default_order_by == 'name' ? 'selected="selected"' : null ?>><?= l('notification_handlers.input.name') ?></option>
                    </select>
                    <?= \Altum\Alerts::output_field_error('notification_handlers_default_order_by') ?>
                </div>

                <?php if(settings()->audits->domains_is_enabled): ?>
                <div class="form-group">
                    <label for="domains_default_order_by"><i class="fas fa-fw fa-sm fa-globe text-muted mr-1"></i> <?= sprintf(l('account_preferences.input.default_order_by_x'), l('domains.title')) ?></label>
                    <select id="domains_default_order_by" name="domains_default_order_by" class="custom-select <?= \Altum\Alerts::has_field_errors('domains_default_order_by') ? 'is-invalid' : null ?>">
                        <option value="domain_id" <?= $this->user->preferences->domains_default_order_by == 'domain_id' ? 'selected="selected"' : null ?>><?= l('global.id') ?></option>
                        <option value="datetime" <?= $this->user->preferences->domains_default_order_by == 'datetime' ? 'selected="selected"' : null ?>><?= l('global.filters.order_by_datetime') ?></option>
                        <option value="last_datetime" <?= $this->user->preferences->domains_default_order_by == 'last_datetime' ? 'selected="selected"' : null ?>><?= l('global.filters.order_by_last_datetime') ?></option>
                        <option value="host" <?= $this->user->preferences->domains_default_order_by == 'host' ? 'selected="selected"' : null ?>><?= l('domains.table.host') ?></option>
                    </select>
                    <?= \Altum\Alerts::output_field_error('domains_default_order_by') ?>
                </div>
                <?php endif ?>

                <button type="submit" name="submit" class="btn btn-block btn-primary"><?= l('global.update') ?></button>
            </form>
        </div>
    </div>
</div>
