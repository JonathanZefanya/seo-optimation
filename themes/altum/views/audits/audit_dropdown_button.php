<?php defined('ALTUMCODE') || die() ?>

<div class="dropdown">
    <button type="button" class="btn btn-link text-secondary dropdown-toggle dropdown-toggle-simple" data-toggle="dropdown" data-boundary="viewport">
        <i class="fas fa-fw fa-ellipsis-v"></i>
    </button>

    <div class="dropdown-menu dropdown-menu-right">
        <a class="dropdown-item" href="<?= url('audit/' . $data->id) ?>"><i class="fas fa-fw fa-sm fa-eye mr-2"></i> <?= l('global.view') ?></a>

        <a class="dropdown-item" href="<?= $data->url ?>" target="_blank" rel="nofollow noreferrer"><i class="fas fa-fw fa-sm fa-up-right-from-square mr-2"></i> <?= l('audits.open') ?></a>

        <a
                href="#"
                data-toggle="modal"
                data-target="#audit_refresh_modal"
                data-audit-id="<?= $data->id ?>"
                data-resource-name="<?= $data->resource_name ?>"
                class="dropdown-item"
        ><i class="fas fa-fw fa-sm fa-retweet mr-2"></i> <?= l('audits.refresh') ?></a>

        <a class="dropdown-item" href="<?= url('archived-audits?audit_id=' . $data->id) ?>"><i class="fas fa-fw fa-sm fa-archive mr-2"></i> <?= l('archived_audits.menu') ?></a>

        <a class="dropdown-item" href="<?= url('audit-update/' . $data->id) ?>"><i class="fas fa-fw fa-sm fa-pencil-alt mr-2"></i> <?= l('global.edit') ?></a>

        <a
                href="#"
                data-toggle="modal"
                data-target="#audit_delete_modal"
                data-audit-id="<?= $data->id ?>"
                data-resource-name="<?= $data->resource_name ?>"
                class="dropdown-item"
        ><i class="fas fa-fw fa-sm fa-trash-alt mr-2"></i> <?= l('global.delete') ?></a>
    </div>
</div>

<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/partials/universal_delete_modal_form.php', [
    'name' => 'audit',
    'resource_id' => 'audit_id',
    'has_dynamic_resource_name' => true,
    'path' => 'audits/delete'
]), 'modals', 'audit_delete_modal'); ?>

<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/audits/audit_refresh_modal.php'), 'modals', 'audit_refresh_modal'); ?>
