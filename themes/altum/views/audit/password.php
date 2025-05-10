<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <div class="card">
        <div class="card-body">

            <div class="mb-4 d-flex">
                <div>
                    <h1 class="h4"><?= l('audits.password.header')  ?></h1>
                    <span class="text-muted"><?= l('audits.password.subheader') ?></span>
                </div>
            </div>

            <?= \Altum\Alerts::output_alerts() ?>

            <form action="" method="post" role="form">
                <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" />

                <div class="form-group" data-password-toggle-view data-password-toggle-view-show="<?= l('global.show') ?>" data-password-toggle-view-hide="<?= l('global.hide') ?>">
                    <label for="password"><?= l('global.password') ?></label>
                    <input type="password" id="password" name="password" value="" class="form-control <?= \Altum\Alerts::has_field_errors('password') ? 'is-invalid' : null ?>" required="required" />
                    <?= \Altum\Alerts::output_field_error('password') ?>
                </div>

                <button type="submit" name="submit" class="btn btn-block btn-primary"><?= l('global.submit') ?></button>
            </form>

        </div>
    </div>
</div>

