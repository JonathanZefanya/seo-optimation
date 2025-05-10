<?php defined('ALTUMCODE') || die() ?>

<?php ob_start() ?>
<div class="card mb-5">
    <div class="card-body">
        <h2 class="h4 mb-4"><i class="fas fa-fw fa-screwdriver-wrench fa-xs text-primary-900 mr-2"></i> <?= l('admin_statistics.tools.total_views') ?></h2>

        <div class="table-responsive table-custom-container">
            <table class="table table-custom">
                <thead>
                <tr>
                    <th><?= l('admin_statistics.tools.tool') ?></th>
                    <th><?= l('admin_statistics.percentage') ?></th>
                    <th><?= l('admin_statistics.tools.total_views') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php if ($data->total['views']): ?>
                    <?php foreach ($data->tools_total_views as $tool => $total): ?>
                        <tr>
                            <td class="text-nowrap">
                                <?= l('tools.' . $tool . '.name') ?>
                            </td>
                            <td class="text-nowrap">
                                <?= nr($total / $data->total['views'] * 100, 2) . '%'; ?>
                            </td>
                            <td class="text-nowrap">
                                <?= nr($total) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td class="text-nowrap text-muted" colspan="3">
                            <?= l('global.no_data') ?>
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card mb-5">
    <div class="card-body">
        <h2 class="h4 mb-4"><i class="fas fa-fw fa-screwdriver-wrench fa-xs text-primary-900 mr-2"></i> <?= l('admin_statistics.tools.total_submissions') ?></h2>

        <div class="table-responsive table-custom-container">
            <table class="table table-custom">
                <thead>
                <tr>
                    <th><?= l('admin_statistics.tools.tool') ?></th>
                    <th><?= l('admin_statistics.percentage') ?></th>
                    <th><?= l('admin_statistics.tools.total_submissions') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php if ($data->total['submissions']): ?>
                    <?php foreach ($data->tools_total_submissions as $tool => $total): ?>
                        <tr>
                            <td class="text-nowrap">
                                <?= l('tools.' . $tool . '.name') ?>
                            </td>
                            <td class="text-nowrap">
                                <?= nr($total / $data->total['submissions'] * 100, 2) . '%'; ?>
                            </td>
                            <td class="text-nowrap">
                                <?= nr($total) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td class="text-nowrap text-muted" colspan="3">
                            <?= l('global.no_data') ?>
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php $html = ob_get_clean() ?>

<?php ob_start() ?>
<?php $javascript = ob_get_clean() ?>

<?php return (object) ['html' => $html, 'javascript' => $javascript] ?>
