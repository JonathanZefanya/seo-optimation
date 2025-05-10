<?php defined('ALTUMCODE') || die() ?>

<div>
    <?php foreach(require APP_PATH . 'includes/available_notification_handlers.php' as $type => $value): ?>
        <div class="form-group custom-control custom-switch">
            <input id="<?= $type . '_is_enabled' ?>" name="<?= $type . '_is_enabled' ?>" type="checkbox" class="custom-control-input" <?= settings()->notification_handlers->{$type . '_is_enabled'} ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="<?= $type . '_is_enabled' ?>">
                <i class="<?= $value['icon'] ?> fa-fw fa-sm text-muted mr-1"></i> <?= sprintf(l('admin_settings.notification_handlers.is_enabled'), l('notification_handlers.input.type_' . $type)) ?>
            </label>
        </div>
    <?php endforeach ?>

    <h2 class="h5 mt-5 mb-4"><?= l('notification_handlers.input.type_twilio') . ' & ' . l('notification_handlers.input.type_twilio_call') ?></h2>
    <div class="form-group">
        <label for="twilio_sid"><?= l('admin_settings.notification_handlers.twilio_sid') ?></label>
        <input id="twilio_sid" type="text" name="twilio_sid" class="form-control" value="<?= settings()->notification_handlers->twilio_sid ?>" />
    </div>

    <div class="form-group">
        <label for="twilio_token"><?= l('admin_settings.notification_handlers.twilio_token') ?></label>
        <input id="twilio_token" type="text" name="twilio_token" class="form-control" value="<?= settings()->notification_handlers->twilio_token ?>" />
    </div>

    <div class="form-group">
        <label for="twilio_number"><?= l('admin_settings.notification_handlers.twilio_number') ?></label>
        <input id="twilio_number" type="text" name="twilio_number" class="form-control" value="<?= settings()->notification_handlers->twilio_number ?>" />
    </div>

    <h2 class="h5 mt-5 mb-4"><?= l('notification_handlers.input.type_whatsapp') ?></h2>
    <div class="form-group">
        <label for="whatsapp_number_id"><?= l('admin_settings.notification_handlers.whatsapp_number_id') ?></label>
        <input id="whatsapp_number_id" type="text" name="whatsapp_number_id" class="form-control" value="<?= settings()->notification_handlers->whatsapp_number_id ?>" />
    </div>

    <div class="form-group">
        <label for="whatsapp_access_token"><?= l('admin_settings.notification_handlers.whatsapp_access_token') ?></label>
        <input id="whatsapp_access_token" type="text" name="whatsapp_access_token" class="form-control" value="<?= settings()->notification_handlers->whatsapp_access_token ?>" />
    </div>
</div>

<button type="submit" name="submit" class="btn btn-lg btn-block btn-primary mt-4"><?= l('global.update') ?></button>
