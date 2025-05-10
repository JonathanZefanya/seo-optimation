<?php defined('ALTUMCODE') || die() ?>

<div>
    <div class="form-group custom-control custom-switch">
        <input id="is_enabled" name="is_enabled" type="checkbox" class="custom-control-input" <?= settings()->tools->is_enabled ? 'checked="checked"' : null?>>
        <label class="custom-control-label" for="is_enabled"><i class="fas fa-fw fa-sm fa-dot-circle text-muted mr-1"></i> <?= l('admin_settings.tools.is_enabled') ?></label>
    </div>

    <div class="form-group">
        <label for="access"><?= l('admin_settings.tools.access') ?></label>
        <select id="access" name="access" class="custom-select">
            <option value="everyone" <?= settings()->tools->access == 'everyone' ? 'selected="selected"' : null ?>><?= l('admin_settings.tools.access_everyone') ?></option>
            <option value="users" <?= settings()->tools->access == 'users' ? 'selected="selected"' : null ?>><?= l('admin_settings.tools.access_users') ?></option>
        </select>
    </div>

    <div class="form-group custom-control custom-switch">
        <input id="extra_content_is_enabled" name="extra_content_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->tools->extra_content_is_enabled ? 'checked="checked"' : null?>>
        <label class="custom-control-label" for="extra_content_is_enabled"><i class="fas fa-fw fa-sm fa-paragraph text-muted mr-1"></i> <?= l('admin_settings.tools.extra_content_is_enabled') ?></label>
    </div>

    <div class="form-group custom-control custom-switch">
        <input id="share_is_enabled" name="share_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->tools->share_is_enabled ? 'checked="checked"' : null?>>
        <label class="custom-control-label" for="share_is_enabled"><i class="fas fa-fw fa-sm fa-share-alt text-muted mr-1"></i> <?= l('admin_settings.tools.share_is_enabled') ?></label>
    </div>

    <div class="form-group custom-control custom-switch">
        <input id="popular_widget_is_enabled" name="popular_widget_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->tools->popular_widget_is_enabled ? 'checked="checked"' : null?>>
        <label class="custom-control-label" for="popular_widget_is_enabled"><i class="fas fa-fw fa-sm fa-fire text-muted mr-1"></i> <?= l('admin_settings.tools.popular_widget_is_enabled') ?></label>
    </div>

    <div class="form-group custom-control custom-switch">
        <input id="similar_widget_is_enabled" name="similar_widget_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->tools->similar_widget_is_enabled ? 'checked="checked"' : null?>>
        <label class="custom-control-label" for="similar_widget_is_enabled"><i class="fas fa-fw fa-sm fa-clone text-muted mr-1"></i> <?= l('admin_settings.tools.similar_widget_is_enabled') ?></label>
    </div>

    <div class="form-group custom-control custom-switch">
        <input id="views_is_enabled" name="views_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->tools->views_is_enabled ? 'checked="checked"' : null?>>
        <label class="custom-control-label" for="views_is_enabled"><i class="fas fa-fw fa-sm fa-eye text-muted mr-1"></i> <?= l('admin_settings.tools.views_is_enabled') ?></label>
    </div>

    <div class="form-group custom-control custom-switch">
        <input id="submissions_is_enabled" name="submissions_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->tools->submissions_is_enabled ? 'checked="checked"' : null?>>
        <label class="custom-control-label" for="submissions_is_enabled"><i class="fas fa-fw fa-sm fa-check text-muted mr-1"></i> <?= l('admin_settings.tools.submissions_is_enabled') ?></label>
    </div>

    <div class="form-group custom-control custom-switch">
        <input id="last_submissions_is_enabled" name="last_submissions_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->tools->last_submissions_is_enabled ? 'checked="checked"' : null?>>
        <label class="custom-control-label" for="last_submissions_is_enabled"><i class="fas fa-fw fa-sm fa-table text-muted mr-1"></i> <?= l('admin_settings.tools.last_submissions_is_enabled') ?></label>
    </div>

    <div class="form-group mt-5">
        <?php $tools = require APP_PATH . 'includes/tools.php'; ?>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="h5"><?= l('admin_settings.tools.available_tools') . ' (' . count($tools) . ')' ?></h3>

            <div>
                <button type="button" class="btn btn-sm btn-light" data-toggle="tooltip" title="<?= l('global.select_all') ?>" data-tooltip-hide-on-click onclick="document.querySelectorAll(`[name='available_tools[]']`).forEach(element => element.checked ? null : element.checked = true)"><i class="fas fa-fw fa-check-square"></i></button>
                <button type="button" class="btn btn-sm btn-light" data-toggle="tooltip" title="<?= l('global.deselect_all') ?>" data-tooltip-hide-on-click onclick="document.querySelectorAll(`[name='available_tools[]']`).forEach(element => element.checked ? element.checked = false : null)"><i class="fas fa-fw fa-minus-square"></i></button>
            </div>
        </div>

        <div class="row">
            <?php foreach($tools as $key => $value): ?>
                <div class="col-12 col-lg-6">
                    <div class="custom-control custom-checkbox my-2">
                        <input id="<?= 'tool_' . $key ?>" name="available_tools[]" value="<?= $key ?>" type="checkbox" class="custom-control-input" <?= settings()->tools->available_tools->{$key} ? 'checked="checked"' : null ?>>
                        <label class="custom-control-label d-flex align-items-center" for="<?= 'tool_' . $key ?>">
                            <?= l('tools.' . $key . '.name') ?>
                        </label>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
    </div>
</div>

<button type="submit" name="submit" class="btn btn-lg btn-block btn-primary mt-4"><?= l('global.update') ?></button>
