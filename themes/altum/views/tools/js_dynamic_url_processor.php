<?php defined('ALTUMCODE') || die() ?>

<?php ob_start() ?>
<script>
    'use strict';

    let current_url = <?= json_encode(url('tools/' . str_replace('_', '-', \Altum\Router::$method))) ?>;
    <?php if(!empty($_POST)): ?>
    let query_parameters = <?= json_encode(http_build_query($data->values)) ?>;
    history.pushState(null, null, `${current_url}?${query_parameters}`);
    <?php endif ?>
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>

