<?php defined('ALTUMCODE') || die() ?>

<div class="index-background py-9">
    <div class="container">
        <?= \Altum\Alerts::output_alerts() ?>

        <div class="row justify-content-center">
            <div class="col-11 col-md-10 col-lg-7">
                <h1 class="index-header text-center mb-2"><?= l('index.header') ?></h1>
            </div>

            <div class="col-10 col-sm-8 col-lg-6">
                <p class="index-subheader text-center mb-5"><?= l('index.subheader') ?></p>
            </div>
        </div>

        <div class="d-flex flex-column flex-lg-row justify-content-center">
            <?php if(is_logged_in()): ?>
                <a href="<?= url('dashboard') ?>" class="btn btn-primary index-button mb-3 mb-lg-0 mr-lg-3">
                    <i class="fas fa-fw fa-sm fa-bolt mr-1"></i> <?= l('index.audit') ?>
                </a>
            <?php else: ?>
                <a href="<?= settings()->plan_guest->status ? url('seo') : url('dashboard') ?>" class="btn btn-primary index-button mb-3 mb-lg-0 mr-lg-3">
                    <i class="fas fa-fw fa-sm fa-bolt mr-1"></i> <?= l('index.audit') ?>
                </a>
            <?php endif ?>

            <?php if(settings()->audits->example_url && settings()->audits->example_url): ?>
                <a href="<?= settings()->audits->example_url ?>" target="_blank" class="btn btn-outline-gray-700 index-button mb-3 mb-lg-0">
                    <?= l('index.example') ?> <i class="fas fa-fw fa-xs fa-external-link-alt"></i>
                </a>
            <?php elseif(settings()->users->register_is_enabled): ?>
                <a href="<?= url('register') ?>" class="btn btn-outline-gray-700 index-button mb-3 mb-lg-0">
                    <?= l('index.register') ?> <i class="fas fa-fw fa-xs fa-arrow-right"></i>
                </a>
            <?php endif ?>
        </div>
    </div>
</div>

<div class="container">
    <div class="row justify-content-center mt-7" data-aos="fade-up">
        <div class="col-12">
            <img src="<?= ASSETS_FULL_URL . 'images/index/hero.webp' ?>" class="img-fluid shadow rounded-lg zoom-animation-subtle" alt="<?= l('index.hero_image_alt') ?>" loading="lazy" />
        </div>
    </div>
</div>

<div class="my-5">&nbsp;</div>

<div class="container">
    <div class="row">
        <div class="col-12 col-lg-4 p-3">
            <div class="card bg-gray-50 mb-md-0 h-100" data-aos="fade-up" data-aos-delay="100">
                <div class="card-body icon-zoom-animation">
                    <div class="index-icon-container mb-2">
                        1
                    </div>

                    <h2 class="h6 m-0"><?= l('index.tutorial.one') ?></h2>

                    <small class="text-muted m-0"><?= l('index.tutorial.one_text') ?></small>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4 p-3">
            <div class="card bg-gray-50 mb-md-0 h-100" data-aos="fade-up" data-aos-delay="200">
                <div class="card-body icon-zoom-animation">
                    <div class="index-icon-container mb-2">
                        2
                    </div>

                    <h2 class="h6 m-0"><?= l('index.tutorial.two') ?></h2>

                    <small class="text-muted m-0"><?= l('index.tutorial.two_text') ?></small>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4 p-3">
            <div class="card bg-gray-50 mb-md-0 h-100" data-aos="fade-up" data-aos-delay="300">
                <div class="card-body icon-zoom-animation">
                    <div class="index-icon-container mb-2">
                        3
                    </div>

                    <h2 class="h6 m-0"><?= l('index.tutorial.three') ?></h2>

                    <small class="text-muted m-0"><?= l('index.tutorial.three_text') ?></small>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="my-5">&nbsp;</div>

<div class="container">
    <div class="row justify-content-between" data-aos="fade-up">
        <div class="col-12 col-md-5 text-center mb-5 mb-md-0" >
            <img src="<?= ASSETS_FULL_URL . 'images/index/generate-audit.webp' ?>" class="img-fluid rounded-2x border-gray-200 border" loading="lazy" alt="<?= l('index.audit_form_image_alt') ?>" />
        </div>

        <div class="col-12 col-md-6 d-flex flex-column justify-content-center">
            <div class="text-uppercase small font-weight-bold text-primary mb-3"><?= l('index.generate_audit.name') ?></div>

            <div>
                <h2 class="mb-4"><?= l('index.generate_audit.header') ?></h2>

                <p class="text-muted mb-4"><?= l('index.generate_audit.subheader') ?></p>

                <div class="font-size-small mb-2"><i class="fas fa-fw fa-sm fa-link text-primary-700 mr-2"></i> <?= l('index.generate_audit.single') ?></div>
                <div class="font-size-small mb-2"><i class="fas fa-fw fa-sm fa-sitemap text-primary-700 mr-2"></i> <?= l('index.generate_audit.sitemap') ?></div>
                <div class="font-size-small mb-2"><i class="fas fa-fw fa-sm fa-layer-group text-primary-700 mr-2"></i> <?= l('index.generate_audit.bulk') ?></div>
                <div class="font-size-small mb-2"><i class="fas fa-fw fa-sm fa-code text-primary-700 mr-2"></i> <?= l('index.generate_audit.html') ?></div>
            </div>
        </div>
    </div>

    <div class="my-5">&nbsp;</div>

    <div class="row justify-content-between" data-aos="fade-up">
        <div class="col-12 col-md-5 text-center mb-5 mb-md-0" >
            <img src="<?= ASSETS_FULL_URL . 'images/index/archived-audit.webp' ?>" class="img-fluid rounded-2x border-gray-200 border" loading="lazy" alt="<?= l('index.audit_history_image_alt') ?>" />
        </div>

        <div class="col-12 col-md-6 d-flex flex-column justify-content-center">
            <div class="text-uppercase small font-weight-bold text-primary mb-3"><?= l('index.archived_audits.name') ?></div>

            <div>
                <h2 class="mb-4"><?= l('index.archived_audits.header') ?></h2>

                <p class="text-muted mb-4"><?= l('index.archived_audits.subheader') ?></p>

                <div class="font-size-small mb-2"><i class="fas fa-fw fa-sm fa-chart-line text-primary-700 mr-2"></i> <?= l('index.archived_audits.one') ?></div>
                <div class="font-size-small mb-2"><i class="fas fa-fw fa-sm fa-exclamation-circle text-primary-700 mr-2"></i> <?= l('index.archived_audits.two') ?></div>
                <div class="font-size-small mb-2"><i class="fas fa-fw fa-sm fa-bug text-primary-700 mr-2"></i> <?= l('index.archived_audits.three') ?></div>
                <div class="font-size-small mb-2"><i class="fas fa-fw fa-sm fa-tasks text-primary-700 mr-2"></i> <?= l('index.archived_audits.four') ?></div>
            </div>
        </div>
    </div>

    <div class="my-5">&nbsp;</div>

    <div class="row justify-content-between" data-aos="fade-up">
        <div class="col-12 col-md-5 text-center mb-5 mb-md-0" >
            <img src="<?= ASSETS_FULL_URL . 'images/index/audit.webp' ?>" class="img-fluid rounded-2x border-gray-200 border" loading="lazy" alt="<?= l('index.audit_tests_image_alt') ?>" />
        </div>

        <div class="col-12 col-md-6 d-flex flex-column justify-content-center">
            <div class="text-uppercase small font-weight-bold text-primary mb-3"><?= l('index.audit.name') ?></div>

            <div>
                <h2 class="mb-4"><?= l('index.audit.header') ?></h2>

                <p class="text-muted mb-4"><?= l('index.audit.subheader') ?></p>

                <div class="font-size-small mb-2"><i class="fas fa-fw fa-sm fa-check-double text-primary-700 mr-2"></i> <?= l('index.audit.one') ?></div>
                <div class="font-size-small mb-2"><i class="fas fa-fw fa-sm fa-heartbeat text-primary-700 mr-2"></i> <?= l('index.audit.two') ?></div>
                <div class="font-size-small mb-2"><i class="fas fa-fw fa-sm fa-stopwatch text-primary-700 mr-2"></i> <?= l('index.audit.three') ?></div>
                <div class="font-size-small mb-2"><i class="fas fa-fw fa-sm fa-tachometer-alt text-primary-700 mr-2"></i> <?= l('index.audit.four') ?></div>
                <div class="font-size-small mb-2"><i class="fas fa-fw fa-sm fa-file-alt text-primary-700 mr-2"></i> <?= l('index.audit.five') ?></div>
                <div class="font-size-small mb-2"><i class="fas fa-fw fa-sm fa-tools text-primary-700 mr-2"></i> <?= l('index.audit.six') ?></div>
            </div>
        </div>
    </div>
</div>

<div class="my-5">&nbsp;</div>

<div class="container">
    <div class="row">
        <div class="col-12 col-md-6 col-lg-4 p-4 up-animation">
            <div class="d-flex flex-column justify-content-between h-100" data-aos="fade-up" data-aos-delay="100">
                <img src="<?= ASSETS_FULL_URL . 'images/index/public_audits.webp' ?>" class="img-fluid rounded-2x mb-4" loading="lazy" alt="<?= l('index.public_audits_image_alt') ?>" />

                <div>
                    <div class="mb-2">
                        <span class="h5"><?= l('index.public_audits.header') ?></span>
                    </div>
                    <span class="text-muted"><?= l('index.public_audits.subheader') ?></span>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-4 p-4 up-animation">
            <div class="d-flex flex-column justify-content-between h-100" data-aos="fade-up" data-aos-delay="200">
                <img src="<?= ASSETS_FULL_URL . 'images/index/password_protection.webp' ?>" class="img-fluid rounded-2x mb-4" loading="lazy" alt="<?= l('index.password_audits_image_alt') ?>" />

                <div>
                    <div class="mb-2">
                        <span class="h5"><?= l('index.password_protection.header') ?></span>
                    </div>
                    <span class="text-muted"><?= l('index.password_protection.subheader') ?></span>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-4 p-4 up-animation">
            <div class="d-flex flex-column justify-content-between h-100" data-aos="fade-up" data-aos-delay="300">
                <img src="<?= ASSETS_FULL_URL . 'images/index/audit_refresh.webp' ?>" class="img-fluid rounded-2x mb-4" loading="lazy" alt="<?= l('index.recurring_audits_image_alt') ?>" />

                <div>
                    <div class="mb-2">
                        <span class="h5"><?= l('index.audit_refresh.header') ?></span>
                    </div>
                    <span class="text-muted"><?= l('index.audit_refresh.subheader') ?></span>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-4 p-4 up-animation">
            <div class="d-flex flex-column justify-content-between h-100" data-aos="fade-up" data-aos-delay="400">
                <img src="<?= ASSETS_FULL_URL . 'images/index/export.webp' ?>" class="img-fluid rounded-2x mb-4" loading="lazy" alt="<?= l('index.export_audits_image_alt') ?>" />

                <div>
                    <div class="mb-2">
                        <span class="h5"><?= l('index.export.header') ?></span>
                    </div>
                    <span class="text-muted"><?= l('index.export.subheader') ?></span>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-4 p-4 up-animation">
            <div class="d-flex flex-column justify-content-between h-100" data-aos="fade-up" data-aos-delay="500">
                <img src="<?= ASSETS_FULL_URL . 'images/index/api.webp' ?>" class="img-fluid rounded-2x mb-4" loading="lazy" alt="<?= l('index.api_image_alt') ?>" />

                <div>
                    <div class="mb-2">
                        <span class="h5"><?= l('index.api.header') ?></span>
                    </div>
                    <span class="text-muted"><?= l('index.api.subheader') ?></span>
                </div>
            </div>
        </div>

        <?php if(settings()->audits->domains_is_enabled): ?>
            <div class="col-12 col-md-6 col-lg-4 p-4 up-animation">
                <div class="d-flex flex-column justify-content-between h-100" data-aos="fade-up" data-aos-delay="600">
                    <img src="<?= ASSETS_FULL_URL . 'images/index/domains.webp' ?>" class="img-fluid rounded-2x mb-4" loading="lazy" alt="<?= l('index.domains_image_alt') ?>" />

                    <div>
                        <div class="mb-2">
                            <span class="h5"><?= l('index.domains.header') ?></span>
                        </div>
                        <span class="text-muted"><?= l('index.domains.subheader') ?></span>
                    </div>
                </div>
            </div>
        <?php endif ?>
    </div>
</div>

<div class="my-5">&nbsp;</div>

<div class="container">
    <h2 class="text-center mb-5"><?= l('index.tests.header') ?> <i class="fas fa-fw fa-xs fa-bolt text-primary ml-1"></i></h2>

    <div class="">
        <?php $available_tests = require APP_PATH . 'includes/available_audit_tests.php'; ?>
        <?php $groups = array_chunk($available_tests, 8, true); ?>

        <?php for ($i = 0; $i < 5; $i++): ?>
            <div class="index-marquee-wrapper">
                <?php $j = 1 ?>
                <?php foreach($groups[$i] as $key => $value): ?>
                    <div class="py-2 px-3 bg-gray-50 border border-gray-100 badge-pill small mx-3 my-2 text-gray-700 zoom-animation-subtle index-marquee-item-<?= $i % 2 == 0 ? 'left' : 'right' ?>" style="--n: <?= $j++ ?>" >
                        <i class="<?= $value['icon'] ?> fa-fw fa-sm text-muted mr-1"></i> <?= l('audits.test.' . $key) ?>
                    </div>
                <?php endforeach ?>
            </div>
        <?php endfor ?>
    </div>
</div>

<div class="d-flex align-items-center justify-content-center flex-wrap"></div>

<?php if(settings()->tools->is_enabled && $data->enabled_tools): ?>
    <div class="my-5">&nbsp;</div>

    <div class="container">
        <h2 class="text-center mb-3"><?= sprintf(l('index.tools.header'), nr($data->enabled_tools)) ?> <i class="fas fa-fw fa-xs fa-screwdriver-wrench text-dark ml-1"></i></h2>

        <p class="text-muted text-center mb-4"><?= l('index.tools.subheader') ?></p>

        <div class="row position-relative">
            <div class="index-fade"></div>
            <?php foreach($data->tools as $key => $value): ?>
                <?php if(settings()->tools->available_tools->{$key}): ?>
                    <div class="col-12 col-lg-4 p-3 position-relative" data-tool-id="<?= $key ?>" data-tool-name="<?= l('tools.' . $key . '.name') ?>">
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
                        </div>
                    </div>
                <?php endif ?>
            <?php endforeach ?>
        </div>
    </div>

<?php endif ?>

<div class="my-5">&nbsp;</div>

<div class="p-4">
    <div class="card rounded-2x index-stats-card">
        <div class="card-body py-5 py-lg-6 text-center">
            <span class="h3"><?= sprintf(l('index.stats'), nr($data->total_audits, 0, true, true), nr($data->total_websites, 0, true, true)) ?></span>
        </div>
    </div>
</div>

<div class="my-5">&nbsp;</div>

<div class="container">
    <div class="text-center mb-4">
        <h2><?= l('index.notifications_handlers.header') ?> <i class="fas fa-fw fa-xs fa-bell ml-1"></i> </h2>
        <p class="text-muted"><?= l('index.notifications_handlers.subheader') ?></p>
    </div>

    <div class="row mx-n4">
        <?php $notification_handlers = require APP_PATH . 'includes/notification_handlers.php' ?>
        <?php $i = 0; ?>
        <?php foreach($notification_handlers as $key => $notification_handler): ?>
            <div class="col-6 col-lg-4 p-4" data-aos="fade-up" data-aos-delay="<?= $i * 100 ?>">
                <div class="position-relative w-100 h-100 icon-zoom-animation">
                    <div class="position-absolute rounded-2x w-100 h-100" style="background: <?= $notification_handler['color'] ?>;opacity: 0.05;"></div>

                    <div class="rounded-2x w-100 p-4 text-truncate text-center">
                        <div><i class="<?= $notification_handler['icon'] ?> fa-fw fa-xl mx-1" style="color: <?= $notification_handler['color'] ?>"></i></div>

                        <div class="mt-3 mb-0 h6"><?= l('notification_handlers.input.type_' . $key) ?></div>
                    </div>
                </div>
            </div>
            <?php $i++ ?>
        <?php endforeach ?>
    </div>
</div>

<?php if(settings()->main->display_index_testimonials): ?>
    <div class="my-5">&nbsp;</div>

    <div class="p-4 mt-5">
        <div class="py-7 bg-primary-100 rounded-2x">
            <div class="container">
                <div class="text-center">
                    <h2><?= l('index.testimonials.header') ?> <i class="fas fa-fw fa-xs fa-check-circle text-primary"></i></h2>
                </div>

                <?php
                $language_array = \Altum\Language::get(\Altum\Language::$name);
                if(\Altum\Language::$main_name != \Altum\Language::$name) {
                    $language_array = array_merge(\Altum\Language::get(\Altum\Language::$main_name), $language_array);
                }

                $testimonials_language_keys = [];
                foreach ($language_array as $key => $value) {
                    if (preg_match('/index\.testimonials\.(\w+)\./', $key, $matches)) {
                        $testimonials_language_keys[] = $matches[1];
                    }
                }

                $testimonials_language_keys = array_unique($testimonials_language_keys);
                ?>

                <div class="row mt-8">
                    <?php foreach($testimonials_language_keys as $key => $value): ?>
                        <div class="col-12 col-lg-4 mb-6 mb-lg-0" data-aos="fade-up" data-aos-delay="<?= $key * 100 ?>">
                            <div class="card border-0 zoom-animation-subtle">
                                <div class="card-body">
                                    <img src="<?= ASSETS_FULL_URL . 'images/index/testimonial-' . $value . '.jpeg' ?>" class="img-fluid index-testimonial-avatar" alt="<?= l('index.testimonials.' . $value . '.name') . ', ' . l('index.testimonials.' . $value . '.attribute') ?>" loading="lazy" />

                                    <p class="mt-5">
                                        <span class="text-gray-800 font-weight-bold text-muted h5">“</span>
                                        <span><?= l('index.testimonials.' . $value . '.text') ?></span>
                                        <span class="text-gray-800 font-weight-bold text-muted h5">”</span>
                                    </p>

                                    <div class="blockquote-footer mt-4">
                                        <span class="font-weight-bold"><?= l('index.testimonials.' . $value . '.name') ?></span>, <span class="text-muted"><?= l('index.testimonials.' . $value . '.attribute') ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>
            </div>
        </div>
    </div>
<?php endif ?>

<?php if(settings()->main->display_index_plans): ?>
    <div class="my-5">&nbsp;</div>

    <div class="container">
        <div class="text-center mb-5">
            <h2><?= l('index.pricing.header') ?></h2>
            <p class="text-muted"><?= l('index.pricing.subheader') ?></p>
        </div>

        <?= $this->views['plans'] ?>
    </div>
<?php endif ?>

<?php if(settings()->main->display_index_faq): ?>
    <div class="my-5">&nbsp;</div>

    <div class="container">
        <div class="text-center mb-5">
            <h2><?= sprintf(l('index.faq.header'), '<span class="text-primary">', '</span>') ?></h2>
        </div>

        <?php
        $language_array = \Altum\Language::get(\Altum\Language::$name);
        if(\Altum\Language::$main_name != \Altum\Language::$name) {
            $language_array = array_merge(\Altum\Language::get(\Altum\Language::$main_name), $language_array);
        }

        $faq_language_keys = [];
        foreach ($language_array as $key => $value) {
            if (preg_match('/index\.faq\.(\w+)\./', $key, $matches)) {
                $faq_language_keys[] = $matches[1];
            }
        }

        $faq_language_keys = array_unique($faq_language_keys);
        ?>

        <div class="accordion index-faq" id="faq_accordion">
            <?php foreach($faq_language_keys as $key): ?>
                <div class="card">
                    <div class="card-body">
                        <div class="" id="<?= 'faq_accordion_' . $key ?>">
                            <h3 class="mb-0">
                                <button class="btn btn-lg font-weight-bold btn-block d-flex justify-content-between text-gray-800 px-0 icon-zoom-animation" type="button" data-toggle="collapse" data-target="<?= '#faq_accordion_answer_' . $key ?>" aria-expanded="true" aria-controls="<?= 'faq_accordion_answer_' . $key ?>">
                                    <span><?= l('index.faq.' . $key . '.question') ?></span>

                                    <span data-icon>
                                        <i class="fas fa-fw fa-circle-chevron-down"></i>
                                    </span>
                                </button>
                            </h3>
                        </div>

                        <div id="<?= 'faq_accordion_answer_' . $key ?>" class="collapse text-muted mt-2" aria-labelledby="<?= 'faq_accordion_' . $key ?>" data-parent="#faq_accordion">
                            <?= l('index.faq.' . $key . '.answer') ?>
                        </div>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
    </div>

    <?php ob_start() ?>
    <script>
        'use strict';

        $('#faq_accordion').on('show.bs.collapse', event => {
            let svg = event.target.parentElement.querySelector('[data-icon] svg')
            svg.style.transform = 'rotate(180deg)';
            svg.style.color = 'var(--primary)';
        })

        $('#faq_accordion').on('hide.bs.collapse', event => {
            let svg = event.target.parentElement.querySelector('[data-icon] svg')
            svg.style.color = 'var(--primary-800)';
            svg.style.removeProperty('transform');
        })
    </script>
    <?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
<?php endif ?>

<div class="my-5">&nbsp;</div>

<div class="container">
    <div class="row">
        <div class="col-12 col-lg-4 p-3">
            <div class="card bg-gray-50 mb-md-0 h-100" data-aos="fade-up" data-aos-delay="100">
                <div class="card-body icon-zoom-animation">
                    <div class="index-icon-container mb-2">
                        <i class="fas fa-fw fa-bolt"></i>
                    </div>

                    <h2 class="h6 m-0"><?= l('index.steps.one') ?></h2>

                    <small class="text-muted m-0"><?= l('index.steps.one_text') ?></small>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4 p-3">
            <div class="card bg-gray-50 mb-md-0 h-100" data-aos="fade-up" data-aos-delay="200">
                <div class="card-body icon-zoom-animation">
                    <div class="index-icon-container mb-2">
                        <i class="fas fa-fw fa-magnifying-glass-plus"></i>
                    </div>

                    <h2 class="h6 m-0"><?= l('index.steps.two') ?></h2>

                    <small class="text-muted m-0"><?= l('index.steps.two_text') ?></small>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4 p-3">
            <div class="card bg-gray-50 mb-md-0 h-100" data-aos="fade-up" data-aos-delay="300">
                <div class="card-body icon-zoom-animation">
                    <div class="index-icon-container mb-2">
                        <i class="fas fa-fw fa-shield"></i>
                    </div>

                    <h2 class="h6 m-0"><?= l('index.steps.three') ?></h2>

                    <small class="text-muted m-0"><?= l('index.steps.three_text') ?></small>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if(settings()->users->register_is_enabled): ?>
    <div class="my-5">&nbsp;</div>

    <div class="container">
        <div class="card border-0 index-cta py-5 py-lg-6" data-aos="fade-up">
            <div class="card-body">
                <div class="row align-items-center justify-content-center">
                    <div class="col-12 col-lg-5">
                        <div class="text-center text-lg-left mb-4 mb-lg-0">
                            <h2 class="h1"><?= l('index.cta.header') ?></h2>
                            <p class="h5"><?= l('index.cta.subheader') ?></p>
                        </div>
                    </div>

                    <div class="col-12 col-lg-5 mt-4 mt-lg-0">
                        <div class="text-center text-lg-right">
                            <?php if(is_logged_in()): ?>
                                <a href="<?= url('dashboard') ?>" class="btn btn-outline-primary zoom-animation">
                                    <?= l('dashboard.menu') ?> <i class="fas fa-fw fa-arrow-right"></i>
                                </a>
                            <?php else: ?>
                                <a href="<?= url('register') ?>" class="btn btn-outline-primary zoom-animation">
                                    <?= l('index.cta.register') ?> <i class="fas fa-fw fa-arrow-right"></i>
                                </a>
                            <?php endif ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif ?>

<?php if(count($data->blog_posts)): ?>
    <div class="my-5">&nbsp;</div>

    <div class="container">
        <div class="text-center mb-5">
            <h2><?= sprintf(l('index.blog.header'), '<span class="text-primary">', '</span>') ?></h2>
        </div>

        <div class="row">
            <?php foreach($data->blog_posts as $blog_post): ?>
                <div class="col-12 col-lg-4 p-4">
                    <div class="card h-100 zoom-animation-subtle">
                        <div class="card-body">
                            <?php if($blog_post->image): ?>
                                <a href="<?= SITE_URL . ($blog_post->language ? \Altum\Language::$active_languages[$blog_post->language] . '/' : null) . 'blog/' . $blog_post->url ?>" aria-label="<?= $blog_post->title ?>">
                                    <img src="<?= \Altum\Uploads::get_full_url('blog') . $blog_post->image ?>" class="blog-post-image-small img-fluid w-100 rounded mb-4" alt="<?= $blog_post->image_description ?>" loading="lazy" />
                                </a>
                            <?php endif ?>

                            <a href="<?= SITE_URL . ($blog_post->language ? \Altum\Language::$active_languages[$blog_post->language] . '/' : null) . 'blog/' . $blog_post->url ?>">
                                <h3 class="h5 card-title mb-2"><?= $blog_post->title ?></h3>
                            </a>

                            <p class="text-muted mb-0"><?= $blog_post->description ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
    </div>
<?php endif ?>


<?php ob_start() ?>
<link rel="stylesheet" href="<?= ASSETS_FULL_URL . 'css/libraries/aos.min.css?v=' . PRODUCT_CODE ?>">
<?php \Altum\Event::add_content(ob_get_clean(), 'head') ?>

<?php ob_start() ?>
<script src="<?= ASSETS_FULL_URL . 'js/libraries/aos.min.js?v=' . PRODUCT_CODE ?>"></script>

<script>
    AOS.init({
        delay: 100,
        duration: 600
    });
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>

<?php ob_start() ?>
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Organization",
        "name": "<?= settings()->main->title ?>",
        "url": "<?= url() ?>",
        <?php if(settings()->main->{'logo_' . \Altum\ThemeStyle::get()}): ?>
        "logo": "<?= settings()->main->{'logo_' . \Altum\ThemeStyle::get() . '_full_url'} ?>",
        <?php endif ?>
        "slogan": "<?= l('index.header') ?>",
        "contactPoint": {
            "@type": "ContactPoint",
            "url": "<?= url('contact') ?>",
            "contactType": "Contact us"
        }
    }
</script>

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
                }
            ]
        }
</script>

<?php if(settings()->main->display_index_faq): ?>
    <?php
    $faqs = [];
    foreach($faq_language_keys as $key) {
        $faqs[] = [
            '@type' => 'Question',
            'name' => l('index.faq.' . $key . '.question'),
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => l('index.faq.' . $key . '.answer'),
            ]
        ];
    }
    ?>
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "FAQPage",
            "mainEntity": <?= json_encode($faqs) ?>
        }
    </script>
<?php endif ?>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
