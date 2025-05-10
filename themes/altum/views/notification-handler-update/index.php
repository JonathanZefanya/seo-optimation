<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <?php if(settings()->main->breadcrumbs_is_enabled): ?>
        <nav aria-label="breadcrumb">
            <ol class="custom-breadcrumbs small">
                <li>
                    <a href="<?= url('notification-handlers') ?>"><?= l('notification_handlers.breadcrumb') ?></a><i class="fas fa-fw fa-angle-right"></i>
                </li>
                <li class="active" aria-current="page"><?= l('notification_handler_update.breadcrumb') ?></li>
            </ol>
        </nav>
    <?php endif ?>

    <div class="d-flex justify-content-between mb-4">
        <h1 class="h4 text-truncate mb-0"><i class="fas fa-fw fa-xs fa-bell mr-1"></i> <?= l('notification_handler_update.header') ?></h1>

        <?= include_view(THEME_PATH . 'views/notification-handlers/notification_handler_dropdown_button.php', ['id' => $data->notification_handler->notification_handler_id, 'resource_name' => $data->notification_handler->name]) ?>
    </div>

    <div class="card">
        <div class="card-body">

            <form action="" method="post" role="form">
                <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" />

                <div class="form-group">
                    <label for="name"><i class="fas fa-fw fa-signature fa-sm text-muted mr-1"></i> <?= l('notification_handlers.input.name') ?></label>
                    <input type="text" id="name" name="name" class="form-control" value="<?= $data->notification_handler->name ?>" required="required" />
                </div>

                <div class="form-group">
                    <label for="type"><i class="fas fa-fw fa-sm fa-fingerprint text-muted mr-1"></i> <?= l('notification_handlers.input.type') ?></label>
                    <select id="type" name="type" class="custom-select" required="required">
                        <?php $available_notification_handlers = require APP_PATH . 'includes/available_notification_handlers.php' ?>
                        <?php foreach(array_keys(require APP_PATH . 'includes/notification_handlers.php') as $notification_handler): ?>
                            <option value="<?= $notification_handler ?>" <?= $data->notification_handler->type == $notification_handler ? 'selected="selected"' : null ?>>
                                <?= $available_notification_handlers[$notification_handler]['emoji'] ?>

                                <?= l('notification_handlers.input.type_' . $notification_handler) ?>

                                <?php if($this->user->plan_settings->{'notification_handlers_' . $notification_handler . '_limit'} != -1 && ($data->total_notification_handlers[$notification_handler] ?? 0) >= $this->user->plan_settings->{'notification_handlers_' . $notification_handler . '_limit'}): ?>
                                    - <?= l('global.info_message.plan_feature_limit') ?>
                                <?php endif ?>
                            </option>

                        <?php endforeach ?>
                    </select>
                </div>

                <div class="form-group" data-type="email">
                    <label for="email"><i class="fas fa-fw fa-sm fa-envelope text-muted mr-1"></i> <?= l('notification_handlers.input.email') ?></label>
                    <input type="email" id="email" name="email" class="form-control" value="<?= $data->notification_handler->settings->email ?? '' ?>" maxlength="512" placeholder="<?= l('global.email_placeholder') ?>" required="required" />
                    <small class="text-muted"><?= l('notification_handlers.input.email_help') ?></small>
                </div>

                <div class="form-group" data-type="webhook">
                    <label for="webhook"><i class="fas fa-fw fa-sm fa-satellite-dish text-muted mr-1"></i> <?= l('notification_handlers.input.webhook') ?></label>
                    <input type="url" id="webhook" name="webhook" class="form-control" value="<?= $data->notification_handler->settings->webhook ?? '' ?>" maxlength="512" placeholder="<?= l('global.url_placeholder') ?>" required="required" />
                    <small class="text-muted"><?= l('notification_handlers.input.webhook_help') ?></small>
                </div>

                <div class="form-group" data-type="slack">
                    <label for="slack"><i class="fab fa-fw fa-sm fa-slack text-muted mr-1"></i> <?= l('notification_handlers.input.slack') ?></label>
                    <input type="url" id="slack" name="slack" class="form-control" value="<?= $data->notification_handler->settings->slack ?? '' ?>" maxlength="512" required="required" />
                    <small class="text-muted"><?= l('notification_handlers.input.slack_help') ?></small>
                </div>

                <div class="form-group" data-type="discord">
                    <label for="discord"><i class="fab fa-fw fa-sm fa-discord text-muted mr-1"></i> <?= l('notification_handlers.input.discord') ?></label>
                    <input type="url" id="discord" name="discord" class="form-control" value="<?= $data->notification_handler->settings->discord ?? '' ?>" maxlength="512" required="required" />
                    <small class="text-muted"><?= l('notification_handlers.input.discord_help') ?></small>
                </div>

                <div class="form-group" data-type="microsoft_teams">
                    <label for="microsoft_teams"><i class="fab fa-fw fa-sm fa-microsoft text-muted mr-1"></i> <?= l('notification_handlers.input.microsoft_teams') ?></label>
                    <input type="url" id="microsoft_teams" name="microsoft_teams" class="form-control" value="<?= $data->notification_handler->settings->microsoft_teams ?? '' ?>" maxlength="512" required="required" />
                    <small class="text-muted"><?= l('notification_handlers.input.microsoft_teams_help') ?></small>
                </div>

                <div class="form-group" data-type="twilio">
                    <label for="twilio"><i class="fas fa-fw fa-sm fa-sms text-muted mr-1"></i> <?= l('notification_handlers.input.twilio') ?></label>
                    <input type="tel" id="twilio" name="twilio" class="form-control" value="<?= $data->notification_handler->settings->twilio ?? '' ?>" maxlength="32" required="required" />
                    <small class="text-muted"><?= l('notification_handlers.input.twilio_help') ?></small>
                </div>

                <div class="form-group" data-type="twilio_call">
                    <label for="twilio_call"><i class="fas fa-fw fa-sm fa-sms text-muted mr-1"></i> <?= l('notification_handlers.input.twilio_call') ?></label>
                    <input type="tel" id="twilio_call" name="twilio_call" class="form-control" value="<?= $data->notification_handler->settings->twilio_call ?? '' ?>" maxlength="32" required="required" />
                    <small class="text-muted"><?= l('notification_handlers.input.twilio_call_help') ?></small>
                </div>

                <div class="form-group" data-type="telegram">
                    <label for="telegram"><i class="fab fa-fw fa-sm fa-telegram text-muted mr-1"></i> <?= l('notification_handlers.input.telegram') ?></label>
                    <input type="tel" id="telegram" name="telegram" class="form-control" value="<?= $data->notification_handler->settings->telegram ?? '' ?>" maxlength="512" required="required" />
                    <small class="text-muted"><?= l('notification_handlers.input.telegram_help') ?></small>
                </div>

                <div class="form-group" data-type="telegram">
                    <label for="telegram_chat_id"><i class="fas fa-fw fa-sm fa-comment-alt text-muted mr-1"></i> <?= l('notification_handlers.input.telegram_chat_id') ?></label>
                    <input type="tel" id="telegram_chat_id" name="telegram_chat_id" class="form-control" value="<?= $data->notification_handler->settings->telegram_chat_id ?? '' ?>" maxlength="512" required="required" />
                    <small class="text-muted"><?= l('notification_handlers.input.telegram_chat_id_help') ?></small>
                </div>

                <div class="form-group" data-type="whatsapp">
                    <label for="whatsapp"><i class="fab fa-fw fa-sm fa-whatsapp text-muted mr-1"></i> <?= l('notification_handlers.input.whatsapp') ?></label>
                    <input type="tel" id="whatsapp" name="whatsapp" class="form-control" value="<?= $data->notification_handler->settings->whatsapp ?? '' ?>" maxlength="32" required="required" />
                    <small class="text-muted"><?= l('notification_handlers.input.whatsapp_help') ?></small>
                </div>

                <div class="form-group" data-type="x">
                    <label for="x_consumer_key"><i class="fa fa-fw fa-sm fa-key text-muted mr-1"></i> <?= l('notification_handlers.input.x_consumer_key') ?></label>
                    <input type="text" id="x_consumer_key" name="x_consumer_key" class="form-control" value="<?= $data->notification_handler->settings->x_consumer_key ?? '' ?>" maxlength="512" required="required" />
                </div>

                <div class="form-group" data-type="x">
                    <label for="x_consumer_secret"><i class="fa fa-fw fa-sm fa-key text-muted mr-1"></i> <?= l('notification_handlers.input.x_consumer_secret') ?></label>
                    <input type="text" id="x_consumer_secret" name="x_consumer_secret" class="form-control" value="<?= $data->notification_handler->settings->x_consumer_secret ?? '' ?>" maxlength="512" required="required" />
                </div>

                <div class="form-group" data-type="x">
                    <label for="x_access_token"><i class="fa fa-fw fa-sm fa-key text-muted mr-1"></i> <?= l('notification_handlers.input.x_access_token') ?></label>
                    <input type="text" id="x_access_token" name="x_access_token" class="form-control" value="<?= $data->notification_handler->settings->x_access_token ?? '' ?>" maxlength="512" required="required" />
                </div>

                <div class="form-group" data-type="x">
                    <label for="x_access_token_secret"><i class="fa fa-fw fa-sm fa-key text-muted mr-1"></i> <?= l('notification_handlers.input.x_access_token_secret') ?></label>
                    <input type="text" id="x_access_token_secret" name="x_access_token_secret" class="form-control" value="<?= $data->notification_handler->settings->x_access_token_secret ?? '' ?>" maxlength="512" required="required" />
                </div>

                <?php if(\Altum\Plugin::is_active('push-notifications') && settings()->push_notifications->is_enabled): ?>
                    <div class="form-group" data-type="push_subscriber_id">
                        <label for="push_subscriber_id"><i class="fas fa-fw fa-sm fa-thumbtack text-muted mr-1"></i> <?= l('notification_handlers.input.push_subscriber_id') ?></label>
                        <select id="push_subscriber_id" name="push_subscriber_id" class="custom-select" required="required">
                            <?php $push_subscribers = db()->where('user_id', $this->user->user_id)->get('push_subscribers'); ?>
                            <?php foreach($push_subscribers as $push_subscriber): ?>
                                <option value="<?= $push_subscriber->push_subscriber_id ?>" <?= $data->notification_handler->settings->push_subscriber_id == $push_subscriber->push_subscriber_id ? 'selected="selected"' : null ?> <?= $this->user->plan_settings->{'notification_handlers_push_subscriber_id_limit'} != -1 && ($data->total_notification_handlers['push_subscriber_id'] ?? 0) >= $this->user->plan_settings->{'notification_handlers_push_subscriber_id_limit'} ? 'disabled="disabled"' : null ?>>
                                    <?= $push_subscriber->ip . ' - ' . l('global.device.' . $push_subscriber->device_type) . ' - ' . $push_subscriber->os_name . ' - ' . $push_subscriber->browser_name ?>

                                    <?php if($this->user->plan_settings->{'notification_handlers_push_subscriber_id_limit'} != -1 && ($data->total_notification_handlers['push_subscriber_id'] ?? 0) >= $this->user->plan_settings->{'notification_handlers_push_subscriber_id_limit'}): ?>
                                        - <?= l('global.info_message.plan_feature_limit') ?>
                                    <?php endif ?>
                                </option>
                            <?php endforeach ?>
                        </select>
                        <small class="text-muted"><?= l('notification_handlers.input.push_subscriber_id_help') ?></small>
                    </div>
                <?php endif ?>

                <div class="form-group custom-control custom-switch">
                    <input id="is_enabled" name="is_enabled" type="checkbox" class="custom-control-input" <?= $data->notification_handler->is_enabled ? 'checked="checked"' : null?>>
                    <label class="custom-control-label" for="is_enabled"><i class="fas fa-fw fa-sm fa-dot-circle text-muted mr-1"></i> <?= l('notification_handlers.input.is_enabled') ?></label>
                </div>

                <button type="submit" name="submit" class="btn btn-block btn-primary"><?= l('global.update') ?></button>
            </form>

        </div>
    </div>
</div>

<?php ob_start() ?>
<script>
    'use strict';

    type_handler('select[name="type"]', 'data-type');
    document.querySelector('select[name="type"]') && document.querySelectorAll('select[name="type"]').forEach(element => element.addEventListener('change', () => { type_handler('select[name="type"]', 'data-type'); }));
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
