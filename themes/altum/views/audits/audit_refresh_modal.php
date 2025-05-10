<?php defined('ALTUMCODE') || die() ?>

<div class="modal fade" id="audit_refresh_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-body">
                <div class="d-flex justify-content-between mb-3">
                    <h5 class="modal-title">
                        <i class="fas fa-fw fa-sm fa-retweet text-dark mr-2"></i>
                        <?= l('audit_refresh_modal.header') ?>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" title="<?= l('global.close') ?>">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <p class="text-muted"><?= l('audit_refresh_modal.subheader') ?></p>

                <form name="audit_refresh_modal" method="post" action="<?= url('audit-refresh') ?>" role="form">
                    <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" required="required" />
                    <input type="hidden" name="audit_id" value="" />

                    <div class="mt-4">
                        <button type="submit" name="submit" class="btn btn-block btn-primary"><?= l('global.submit') ?></button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<?php ob_start() ?>
<script>
    'use strict';

    /* On modal show load new data */
    $('#audit_refresh_modal').on('show.bs.modal', event => {
        let id = $(event.relatedTarget).data('audit-id');

        $(event.currentTarget).find('input[name="audit_id"]').val(id);
    });
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
