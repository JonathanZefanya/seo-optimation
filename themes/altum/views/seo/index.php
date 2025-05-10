<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <div class="text-center">
        <h1 class="index-header mb-2"><?= l('seo.header') ?></h1>
        <p class="index-subheader mb-5"><?= l('seo.subheader') ?></p>
    </div>

    <div class="row">
        <div class="col col-lg-8 offset-lg-2">
            <form id="audit_create_form" action="<?= url('audit-create') ?>" method="post" role="form">
                <input type="hidden" name="token" value="<?= \Altum\Csrf::get('token') ?>" />

                <div class="notification-container"></div>

                <div class="row">
                    <div class="col-12 col-lg mb-3 mb-lg-0">
                        <input id="url" type="text" name="url" class="form-control <?= \Altum\Alerts::has_field_errors('url') ? 'is-invalid' : null ?>" placeholder="<?= l('global.url_placeholder') ?>" aria-label="<?= l('global.url') ?>" maxlength=2048" required="required" data-url-autofocus>
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


                <div class="collapse mt-4" id="audit_settings" data-toggle="tooltip" title="<?= l('global.info_message.plan_feature_no_access') ?>">
                    <div class="container-disabled">
                        <div class="form-group">
                            <label for="type"><i class="fas fa-fw fa-sm fa-fingerprint text-muted mr-1"></i> <?= l('global.type') ?></label>
                            <div class="row btn-group-toggle" data-toggle="buttons">
                                <div class="col-12 col-lg-6">
                                    <label class="btn btn-light btn-block text-truncate active">
                                        <input type="radio" name="type" value="single" class="custom-control-input" checked="checked" required="required" />
                                        <i class="fas fa-link fa-fw fa-sm mr-1"></i> <?= l('audits.input.type.single') ?>
                                    </label>
                                </div>

                                <div class="col-12 col-lg-6">
                                    <label class="btn btn-light btn-block text-truncate">
                                        <input type="radio" name="type" value="sitemap" class="custom-control-input" required="required" />
                                        <i class="fas fa-network-wired fa-fw fa-sm mr-1"></i> <?= l('audits.input.type.sitemap') ?>
                                    </label>
                                </div>

                                <div class="col-12 col-lg-6">
                                    <label class="btn btn-light btn-block text-truncate">
                                        <input type="radio" name="type" value="bulk" class="custom-control-input" required="required" />
                                        <i class="fas fa-layer-group fa-fw fa-sm mr-1"></i> <?= l('audits.input.type.bulk') ?>
                                    </label>
                                </div>

                                <div class="col-12 col-lg-6">
                                    <label class="btn btn-light btn-block text-truncate">
                                        <input type="radio" name="type" value="html" class="custom-control-input" required="required" />
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
                                    checked="checked"
                            >
                            <label class="custom-control-label" for="is_public"><i class="fas fa-fw fa-sm fa-eye text-muted mr-1"></i> <?= l('audits.input.is_public') ?></label>
                            <small class="form-text text-muted"><?= l('audits.input.is_public_help') ?></small>
                        </div>

                        <div class="form-group" data-password-toggle-view data-password-toggle-view-show="<?= l('global.show') ?>" data-password-toggle-view-hide="<?= l('global.hide') ?>" data-is-public="on">
                            <label for="password"><i class="fas fa-fw fa-sm fa-lock text-muted mr-1"></i> <?= l('global.password') ?></label>
                            <input id="password" type="password" class="form-control" name="password" value="" />
                            <small class="form-text text-muted"><?= l('audits.input.password_help') ?></small>
                        </div>

                        <?php if(count($data->domains ?? []) && settings()->audits->domains_is_enabled): ?>
                            <div class="form-group">
                                <label for="domain_id"><i class="fas fa-fw fa-sm fa-globe text-muted mr-1"></i> <?= l('audits.input.domain_id') ?></label>
                                <select id="domain_id" name="domain_id" class="custom-select">
                                    <option value="" selected="selected"><?= parse_url(SITE_URL, PHP_URL_HOST) ?></option>
                                    <?php foreach($data->domains ?? [] as $row): ?>
                                        <option value="<?= $row->domain_id ?>"><?= $row->host ?></option>
                                    <?php endforeach ?>
                                </select>
                                <small class="form-text text-muted"><?= l('audits.input.domain_id_help') ?></small>
                            </div>
                        <?php endif ?>

                        <div class="form-group">
                            <label for="audit_check_interval"><i class="fas fa-fw fa-sm fa-sync text-muted mr-1"></i> <?= l('audits.input.audit_check_interval') ?></label>
                            <select id="audit_check_interval" name="audit_check_interval" class="custom-select">
                                <option value="" selected="selected"><?= l('global.none') ?></option>
                                <?php foreach(require APP_PATH . 'includes/audits_check_intervals.php' as $key => $value): ?>
                                    <option value="<?= $key ?>" <?= !in_array($key, $this->user->plan_settings->audits_check_intervals ?? []) ? 'disabled="disabled"' : null ?>><?= $value ?></option>
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
                                    <?php foreach($data->notification_handlers ?? [] as $notification_handler): ?>
                                        <div class="col-12">
                                            <div class="custom-control custom-checkbox my-2">
                                                <input id="notifications_<?= $notification_handler->notification_handler_id ?>" name="notifications[]" value="<?= $notification_handler->notification_handler_id ?>" type="checkbox" class="custom-control-input">
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
                    </div>
                </div>
            </form>

            <?php if(l('seo.extra_content')): ?>
                <div class="mt-5">
                    <div class="card">
                        <div class="card-body">
                            <?= l('seo.extra_content') ?>
                        </div>
                    </div>
                </div>
            <?php endif ?>
        </div>
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



