<?php defined('ALTUMCODE') || die() ?>

<div class="dropdown">
    <button type="button" class="btn btn-link text-secondary dropdown-toggle dropdown-toggle-simple" data-toggle="dropdown" data-boundary="viewport">
        <i class="fas fa-fw fa-ellipsis-v"></i>
    </button>

    <div class="dropdown-menu dropdown-menu-right">
        <a class="dropdown-item" href="<?= $data->url ?>" target="_blank" rel="nofollow noreferrer"><i class="fas fa-fw fa-sm fa-up-right-from-square mr-2"></i> <?= l('audits.open') ?></a>

        <a href="#" data-toggle="modal" data-target="#audit_delete_modal" data-audit-id="<?= $data->id ?>" data-resource-name="<?= $data->resource_name ?>" class="dropdown-item"><i class="fas fa-fw fa-sm fa-trash-alt mr-2"></i> <?= l('global.delete') ?></a>
    </div>
</div>

<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/partials/universal_delete_modal_url.php', [
    'name' => 'audit',
    'resource_id' => 'audit_id',
    'has_dynamic_resource_name' => true,
    'path' => 'admin/audits/delete/'
]), 'modals', 'audit_delete_modal'); ?>
