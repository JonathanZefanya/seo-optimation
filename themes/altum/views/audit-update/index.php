<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <?php if(settings()->main->breadcrumbs_is_enabled): ?>
        <nav aria-label="breadcrumb">
            <ol class="custom-breadcrumbs small">
                <li>
                    <a href="<?= url('audits') ?>"><?= l('audits.breadcrumb') ?></a><i class="fas fa-fw fa-angle-right"></i>
                </li>
                <li>
                    <a href="<?= url('audit/' . $data->audit->audit_id) ?>"><?= l('audit.breadcrumb') ?></a><i class="fas fa-fw fa-angle-right"></i>
                </li>
                <li class="active" aria-current="page"><?= l('audit_update.breadcrumb') ?></li>
            </ol>
        </nav>
    <?php endif ?>

    <div class="d-flex justify-content-between mb-4">
        <h1 class="h4 text-truncate mb-0"><i class="fas fa-fw fa-xs fa-bolt mr-1"></i> <?= l('audit_update.header') ?></h1>

        <?= include_view(THEME_PATH . 'views/audits/audit_dropdown_button.php', ['id' => $data->audit->audit_id, 'resource_name' => remove_url_protocol_from_url($data->audit->url), 'url' => $data->audit->url]) ?>
    </div>

    <div class="card">
        <div class="card-body">

            <form action="" method="post" role="form" enctype="multipart/form-data">
                <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" />

                <div class="form-group">
                    <label for="url"><i class="fas fa-fw fa-link fa-sm text-muted mr-1"></i> <?= l('global.url') ?></label>
                    <input type="url" id="url" name="url" class="form-control" value="<?= $data->audit->url ?>" disabled="disabled" />
                </div>

                <?php if(count($data->domains) && settings()->audits->domains_is_enabled): ?>
                    <div class="form-group">
                        <label for="domain_id"><i class="fas fa-fw fa-sm fa-globe text-muted mr-1"></i> <?= l('audits.input.domain_id') ?></label>
                        <select id="domain_id" name="domain_id" class="custom-select">
                            <option value="" <?= $data->audit->domain_id ? null : 'selected="selected"' ?>><?= remove_url_protocol_from_url(SITE_URL) ?></option>
                            <?php foreach($data->domains as $row): ?>
                                <option value="<?= $row->domain_id ?>" <?= $data->audit->domain_id && $data->audit->domain_id == $row->domain_id ? 'selected="selected"' : null ?>><?= $row->host ?></option>
                            <?php endforeach ?>
                        </select>
                        <small class="form-text text-muted"><?= l('audits.input.domain_id_help') ?></small>
                    </div>
                <?php endif ?>

                <div class="form-group custom-control custom-switch" data-tracking-type="normal">
                    <input
                            type="checkbox"
                            class="custom-control-input"
                            name="is_public"
                            id="is_public"
                        <?= $data->audit->settings->is_public ? 'checked="checked"' : null ?>
                    >
                    <label class="custom-control-label" for="is_public"><i class="fas fa-fw fa-sm fa-eye text-muted mr-1"></i> <?= l('audits.input.is_public') ?></label>
                    <small class="form-text text-muted"><?= l('audits.input.is_public_help') ?></small>
                </div>

                <div class="form-group" data-password-toggle-view data-password-toggle-view-show="<?= l('global.show') ?>" data-password-toggle-view-hide="<?= l('global.hide') ?>" data-is-public="on">
                    <label for="password"><i class="fas fa-fw fa-sm fa-lock text-muted mr-1"></i> <?= l('global.password') ?></label>
                    <input id="password" type="password" class="form-control" name="password" value="<?= $data->audit->settings->password ?>" />
                    <small class="form-text text-muted"><?= l('audits.input.password_help') ?></small>
                </div>

                <div class="form-group">
                    <label for="audit_check_interval"><i class="fas fa-fw fa-sm fa-sync text-muted mr-1"></i> <?= l('audits.input.audit_check_interval') ?></label>
                    <select id="audit_check_interval" name="audit_check_interval" class="custom-select">
                        <option value="" <?= $data->audit->settings->audit_check_interval ? 'selected="selected"' : null ?>><?= l('global.none') ?></option>
                        <?php foreach(require APP_PATH . 'includes/audits_check_intervals.php' as $key => $value): ?>
                            <option value="<?= $key ?>" <?= $data->audit->settings->audit_check_interval == $key ? 'selected="selected"' : null ?> <?= !in_array($key, $this->user->plan_settings->audits_check_intervals ?? []) ? 'disabled="disabled"' : null ?>><?= $value ?></option>
                        <?php endforeach ?>
                    </select>
                    <small class="form-text text-muted"><?= l('audits.input.audit_check_interval_help') ?></small>
                </div>

                <div class="form-group">
                    <div class="d-flex flex-column flex-xl-row justify-content-between">
                        <label><i class="fas fa-fw fa-sm fa-bell text-muted mr-1"></i> <?= l('audits.input.notification_handlers') ?></label>
                        <a href="<?= url('notification-handler-create') ?>" target="_blank" class="small mb-2"><i class="fas fa-fw fa-sm fa-plus mr-1"></i> <?= l('notification_handlers.create') ?></a>
                    </div>
                    <div class="mb-2"><small class="text-muted"><?= l('audits.input.notification_handlers_help') ?></small></div>

                    <div class="row">
                        <?php foreach($data->notification_handlers as $notification_handler): ?>
                            <div class="col-12 col-lg-6">
                                <div class="custom-control custom-checkbox my-2">
                                    <input id="notifications_<?= $notification_handler->notification_handler_id ?>" name="notifications[]" value="<?= $notification_handler->notification_handler_id ?>" type="checkbox" class="custom-control-input" <?= in_array($notification_handler->notification_handler_id, $data->audit->notifications ?? []) ? 'checked="checked"' : null ?>>
                                    <label class="custom-control-label" for="notifications_<?= $notification_handler->notification_handler_id ?>">
                                        <span class="mr-1"><?= $notification_handler->name ?></span>
                                        <small class="badge badge-light badge-pill"><?= l('notification_handlers.input.type_' . $notification_handler->type) ?></small>
                                    </label>
                                </div>
                            </div>
                        <?php endforeach ?>
                    </div>
                </div>

                <button type="submit" name="submit" class="btn btn-block btn-primary"><?= l('global.update') ?></button>
            </form>

        </div>
    </div>
</div>


<?php ob_start() ?>
<script>
    'use strict';

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
