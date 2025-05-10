<?php defined('ALTUMCODE') || die() ?>

<?php ob_start() ?>
<div class="card mb-5">
    <div class="card-body">
        <div class="d-flex justify-content-between mb-4">
            <h2 class="h4 text-truncate mb-0"><i class="fas fa-fw fa-bell fa-xs text-primary-900 mr-2"></i> <?= l('admin_notification_handlers.header') ?></h2>

            <div>
                <span class="badge <?= $data->total['notification_handlers'] > 0 ? 'badge-success' : 'badge-secondary' ?>"><?= ($data->total['notification_handlers'] > 0 ? '+' : null) . nr($data->total['notification_handlers']) ?></span>
            </div>
        </div>

        <div class="chart-container <?= $data->total['notification_handlers'] ? null : 'd-none' ?>">
            <canvas id="notification_handlers"></canvas>
        </div>
        <?= $data->total['notification_handlers'] ? null : include_view(THEME_PATH . 'views/partials/no_chart_data.php', ['has_wrapper' => false]); ?>
    </div>
</div>

<?php $html = ob_get_clean() ?>

<?php ob_start() ?>
<script>
    'use strict';

    let color = css.getPropertyValue('--primary');
    let color_gradient = null;

    /* Display chart */
    let notification_handlers_chart = document.getElementById('notification_handlers').getContext('2d');
    color_gradient = notification_handlers_chart.createLinearGradient(0, 0, 0, 250);
    color_gradient.addColorStop(0, set_hex_opacity(color, 0.1));
    color_gradient.addColorStop(1, set_hex_opacity(color, 0.025));

    new Chart(notification_handlers_chart, {
        type: 'line',
        data: {
            labels: <?= $data->notification_handlers_chart['labels'] ?>,
            datasets: [
                {
                    label: <?= json_encode(l('admin_notification_handlers.title')) ?>,
                    data: <?= $data->notification_handlers_chart['notification_handlers'] ?? '[]' ?>,
                    backgroundColor: color_gradient,
                    borderColor: color,
                    fill: true
                }
            ]
        },
        options: chart_options
    });
</script>
<?php $javascript = ob_get_clean() ?>

<?php return (object) ['html' => $html, 'javascript' => $javascript] ?>
