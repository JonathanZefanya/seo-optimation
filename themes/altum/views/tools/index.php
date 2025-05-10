<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <div class="row mb-4">
        <div class="col-12 col-lg d-flex align-items-center mb-3 mb-lg-0 text-truncate">
            <h1 class="h4 m-0 text-truncate"><i class="fas fa-fw fa-xs fa-screwdriver-wrench mr-1"></i> <?= l('tools.header') ?></h1>

            <div class="ml-2">
                <span data-toggle="tooltip" title="<?= l('tools.subheader') ?>">
                    <i class="fas fa-fw fa-info-circle text-muted"></i>
                </span>
            </div>
        </div>
    </div>

    <form action="" method="get" role="form">
        <div class="form-group">
            <input type="search" name="search" class="form-control form-control-lg" value="" placeholder="<?= l('global.filters.search') ?>" aria-label="<?= l('global.filters.search') ?>" />
        </div>
    </form>

    <div class="row">
        <?php foreach($data->tools as $key => $value): ?>
            <?php if(settings()->tools->available_tools->{$key}): ?>
                <div class="col-12 col-lg-6 p-3 position-relative" data-tool-id="<?= $key ?>" data-tool-name="<?= l('tools.' . $key . '.name') ?>">
                    <div class="card d-flex flex-row h-100 overflow-hidden">
                        <div class="tool-icon-wrapper d-flex flex-column justify-content-center">
                            <div class="bg-primary-100 d-flex align-items-center justify-content-center rounded tool-icon">
                                <i class="<?= $value['icon'] ?> fa-fw text-primary-600"></i>
                            </div>
                        </div>

                        <div class="card-body text-truncate">
                            <a href="<?= url('tools/' . str_replace('_', '-', $key)) ?>" class="stretched-link text-decoration-none text-dark">
                                <strong><?= l('tools.' . $key . '.name') ?></strong>
                            </a>
                            <p class="text-truncate text-muted small m-0"><?= l('tools.' . $key . '.description') ?></p>
                        </div>

                        <?php if(settings()->tools->views_is_enabled || settings()->tools->last_submissions_is_enabled): ?>
                            <div class="p-3 d-flex flex-column">
                                <?php if(settings()->tools->views_is_enabled): ?>
                                <div class="badge badge-gray-100 mb-2" data-toggle="tooltip" title="<?= l('tools.total_views') ?>">
                                    <i class="fas fa-fw fa-sm fa-eye mr-1"></i> <?= nr($data->tools_usage[$key]->total_views ?? 0) ?>
                                </div>
                                <?php endif ?>

                                <?php if(settings()->tools->last_submissions_is_enabled): ?>
                                    <div class="badge badge-gray-100" data-toggle="tooltip" title="<?= l('tools.total_submissions') ?>">
                                        <i class="fas fa-fw fa-sm fa-check mr-1"></i> <?= nr($data->tools_usage[$key]->total_submissions ?? 0) ?>
                                    </div>
                                <?php endif ?>
                            </div>
                        <?php endif ?>
                    </div>
                </div>
            <?php endif ?>
        <?php endforeach ?>
    </div>
</div>

<?php ob_start() ?>
    <script>
        'use strict';

        let tools = [];
        document.querySelectorAll('[data-tool-id]').forEach(element => tools.push({
            id: element.getAttribute('data-tool-id'),
            name: element.getAttribute('data-tool-name').toLowerCase(),
        }));

        document.querySelector('input[name="search"]').addEventListener('keyup', event => {
            let string = event.currentTarget.value.toLowerCase();

            for(let tool of tools) {
                if(tool.name.includes(string)) {
                    document.querySelector(`[data-tool-id="${tool.id}"]`).classList.remove('d-none');
                } else {
                    document.querySelector(`[data-tool-id="${tool.id}"]`).classList.add('d-none');
                }
            }
        });
    </script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>

<?php ob_start() ?>
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "BreadcrumbList",
            "itemListElement": [
                {
                    "@type": "ListItem",
                    "position": 1,
                    "name": "<?= l('index.title') ?>",
                    "item": "<?= url() ?>"
                },
                {
                    "@type": "ListItem",
                    "position": 2,
                    "name": "<?= l('tools.title') ?>",
                    "item": "<?= url('tools') ?>"
                }
            ]
        }
    </script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
