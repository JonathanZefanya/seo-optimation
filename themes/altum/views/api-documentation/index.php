<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?php if(settings()->main->breadcrumbs_is_enabled): ?>
        <nav aria-label="breadcrumb">
            <ol class="custom-breadcrumbs small">
                <li><a href="<?= url() ?>"><?= l('index.breadcrumb') ?></a> <i class="fas fa-fw fa-angle-right"></i></li>
                <li class="active" aria-current="page"><?= l('api_documentation.breadcrumb') ?></li>
            </ol>
        </nav>
    <?php endif ?>

    <h1 class="h4"><?= l('api_documentation.header') ?></h1>
    <p class="text-muted"><?= l('api_documentation.subheader') ?></p>

    <div class="card mb-5">
        <div class="card-body">
            <div class="mb-5">
                <?php if(is_logged_in()): ?>
                    <div class="form-group">
                        <label for="api_key"><?= l('api_documentation.api_key') ?></label>
                        <input type="text" id="api_key" value="<?= $this->user->api_key ?>" class="form-control" onclick="this.select();" readonly="readonly" />
                    </div>
                <?php else: ?>
                    <div class="mb-3">
                        <a href="<?= url('account-api') ?>" target="_blank" class="btn btn-block btn-outline-primary"><?= l('api_documentation.api_key') ?></a>
                    </div>
                <?php endif ?>

                <div class="form-group">
                    <label for="base_url"><?= l('api_documentation.base_url') ?></label>
                    <input type="text" id="base_url" value="<?= SITE_URL . 'api' ?>" class="form-control" onclick="this.select();" readonly="readonly" />
                </div>
            </div>

            <div class="mb-4">
                <h2 class="h5"><?= l('api_documentation.authentication.header') ?></h2>
                <p class="text-muted"><?= l('api_documentation.authentication.subheader') ?></p>
            </div>

            <div class="form-group">
                <label><?= l('api_documentation.example') ?></label>
                <div class="card bg-gray-50 border-0">
                    <div class="card-body">
                        curl --request GET \<br />
                        --url '<?= SITE_URL . 'api/' ?><span class="text-primary">{endpoint}</span>' \<br />
                        --header 'Authorization: Bearer <span class="text-primary">{api_key}</span>' \
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-sm-6 col-xl-4 p-3 position-relative">
            <div class="card d-flex flex-row h-100 overflow-hidden">
                <div class="border-right border-gray-100 px-3 d-flex flex-column justify-content-center">
                    <a href="<?= url('api-documentation/user') ?>" class="stretched-link">
                        <i class="fas fa-fw fa-user text-primary-600"></i>
                    </a>
                </div>

                <div class="card-body d-flex align-items-center">
                    <?= l('api_documentation.user') ?>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-4 p-3 position-relative">
            <div class="card d-flex flex-row h-100 overflow-hidden">
                <div class="border-right border-gray-100 px-3 d-flex flex-column justify-content-center">
                    <a href="<?= url('api-documentation/websites') ?>" class="stretched-link">
                        <i class="fas fa-fw fa-pager text-primary-600"></i>
                    </a>
                </div>

                <div class="card-body d-flex align-items-center">
                    <?= l('websites.title') ?>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-4 p-3 position-relative">
            <div class="card d-flex flex-row h-100 overflow-hidden">
                <div class="border-right border-gray-100 px-3 d-flex flex-column justify-content-center">
                    <a href="<?= url('api-documentation/audits') ?>" class="stretched-link">
                        <i class="fas fa-fw fa-bolt text-primary-600"></i>
                    </a>
                </div>

                <div class="card-body d-flex align-items-center">
                    <?= l('audits.title') ?>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-4 p-3 position-relative">
            <div class="card d-flex flex-row h-100 overflow-hidden">
                <div class="border-right border-gray-100 px-3 d-flex flex-column justify-content-center">
                    <a href="<?= url('api-documentation/archived-audits') ?>" class="stretched-link">
                        <i class="fas fa-fw fa-archive text-primary-600"></i>
                    </a>
                </div>

                <div class="card-body d-flex align-items-center">
                    <?= l('archived_audits.title') ?>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-4 p-3 position-relative">
            <div class="card d-flex flex-row h-100 overflow-hidden">
                <div class="border-right border-gray-100 px-3 d-flex flex-column justify-content-center">
                    <a href="<?= url('api-documentation/notification-handlers') ?>" class="stretched-link">
                        <i class="fas fa-fw fa-bell text-primary-600"></i>
                    </a>
                </div>

                <div class="card-body d-flex align-items-center">
                    <?= l('api_documentation.notification_handlers') ?>
                </div>
            </div>
        </div>

        <?php if(settings()->audits->domains_is_enabled): ?>
            <div class="col-12 col-sm-6 col-xl-4 p-3 position-relative">
                <div class="card d-flex flex-row h-100 overflow-hidden">
                    <div class="border-right border-gray-100 px-3 d-flex flex-column justify-content-center">
                        <a href="<?= url('api-documentation/domains') ?>" class="stretched-link">
                            <i class="fas fa-fw fa-globe text-primary-600"></i>
                        </a>
                    </div>

                    <div class="card-body">
                        <?= l('domains.title') ?>
                    </div>
                </div>
            </div>
        <?php endif ?>

        <?php if(\Altum\Plugin::is_active('teams')): ?>
            <div class="col-12 col-sm-6 col-xl-4 p-3 position-relative">
                <div class="card d-flex flex-row h-100 overflow-hidden">
                    <div class="border-right border-gray-100 px-3 d-flex flex-column justify-content-center">
                        <a href="<?= url('api-documentation/teams') ?>" class="stretched-link">
                            <i class="fas fa-fw fa-user-cog text-primary-600"></i>
                        </a>
                    </div>

                    <div class="card-body">
                        <?= l('teams.title') ?>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-4 p-3 position-relative">
                <div class="card d-flex flex-row h-100 overflow-hidden">
                    <div class="border-right border-gray-100 px-3 d-flex flex-column justify-content-center">
                        <a href="<?= url('api-documentation/team-members') ?>" class="stretched-link">
                            <i class="fas fa-fw fa-users-cog text-primary-600"></i>
                        </a>
                    </div>

                    <div class="card-body">
                        <?= l('api_documentation.team_members') ?>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-4 p-3 position-relative">
                <div class="card d-flex flex-row h-100 overflow-hidden">
                    <div class="border-right border-gray-100 px-3 d-flex flex-column justify-content-center">
                        <a href="<?= url('api-documentation/teams-member') ?>" class="stretched-link">
                            <i class="fas fa-fw fa-user-tag text-primary-600"></i>
                        </a>
                    </div>

                    <div class="card-body">
                        <?= l('api_documentation.teams_member') ?>
                    </div>
                </div>
            </div>
        <?php endif ?>

        <div class="col-12 col-sm-6 col-xl-4 p-3 position-relative">
            <div class="card d-flex flex-row h-100 overflow-hidden">
                <div class="border-right border-gray-100 px-3 d-flex flex-column justify-content-center">
                    <a href="<?= url('api-documentation/payments') ?>" class="stretched-link">
                        <i class="fas fa-fw fa-credit-card text-primary-600"></i>
                    </a>
                </div>

                <div class="card-body d-flex align-items-center">
                    <?= l('account_payments.title') ?>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-4 p-3 position-relative">
            <div class="card d-flex flex-row h-100 overflow-hidden">
                <div class="border-right border-gray-100 px-3 d-flex flex-column justify-content-center">
                    <a href="<?= url('api-documentation/users-logs') ?>" class="stretched-link">
                        <i class="fas fa-fw fa-scroll text-primary-600"></i>
                    </a>
                </div>

                <div class="card-body d-flex align-items-center">
                    <?= l('account_logs.title') ?>
                </div>
            </div>
        </div>
    </div>
</div>

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
                    "name": "<?= l('api_documentation.title') ?>",
                    "item": "<?= url('api-documentation') ?>"
                }
            ]
        }
    </script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
