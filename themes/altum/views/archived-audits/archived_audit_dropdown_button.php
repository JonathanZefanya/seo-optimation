<?php defined('ALTUMCODE') || die() ?>

<div class="dropdown">
    <button type="button" class="btn btn-link text-secondary dropdown-toggle dropdown-toggle-simple" data-toggle="dropdown" data-boundary="viewport">
        <i class="fas fa-fw fa-ellipsis-v"></i>
    </button>

    <div class="dropdown-menu dropdown-menu-right">
        <a class="dropdown-item" href="<?= url('archived-audit/' . $data->id) ?>"><i class="fas fa-fw fa-sm fa-eye mr-2"></i> <?= l('global.view') ?></a>

        <a class="dropdown-item" href="<?= url('audit/' . $data->audit_id) ?>"><i class="fas fa-fw fa-sm fa-bolt mr-2"></i> <?= l('audits.main_audit') ?></a>

        <a
                href="#"
                data-toggle="modal"
                data-target="#archived_audit_delete_modal"
                data-archived-audit-id="<?= $data->id ?>"
                data-resource-name="<?= $data->resource_name ?>"
                class="dropdown-item"
        ><i class="fas fa-fw fa-sm fa-trash-alt mr-2"></i> <?= l('global.delete') ?></a>
    </div>
</div>

<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/partials/universal_delete_modal_form.php', [
    'name' => 'archived_audit',
    'resource_id' => 'archived_audit_id',
    'has_dynamic_resource_name' => true,
    'path' => 'archived-audits/delete'
]), 'modals', 'archived_audit_delete_modal'); ?>

