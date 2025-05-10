<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <?php if(settings()->main->breadcrumbs_is_enabled): ?>
        <nav aria-label="breadcrumb">
            <ol class="custom-breadcrumbs small">
                <li><a href="<?= url('tools') ?>"><?= l('tools.breadcrumb') ?></a> <i class="fas fa-fw fa-angle-right"></i></li>
                <li class="active" aria-current="page"><?= l('tools.website_hosting_checker.name') ?></li>
            </ol>
        </nav>
    <?php endif ?>

    <div class="row mb-4">
        <div class="col-12 col-lg d-flex align-items-center mb-3 mb-lg-0 text-truncate">
            <h1 class="h4 m-0 text-truncate"><?= l('tools.website_hosting_checker.name') ?></h1>

            <div class="ml-2">
                <span data-toggle="tooltip" title="<?= l('tools.website_hosting_checker.description') ?>">
                    <i class="fas fa-fw fa-info-circle text-muted"></i>
                </span>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">

            <form action="" method="post" role="form">
                <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" />

                <div class="form-group">
                    <label for="host"><i class="fas fa-fw fa-globe fa-sm text-muted mr-1"></i> <?= l('tools.website_hosting_checker.host') ?></label>
                    <input type="text" id="host" name="host" class="form-control <?= \Altum\Alerts::has_field_errors('host') ? 'is-invalid' : null ?>" value="<?= $data->values['host'] ?>" placeholder="<?= l('global.host_placeholder') ?>" required="required" />
                    <?= \Altum\Alerts::output_field_error('host') ?>
                </div>

                <button type="submit" name="submit" class="btn btn-block btn-primary"><?= l('global.submit') ?></button>
            </form>

        </div>
    </div>

    <?php if(isset($data->result)): ?>
        <div class="mt-4">
            <div class="table-responsive table-custom-container">
                <table class="table table-custom">
                    <tbody>

                    <?php if(isset($data->result->country)): ?>
                        <tr>
                            <td class="font-weight-bold">
                                <?= l('tools.website_hosting_checker.result.isp') ?>
                            </td>
                            <td class="text-nowrap">
                                <?= $data->result->isp ?>
                            </td>
                        </tr>
                    <?php endif ?>

                    <?php if(isset($data->result->country)): ?>
                        <tr>
                            <td class="font-weight-bold">
                                <?= l('tools.website_hosting_checker.result.org') ?>
                            </td>
                            <td class="text-nowrap">
                                <?= $data->result->org ?>
                            </td>
                        </tr>
                    <?php endif ?>

                    <?php if(isset($data->result->country)): ?>
                        <tr>
                            <td class="font-weight-bold">
                                <?= l('global.country') ?>
                            </td>
                            <td class="text-nowrap">
                                <img src="<?= ASSETS_FULL_URL . 'images/countries/' . mb_strtolower($data->result->countryCode) . '.svg' ?>" class="img-fluid icon-favicon mr-1" /> <?= get_country_from_country_code($data->result->countryCode) ?>
                            </td>
                        </tr>
                    <?php endif ?>

                    <?php if(isset($data->result->city)): ?>
                        <tr>
                            <td class="font-weight-bold">
                                <?= l('global.city') ?>
                            </td>
                            <td class="text-nowrap">
                                <?= $data->result->city ?>
                            </td>
                        </tr>
                    <?php endif ?>

                    <?php if(isset($data->result->lat)): ?>
                        <tr>
                            <td class="font-weight-bold">
                                <?= l('tools.website_hosting_checker.result.latitude') ?>
                            </td>
                            <td class="text-nowrap">
                                <?= $data->result->lat ?>
                            </td>
                        </tr>

                        <tr>
                            <td class="font-weight-bold">
                                <?= l('tools.website_hosting_checker.result.longitude') ?>
                            </td>
                            <td class="text-nowrap">
                                <?= $data->result->lon ?>
                            </td>
                        </tr>
                    <?php endif ?>


                    <?php if(isset($data->result->timezone)): ?>
                        <tr>
                            <td class="font-weight-bold">
                                <?= l('tools.website_hosting_checker.result.timezone') ?>
                            </td>
                            <td class="text-nowrap">
                                <?= $data->result->timezone ?>
                            </td>
                        </tr>
                    <?php endif ?>

                    </tbody>
                </table>
            </div>
        </div>
    <?php endif ?>

    <?php if(settings()->tools->last_submissions_is_enabled && isset($data->tools_usage[\Altum\Router::$method]) && !empty((array) $data->tools_usage[\Altum\Router::$method]->data)): ?>
        <div class="mt-5">
            <h2 class="h4 mb-4"><?= l('tools.last_submissions') ?></h2>
            <div class="card">
                <div class="card-body">

                    <div class="row">
                        <?php foreach((array) $data->tools_usage[\Altum\Router::$method]->data as $key => $value): ?>
                            <div class="col-12 col-lg-6">
                                <div class="text-truncate my-2">
                                    <a href="<?= url('tools/' . \Altum\Router::$method . '?submit=1' . \Altum\Csrf::get_url_query() . '&' . http_build_query((array) $value)) ?>">
                                        <img referrerpolicy="no-referrer" src="<?= get_favicon_url_from_domain($value->host) ?>" class="img-fluid icon-favicon mr-1" loading="lazy" />

                                        <?= $value->host ?>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach ?>
                    </div>

                </div>
            </div>
        </div>

        <?php require_once THEME_PATH . 'views/tools/js_dynamic_url_processor.php' ?>
    <?php endif ?>

    <?= $this->views['extra_content'] ?>

    <?= $this->views['similar_tools'] ?>

    <?= $this->views['popular_tools'] ?>
</div>

