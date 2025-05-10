<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <?php if(!$this->user->plan_settings->removable_branding_is_enabled): ?>
        <div class="d-none d-print-block mb-4">
            <div class="card">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <p class="mb-0 h5 font-weight-bold"><?= settings()->main->title ?></p>
                        <a href="<?= url() ?>" class="small"><?= remove_url_protocol_from_url(url()) ?></a>
                    </div>

                    <?php if(settings()->main->{'logo_' . \Altum\ThemeStyle::get()} != ''): ?>
                        <img src="<?= settings()->main->{'logo_' . \Altum\ThemeStyle::get() . '_full_url'} ?>" class="audit-logo ml-3" alt="<?= l('global.accessibility.logo_alt') ?>" />
                    <?php endif ?>
                </div>
            </div>
        </div>
    <?php endif ?>

    <div class="d-print-none">
        <?php if(settings()->main->breadcrumbs_is_enabled): ?>
            <?php if(is_logged_in()): ?>
                <nav aria-label="breadcrumb">
                    <ol class="custom-breadcrumbs small">
                        <li>
                            <a href="<?= url('audits') ?>"><?= l('archived_audits.breadcrumb') ?></a><i class="fas fa-fw fa-angle-right"></i>
                        </li>
                        <li class="active" aria-current="page"><?= l('archived_audit.breadcrumb') ?></li>
                    </ol>
                </nav>
            <?php endif ?>
        <?php endif ?>
    </div>

    <?php
    $audit_score_class_name = match (true) {
        $data->archived_audit->score >= 80 => 'success',
        $data->archived_audit->score >= 50 => 'warning',
        $data->archived_audit->score >= 0 => 'danger',
    };
    ?>

    <div class="position-relative mb-3 d-print-none">
        <div class="audit-archived-audits-wrapper-left"></div>
        <div class="audit-archived-audits-wrapper-right"></div>

        <div class="d-flex align-items-center audit-archived-audits-wrapper">
            <?php foreach($data->archived_audits_left as $archived_audit): ?>
                <?php
                $archived_audit_score_class_name = match (true) {
                    $archived_audit->score >= 80 => 'text-success',
                    $archived_audit->score >= 50 => 'text-warning',
                    $archived_audit->score >= 0 => 'text-danger',
                };
                ?>
                <div class="card p-3 text-center position-relative white-space-normal min-width-fit-content">
                    <a href="<?= url('archived-audit/' . $archived_audit->archived_audit_id) ?>" class="stretched-link font-weight-bold text-decoration-none <?= $archived_audit_score_class_name ?>">
                        <?= $archived_audit->score ?>
                    </a>
                    <div class="small text-muted"><?= \Altum\Date::get_timeago($archived_audit->datetime) ?></div>
                </div>

                <div>
                    <i class="fas fa-fw fa-sm fa-arrow-right text-gray-300 mx-3"></i>
                </div>
            <?php endforeach ?>

            <div id="current_audit" class="card p-3 text-center position-relative white-space-normal min-width-fit-content border-primary">
                <span href="<?= url('archived-audit/' . $data->archived_audit->audit_id) ?>" class="stretched-link font-weight-bold text-decoration-none <?= 'text-' . $audit_score_class_name ?>">
                    <?= $data->archived_audit->score ?>
                </span>
                <div class="small text-muted"><?= \Altum\Date::get_timeago($data->archived_audit->datetime) ?></div>
            </div>

            <?php $total_archived_audits_right = count($data->archived_audits_right); ?>

            <?php if($total_archived_audits_right): ?>
                <div>
                    <i class="fas fa-fw fa-sm fa-arrow-right text-gray-300 mx-3"></i>
                </div>
            <?php endif ?>

            <?php $i = 1; ?>
            <?php foreach($data->archived_audits_right as $archived_audit): ?>
                <?php
                $archived_audit_score_class_name = match (true) {
                    $archived_audit->score >= 80 => 'text-success',
                    $archived_audit->score >= 50 => 'text-warning',
                    $archived_audit->score >= 0 => 'text-danger',
                };
                ?>
                <div class="card p-3 text-center position-relative white-space-normal min-width-fit-content">
                    <a href="<?= url('archived-audit/' . $archived_audit->archived_audit_id) ?>" class="stretched-link font-weight-bold text-decoration-none <?= $archived_audit_score_class_name ?>">
                        <?= $archived_audit->score ?>
                    </a>
                    <div class="small text-muted"><?= \Altum\Date::get_timeago($archived_audit->datetime) ?></div>
                </div>

                <?php if($i++ != $total_archived_audits_right): ?>
                    <div>
                        <i class="fas fa-fw fa-sm fa-arrow-right text-gray-300 mx-3"></i>
                    </div>
                <?php endif ?>
            <?php endforeach ?>

            <div>
                <i class="fas fa-fw fa-sm fa-bolt text-gray-300 mx-3"></i>
            </div>

            <div class="card p-3 text-center position-relative white-space-normal min-width-fit-content border-primary-100 border0">
                <a href="<?= url('audit/' . $data->audit->audit_id) ?>" class="stretched-link font-weight-bold text-decoration-none <?= 'text-' . $audit_score_class_name ?>">
                    <?= $data->audit->score ?>
                </a>
                <div class="small text-muted"><?= \Altum\Date::get_timeago($data->audit->last_refresh_datetime) ?></div>
            </div>
        </div>
    </div>

    <?php ob_start() ?>
    <script>
        const container = document.querySelector('.audit-archived-audits-wrapper');
        if (container) {
            const fade_left = document.querySelector('.audit-archived-audits-wrapper-left');
            const fade_right = document.querySelector('.audit-archived-audits-wrapper-right');

            const update_fades = () => {
                fade_left.style.opacity = container.scrollLeft ? 1 : 0;
                fade_right.style.opacity = (container.scrollLeft + container.clientWidth + 1 >= container.scrollWidth) ? 0 : 1;
            };

            container.addEventListener('scroll', update_fades);
            window.addEventListener('resize', update_fades);
            update_fades();

            document.querySelector('#current_audit').scrollIntoView({behavior: 'smooth', block: 'center', inline: 'start'});
        }
    </script>
    <?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>

    <?php
    $audit_score_circle_attributes = [
        'progress' => $data->archived_audit->score,
        'size' => 125,
        'circleColor' => 'var(--gray-200)',
        'progressColor' => 'var(--' . $audit_score_class_name . ')',
        'circleWidth' => '12px',
        'progressWidth' => '12px',
        'progressShape' => 'round',
        'textColor' => 'var(--' . $audit_score_class_name . ')',
        'textSize' => [
            'fontSize' => 30
        ],
        'valueToggle' => true,
        'percentageToggle' => false
    ];
    ?>

    <?php
    $major_issues_percentage = number_format($data->archived_audit->major_issues * 100 / $data->archived_audit->total_tests, '2', '.', '');
    $moderate_issues_percentage = number_format($data->archived_audit->moderate_issues * 100 / $data->archived_audit->total_tests, '2', '.', '');
    $minor_issues_percentage = number_format($data->archived_audit->minor_issues * 100 / $data->archived_audit->total_tests, '2', '.', '');
    $passed_tests = $data->archived_audit->total_tests - $data->archived_audit->major_issues - $data->archived_audit->moderate_issues - $data->archived_audit->minor_issues;
    $passed_tests_percentage = number_format($passed_tests * 100 / $data->archived_audit->total_tests, '2', '.', '');
    ?>

    <div class="card mb-4">
        <div class="d-flex flex-column flex-md-row">
            <div class="d-flex align-items-center justify-content-center">
                <div class="audit-score-circle">
                    <?= get_audit_score_circle($audit_score_circle_attributes) ?>
                </div>
            </div>

            <div class="card-body text-truncate d-flex justify-content-between align-items-center">
                <div class="text-truncate">
                    <div class="d-flex align-items-center">
                        <img referrerpolicy="no-referrer" src="<?= get_favicon_url_from_domain($data->archived_audit->host) ?>" class="img-fluid icon-favicon-small mr-1" loading="lazy" />

                        <a href="<?= url('website/' . $data->archived_audit->website_id) ?>" class="small text-muted text-truncate" data-toggle="tooltip" title="<?= $data->archived_audit->host ?>">
                            <?= string_truncate($data->archived_audit->host, 32) ?>
                        </a>
                    </div>

                    <h1 class="h4 text-truncate mb-2">
                        <span title="<?= $data->archived_audit->url ?>"><?= sprintf(l('audit.header'), remove_url_protocol_from_url($data->archived_audit->url)) ?></span>

                        <a href="<?= $data->archived_audit->url ?>" class="small" target="_blank" rel="noreferrer">
                            <i class="fas fa-fw fa-xs fa-external-link-alt text-muted ml-1"></i>
                        </a>
                    </h1>

                    <p class="small text-muted white-space-normal m-0">
                        <?= sprintf(l('audits.dynamic_description'), $data->archived_audit->score, $data->archived_audit->total_issues, $passed_tests, $data->archived_audit->total_tests) ?>
                    </p>
                </div>

                <div class="d-flex">
                    <button type="button" class="btn btn-link text-secondary <?= $this->user->plan_settings->export->pdf ? null : 'disabled' ?>" onclick="window.print()" data-toggle="tooltip" title="<?= sprintf(l('global.export_to'), 'PDF') ?>" data-tooltip-hide-on-click>
                        <i class="fas fa-fw fa-file-pdf"></i>
                    </button>

                    <?php if((is_logged_in() && $this->user->user_id == $data->audit->user_id) || (md5(get_ip()) == $data->audit->uploader_id)): ?>
                    <?= include_view(THEME_PATH . 'views/audits/audit_dropdown_button.php', ['id' => $data->archived_audit->audit_id, 'resource_name' => remove_url_protocol_from_url($data->archived_audit->url), 'url' => $data->archived_audit->url]) ?>
                    <?php endif ?>
                </div>
            </div>
        </div>

        <div class="card-footer bg-white">
            <div class="row">
                <div class="col-6 col-md-3 col-lg-6 col-xl-3 mb-2 mb-md-0 mb-lg-2 mb-xl-0">
                    <div data-html="true" data-toggle="tooltip" title="<?= l('audits.ttfb') . ' (' . l('audits.ttfb_help') . ')<br /><small>' . l('audits.ttfb_help2') . '</small>' ?>">
                    <span class="badge badge-light text-body mr-1">
                        <i class="fas fa-fw fa-sm fa-server"></i>
                    </span>

                        <span class="small font-weight-bold"><?= display_response_time($data->archived_audit->ttfb) ?></span>
                    </div>
                </div>

                <div class="col-6 col-md-3 col-lg-6 col-xl-3 mb-2 mb-md-0 mb-lg-2 mb-xl-0">
                    <div data-html="true" data-toggle="tooltip" title="<?= l('audits.response_time') . '<br /><small>' . l('audits.response_time_help') . '</small>' ?>">
                        <span class="badge badge-light text-body mr-1">
                            <i class="fas fa-fw fa-sm fa-tachometer-alt"></i>
                        </span>

                        <span class="small font-weight-bold"><?= display_response_time($data->archived_audit->response_time) ?></span>
                    </div>
                </div>

                <div class="col-6 col-md-3 col-lg-6 col-xl-3">
                    <div data-html="true" data-toggle="tooltip" title="<?= l('audits.page_size') . '<br /><small>' . l('audits.page_size_help') . '</small>' ?>">
                        <span class="badge badge-light text-body mr-1">
                            <i class="fas fa-fw fa-sm fa-file"></i>
                        </span>

                        <span class="small font-weight-bold"><?= get_formatted_bytes($data->archived_audit->page_size) ?></span>
                    </div>
                </div>

                <div class="col-6 col-md-3 col-lg-6 col-xl-3">
                    <div data-html="true" data-toggle="tooltip" title="<?= l('audits.http_requests') . '<br /><small>' . l('audits.http_requests_help') . '</small>' ?>">
                        <span class="badge badge-light text-body mr-1">
                            <i class="fas fa-fw fa-sm fa-sitemap"></i>
                        </span>

                        <span class="small font-weight-bold"><?= sprintf(l('audits.http_requests_x'), nr($data->archived_audit->http_requests)) ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer bg-white">
            <?php
            $score_bar_tooltip = '<div class=\'text-left\'>';
            $score_bar_tooltip .= '<div><i class=\'fas fa-fw fa-sm fa-exclamation-circle text-danger mr-1\'></i> ' . sprintf(l('audits.major_issues_x'), nr($data->audit->major_issues)) . '</div>';
            $score_bar_tooltip .= '<div><i class=\'fas fa-fw fa-sm fa-exclamation-triangle text-warning mr-1\'></i> ' . sprintf(l('audits.moderate_issues_x'), nr($data->audit->moderate_issues)) . '</div>';
            $score_bar_tooltip .= '<div><i class=\'fas fa-fw fa-sm fa-circle text-muted mr-1\'></i> ' . sprintf(l('audits.minor_issues_x'), nr($data->audit->minor_issues)) . '</div>';
            $score_bar_tooltip .= '<div><i class=\'fas fa-fw fa-sm fa-check-circle text-success mr-1\'></i> ' . sprintf(l('audits.passed_tests_x'), nr($data->audit->passed_tests)) . '</div>';
            $score_bar_tooltip .= '</div>';
            ?>

            <div class="d-flex flex-column flex-md-row flex-lg-column flex-xl-row audit-checks-bar-wrapper" data-toggle="tooltip" data-html="true" title="<?= $score_bar_tooltip ?>">
                <?php if($data->archived_audit->major_issues): ?>
                    <div class="audit-checks-bar-item bg-danger my-1 my-md-0 my-lg-1 my-xl-0" style="width: <?= $major_issues_percentage ?>%;"></div>
                <?php endif ?>

                <?php if($data->archived_audit->moderate_issues): ?>
                    <div class="audit-checks-bar-item bg-warning my-1 my-md-0 my-lg-1 my-xl-0" style="width: <?= $moderate_issues_percentage ?>%;"></div>
                <?php endif ?>

                <?php if($data->archived_audit->minor_issues): ?>
                    <div class="audit-checks-bar-item bg-gray-600 my-1 my-md-0 my-lg-1 my-xl-0" style="width: <?= $minor_issues_percentage ?>%;"></div>
                <?php endif ?>

                <?php if($data->archived_audit->passed_tests): ?>
                    <div class="audit-checks-bar-item bg-success my-1 my-md-0 my-lg-1 my-xl-0" style="width: <?= $passed_tests_percentage ?>%;"></div>
                <?php endif ?>
            </div>
        </div>
    </div>

    <div class="card mb-2">
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-auto mb-3 mb-md-0 d-flex justify-content-center">
                    <img src="<?= $data->archived_audit->data->opengraph->{'og:image'} ?? ASSETS_FULL_URL . 'images/audit/opengraph-not-found.svg' ?>" class="audit-opengraph-image img-fluid rounded" data-toggle="tooltip" title="<?= l('audits.opengraph_image') ?>" loading="lazy" />
                </div>

                <div class="col text-center text-md-left">
                    <p class="h6"><?= e($data->archived_audit->data->title) ?></p>

                    <p class="small m-0 text-muted"><?= $data->archived_audit->data->meta_description ? e($data->archived_audit->data->meta_description) : l('audits.meta_description_missing') ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-md-4 p-3 text-truncate">
            <div class="card d-flex flex-row h-100 overflow-hidden" data-toggle="tooltip" data-html="true" title="<?= l('audits.last_refresh_datetime') . ($data->audit->last_refresh_datetime ? '<br />' . \Altum\Date::get($data->audit->last_refresh_datetime, 2) . '<br /><small>' . \Altum\Date::get($data->audit->last_refresh_datetime, 3) . '</small>' . '<br /><small>(' . \Altum\Date::get_timeago($data->audit->last_refresh_datetime) . ')</small>' : '<br />-') ?>">
                <div class="px-3 d-flex flex-column justify-content-center">
                    <div class="p-2 rounded-2x card-widget-icon d-flex align-items-center justify-content-center bg-gray-50">
                        <i class="fas fa-fw fa-sm fa-calendar-check text-muted"></i>
                    </div>
                </div>

                <div class="card-body text-truncate">
                    <?= $data->audit->last_refresh_datetime ? \Altum\Date::get_timeago($data->audit->last_refresh_datetime) : '-' ?>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4 p-3 text-truncate">
            <div class="card d-flex flex-row h-100 overflow-hidden" data-toggle="tooltip" data-html="true" title="<?= l('audits.next_refresh_datetime') . ($data->audit->next_refresh_datetime ? '<br />' . \Altum\Date::get($data->audit->next_refresh_datetime, 2) . '<br /><small>' . \Altum\Date::get($data->audit->next_refresh_datetime, 3) . '</small>' . '<br /><small>(' . \Altum\Date::get_timeago($data->audit->next_refresh_datetime) . ')</small>' : '<br />') ?>">
                <div class="px-3 d-flex flex-column justify-content-center">
                    <div class="p-2 rounded-2x card-widget-icon d-flex align-items-center justify-content-center bg-gray-50">
                        <i class="fas fa-fw fa-sm fa-retweet text-muted"></i>
                    </div>
                </div>

                <div class="card-body text-truncate">
                    <?= $data->audit->next_refresh_datetime ? \Altum\Date::get_timeago($data->audit->next_refresh_datetime) : l('global.none') ?>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4 p-3 text-truncate">
            <div class="card d-flex flex-row h-100 overflow-hidden" data-toggle="tooltip" data-html="true" title="<?= sprintf(l('global.datetime_tooltip'), '<br />' . \Altum\Date::get($data->archived_audit->datetime, 2) . '<br /><small>' . \Altum\Date::get($data->archived_audit->datetime, 3) . '</small>' . '<br /><small>(' . \Altum\Date::get_timeago($data->archived_audit->datetime) . ')</small>') ?>">
                <div class="px-3 d-flex flex-column justify-content-center">
                    <div class="p-2 rounded-2x card-widget-icon d-flex align-items-center justify-content-center bg-gray-50">
                        <i class="fas fa-fw fa-sm fa-calendar text-muted"></i>
                    </div>
                </div>

                <div class="card-body text-truncate">
                    <?= $data->archived_audit->datetime ? \Altum\Date::get_timeago($data->archived_audit->datetime) : '-' ?>
                </div>
            </div>
        </div>
    </div>


    <div class="card mt-4 mb-5">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between">
                <span class="small font-weight-bold"><?= l('audits.basic') ?></span>

                <span class="badge bg-primary-50 text-primary-700">
                    <i class="fas fa-fw fa-sm fa-file-code"></i>
                </span>
            </div>
        </div>

        <div class="card-body">

            <div id="doctype" class="row">
                <div class="col-12 col-xl-4 mb-2 mb-xl-0">
                    <div>
                        <?= get_audit_test_icon('doctype', $data->archived_audit->issues) ?>
                        <span class="font-weight-bold"><?= l('audits.test.doctype') ?></span>
                    </div>
                </div>

                <div class="col-12 col-xl-8">
                    <?php if(!$this->user->plan_settings->audits_enabled_tests->doctype): ?>
                        <p class="small m-0">
                            <i class="fas fa-fw fa-question-circle mr-1"></i> <?=  l('global.info_message.plan_feature_no_access') ?>
                        </p>
                    <?php else: ?>
                        <div class="mb-3">
                            <p class="small m-0">
                                <?= $data->archived_audit->data->doctype ? '<code>' . e($data->archived_audit->data->doctype) . '</code>' : l('global.none') ?>
                            </p>
                            <small class="text-muted"><?= l('audits.test.doctype_help') ?></small>
                        </div>

                        <?php if(in_array('missing', $data->archived_audit->issues->moderate->doctype ?? [])): ?>
                            <div class="mb-3 audit-issue-wrapper audit-issue-moderate">
                                <p class="m-0 font-size-small"><?= l('audits.test.doctype.missing') ?></p>
                                <small class="text-muted"><?= l('audits.test.doctype.missing_help') ?></small>
                            </div>
                        <?php endif ?>
                    <?php endif ?>
                </div>
            </div>

            <div class="flex-fill my-4">
                <hr class="border-gray-100" />
            </div>

            <div id="language" class="row">
                <div class="col-12 col-xl-4 mb-2 mb-xl-0">
                    <div>
                        <?= get_audit_test_icon('language', $data->archived_audit->issues) ?>
                        <span class="font-weight-bold"><?= l('audits.test.language') ?></span>
                    </div>
                </div>

                <div class="col-12 col-xl-8">
                    <?php if(!$this->user->plan_settings->audits_enabled_tests->language): ?>
                        <p class="small m-0">
                            <i class="fas fa-fw fa-question-circle mr-1"></i> <?=  l('global.info_message.plan_feature_no_access') ?>
                        </p>
                    <?php else: ?>
                        <?php if(!empty($data->archived_audit->data->language)): ?>
                            <div class="mb-3">
                                <p class="small font-weight-bold m-0"><?= e($data->archived_audit->data->language) ?></p>
                                <small class="text-muted"><?= l('audits.test.language_help') ?></small>
                            </div>
                        <?php endif ?>

                        <?php if(in_array('missing', $data->archived_audit->issues->minor->language ?? [])): ?>
                            <div class="mb-3 audit-issue-wrapper audit-issue-minor">
                                <p class="m-0 font-size-small"><?= l('audits.test.language.missing') ?></p>
                                <small class="text-muted"><?= l('audits.test.language.missing_help') ?></small>
                            </div>
                        <?php endif ?>
                    <?php endif ?>
                </div>
            </div>

            <div class="flex-fill my-4">
                <hr class="border-gray-100" />
            </div>

            <div id="meta_charset" class="row">
                <div class="col-12 col-xl-4 mb-2 mb-xl-0">
                    <div>
                        <?= get_audit_test_icon('meta_charset', $data->archived_audit->issues) ?>
                        <span class="font-weight-bold"><?= l('audits.test.meta_charset') ?></span>
                    </div>
                </div>

                <div class="col-12 col-xl-8">
                    <?php if(!$this->user->plan_settings->audits_enabled_tests->meta_charset): ?>
                        <p class="small m-0">
                            <i class="fas fa-fw fa-question-circle mr-1"></i> <?=  l('global.info_message.plan_feature_no_access') ?>
                        </p>
                    <?php else: ?>
                        <div class="mb-3">
                            <?php if($data->archived_audit->data->meta_charset): ?>
                                <p class="small m-0">
                                    <code><?= $data->archived_audit->data->meta_charset ?></code>
                                </p>
                            <?php endif ?>
                            <small class="text-muted"><?= l('audits.test.meta_charset_help') ?></small>
                        </div>

                        <?php if(in_array('missing', $data->archived_audit->issues->moderate->meta_charset ?? [])): ?>
                            <div class="mb-3 audit-issue-wrapper audit-issue-moderate">
                                <p class="m-0 font-size-small"><?= l('audits.test.meta_charset.missing') ?></p>
                                <small class="text-muted"><?= l('audits.test.meta_charset.missing_help') ?></small>
                            </div>
                        <?php endif ?>
                    <?php endif ?>
                </div>
            </div>

            <div class="flex-fill my-4">
                <hr class="border-gray-100" />
            </div>

            <div id="meta_viewport" class="row">
                <div class="col-12 col-xl-4 mb-2 mb-xl-0">
                    <div>
                        <?= get_audit_test_icon('meta_viewport', $data->archived_audit->issues) ?>
                        <span class="font-weight-bold"><?= l('audits.test.meta_viewport') ?></span>
                    </div>
                </div>

                <div class="col-12 col-xl-8">
                    <?php if(!$this->user->plan_settings->audits_enabled_tests->meta_viewport): ?>
                        <p class="small m-0">
                            <i class="fas fa-fw fa-question-circle mr-1"></i> <?=  l('global.info_message.plan_feature_no_access') ?>
                        </p>
                    <?php else: ?>
                        <div class="mb-3">
                            <p class="small m-0">
                                <?php if($data->archived_audit->data->meta_viewport): ?>
                                    <code><?= $data->archived_audit->data->meta_viewport ?></code>
                                <?php endif ?>
                            </p>
                            <small class="text-muted"><?= l('audits.test.meta_viewport_help') ?></small>
                        </div>

                        <?php if(in_array('missing', $data->archived_audit->issues->moderate->meta_viewport ?? [])): ?>
                            <div class="mb-3 audit-issue-wrapper audit-issue-moderate">
                                <p class="m-0 font-size-small"><?= l('audits.test.meta_viewport.missing') ?></p>
                                <small class="text-muted"><?= l('audits.test.meta_viewport.missing_help') ?></small>
                            </div>
                        <?php endif ?>
                    <?php endif ?>
                </div>
            </div>

            <div class="flex-fill my-4">
                <hr class="border-gray-100" />
            </div>

            <div id="favicon" class="row">
                <div class="col-12 col-xl-4 mb-2 mb-xl-0">
                    <div>
                        <?= get_audit_test_icon('favicon', $data->archived_audit->issues) ?>
                        <span class="font-weight-bold"><?= l('audits.test.favicon') ?></span>
                    </div>
                </div>

                <div class="col-12 col-xl-8">
                    <?php if(!$this->user->plan_settings->audits_enabled_tests->favicon): ?>
                        <p class="small m-0">
                            <i class="fas fa-fw fa-question-circle mr-1"></i> <?=  l('global.info_message.plan_feature_no_access') ?>
                        </p>
                    <?php else: ?>
                        <?php if(!empty($data->archived_audit->data->favicon)): ?>
                            <div class="mb-3">
                                <p class="small m-0">
                                    <img referrerpolicy="no-referrer" src="<?= $data->archived_audit->data->favicon ?>" class="img-fluid icon-favicon-small mr-1" loading="lazy" />
                                    <a href="<?= $data->archived_audit->data->favicon ?>" target="_blank" rel="nofollow noreferrer"><?= $data->archived_audit->data->favicon ?></a>
                                </p>
                                <small class="text-muted"><?= l('audits.test.favicon_help') ?></small>
                            </div>
                        <?php endif ?>

                        <?php if(in_array('missing', $data->archived_audit->issues->moderate->favicon ?? [])): ?>
                            <div class="mb-3 audit-issue-wrapper audit-issue-moderate">
                                <p class="m-0 font-size-small"><?= l('audits.test.favicon.missing') ?></p>
                                <small class="text-muted"><?= l('audits.test.favicon.missing_help') ?></small>
                            </div>
                        <?php endif ?>
                    <?php endif ?>
                </div>
            </div>

        </div>
    </div>

    <div class="card mb-5">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between">
                <span class="small font-weight-bold"><?= l('audits.seo') ?></span>

                <span class="badge bg-primary-50 text-primary-700">
                    <i class="fas fa-fw fa-sm fa-search-plus"></i>
                </span>
            </div>
        </div>

        <div class="card-body">

            <div id="title" class="row">
                <div class="col-12 col-xl-4 mb-2 mb-xl-0">
                    <div>
                        <?= get_audit_test_icon('title', $data->archived_audit->issues) ?>
                        <span class="font-weight-bold"><?= l('audits.test.title') ?></span>
                    </div>
                </div>

                <div class="col-12 col-xl-8">
                    <?php if(!$this->user->plan_settings->audits_enabled_tests->title): ?>
                        <p class="small m-0">
                            <i class="fas fa-fw fa-question-circle mr-1"></i> <?=  l('global.info_message.plan_feature_no_access') ?>
                        </p>
                    <?php else: ?>
                        <?php if(!empty($data->archived_audit->data->title)): ?>
                            <div class="mb-3">
                                <p class="small font-weight-bold m-0"><?= e($data->archived_audit->data->title) ?></p>
                                <small class="text-muted"><?= sprintf(l('audits.characters'), mb_strlen($data->archived_audit->data->title ?? '')) ?></small>
                            </div>
                        <?php endif ?>

                        <?php if(in_array('missing', $data->archived_audit->issues->major->title ?? [])): ?>
                            <div class="mb-3 audit-issue-wrapper audit-issue-major">
                                <p class="m-0 font-size-small"><?= l('audits.test.title.missing') ?></p>
                                <small class="text-muted"><?= l('audits.test.title.missing_help') ?></small>
                            </div>
                        <?php endif ?>

                        <?php if(in_array('too_long', $data->archived_audit->issues->major->title ?? [])): ?>
                            <div class="mb-3 audit-issue-wrapper audit-issue-major">
                                <p class="m-0 font-size-small"><?= l('audits.test.title.too_long') ?></p>
                                <small class="text-muted"><?= l('audits.test.title.too_long_help') ?></small>
                            </div>
                        <?php endif ?>

                        <?php if(in_array('too_short', $data->archived_audit->issues->major->title ?? [])): ?>
                            <div class="mb-3 audit-issue-wrapper audit-issue-major">
                                <p class="m-0 font-size-small"><?= l('audits.test.title.too_short') ?></p>
                                <small class="text-muted"><?= l('audits.test.title.too_short_help') ?></small>
                            </div>
                        <?php endif ?>
                    <?php endif ?>
                </div>
            </div>

            <div class="flex-fill my-4">
                <hr class="border-gray-100" />
            </div>

            <div id="meta_description" class="row">
                <div class="col-12 col-xl-4 mb-2 mb-xl-0">
                    <div>
                        <?= get_audit_test_icon('meta_description', $data->archived_audit->issues) ?>
                        <span class="font-weight-bold"><?= l('audits.test.meta_description') ?></span>
                    </div>
                </div>

                <div class="col-12 col-xl-8">
                    <?php if(!$this->user->plan_settings->audits_enabled_tests->meta_description): ?>
                        <p class="small m-0">
                            <i class="fas fa-fw fa-question-circle mr-1"></i> <?=  l('global.info_message.plan_feature_no_access') ?>
                        </p>
                    <?php else: ?>
                        <?php if(!empty($data->archived_audit->data->meta_description)): ?>
                            <div class="mb-3">
                                <p class="small m-0"><?= e($data->archived_audit->data->meta_description) ?></p>
                                <small class="text-muted"><?= sprintf(l('audits.characters'), mb_strlen($data->archived_audit->data->meta_description)) ?></small>
                            </div>
                        <?php endif ?>

                        <?php if(in_array('missing', $data->archived_audit->issues->major->meta_description ?? [])): ?>
                            <div class="mb-3 audit-issue-wrapper audit-issue-major">
                                <p class="m-0 font-size-small"><?= l('audits.test.meta_description.missing') ?></p>
                                <small class="text-muted"><?= l('audits.test.meta_description.missing_help') ?></small>
                            </div>
                        <?php endif ?>

                        <?php if(in_array('too_long', $data->archived_audit->issues->moderate->meta_description ?? [])): ?>
                            <div class="mb-3 audit-issue-wrapper audit-issue-moderate">
                                <p class="m-0 font-size-small"><?= l('audits.test.meta_description.too_long') ?></p>
                                <small class="text-muted"><?= l('audits.test.meta_description.too_long_help') ?></small>
                            </div>
                        <?php endif ?>

                        <?php if(in_array('too_short', $data->archived_audit->issues->moderate->meta_description  ?? [])): ?>
                            <div class="mb-3 audit-issue-wrapper audit-issue-moderate">
                                <p class="m-0 font-size-small"><?= l('audits.test.meta_description.too_short') ?></p>
                                <small class="text-muted"><?= l('audits.test.meta_description.too_short_help') ?></small>
                            </div>
                        <?php endif ?>
                    <?php endif ?>
                </div>
            </div>

            <div class="flex-fill my-4">
                <hr class="border-gray-100" />
            </div>

            <div id="h1" class="row">
                <div class="col-12 col-xl-4 mb-2 mb-xl-0">
                    <div>
                        <?= get_audit_test_icon('h1', $data->archived_audit->issues) ?>
                        <span class="font-weight-bold"><?= l('audits.test.h1') ?></span>
                    </div>
                </div>

                <div class="col-12 col-xl-8">
                    <?php if(!$this->user->plan_settings->audits_enabled_tests->h1): ?>
                        <p class="small m-0">
                            <i class="fas fa-fw fa-question-circle mr-1"></i> <?=  l('global.info_message.plan_feature_no_access') ?>
                        </p>
                    <?php else: ?>
                        <?php if(count($data->archived_audit->data->headings->h1) > 1): ?>
                            <ol class="mb-0 pl-3 audit-ol">
                                <?php foreach($data->archived_audit->data->headings->h1 ?? [] as $h1): ?>
                                    <li class="mb-3">
                                        <p class="small font-weight-bold m-0"><?= e($h1) ?></p>
                                        <small class="text-muted"><?= sprintf(l('audits.characters'), mb_strlen($h1)) ?></small>
                                    </li>
                                <?php endforeach ?>
                            </ol>
                        <?php else: ?>
                            <?php foreach($data->archived_audit->data->headings->h1 ?? [] as $h1): ?>
                                <div class="mb-3">
                                    <p class="small font-weight-bold m-0"><?= e($h1) ?></p>
                                    <small class="text-muted"><?= sprintf(l('audits.characters'), mb_strlen($h1)) ?></small>
                                </div>
                            <?php endforeach ?>
                        <?php endif ?>

                        <?php if(in_array('missing', $data->archived_audit->issues->major->h1 ?? [])): ?>
                            <div class="mb-3 audit-issue-wrapper audit-issue-major">
                                <p class="m-0 font-size-small"><?= l('audits.test.h1.missing') ?></p>
                                <small class="text-muted"><?= l('audits.test.h1.missing_help') ?></small>
                            </div>
                        <?php else: ?>

                            <?php if(in_array('too_many', $data->archived_audit->issues->moderate->h1 ?? [])): ?>
                                <div class="mb-3 audit-issue-wrapper audit-issue-moderate">
                                    <p class="m-0 font-size-small"><?= l('audits.test.h1.too_many') ?></p>
                                    <small class="text-muted"><?= l('audits.test.h1.too_many_help') ?></small>
                                </div>
                            <?php endif ?>

                            <?php if(in_array('too_long', $data->archived_audit->issues->minor->h1 ?? [])): ?>
                                <div class="mb-3 audit-issue-wrapper audit-issue-minor">
                                    <p class="m-0 font-size-small"><?= l('audits.test.h1.too_long') ?></p>
                                    <small class="text-muted"><?= l('audits.test.h1.too_long_help') ?></small>
                                </div>
                            <?php endif ?>

                            <?php if(in_array('too_short', $data->archived_audit->issues->minor->h1  ?? [])): ?>
                                <div class="mb-3 audit-issue-wrapper audit-issue-minor">
                                    <p class="m-0 font-size-small"><?= l('audits.test.h1.too_short') ?></p>
                                    <small class="text-muted"><?= l('audits.test.h1.too_short_help') ?></small>
                                </div>
                            <?php endif ?>

                        <?php endif ?>
                    <?php endif ?>
                </div>
            </div>

            <div class="flex-fill my-4">
                <hr class="border-gray-100" />
            </div>

            <div id="meta_robots" class="row">
                <div class="col-12 col-xl-4 mb-2 mb-xl-0">
                    <div>
                        <?= get_audit_test_icon('meta_robots', $data->archived_audit->issues) ?>
                        <span class="font-weight-bold"><?= l('audits.test.meta_robots') ?></span>
                    </div>
                </div>

                <div class="col-12 col-xl-8">
                    <?php if(!$this->user->plan_settings->audits_enabled_tests->meta_robots): ?>
                        <p class="small m-0">
                            <i class="fas fa-fw fa-question-circle mr-1"></i> <?=  l('global.info_message.plan_feature_no_access') ?>
                        </p>
                    <?php else: ?>
                        <div class="mb-3">
                            <?php if(count($data->archived_audit->data->meta_robots)): ?>
                                <p class="small font-weight-bold m-0"><?= e(implode(', ', $data->archived_audit->data->meta_robots)) ?></p>
                            <?php else: ?>
                                <p class="small font-weight-bold m-0"><?= l('audits.test.meta_robots_missing') ?></p>
                            <?php endif ?>

                            <small class="text-muted"><?= l('audits.test.meta_robots_help') ?></small>
                        </div>

                        <?php if(in_array('excluded', $data->archived_audit->issues->major->meta_robots ?? [])): ?>
                            <div class="mb-3 audit-issue-wrapper audit-issue-major">
                                <p class="m-0 font-size-small"><?= l('audits.test.meta_robots.excluded') ?></p>
                                <small class="text-muted"><?= l('audits.test.meta_robots.excluded_help') ?></small>
                            </div>
                        <?php endif ?>
                    <?php endif ?>
                </div>
            </div>

            <div class="flex-fill my-4">
                <hr class="border-gray-100" />
            </div>

            <div id="header_robots" class="row">
                <div class="col-12 col-xl-4 mb-2 mb-xl-0">
                    <div>
                        <?= get_audit_test_icon('header_robots', $data->archived_audit->issues) ?>
                        <span class="font-weight-bold"><?= l('audits.test.header_robots') ?></span>
                    </div>
                </div>

                <div class="col-12 col-xl-8">
                    <?php if(!$this->user->plan_settings->audits_enabled_tests->header_robots): ?>
                        <p class="small m-0">
                            <i class="fas fa-fw fa-question-circle mr-1"></i> <?=  l('global.info_message.plan_feature_no_access') ?>
                        </p>
                    <?php else: ?>
                        <div class="mb-3">
                            <p class="small font-weight-bold m-0"><?= $data->archived_audit->data->response_headers->x_robots_tag ? e($data->archived_audit->data->response_headers->x_robots_tag) : l('audits.test.header_robots_missing') ?></p>

                            <small class="text-muted"><?= l('audits.test.header_robots_help') ?></small>
                        </div>

                        <?php if(in_array('excluded', $data->archived_audit->issues->major->header_robots ?? [])): ?>
                            <div class="mb-3 audit-issue-wrapper audit-issue-major">
                                <p class="m-0 font-size-small"><?= l('audits.test.header_robots.excluded') ?></p>
                                <small class="text-muted"><?= l('audits.test.header_robots.excluded_help') ?></small>
                            </div>
                        <?php endif ?>
                    <?php endif ?>
                </div>
            </div>


            <div class="flex-fill my-4">
                <hr class="border-gray-100" />
            </div>

            <div id="canonical" class="row">
                <div class="col-12 col-xl-4 mb-2 mb-xl-0">
                    <div>
                        <?= get_audit_test_icon('canonical', $data->archived_audit->issues) ?>
                        <span class="font-weight-bold"><?= l('audits.test.canonical') ?></span>
                    </div>
                </div>

                <div class="col-12 col-xl-8">
                    <?php if(!$this->user->plan_settings->audits_enabled_tests->canonical): ?>
                        <p class="small m-0">
                            <i class="fas fa-fw fa-question-circle mr-1"></i> <?=  l('global.info_message.plan_feature_no_access') ?>
                        </p>
                    <?php else: ?>
                        <div class="mb-3">
                            <p class="small m-0"><strong><?= $data->archived_audit->data->canonical ? e($data->archived_audit->data->canonical) : l('global.none') ?></strong></p>
                            <small class="text-muted"><?= l('audits.test.canonical_help') ?></small>
                        </div>

                        <?php if(in_array('missing', $data->archived_audit->issues->minor->canonical ?? [])): ?>
                            <div class="mb-3 audit-issue-wrapper audit-issue-minor">
                                <p class="m-0 font-size-small"><?= l('audits.test.canonical.missing') ?></p>
                                <small class="text-muted"><?= l('audits.test.canonical.missing_help') ?></small>
                                <small class="text-muted"><?= sprintf(l('audits.test.canonical.missing_help2'), '<br /><code>&lt;link rel="canonical" href="' . $data->archived_audit->url . '" /&gt;</code>') ?></small>
                            </div>
                        <?php endif ?>
                    <?php endif ?>
                </div>
            </div>

            <div class="flex-fill my-4">
                <hr class="border-gray-100" />
            </div>

            <div id="is_seo_friendly_url" class="row">
                <div class="col-12 col-xl-4 mb-2 mb-xl-0">
                    <div>
                        <?= get_audit_test_icon('is_seo_friendly_url', $data->archived_audit->issues) ?>
                        <span class="font-weight-bold"><?= l('audits.test.is_seo_friendly_url') ?></span>
                    </div>
                </div>

                <div class="col-12 col-xl-8">
                    <?php if(!$this->user->plan_settings->audits_enabled_tests->is_seo_friendly_url): ?>
                        <p class="small m-0">
                            <i class="fas fa-fw fa-question-circle mr-1"></i> <?=  l('global.info_message.plan_feature_no_access') ?>
                        </p>
                    <?php else: ?>
                        <div class="mb-3">
                            <p class="small m-0"><strong><?= $data->archived_audit->data->url ?></strong></p>
                            <small class="text-muted"><?= l('audits.test.is_seo_friendly_url_help') ?></small>
                        </div>

                        <?php if(in_array('false', $data->archived_audit->issues->minor->is_seo_friendly_url ?? [])): ?>
                            <div class="mb-3 audit-issue-wrapper audit-issue-minor">
                                <p class="m-0 font-size-small"><?= l('audits.test.is_seo_friendly_url.false') ?></p>
                                <small class="text-muted"><?= l('audits.test.is_seo_friendly_url.false_help') ?></small>
                            </div>
                        <?php endif ?>
                    <?php endif ?>
                </div>
            </div>

            <div class="flex-fill my-4">
                <hr class="border-gray-100" />
            </div>

            <div id="opengraph" class="row">
                <div class="col-12 col-xl-4 mb-2 mb-xl-0">
                    <div>
                        <?= get_audit_test_icon('opengraph', $data->archived_audit->issues) ?>
                        <span class="font-weight-bold"><?= l('audits.test.opengraph') ?></span>
                    </div>
                </div>

                <div class="col-12 col-xl-8">
                    <?php if(!$this->user->plan_settings->audits_enabled_tests->opengraph): ?>
                        <p class="small m-0">
                            <i class="fas fa-fw fa-question-circle mr-1"></i> <?=  l('global.info_message.plan_feature_no_access') ?>
                        </p>
                    <?php else: ?>
                        <div class="mb-3">
                            <button class="btn btn-sm btn-block btn-gray-200 mb-2" type="button" data-toggle="collapse" data-target="#opengraph_container" aria-expanded="false" aria-controls="opengraph_container" <?= count((array) $data->archived_audit->data->opengraph) ? null : 'disabled="disabled"' ?>>
                                <?= sprintf(l('audits.test.opengraph_count'), count((array) $data->archived_audit->data->opengraph)) ?>
                            </button>

                            <div class="collapse" id="opengraph_container">
                                <div class="card card-body">
                                    <ol class="mb-0 pl-3 audit-ol">
                                        <?php foreach ($data->archived_audit->data->opengraph as $key => $value): ?>
                                            <li class="text-truncate mb-3">
                                                <p class="m-0 font-size-small font-weight-bold">
                                                    <?= e($key) ?>
                                                </p>

                                                <small class="text-muted">
                                                    <?php if($key == 'og:image'): ?>
                                                        <img referrerpolicy="no-referrer" src="<?= get_favicon_url_from_domain(parse_url($value, PHP_URL_HOST)) ?>" class="img-fluid icon-favicon-small mr-1" loading="lazy" />
                                                        <a href="<?= e($value) ?>" target="_blank" rel="nofollow noreferrer" class="text-truncate"><?= e($value) ?></a>
                                                    <?php elseif($key == 'og:url'): ?>
                                                        <a href="<?= e($value) ?>" target="_blank" rel="nofollow noreferrer" class="text-truncate"><?= e($value) ?></a>
                                                    <?php else: ?>
                                                        <?= e($value) ?>
                                                    <?php endif ?>
                                                </small>
                                            </li>
                                        <?php endforeach ?>
                                    </ol>
                                </div>
                            </div>
                            <small class="text-muted"><?= l('audits.test.opengraph_help') ?></small>
                        </div>

                        <?php if(in_array('missing', $data->archived_audit->issues->minor->opengraph ?? [])): ?>
                            <div class="mb-3 audit-issue-wrapper audit-issue-minor">
                                <p class="m-0 font-size-small"><?= l('audits.test.opengraph.missing') ?></p>
                                <small class="text-muted"><?= l('audits.test.opengraph.missing_help') ?></small>
                            </div>
                        <?php endif ?>
                    <?php endif ?>
                </div>
            </div>

            <div class="flex-fill my-4">
                <hr class="border-gray-100" />
            </div>

            <div id="other_headings" class="row">
                <div class="col-12 col-xl-4 mb-2 mb-xl-0">
                    <div>
                        <span data-toggle="tooltip" title="<?= l('audits.informational_test') ?>"><i class="fas fa-fw fa-sm fa-info-circle text-info mr-1"></i></span>
                        <span class="font-weight-bold"><?= l('audits.test.other_headings') ?></span>
                    </div>
                </div>

                <div class="col-12 col-xl-8">
                    <?php if(!$this->user->plan_settings->audits_enabled_tests->other_headings): ?>
                        <p class="small m-0">
                            <i class="fas fa-fw fa-question-circle mr-1"></i> <?=  l('global.info_message.plan_feature_no_access') ?>
                        </p>
                    <?php else: ?>
                        <div class="mb-3">
                            <?php foreach(['h2', 'h3', 'h4', 'h5', 'h6'] as $heading_type): ?>
                                <button class="btn btn-sm btn-block btn-gray-200 mb-2" type="button" data-toggle="collapse" data-target="#other_headings_container_<?= $heading_type ?>" aria-expanded="false" aria-controls="other_headings_container_<?= $heading_type ?>" <?= count($data->archived_audit->data->headings->{$heading_type}) ? null : 'disabled="disabled"' ?>>
                                    <?= sprintf(l('audits.test.other_headings_count'), '<span class="badge badge-light">' . count($data->archived_audit->data->headings->{$heading_type}) . '</span>', $heading_type) ?>
                                </button>

                                <div class="collapse" id="other_headings_container_<?= $heading_type ?>">
                                    <div class="card card-body">
                                        <ol class="mb-0 pl-3 audit-ol">
                                            <?php foreach ($data->archived_audit->data->headings->{$heading_type} as $heading_text): ?>
                                                <li class="font-size-small mb-2">
                                                    <?= e($heading_text) ?>
                                                </li>
                                            <?php endforeach ?>
                                        </ol>
                                    </div>
                                </div>
                            <?php endforeach ?>

                            <small class="text-muted"><?= l('audits.test.other_headings_help') ?></small>
                        </div>
                    <?php endif ?>
                </div>
            </div>

            <div class="flex-fill my-4">
                <hr class="border-gray-100" />
            </div>

            <div id="meta_keywords" class="row">
                <div class="col-12 col-xl-4 mb-2 mb-xl-0">
                    <div>
                        <span data-toggle="tooltip" title="<?= l('audits.informational_test') ?>"><i class="fas fa-fw fa-sm fa-info-circle text-info mr-1"></i></span>
                        <span class="font-weight-bold"><?= l('audits.test.meta_keywords') ?></span>
                    </div>
                </div>

                <div class="col-12 col-xl-8">
                    <?php if(!$this->user->plan_settings->audits_enabled_tests->meta_keywords): ?>
                        <p class="small m-0">
                            <i class="fas fa-fw fa-question-circle mr-1"></i> <?=  l('global.info_message.plan_feature_no_access') ?>
                        </p>
                    <?php else: ?>
                        <?php $meta_keywords = explode(',', $data->archived_audit->data->meta_keywords ?? '') ?>

                        <?php if(!empty($data->archived_audit->data->meta_keywords)): ?>
                            <div class="mb-3">
                                <p class="m-0 font-size-small">
                                    <?php foreach ($meta_keywords as $keyword): ?>
                                        <code class="badge badge-light mr-2 mb-1"><?= $keyword ?></code>
                                    <?php endforeach ?>
                                </p>

                                <small class="text-muted"><?= sprintf(l('audits.characters'), mb_strlen($data->archived_audit->data->meta_keywords ?? '')) ?></small>
                                <small class="text-muted"><?= sprintf(l('audits.test.meta_keywords_count'), count($meta_keywords)) ?></small>
                            </div>
                        <?php else: ?>
                            <div class="mb-3 audit-issue-wrapper audit-issue-info">
                                <p class="m-0 font-size-small"><?= l('audits.test.meta_keywords.missing') ?></p>
                                <small class="text-muted"><?= l('audits.test.meta_keywords.missing_help') ?></small>
                            </div>
                        <?php endif ?>
                    <?php endif ?>
                </div>
            </div>

        </div>
    </div>

    <div class="card mb-5">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between">
                <span class="small font-weight-bold"><?= l('audits.content') ?></span>

                <span class="badge bg-primary-50 text-primary-700">
                    <i class="fas fa-fw fa-sm fa-paragraph"></i>
                </span>
            </div>
        </div>

        <div class="card-body">

            <div id="words_count" class="row">
                <div class="col-12 col-xl-4 mb-2 mb-xl-0">
                    <div>
                        <?= get_audit_test_icon('words_count', $data->archived_audit->issues) ?>
                        <span class="font-weight-bold"><?= l('audits.test.words_count') ?></span>
                    </div>
                </div>

                <div class="col-12 col-xl-8">
                    <?php if(!$this->user->plan_settings->audits_enabled_tests->words_count): ?>
                        <p class="small m-0">
                            <i class="fas fa-fw fa-question-circle mr-1"></i> <?=  l('global.info_message.plan_feature_no_access') ?>
                        </p>
                    <?php else: ?>
                        <div class="mb-3">
                            <p class="small m-0"><?= sprintf(l('audits.test.words_count_count'), '<strong>' . nr($data->archived_audit->data->words_count) . '</strong>') ?></p>
                            <small class="text-muted"><?= l('audits.test.words_count_help') ?></small>
                        </div>

                        <?php if(in_array('too_few', $data->archived_audit->issues->moderate->words_count ?? [])): ?>
                            <div class="mb-3 audit-issue-wrapper audit-issue-moderate">
                                <p class="m-0 font-size-small"><?= l('audits.test.words_count.too_few') ?></p>
                                <small class="text-muted"><?= l('audits.test.words_count.too_few_help') ?></small>
                            </div>
                        <?php endif ?>
                    <?php endif ?>
                </div>
            </div>

            <div class="flex-fill my-4">
                <hr class="border-gray-100" />
            </div>

            <div id="words_used" class="row">
                <div class="col-12 col-xl-4 mb-2 mb-xl-0">
                    <div>
                        <span data-toggle="tooltip" title="<?= l('audits.informational_test') ?>"><i class="fas fa-fw fa-sm fa-info-circle text-info mr-1"></i></span>
                        <span class="font-weight-bold"><?= l('audits.test.words_used') ?></span>
                    </div>
                </div>

                <div class="col-12 col-xl-8">
                    <?php if(!$this->user->plan_settings->audits_enabled_tests->words_used): ?>
                        <p class="small m-0">
                            <i class="fas fa-fw fa-question-circle mr-1"></i> <?=  l('global.info_message.plan_feature_no_access') ?>
                        </p>
                    <?php else: ?>
                        <?php if(!empty($data->archived_audit->data->top_words)): ?>
                            <div class="mb-3">
                                <button class="btn btn-sm btn-block btn-gray-200 mb-2" type="button" data-toggle="collapse" data-target="#words_used_container" aria-expanded="false" aria-controls="words_used_container">
                                    <?= sprintf(l('audits.test.words_used_count'), count((array) $data->archived_audit->data->top_words)) ?>
                                </button>

                                <div class="collapse" id="words_used_container">
                                    <div class="card card-body">
                                        <div class="row">
                                            <?php foreach ($data->archived_audit->data->top_words as $word => $count): ?>
                                                <div class="col-md-6">
                                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                                        <div class="text-truncate">
                                                            <p class="m-0 font-size-small text-truncate" title="<?= e($word) ?>"><?= e($word) ?></p>
                                                        </div>

                                                        <span class="badge badge-light">
                                                        <?= nr($count) ?>
                                                    </span>
                                                    </div>
                                                </div>
                                            <?php endforeach ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="mb-3 audit-issue-wrapper audit-issue-info">
                                <p class="m-0 font-size-small"><?= l('audits.test.words_used.missing') ?></p>
                                <small class="text-muted"><?= l('audits.test.words_used.missing_help') ?></small>
                            </div>
                        <?php endif ?>
                    <?php endif ?>
                </div>
            </div>

            <div class="flex-fill my-4">
                <hr class="border-gray-100" />
            </div>

            <div id="deprecated_html_tags" class="row">
                <div class="col-12 col-xl-4 mb-2 mb-xl-0">
                    <div>
                        <?= get_audit_test_icon('deprecated_html_tags', $data->archived_audit->issues) ?>
                        <span class="font-weight-bold"><?= l('audits.test.deprecated_html_tags') ?></span>
                    </div>
                </div>

                <div class="col-12 col-xl-8">
                    <?php if(!$this->user->plan_settings->audits_enabled_tests->deprecated_html_tags): ?>
                        <p class="small m-0">
                            <i class="fas fa-fw fa-question-circle mr-1"></i> <?=  l('global.info_message.plan_feature_no_access') ?>
                        </p>
                    <?php else: ?>
                        <?php if(count((array) $data->archived_audit->data->deprecated_html_tags)): ?>
                            <div class="mb-3">
                                <button class="btn btn-sm btn-block btn-gray-200 mb-2" type="button" data-toggle="collapse" data-target="#deprecated_html_tags_container" aria-expanded="false" aria-controls="deprecated_html_tags_container">
                                    <?= sprintf(l('audits.test.deprecated_html_tags_count'), count((array) $data->archived_audit->data->deprecated_html_tags)) ?>
                                </button>

                                <div class="collapse" id="deprecated_html_tags_container">
                                    <div class="card card-body">
                                        <?php foreach ($data->archived_audit->data->deprecated_html_tags as $tag => $count): ?>
                                            <div class="d-flex justify-content-between small mb-2">
                                                <code>&lt;<?= $tag ?>&gt;</code>

                                                <span class="font-weight-bold"><?= nr($count) ?></span>
                                            </div>
                                        <?php endforeach ?>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3 audit-issue-wrapper audit-issue-minor">
                                <p class="m-0 font-size-small"><?= l('audits.test.deprecated_html_tags.existing') ?></p>
                                <small class="text-muted"><?= l('audits.test.deprecated_html_tags.existing_help') ?></small>
                            </div>
                        <?php else: ?>
                            <div class="mb-3">
                                <p class="small font-weight-bold m-0"><?= l('audits.test.deprecated_html_tags_missing') ?></p>
                                <small class="text-muted"><?= l('audits.test.deprecated_html_tags_help') ?></small>
                            </div>
                        <?php endif ?>
                    <?php endif ?>
                </div>
            </div>

            <div class="flex-fill my-4">
                <hr class="border-gray-100" />
            </div>

            <div id="inline_css" class="row">
                <div class="col-12 col-xl-4 mb-2 mb-xl-0">
                    <div>
                        <?= get_audit_test_icon('inline_css', $data->archived_audit->issues) ?>
                        <span class="font-weight-bold"><?= l('audits.test.inline_css') ?></span>
                    </div>
                </div>

                <div class="col-12 col-xl-8">
                    <?php if(!$this->user->plan_settings->audits_enabled_tests->inline_css): ?>
                        <p class="small m-0">
                            <i class="fas fa-fw fa-question-circle mr-1"></i> <?=  l('global.info_message.plan_feature_no_access') ?>
                        </p>
                    <?php else: ?>
                        <div class="mb-3">
                            <?php if(count((array) $data->archived_audit->data->inline_css)): ?>
                                <button class="btn btn-sm btn-block btn-gray-200 mb-2" type="button" data-toggle="collapse" data-target="#inline_css_container" aria-expanded="false" aria-controls="inline_css_container">
                                    <?= sprintf(l('audits.test.inline_css_count'), count((array) $data->archived_audit->data->inline_css)) ?>
                                </button>

                                <div class="collapse" id="inline_css_container">
                                    <div class="card card-body">
                                        <ol class="mb-0 pl-3 audit-ol">
                                            <?php foreach ($data->archived_audit->data->inline_css as $inline_css): ?>
                                                <li class="mb-3">
                                                    <p class="m-0 font-size-small">&lt;<?= e($inline_css->tag) ?>&gt;</p>
                                                    <small class="text-muted"><code><?= string_truncate(e($inline_css->style), 64) ?></code></small>
                                                </li>
                                            <?php endforeach ?>
                                        </ol>
                                    </div>
                                </div>
                            <?php else: ?>
                                <p class="small font-weight-bold m-0"><?= l('audits.test.inline_css_missing') ?></p>
                                <small class="text-muted"><?= l('audits.test.inline_css_help') ?></small>
                            <?php endif ?>
                        </div>

                        <?php if(in_array('existing', $data->archived_audit->issues->minor->inline_css ?? [])): ?>
                            <div class="mb-3 audit-issue-wrapper audit-issue-minor">
                                <p class="m-0 font-size-small"><?= l('audits.test.inline_css.existing') ?></p>
                                <small class="text-muted"><?= l('audits.test.inline_css.existing_help') ?></small>
                            </div>
                        <?php endif ?>
                    <?php endif ?>
                </div>
            </div>

            <div class="flex-fill my-4">
                <hr class="border-gray-100" />
            </div>

            <div id="emails" class="row">
                <div class="col-12 col-xl-4 mb-2 mb-xl-0">
                    <div>
                        <?= get_audit_test_icon('emails', $data->archived_audit->issues) ?>
                        <span class="font-weight-bold"><?= l('audits.test.emails') ?></span>
                    </div>
                </div>

                <div class="col-12 col-xl-8">
                    <?php if(!$this->user->plan_settings->audits_enabled_tests->emails): ?>
                        <p class="small m-0">
                            <i class="fas fa-fw fa-question-circle mr-1"></i> <?=  l('global.info_message.plan_feature_no_access') ?>
                        </p>
                    <?php else: ?>
                        <div class="mb-3">
                            <?php if(count((array) $data->archived_audit->data->emails)): ?>
                                <button class="btn btn-sm btn-block btn-gray-200 mb-2" type="button" data-toggle="collapse" data-target="#emails_container" aria-expanded="false" aria-controls="emails_container">
                                    <?= sprintf(l('audits.test.emails_count'), count((array) $data->archived_audit->data->emails)) ?>
                                </button>

                                <div class="collapse" id="emails_container">
                                    <div class="card card-body">
                                        <ol class="mb-0">
                                            <?php foreach ($data->archived_audit->data->emails as $email): ?>
                                                <li class="font-size-small mb-2">
                                                    <?= e($email) ?>
                                                </li>
                                            <?php endforeach ?>
                                        </ol>
                                    </div>
                                </div>
                            <?php else: ?>
                                <p class="small font-weight-bold m-0"><?= l('audits.test.emails_missing') ?></p>
                                <small class="text-muted"><?= l('audits.test.emails_help') ?></small>
                            <?php endif ?>
                        </div>

                        <?php if(in_array('existing', $data->archived_audit->issues->minor->emails ?? [])): ?>
                            <div class="mb-3 audit-issue-wrapper audit-issue-minor">
                                <p class="m-0 font-size-small"><?= l('audits.test.emails.existing') ?></p>
                                <small class="text-muted"><?= l('audits.test.emails.existing_help') ?></small>
                            </div>
                        <?php endif ?>
                    <?php endif ?>
                </div>
            </div>

            <div class="flex-fill my-4">
                <hr class="border-gray-100" />
            </div>

            <div id="text_to_html_ratio" class="row">
                <div class="col-12 col-xl-4 mb-2 mb-xl-0">
                    <div>
                        <?= get_audit_test_icon('text_to_html_ratio', $data->archived_audit->issues) ?>
                        <span class="font-weight-bold"><?= l('audits.test.text_to_html_ratio') ?></span>
                    </div>
                </div>

                <div class="col-12 col-xl-8">
                    <?php if(!$this->user->plan_settings->audits_enabled_tests->text_to_html_ratio): ?>
                        <p class="small m-0">
                            <i class="fas fa-fw fa-question-circle mr-1"></i> <?=  l('global.info_message.plan_feature_no_access') ?>
                        </p>
                    <?php else: ?>
                        <div class="mb-3">
                            <p class="small m-0"><strong><?= nr($data->archived_audit->data->text_to_html_ratio) ?>%</strong></p>
                            <small class="text-muted"><?= l('audits.test.text_to_html_ratio_help') ?></small>
                        </div>

                        <?php if(in_array('too_low', $data->archived_audit->issues->minor->text_to_html_ratio ?? [])): ?>
                            <div class="mb-3 audit-issue-wrapper audit-issue-minor">
                                <p class="m-0 font-size-small"><?= l('audits.test.text_to_html_ratio.too_low') ?></p>
                                <small class="text-muted"><?= l('audits.test.text_to_html_ratio.too_low_help') ?></small>
                            </div>
                        <?php endif ?>
                    <?php endif ?>
                </div>
            </div>

        </div>
    </div>

    <div class="card mb-5">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between">
                <span class="small font-weight-bold"><?= l('audits.media') ?></span>

                <span class="badge bg-primary-50 text-primary-700">
                    <i class="fas fa-fw fa-sm fa-image"></i>
                </span>
            </div>
        </div>

        <div class="card-body">

            <div id="image_formats" class="row">
                <div class="col-12 col-xl-4 mb-2 mb-xl-0">
                    <div>
                        <?= get_audit_test_icon('image_formats', $data->archived_audit->issues) ?>
                        <span class="font-weight-bold"><?= l('audits.test.image_formats') ?></span>
                    </div>
                </div>

                <div class="col-12 col-xl-8">
                    <?php if(!$this->user->plan_settings->audits_enabled_tests->image_formats): ?>
                        <p class="small m-0">
                            <i class="fas fa-fw fa-question-circle mr-1"></i> <?=  l('global.info_message.plan_feature_no_access') ?>
                        </p>
                    <?php else: ?>
                        <?php
                        $failed_images = [];
                        foreach ($data->archived_audit->data->images as $image) {
                            if($image->extension && !in_array($image->extension, ['webp', 'avif', 'svg'])) {
                                $failed_images[] = $image;
                            }
                        }
                        ?>
                        <?php if(count($failed_images)): ?>
                            <div class="mb-3">
                                <button class="btn btn-sm btn-block btn-gray-200 mb-2" type="button" data-toggle="collapse" data-target="#image_formats_container" aria-expanded="false" aria-controls="image_formats_container">
                                    <?= sprintf(l('audits.test.image_formats_count'), count($failed_images)) ?>
                                </button>

                                <div class="collapse" id="image_formats_container">
                                    <div class="card card-body">
                                        <?php foreach ($failed_images as $image): ?>
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <div class="text-truncate">
                                                    <p class="m-0 font-size-small">
                                                        <span class="badge badge-light"><?= $image->extension ?></span>
                                                        <?= $image->title ? e($image->title) : l('audits.test.image_formats_title_missing') ?>
                                                    </p>
                                                    <small class="text-muted">
                                                        <img referrerpolicy="no-referrer" src="<?= get_favicon_url_from_domain(parse_url($image->src, PHP_URL_HOST)) ?>" class="img-fluid icon-favicon-small mr-1" loading="lazy" />
                                                        <a href="<?= e($image->src) ?>" target="_blank" rel="nofollow noreferrer" class="text-truncate"><?= e($image->src) ?></a>
                                                    </small>
                                                </div>
                                            </div>
                                        <?php endforeach ?>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3 audit-issue-wrapper audit-issue-minor">
                                <p class="m-0 font-size-small"><?= l('audits.test.image_formats.existing') ?></p>
                                <small class="text-muted"><?= l('audits.test.image_formats.existing_help') ?></small>
                            </div>
                        <?php else: ?>
                            <div class="mb-3">
                                <p class="small font-weight-bold m-0"><?= l('audits.test.image_formats_missing') ?></p>
                                <small class="text-muted"><?= l('audits.test.image_formats_help') ?></small>
                            </div>
                        <?php endif ?>
                    <?php endif ?>
                </div>
            </div>

            <div class="flex-fill my-4">
                <hr class="border-gray-100" />
            </div>

            <div id="image_alt" class="row">
                <div class="col-12 col-xl-4 mb-2 mb-xl-0">
                    <div>
                        <?= get_audit_test_icon('image_alt', $data->archived_audit->issues) ?>
                        <span class="font-weight-bold"><?= l('audits.test.image_alt') ?></span>
                    </div>
                </div>

                <div class="col-12 col-xl-8">
                    <?php if(!$this->user->plan_settings->audits_enabled_tests->image_alt): ?>
                        <p class="small m-0">
                            <i class="fas fa-fw fa-question-circle mr-1"></i> <?=  l('global.info_message.plan_feature_no_access') ?>
                        </p>
                    <?php else: ?>
                        <?php
                        $failed_images = [];
                        foreach($data->archived_audit->data->images as $image) {
                            if(empty($image->alt)) {
                                $failed_images[] = $image;
                            }
                        }
                        ?>
                        <?php if(count($failed_images)): ?>
                            <div class="mb-3">
                                <button class="btn btn-sm btn-block btn-gray-200 mb-2" type="button" data-toggle="collapse" data-target="#image_alt_container" aria-expanded="false" aria-controls="image_alt_container">
                                    <?= sprintf(l('audits.test.image_alt_count'), count($failed_images)) ?>
                                </button>

                                <div class="collapse" id="image_alt_container">
                                    <div class="card card-body">
                                        <?php foreach ($failed_images as $image): ?>
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <div class="text-truncate">
                                                    <p class="m-0 font-size-small">
                                                        <span class="badge badge-light"><?= $image->extension ?></span>
                                                        <?= $image->title ? e($image->title) : l('audits.test.image_alt_title_missing') ?>
                                                    </p>
                                                    <small class="text-muted">
                                                        <img referrerpolicy="no-referrer" src="<?= get_favicon_url_from_domain(parse_url($image->src, PHP_URL_HOST)) ?>" class="img-fluid icon-favicon-small mr-1" loading="lazy" />
                                                        <a href="<?= e($image->src) ?>" target="_blank" rel="nofollow noreferrer" class="text-truncate"><?= e($image->src) ?></a>
                                                    </small>
                                                </div>
                                            </div>
                                        <?php endforeach ?>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3 audit-issue-wrapper audit-issue-minor">
                                <p class="m-0 font-size-small"><?= l('audits.test.image_alt.missing') ?></p>
                                <small class="text-muted"><?= l('audits.test.image_alt.missing_help') ?></small>
                            </div>
                        <?php else: ?>
                            <div class="mb-3">
                                <p class="small font-weight-bold m-0"><?= l('audits.test.image_alt_existing') ?></p>
                                <small class="text-muted"><?= l('audits.test.image_alt_help') ?></small>
                            </div>
                        <?php endif ?>
                    <?php endif ?>
                </div>
            </div>

            <div class="flex-fill my-4">
                <hr class="border-gray-100" />
            </div>

            <div id="image_lazy_loading" class="row">
                <div class="col-12 col-xl-4 mb-2 mb-xl-0">
                    <div>
                        <span data-toggle="tooltip" title="<?= l('audits.informational_test') ?>"><i class="fas fa-fw fa-sm fa-info-circle text-info mr-1"></i></span>
                        <span class="font-weight-bold"><?= l('audits.test.image_lazy_loading') ?></span>
                    </div>
                </div>

                <div class="col-12 col-xl-8">
                    <?php if(!$this->user->plan_settings->audits_enabled_tests->image_lazy_loading): ?>
                        <p class="small m-0">
                            <i class="fas fa-fw fa-question-circle mr-1"></i> <?=  l('global.info_message.plan_feature_no_access') ?>
                        </p>
                    <?php else: ?>
                        <?php
                        $failed_images = [];
                        foreach($data->archived_audit->data->images as $image) {
                            if(empty($image->loading) || $image->loading != 'lazy') {
                                $failed_images[] = $image;
                            }
                        }
                        ?>
                        <?php if(count($failed_images)): ?>
                            <div class="mb-3">
                                <button class="btn btn-sm btn-block btn-gray-200 mb-2" type="button" data-toggle="collapse" data-target="#image_lazy_loading_container" aria-expanded="false" aria-controls="image_lazy_loading_container">
                                    <?= sprintf(l('audits.test.image_lazy_loading_count'), count($failed_images)) ?>
                                </button>

                                <div class="collapse" id="image_lazy_loading_container">
                                    <div class="card card-body">
                                        <?php foreach ($failed_images as $image): ?>
                                            <div class="text-truncate mb-3">
                                                <p class="m-0 font-size-small">
                                                    <span class="badge badge-light"><?= $image->extension ?></span>
                                                    <?= $image->title ? e($image->title) : l('audits.test.image_lazy_loading_title_missing') ?>
                                                </p>
                                                <small class="text-muted">
                                                    <img referrerpolicy="no-referrer" src="<?= get_favicon_url_from_domain(parse_url($image->src, PHP_URL_HOST)) ?>" class="img-fluid icon-favicon-small mr-1" loading="lazy" />
                                                    <a href="<?= e($image->src) ?>" target="_blank" rel="nofollow noreferrer" class="text-truncate"><?= e($image->src) ?></a>
                                                </small>
                                            </div>
                                        <?php endforeach ?>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3 audit-issue-wrapper audit-issue-minor">
                                <p class="m-0 font-size-small"><?= l('audits.test.image_lazy_loading.missing') ?></p>
                                <small class="text-muted"><?= l('audits.test.image_lazy_loading.missing_help') ?></small>
                            </div>
                        <?php else: ?>
                            <div class="mb-3">
                                <p class="small font-weight-bold m-0"><?= l('audits.test.image_lazy_loading_existing') ?></p>
                                <small class="text-muted"><?= l('audits.test.image_lazy_loading_help') ?></small>
                            </div>
                        <?php endif ?>
                    <?php endif ?>
                </div>
            </div>

        </div>
    </div>

    <div class="card mb-5">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between">
                <span class="small font-weight-bold"><?= l('audits.technical_performance') ?></span>

                <span class="badge bg-primary-50 text-primary-700">
                    <i class="fas fa-fw fa-sm fa-tachometer-alt"></i>
                </span>
            </div>
        </div>

        <div class="card-body">

            <div id="robots" class="row">
                <div class="col-12 col-xl-4 mb-2 mb-xl-0">
                    <div>
                        <?= get_audit_test_icon('robots', $data->archived_audit->issues) ?>
                        <span class="font-weight-bold"><?= l('audits.test.robots') ?></span>
                    </div>
                </div>

                <div class="col-12 col-xl-8">
                    <?php if(!$this->user->plan_settings->audits_enabled_tests->robots): ?>
                        <p class="small m-0">
                            <i class="fas fa-fw fa-question-circle mr-1"></i> <?=  l('global.info_message.plan_feature_no_access') ?>
                        </p>
                    <?php else: ?>
                        <div class="mb-3">
                            <p class="small m-0"><a href="<?= $data->archived_audit->data->robots_url ?>" target="_blank" rel="nofollow noreferrer"><?= $data->archived_audit->data->robots_url ?></a></p>
                            <small class="text-muted"><?= l('audits.test.robots_help') ?></small>
                        </div>

                        <?php if(in_array('missing', $data->archived_audit->issues->minor->robots ?? [])): ?>
                            <div class="mb-3 audit-issue-wrapper audit-issue-minor">
                                <p class="m-0 font-size-small"><?= l('audits.test.robots.missing') ?></p>
                                <small class="text-muted"><?= l('audits.test.robots.missing_help') ?></small>
                            </div>
                        <?php endif ?>

                        <?php if(in_array('excluded', $data->archived_audit->issues->major->robots ?? [])): ?>
                            <div class="mb-3 audit-issue-wrapper audit-issue-major">
                                <p class="m-0 font-size-small"><?= l('audits.test.robots.excluded') ?></p>
                                <small class="text-muted"><?= l('audits.test.robots.excluded_help') ?></small>
                            </div>
                        <?php endif ?>
                    <?php endif ?>
                </div>
            </div>

            <div class="flex-fill my-4">
                <hr class="border-gray-100" />
            </div>

            <div id="not_found" class="row">
                <div class="col-12 col-xl-4 mb-2 mb-xl-0">
                    <div>
                        <?= get_audit_test_icon('not_found', $data->archived_audit->issues) ?>
                        <span class="font-weight-bold"><?= l('audits.test.not_found') ?></span>
                    </div>
                </div>

                <div class="col-12 col-xl-8">
                    <?php if(!$this->user->plan_settings->audits_enabled_tests->not_found): ?>
                        <p class="small m-0">
                            <i class="fas fa-fw fa-question-circle mr-1"></i> <?=  l('global.info_message.plan_feature_no_access') ?>
                        </p>
                    <?php else: ?>
                        <div class="mb-3">
                            <p class="small m-0"><a href="<?= $data->archived_audit->data->not_found_url ?>" target="_blank" rel="nofollow noreferrer"><?= $data->archived_audit->data->not_found_url ?></a> - <?= $data->archived_audit->data->not_found_status_code ?></p>
                            <small class="text-muted"><?= l('audits.test.not_found_help') ?></small>
                        </div>

                        <?php if(in_array('missing', $data->archived_audit->issues->moderate->not_found ?? [])): ?>
                            <div class="mb-3 audit-issue-wrapper audit-issue-moderate">
                                <p class="m-0 font-size-small"><?= l('audits.test.not_found.missing') ?></p>
                                <small class="text-muted"><?= l('audits.test.not_found.missing_help') ?></small>
                            </div>
                        <?php endif ?>
                    <?php endif ?>
                </div>
            </div>

            <div class="flex-fill my-4">
                <hr class="border-gray-100" />
            </div>

            <div id="header_server" class="row">
                <div class="col-12 col-xl-4 mb-2 mb-xl-0">
                    <div>
                        <?= get_audit_test_icon('header_server', $data->archived_audit->issues) ?>
                        <span class="font-weight-bold"><?= l('audits.test.header_server') ?></span>
                    </div>
                </div>

                <div class="col-12 col-xl-8">
                    <?php if(!$this->user->plan_settings->audits_enabled_tests->header_server): ?>
                        <p class="small m-0">
                            <i class="fas fa-fw fa-question-circle mr-1"></i> <?=  l('global.info_message.plan_feature_no_access') ?>
                        </p>
                    <?php else: ?>
                        <div class="mb-3">
                            <p class="small font-weight-bold m-0"><?= $data->archived_audit->data->response_headers->server ? e($data->archived_audit->data->response_headers->server) : l('audits.test.header_server_missing') ?></p>

                            <small class="text-muted"><?= l('audits.test.header_server_help') ?></small>
                        </div>

                        <?php if(in_array('existing', $data->archived_audit->issues->minor->header_server ?? [])): ?>
                            <div class="mb-3 audit-issue-wrapper audit-issue-minor">
                                <p class="m-0 font-size-small"><?= l('audits.test.header_server.existing') ?></p>
                                <small class="text-muted"><?= l('audits.test.header_server.existing_help') ?></small>
                            </div>
                        <?php endif ?>
                    <?php endif ?>
                </div>
            </div>

            <div class="flex-fill my-4">
                <hr class="border-gray-100" />
            </div>

            <div id="server_compression" class="row">
                <div class="col-12 col-xl-4 mb-2 mb-xl-0">
                    <div>
                        <?= get_audit_test_icon('server_compression', $data->archived_audit->issues) ?>
                        <span class="font-weight-bold"><?= l('audits.test.server_compression') ?></span>
                    </div>
                </div>

                <div class="col-12 col-xl-8">
                    <?php if(!$this->user->plan_settings->audits_enabled_tests->server_compression): ?>
                        <p class="small m-0">
                            <i class="fas fa-fw fa-question-circle mr-1"></i> <?=  l('global.info_message.plan_feature_no_access') ?>
                        </p>
                    <?php else: ?>
                        <div class="mb-3">
                            <p class="small m-0">
                                <?php
                                $compression_algorithm = match ($data->archived_audit->data->response_headers->content_encoding) {
                                    'br' => 'Brotli',
                                    'gzip' => 'GNU zip',
                                    'compress' => 'Unix compression',
                                    'deflate' => 'Deflate compression',
                                    'zstd' => 'Zstandard compression',
                                    default => null
                                };
                                ?>

                                <span class="font-weight-bold">
                                <?= $data->archived_audit->data->response_headers->content_encoding ? e($data->archived_audit->data->response_headers->content_encoding) : l('global.none') ?>
                            </span>

                                <span>(<?= $compression_algorithm ?>)</span>
                            </p>

                            <?php if($data->archived_audit->data->response_headers->content_encoding): ?>
                                <p class="small m-0">
                                    <?= sprintf(l('audits.test.server_compression_comparison'), '<strong>' . get_formatted_bytes($data->archived_audit->data->page_size) . '</strong>', '<strong>' . get_formatted_bytes($data->archived_audit->data->download_size) . '</strong>', nr(get_percentage_change($data->archived_audit->data->page_size, $data->archived_audit->data->download_size))) ?>
                                </p>
                            <?php endif ?>

                            <small class="text-muted"><?= l('audits.test.server_compression_help') ?></small>
                        </div>

                        <?php if(in_array('missing', $data->archived_audit->issues->moderate->server_compression ?? [])): ?>
                            <div class="mb-3 audit-issue-wrapper audit-issue-moderate">
                                <p class="m-0 font-size-small"><?= l('audits.test.server_compression.missing') ?></p>
                                <small class="text-muted"><?= l('audits.test.server_compression.missing_help') ?></small>
                            </div>
                        <?php endif ?>
                    <?php endif ?>
                </div>
            </div>

            <div class="flex-fill my-4">
                <hr class="border-gray-100" />
            </div>

            <div id="response_time" class="row">
                <div class="col-12 col-xl-4 mb-2 mb-xl-0">
                    <div>
                        <?= get_audit_test_icon('response_time', $data->archived_audit->issues) ?>
                        <span class="font-weight-bold"><?= l('audits.test.response_time') ?></span>
                    </div>
                </div>

                <div class="col-12 col-xl-8">
                    <?php if(!$this->user->plan_settings->audits_enabled_tests->response_time): ?>
                        <p class="small m-0">
                            <i class="fas fa-fw fa-question-circle mr-1"></i> <?=  l('global.info_message.plan_feature_no_access') ?>
                        </p>
                    <?php else: ?>
                        <div class="mb-3">
                            <p class="small font-weight-bold m-0"><?= display_response_time($data->archived_audit->data->response_time) ?></p>
                            <small class="text-muted"><?= l('audits.test.response_time_help') ?></small>
                        </div>

                        <?php if(in_array('too_slow', $data->archived_audit->issues->major->response_time ?? [])): ?>
                            <div class="mb-3 audit-issue-wrapper audit-issue-major">
                                <p class="m-0 font-size-small"><?= l('audits.test.response_time.too_slow') ?></p>
                                <small class="text-muted"><?= l('audits.test.response_time.too_slow_help') ?></small>
                            </div>
                        <?php endif ?>
                    <?php endif ?>
                </div>
            </div>

            <div class="flex-fill my-4">
                <hr class="border-gray-100" />
            </div>

            <div id="page_size" class="row">
                <div class="col-12 col-xl-4 mb-2 mb-xl-0">
                    <div>
                        <?= get_audit_test_icon('page_size', $data->archived_audit->issues) ?>
                        <span class="font-weight-bold"><?= l('audits.test.page_size') ?></span>
                    </div>
                </div>

                <div class="col-12 col-xl-8">
                    <?php if(!$this->user->plan_settings->audits_enabled_tests->page_size): ?>
                        <p class="small m-0">
                            <i class="fas fa-fw fa-question-circle mr-1"></i> <?=  l('global.info_message.plan_feature_no_access') ?>
                        </p>
                    <?php else: ?>
                        <div class="mb-3">
                            <p class="small font-weight-bold m-0"><?= get_formatted_bytes($data->archived_audit->data->page_size) ?></p>
                            <small class="text-muted"><?= l('audits.test.page_size_help') ?></small>
                        </div>

                        <?php if(in_array('too_big', $data->archived_audit->issues->moderate->page_size ?? [])): ?>
                            <div class="mb-3 audit-issue-wrapper audit-issue-moderate">
                                <p class="m-0 font-size-small"><?= l('audits.test.page_size.too_big') ?></p>
                                <small class="text-muted"><?= l('audits.test.page_size.too_big_help') ?></small>
                            </div>
                        <?php endif ?>
                    <?php endif ?>
                </div>
            </div>

            <div class="flex-fill my-4">
                <hr class="border-gray-100" />
            </div>

            <div id="dom_size" class="row">
                <div class="col-12 col-xl-4 mb-2 mb-xl-0">
                    <div>
                        <?= get_audit_test_icon('dom_size', $data->archived_audit->issues) ?>
                        <span class="font-weight-bold"><?= l('audits.test.dom_size') ?></span>
                    </div>
                </div>

                <div class="col-12 col-xl-8">
                    <?php if(!$this->user->plan_settings->audits_enabled_tests->dom_size): ?>
                        <p class="small m-0">
                            <i class="fas fa-fw fa-question-circle mr-1"></i> <?=  l('global.info_message.plan_feature_no_access') ?>
                        </p>
                    <?php else: ?>
                        <div class="mb-3">
                            <p class="small font-weight-bold m-0"><?= sprintf(l('audits.test.dom_size_nodes'), nr($data->archived_audit->data->dom_size)) ?></p>
                            <small class="text-muted"><?= l('audits.test.dom_size_help') ?></small>
                        </div>

                        <?php if(in_array('too_big', $data->archived_audit->issues->minor->dom_size ?? [])): ?>
                            <div class="mb-3 audit-issue-wrapper audit-issue-minor">
                                <p class="m-0 font-size-small"><?= l('audits.test.dom_size.too_big') ?></p>
                                <small class="text-muted"><?= l('audits.test.dom_size.too_big_help') ?></small>
                            </div>
                        <?php endif ?>
                    <?php endif ?>
                </div>
            </div>

            <div class="flex-fill my-4">
                <hr class="border-gray-100" />
            </div>

            <div id="non_deferred_scripts" class="row">
                <div class="col-12 col-xl-4 mb-2 mb-xl-0">
                    <div>
                        <?= get_audit_test_icon('non_deferred_scripts', $data->archived_audit->issues) ?>
                        <span class="font-weight-bold"><?= l('audits.test.non_deferred_scripts') ?></span>
                    </div>
                </div>

                <div class="col-12 col-xl-8">
                    <?php if(!$this->user->plan_settings->audits_enabled_tests->non_deferred_scripts): ?>
                        <p class="small m-0">
                            <i class="fas fa-fw fa-question-circle mr-1"></i> <?=  l('global.info_message.plan_feature_no_access') ?>
                        </p>
                    <?php else: ?>
                        <?php if($data->archived_audit->data->non_deferred_scripts_count): ?>
                            <div class="mb-3">
                                <button class="btn btn-sm btn-block btn-gray-200 mb-2" type="button" data-toggle="collapse" data-target="#non_deferred_scripts_container" aria-expanded="false" aria-controls="non_deferred_scripts_container">
                                    <?= sprintf(l('audits.test.non_deferred_scripts_count'), $data->archived_audit->data->non_deferred_scripts_count) ?>
                                </button>

                                <div class="collapse" id="non_deferred_scripts_container">
                                    <div class="card card-body">
                                        <ol class="mb-0 pl-3 audit-ol">
                                            <?php foreach ($data->archived_audit->data->scripts as $script): ?>
                                                <?php if($script->is_deferred) continue ?>
                                                <li class="font-size-small mb-2">
                                                    <?php if($script->src): ?>
                                                        <a href="<?= e($script->src) ?>" target="_blank" rel="nofollow noreferrer">
                                                            <?= e($script->src) ?>
                                                        </a>
                                                    <?php else: ?>
                                                        <?= l('audits.test.non_deferred_scripts_src_missing') ?>
                                                    <?php endif ?>
                                                </li>
                                            <?php endforeach ?>
                                        </ol>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3 audit-issue-wrapper audit-issue-minor">
                                <p class="m-0 font-size-small"><?= l('audits.test.non_deferred_scripts.existing') ?></p>
                                <small class="text-muted"><?= l('audits.test.non_deferred_scripts.existing_help') ?></small>
                                <small class="text-muted"><?= l('audits.test.non_deferred_scripts.existing_help2') ?></small>
                            </div>
                        <?php else: ?>
                            <div class="mb-3">
                                <p class="small font-weight-bold m-0"><?= l('audits.test.non_deferred_scripts_missing') ?></p>
                                <small class="text-muted"><?= l('audits.test.non_deferred_scripts_help') ?></small>
                            </div>
                        <?php endif ?>
                    <?php endif ?>
                </div>
            </div>

            <div class="flex-fill my-4">
                <hr class="border-gray-100" />
            </div>

            <div id="http_requests" class="row">
                <div class="col-12 col-xl-4 mb-2 mb-xl-0">
                    <div>
                        <?= get_audit_test_icon('http_requests', $data->archived_audit->issues) ?>
                        <span class="font-weight-bold"><?= l('audits.test.http_requests') ?></span>
                    </div>
                </div>

                <div class="col-12 col-xl-8">
                    <?php if(!$this->user->plan_settings->audits_enabled_tests->http_requests): ?>
                        <p class="small m-0">
                            <i class="fas fa-fw fa-question-circle mr-1"></i> <?=  l('global.info_message.plan_feature_no_access') ?>
                        </p>
                    <?php else: ?>
                        <p class="small mb-2"><?= sprintf(l('audits.test.http_requests_count'), '<strong>' . $data->archived_audit->data->http_requests . '</strong>') ?></p>

                        <button class="btn btn-sm btn-block btn-gray-200 mb-2" type="button" data-toggle="collapse" data-target="#http_requests_container_css" aria-expanded="false" aria-controls="http_requests_container_css" <?= $data->archived_audit->data->http_requests_data->css ? null : 'disabled="disabled"' ?>>
                            <?= sprintf(l('audits.test.http_requests_css'), '<span class="badge badge-light">' . $data->archived_audit->data->http_requests_data->css . '</span>') ?>
                        </button>

                        <div class="collapse" id="http_requests_container_css">
                            <div class="card card-body">
                                <ol class="mb-0 pl-3 audit-ol">
                                    <?php foreach($data->archived_audit->data->stylesheets as $stylesheet): ?>
                                        <li class="font-size-small mb-2">
                                            <a href="<?= e($stylesheet->href) ?>" rel="nofollow noreferrer" target="_blank">
                                                <?= e($stylesheet->href) ?>
                                            </a>
                                        </li>
                                    <?php endforeach ?>
                                </ol>
                            </div>
                        </div>

                        <button class="btn btn-sm btn-block btn-gray-200 mb-2" type="button" data-toggle="collapse" data-target="#http_requests_container_js" aria-expanded="false" aria-controls="http_requests_container_js" <?= $data->archived_audit->data->http_requests_data->js ? null : 'disabled="disabled"' ?>>
                            <?= sprintf(l('audits.test.http_requests_js'), '<span class="badge badge-light">' . $data->archived_audit->data->http_requests_data->js . '</span>') ?>
                        </button>

                        <div class="collapse" id="http_requests_container_js">
                            <div class="card card-body">
                                <ol class="mb-0 pl-3 audit-ol">
                                    <?php foreach($data->archived_audit->data->scripts as $script): ?>
                                        <?php if($script->type != 'url') continue ?>
                                        <li class="font-size-small mb-2">
                                            <a href="<?= e($script->src) ?>" rel="nofollow noreferrer" target="_blank">
                                                <?= e($script->src) ?>
                                            </a>
                                        </li>
                                    <?php endforeach ?>
                                </ol>
                            </div>
                        </div>

                        <button class="btn btn-sm btn-block btn-gray-200 mb-2" type="button" data-toggle="collapse" data-target="#http_requests_container_images" aria-expanded="false" aria-controls="http_requests_container_images" <?= $data->archived_audit->data->http_requests_data->images ? null : 'disabled="disabled"' ?>>
                            <?= sprintf(l('audits.test.http_requests_images'), '<span class="badge badge-light">' . $data->archived_audit->data->http_requests_data->images . '</span>') ?>
                        </button>

                        <div class="collapse" id="http_requests_container_images">
                            <div class="card card-body">
                                <ol class="mb-0 pl-3 audit-ol">
                                    <?php foreach($data->archived_audit->data->images as $image): ?>
                                        <?php if($image->type != 'url') continue ?>
                                        <li class="font-size-small mb-2">
                                            <a href="<?= e($image->src) ?>" rel="nofollow noreferrer" target="_blank">
                                                <?= e($image->src) ?>
                                            </a>
                                        </li>
                                    <?php endforeach ?>

                                    <?php if(!empty($data->archived_audit->data->favicon)): ?>
                                        <li class="font-size-small mb-2">
                                            <a href="<?= $data->archived_audit->data->favicon ?>" rel="nofollow noreferrer" target="_blank">
                                                <?= e($data->archived_audit->data->favicon) ?>
                                            </a>
                                        </li>
                                    <?php endif ?>
                                </ol>
                            </div>
                        </div>

                        <?php foreach(['audios', 'videos', 'iframes'] as $http_request_type): ?>
                            <button class="btn btn-sm btn-block btn-gray-200 mb-2" type="button" data-toggle="collapse" data-target="#<?= 'http_requests_container_' . $http_request_type ?>" aria-expanded="false" aria-controls="<?= 'http_requests_container_' . $http_request_type ?>" <?= $data->archived_audit->data->http_requests_data->{$http_request_type} ? null : 'disabled="disabled"' ?>>
                                <?= sprintf(l('audits.test.http_requests_' . $http_request_type), '<span class="badge badge-light">' . $data->archived_audit->data->http_requests_data->{$http_request_type} . '</span>') ?>
                            </button>

                            <div class="collapse" id="<?= 'http_requests_container_' . $http_request_type ?>">
                                <div class="card card-body">
                                    <ol class="mb-0 pl-3 audit-ol">
                                        <?php foreach($data->archived_audit->data->{$http_request_type} as $http_request): ?>
                                            <li class="font-size-small mb-2">
                                                <a href="<?= e($http_request->src) ?>" rel="nofollow noreferrer" target="_blank">
                                                    <?= e($http_request->src) ?>
                                                </a>
                                            </li>
                                        <?php endforeach ?>
                                    </ol>
                                </div>
                            </div>
                        <?php endforeach ?>

                        <small class="text-muted"><?= l('audits.test.http_requests_help') ?></small>

                        <?php if(in_array('too_many', $data->archived_audit->issues->moderate->http_requests ?? [])): ?>
                            <div class="mb-3 audit-issue-wrapper audit-issue-moderate">
                                <p class="m-0 font-size-small"><?= l('audits.test.http_requests.too_many') ?></p>
                                <small class="text-muted"><?= l('audits.test.http_requests.too_many_help') ?></small>
                            </div>
                        <?php endif ?>
                    <?php endif ?>
                </div>
            </div>

            <div class="flex-fill my-4">
                <hr class="border-gray-100" />
            </div>

            <div id="is_http2" class="row">
                <div class="col-12 col-xl-4 mb-2 mb-xl-0">
                    <div>
                        <?= get_audit_test_icon('is_http2', $data->archived_audit->issues) ?>
                        <span class="font-weight-bold"><?= l('audits.test.is_http2') ?></span>
                    </div>
                </div>

                <div class="col-12 col-xl-8">
                    <?php if(!$this->user->plan_settings->audits_enabled_tests->is_http2): ?>
                        <p class="small m-0">
                            <i class="fas fa-fw fa-question-circle mr-1"></i> <?=  l('global.info_message.plan_feature_no_access') ?>
                        </p>
                    <?php else: ?>
                        <div class="mb-3">
                            <p class="small m-0"><strong><?= $data->archived_audit->data->http_version == 3 ? l('global.yes') : l('global.none') ?></strong></p>
                            <small class="text-muted"><?= l('audits.test.is_http2_help') ?></small>
                        </div>

                        <?php if(in_array('missing', $data->archived_audit->issues->moderate->is_http2 ?? [])): ?>
                            <div class="mb-3 audit-issue-wrapper audit-issue-moderate">
                                <p class="m-0 font-size-small"><?= l('audits.test.is_http2.missing') ?></p>
                                <small class="text-muted"><?= l('audits.test.is_http2.missing_help') ?></small>
                            </div>
                        <?php endif ?>
                    <?php endif ?>
                </div>
            </div>

            <div class="flex-fill my-4">
                <hr class="border-gray-100" />
            </div>

            <div id="is_https" class="row">
                <div class="col-12 col-xl-4 mb-2 mb-xl-0">
                    <div>
                        <?= get_audit_test_icon('is_https', $data->archived_audit->issues) ?>
                        <span class="font-weight-bold"><?= l('audits.test.is_https') ?></span>
                    </div>
                </div>

                <div class="col-12 col-xl-8">
                    <?php if(!$this->user->plan_settings->audits_enabled_tests->is_https): ?>
                        <p class="small m-0">
                            <i class="fas fa-fw fa-question-circle mr-1"></i> <?=  l('global.info_message.plan_feature_no_access') ?>
                        </p>
                    <?php else: ?>
                        <div class="mb-3">
                            <p class="small m-0"><strong><?= $data->archived_audit->data->is_https ? l('global.yes') : l('global.none') ?></strong></p>
                            <small class="text-muted"><?= l('audits.test.is_https_help') ?></small>
                        </div>

                        <?php if(in_array('missing', $data->archived_audit->issues->major->is_https ?? [])): ?>
                            <div class="mb-3 audit-issue-wrapper audit-issue-major">
                                <p class="m-0 font-size-small"><?= l('audits.test.is_https.missing') ?></p>
                                <small class="text-muted"><?= l('audits.test.is_https.missing_help') ?></small>
                            </div>
                        <?php endif ?>
                    <?php endif ?>
                </div>
            </div>

            <div class="flex-fill my-4">
                <hr class="border-gray-100" />
            </div>

            <div id="is_ssl_valid" class="row">
                <div class="col-12 col-xl-4 mb-2 mb-xl-0">
                    <div>
                        <?= get_audit_test_icon('is_ssl_valid', $data->archived_audit->issues) ?>
                        <span class="font-weight-bold"><?= l('audits.test.is_ssl_valid') ?></span>
                    </div>
                </div>

                <div class="col-12 col-xl-8">
                    <?php if(!$this->user->plan_settings->audits_enabled_tests->is_ssl_valid): ?>
                        <p class="small m-0">
                            <i class="fas fa-fw fa-question-circle mr-1"></i> <?=  l('global.info_message.plan_feature_no_access') ?>
                        </p>
                    <?php else: ?>
                        <div class="mb-3">
                            <p class="small m-0"><strong><?= $data->archived_audit->data->is_ssl_valid ? l('global.yes') : l('global.none') ?></strong></p>
                            <small class="text-muted"><?= l('audits.test.is_ssl_valid_help') ?></small>
                        </div>

                        <?php if(in_array('invalid', $data->archived_audit->issues->major->is_ssl_valid ?? [])): ?>
                            <div class="mb-3 audit-issue-wrapper audit-issue-major">
                                <p class="m-0 font-size-small"><?= l('audits.test.is_ssl_valid.missing') ?></p>
                                <small class="text-muted"><?= l('audits.test.is_ssl_valid.missing_help') ?></small>
                            </div>
                        <?php endif ?>
                    <?php endif ?>
                </div>
            </div>

        </div>
    </div>

    <div class="card">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between">
                <span class="small font-weight-bold"><?= l('audits.links') ?></span>

                <span class="badge bg-primary-50 text-primary-700">
                    <i class="fas fa-fw fa-sm fa-link"></i>
                </span>
            </div>
        </div>

        <div class="card-body">

            <div id="unsafe_external_links" class="row">
                <div class="col-12 col-xl-4 mb-2 mb-xl-0">
                    <div>
                        <?= get_audit_test_icon('unsafe_external_links', $data->archived_audit->issues) ?>
                        <span class="font-weight-bold"><?= l('audits.test.unsafe_external_links') ?></span>
                    </div>
                </div>

                <div class="col-12 col-xl-8">
                    <?php if(!$this->user->plan_settings->audits_enabled_tests->unsafe_external_links): ?>
                        <p class="small m-0">
                            <i class="fas fa-fw fa-question-circle mr-1"></i> <?=  l('global.info_message.plan_feature_no_access') ?>
                        </p>
                    <?php else: ?>
                        <?php if($data->archived_audit->data->unsafe_external_links_count): ?>
                            <div class="mb-3">
                                <button class="btn btn-sm btn-block btn-gray-200 mb-2" type="button" data-toggle="collapse" data-target="#unsafe_external_links_container" aria-expanded="false" aria-controls="unsafe_external_links_container">
                                    <?= sprintf(l('audits.test.unsafe_external_links_count'), $data->archived_audit->data->unsafe_external_links_count) ?>
                                </button>

                                <div class="collapse" id="unsafe_external_links_container">
                                    <div class="card card-body">
                                        <?php foreach ($data->archived_audit->data->links as $link): ?>
                                            <?php if(!$link->is_unsafe) continue ?>

                                            <div class="text-truncate mb-3">
                                                <p class="m-0 font-size-small">
                                                    <?= $link->text ? e($link->text) : l('audits.test.unsafe_external_links_text_missing') ?>
                                                </p>

                                                <small class="text-muted">
                                                    <img referrerpolicy="no-referrer" src="<?= get_favicon_url_from_domain(parse_url($link->href, PHP_URL_HOST)) ?>" class="img-fluid icon-favicon-small mr-1" loading="lazy" />
                                                    <a href="<?= e($link->href) ?>" target="_blank" rel="nofollow noreferrer" class="text-truncate"><?= e($link->href) ?></a>
                                                </small>
                                            </div>
                                        <?php endforeach ?>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3 audit-issue-wrapper audit-issue-minor">
                                <p class="m-0 font-size-small"><?= l('audits.test.unsafe_external_links.existing') ?></p>
                                <small class="text-muted"><?= l('audits.test.unsafe_external_links.existing_help') ?></small>
                            </div>
                        <?php else: ?>
                            <div class="mb-3">
                                <p class="small font-weight-bold m-0"><?= l('audits.test.unsafe_external_links_missing') ?></p>
                                <small class="text-muted"><?= l('audits.test.unsafe_external_links_help') ?></small>
                            </div>
                        <?php endif ?>
                    <?php endif ?>
                </div>
            </div>

            <div class="flex-fill my-4">
                <hr class="border-gray-100" />
            </div>

            <div id="external_links" class="row">
                <div class="col-12 col-xl-4 mb-2 mb-xl-0">
                    <div>
                        <?= get_audit_test_icon('external_links', $data->archived_audit->issues) ?>
                        <span class="font-weight-bold"><?= l('audits.test.external_links') ?></span>
                    </div>
                </div>

                <div class="col-12 col-xl-8">
                    <?php if(!$this->user->plan_settings->audits_enabled_tests->external_links): ?>
                        <p class="small m-0">
                            <i class="fas fa-fw fa-question-circle mr-1"></i> <?=  l('global.info_message.plan_feature_no_access') ?>
                        </p>
                    <?php else: ?>
                        <div class="mb-3">
                            <button class="btn btn-sm btn-block btn-gray-200 mb-2" type="button" data-toggle="collapse" data-target="#external_links_container" aria-expanded="false" aria-controls="external_links_container" <?= $data->archived_audit->data->external_links_count ? null : 'disabled="disabled"' ?>>
                                <?= sprintf(l('audits.test.external_links_count'), $data->archived_audit->data->external_links_count) ?>
                            </button>

                            <div class="collapse" id="external_links_container">
                                <div class="card card-body">
                                    <?php foreach ($data->archived_audit->data->links as $link): ?>
                                        <?php if($link->is_internal) continue ?>

                                        <div class="text-truncate mb-3">
                                            <p class="m-0 font-size-small">
                                                <?= $link->text ? e($link->text) : l('audits.test.external_links_text_missing') ?>
                                            </p>

                                            <small class="text-muted">
                                                <img referrerpolicy="no-referrer" src="<?= get_favicon_url_from_domain(parse_url($link->href, PHP_URL_HOST)) ?>" class="img-fluid icon-favicon-small mr-1" loading="lazy" />
                                                <a href="<?= e($link->href) ?>" target="_blank" rel="nofollow noreferrer" class="text-truncate"><?= e($link->href) ?></a>
                                            </small>
                                        </div>
                                    <?php endforeach ?>
                                </div>
                            </div>
                        </div>

                        <?php if(in_array('too_many', $data->archived_audit->issues->moderate->external_links ?? [])): ?>
                            <div class="mb-3 audit-issue-wrapper audit-issue-moderate">
                                <p class="m-0 font-size-small"><?= l('audits.test.external_links.too_many') ?></p>
                                <small class="text-muted"><?= l('audits.test.external_links.too_many_help') ?></small>
                            </div>
                        <?php endif ?>
                    <?php endif ?>
                </div>
            </div>

            <div class="flex-fill my-4">
                <hr class="border-gray-100" />
            </div>

            <div id="internal_links" class="row">
                <div class="col-12 col-xl-4 mb-2 mb-xl-0">
                    <div>
                        <?= get_audit_test_icon('internal_links', $data->archived_audit->issues) ?>
                        <span class="font-weight-bold"><?= l('audits.test.internal_links') ?></span>
                    </div>
                </div>

                <div class="col-12 col-xl-8">
                    <?php if(!$this->user->plan_settings->audits_enabled_tests->internal_links): ?>
                        <p class="small m-0">
                            <i class="fas fa-fw fa-question-circle mr-1"></i> <?=  l('global.info_message.plan_feature_no_access') ?>
                        </p>
                    <?php else: ?>
                        <div class="mb-3">
                            <button class="btn btn-sm btn-block btn-gray-200 mb-2" type="button" data-toggle="collapse" data-target="#internal_links_container" aria-expanded="false" aria-controls="internal_links_container" <?= $data->archived_audit->data->internal_links_count ? null : 'disabled="disabled"' ?>>
                                <?= sprintf(l('audits.test.internal_links_count'), $data->archived_audit->data->internal_links_count) ?>
                            </button>

                            <div class="collapse" id="internal_links_container">
                                <div class="card card-body">
                                    <?php foreach ($data->archived_audit->data->links as $link): ?>
                                        <?php if(!$link->is_internal) continue ?>

                                        <div class="text-truncate mb-3">
                                            <p class="m-0 font-size-small">
                                                <?= $link->text ? e($link->text) : l('audits.test.internal_links_text_missing') ?>
                                            </p>

                                            <small class="text-muted">
                                                <img referrerpolicy="no-referrer" src="<?= get_favicon_url_from_domain(parse_url($link->href, PHP_URL_HOST)) ?>" class="img-fluid icon-favicon-small mr-1" loading="lazy" />
                                                <a href="<?= e($link->href) ?>" target="_blank" rel="nofollow noreferrer" class="text-truncate"><?= e($link->href) ?></a>
                                            </small>
                                        </div>
                                    <?php endforeach ?>
                                </div>
                            </div>
                        </div>

                        <?php if(in_array('too_many', $data->archived_audit->issues->minor->internal_links ?? [])): ?>
                            <div class="mb-3 audit-issue-wrapper audit-issue-minor">
                                <p class="m-0 font-size-small"><?= l('audits.test.internal_links.too_many') ?></p>
                                <small class="text-muted"><?= l('audits.test.internal_links.too_many_help') ?></small>
                            </div>
                        <?php endif ?>
                    <?php endif ?>
                </div>
            </div>

            <div class="flex-fill my-4">
                <hr class="border-gray-100" />
            </div>

            <div id="social_links" class="row">
                <div class="col-12 col-xl-4 mb-2 mb-xl-0">
                    <div>
                        <span data-toggle="tooltip" title="<?= l('audits.informational_test') ?>"><i class="fas fa-fw fa-sm fa-info-circle text-info mr-1"></i></span>
                        <span class="font-weight-bold"><?= l('audits.test.social_links') ?></span>
                    </div>
                </div>

                <div class="col-12 col-xl-8">
                    <?php if(!$this->user->plan_settings->audits_enabled_tests->social_links): ?>
                        <p class="small m-0">
                            <i class="fas fa-fw fa-question-circle mr-1"></i> <?=  l('global.info_message.plan_feature_no_access') ?>
                        </p>
                    <?php else: ?>
                        <?php if(!empty($data->archived_audit->data->social_links)): ?>
                            <div class="mb-3">
                                <button class="btn btn-sm btn-block btn-gray-200 mb-2" type="button" data-toggle="collapse" data-target="#social_links_container" aria-expanded="false" aria-controls="social_links_container">
                                    <?= sprintf(l('audits.test.social_links_count'), count((array) $data->archived_audit->data->social_links)) ?>
                                </button>

                                <div class="collapse" id="social_links_container">
                                    <div class="card card-body">
                                        <ol class="mb-0 pl-3 audit-ol">
                                            <?php foreach ($data->archived_audit->data->social_links as $social_link): ?>
                                                <?php
                                                $social_link_icon = match ($social_link->type) {
                                                    'facebook' => 'facebook',
                                                    'twitter' => 'twitter',
                                                    'x' => 'x-twitter',
                                                    'instagram' => 'instagram',
                                                    'linkedin' => 'linkedin',
                                                    'youtube' => 'youtube',
                                                    'pinterest' => 'pinterest',
                                                    'tiktok' => 'tiktok',
                                                };
                                                ?>
                                                <li class="mb-3">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <p class="m-0 font-size-small"><?= $social_link->text ? e($social_link->text) : l('audits.test.social_links_text_missing') ?></p>
                                                            <small class="text-muted"><a href="<?= e($social_link->href) ?>" target="_blank" rel="nofollow noreferrer"><?= e($social_link->href) ?></a></small>
                                                        </div>

                                                        <span class="font-weight-bold">
                                                <i class="fab fa-fw fa-<?= $social_link_icon ?> fa-lg"></i>
                                            </span>
                                                    </div>
                                                </li>
                                            <?php endforeach ?>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="mb-3 audit-issue-wrapper audit-issue-info">
                                <p class="m-0 font-size-small"><?= l('audits.test.social_links.missing') ?></p>
                                <small class="text-muted"><?= l('audits.test.social_links.missing_help') ?></small>
                            </div>
                        <?php endif ?>
                    <?php endif ?>
                </div>
            </div>

        </div>
    </div>
</div>
