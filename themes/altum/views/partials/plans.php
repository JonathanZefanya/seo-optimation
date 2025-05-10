<?php defined('ALTUMCODE') || die() ?>

<?php if(settings()->payment->is_enabled): ?>

    <?php
    $plans = [];
    $available_payment_frequencies = [];

    $plans = (new \Altum\Models\Plan())->get_plans();

    foreach($plans as $plan) {
        if($plan->status != 1) continue;

        foreach(['monthly', 'annual', 'lifetime'] as $value) {
            if($plan->prices->{$value}->{currency()}) {
                $available_payment_frequencies[$value] = true;
            }
        }
    }
    ?>

    <?php if(count($plans)): ?>
        <?php if(\Altum\Router::$controller_settings['currency_switcher'] && count((array) settings()->payment->currencies ?? []) > 1): ?>
            <div class="mb-3 text-center">
                <div class="dropdown mb-2 ml-lg-3">
                    <span class="font-weight-bold small mr-3"><?= l('global.choose_currency') ?></span>

                    <button type="button" class="btn btn-sm rounded-2x btn-light py-2 px-3" id="currency_switch" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-fw fa-sm fa-coins mr-1"></i> <?= currency() ?>
                    </button>

                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="currency_switch">
                        <?php foreach((array) settings()->payment->currencies as $currency => $currency_data): ?>
                            <a href="#" class="dropdown-item" data-set-currency="<?= $currency ?>">
                                <?php if($currency == currency()): ?>
                                    <i class="fas fa-fw fa-sm fa-check mr-2 text-success"></i>
                                <?php else: ?>
                                    <span class="fas fa-fw text-muted mr-2"><?= $currency_data->symbol ?: '&nbsp;' ?></span>
                                <?php endif ?>

                                <?= $currency ?>
                            </a>
                        <?php endforeach ?>
                    </div>
                </div>
            </div>
        <?php endif ?>

        <div class="mb-5 text-center">
            <div class="btn-group btn-group-toggle btn-group-custom" data-toggle="buttons">

                <?php if(isset($available_payment_frequencies['monthly'])): ?>
                    <label class="btn <?= settings()->payment->default_payment_frequency == 'monthly' ? 'active' : null ?>" data-payment-frequency="monthly">
                        <input type="radio" name="payment_frequency" <?= settings()->payment->default_payment_frequency == 'monthly' ? 'checked="checked"' : null ?>> <?= l('plan.custom_plan.monthly') ?>
                    </label>
                <?php endif ?>

                <?php if(isset($available_payment_frequencies['annual'])): ?>
                    <label class="btn <?= settings()->payment->default_payment_frequency == 'annual' ? 'active' : null ?>" data-payment-frequency="annual">
                        <input type="radio" name="payment_frequency" <?= settings()->payment->default_payment_frequency == 'annual' ? 'checked="checked"' : null ?>> <?= l('plan.custom_plan.annual') ?>
                    </label>
                <?php endif ?>

                <?php if(isset($available_payment_frequencies['lifetime'])): ?>
                    <label class="btn <?= settings()->payment->default_payment_frequency == 'lifetime' ? 'active' : null ?>" data-payment-frequency="lifetime">
                        <input type="radio" name="payment_frequency" <?= settings()->payment->default_payment_frequency == 'lifetime' ? 'checked="checked"' : null ?>> <?= l('plan.custom_plan.lifetime') ?>
                    </label>
                <?php endif ?>

            </div>
        </div>
    <?php endif ?>
<?php endif ?>

<div class="row justify-content-around">
    <?php if(settings()->plan_guest->status == 1): ?>

        <div class="col-12 col-lg-6 col-xl-4 mb-4">
            <div class="card pricing-card h-100" style="<?= settings()->plan_guest->color ? 'border-color: ' . settings()->plan_guest->color : null ?>">
                <div class="card-body d-flex flex-column">

                    <div class="mb-3">
                        <div class="font-weight-bold text-center text-uppercase pb-2 text-muted border-bottom border-gray-200"><?= settings()->plan_guest->translations->{\Altum\Language::$name}->name ?: settings()->plan_guest->name ?></div>
                    </div>

                    <div class="mb-4 text-center">
                        <div class="h1">
                            <?= settings()->plan_guest->translations->{\Altum\Language::$name}->price ?: settings()->plan_guest->price ?>
                        </div>
                        <div>
                            <span class="text-muted"><?= settings()->plan_guest->translations->{\Altum\Language::$name}->description ?: settings()->plan_guest->description ?></span>
                        </div>
                    </div>

                    <?= include_view(THEME_PATH . 'views/partials/plans_plan_content.php', ['plan_settings' => settings()->plan_guest->settings]) ?>
                </div>

                <div class="p-3">
                    <button type="button" class="btn btn-block btn-primary disabled" disabled="disabled"><?= l('plan.button.choose') ?></button>
                </div>
            </div>
        </div>

    <?php endif ?>

    <?php if(settings()->plan_free->status == 1): ?>

        <div class="col-12 col-lg-6 col-xl-4 mb-4">
            <div class="card pricing-card h-100" style="<?= settings()->plan_free->color ? 'border-color: ' . settings()->plan_free->color : null ?>">
                <div class="card-body d-flex flex-column">

                    <div class="mb-3">
                        <div class="font-weight-bold text-center text-uppercase pb-2 text-muted border-bottom border-gray-200"><?= settings()->plan_free->translations->{\Altum\Language::$name}->name ?: settings()->plan_free->name ?></div>
                    </div>

                    <div class="mb-4 text-center">
                        <div class="h1">
                            <?= settings()->plan_free->translations->{\Altum\Language::$name}->price ?: settings()->plan_free->price ?>
                        </div>
                        <div>
                            <span class="text-muted"><?= settings()->plan_free->translations->{\Altum\Language::$name}->description ?: settings()->plan_free->description ?></span>
                        </div>
                    </div>

                    <?= include_view(THEME_PATH . 'views/partials/plans_plan_content.php', ['plan_settings' => settings()->plan_free->settings]) ?>
                </div>

                <div class="p-3">
                    <a href="<?= url('register') ?>" class="btn btn-block btn-primary <?= is_logged_in() && $this->user->plan_id != 'free' ? 'disabled' : null ?>"><?= l('plan.button.choose') ?></a>
                </div>
            </div>
        </div>

    <?php endif ?>

    <?php if(settings()->payment->is_enabled): ?>

        <?php foreach($plans as $plan): ?>
        <?php if($plan->status != 1) continue; ?>

        <?php $annual_price_savings = ceil(($plan->prices->monthly->{currency()} * 12) - $plan->prices->annual->{currency()}); ?>

        <div
                class="col-12 col-lg-6 col-xl-4 mb-4"
                data-plan-monthly="<?= json_encode((bool) $plan->prices->monthly->{currency()}) ?>"
                data-plan-annual="<?= json_encode((bool) $plan->prices->annual->{currency()}) ?>"
                data-plan-lifetime="<?= json_encode((bool) $plan->prices->lifetime->{currency()}) ?>"
        >
            <div class="card pricing-card h-100" style="<?= $plan->color ? 'border-color: ' . $plan->color : null ?>">
                <div class="card-body d-flex flex-column">

                    <div class="mb-3 text-center pb-2 border-bottom border-gray-200">
                        <span class="font-weight-bold text-uppercase text-muted"><?= $plan->translations->{\Altum\Language::$name}->name ?: $plan->name ?></span>

                        <?php if($plan->prices->monthly->{currency()} && $annual_price_savings > 0): ?>
                            <span class="badge badge-success mx-1 d-none" data-plan-payment-frequency="annual" data-toggle="tooltip" title="<?= sprintf(l('global.plan_settings.annual_price_savings'), $annual_price_savings . ' ' . currency()) ?>">
                                <i class="fas fa-fw fa-sm fa-percentage"></i>
                            </span>
                        <?php endif ?>
                    </div>

                    <div class="mb-4 text-center">
                        <div class="h1 d-none" data-plan-payment-frequency="monthly"><?= nr($plan->prices->monthly->{currency()}, 2, false) ?></div>
                        <div class="h1 d-none" data-plan-payment-frequency="annual"><?= nr($plan->prices->annual->{currency()}, 2, false) ?></div>
                        <div class="h1 d-none" data-plan-payment-frequency="lifetime"><?= nr($plan->prices->lifetime->{currency()}, 2, false) ?></div>

                        <span class="h5 text-muted">
                            <?= currency() ?>
                        </span>

                        <div class="text-muted">
                            <?= $plan->translations->{\Altum\Language::$name}->description ?: $plan->description ?>
                        </div>
                    </div>

                    <?= include_view(THEME_PATH . 'views/partials/plans_plan_content.php', ['plan_settings' => $plan->settings]) ?>
                </div>

                <div class="p-3">
                    <a href="<?= url('register?redirect=pay/' . $plan->plan_id) ?>" class="btn btn-block btn-primary <?= is_logged_in() && $this->user->plan_id == $plan->plan_id && (new \DateTime($this->user->plan_expiration_date)) > (new \DateTime())->modify('+10 years') ? 'disabled' : null ?>">
                        <?php if(is_logged_in()): ?>
                            <?php if($this->user->plan_id == $plan->plan_id && (new \DateTime($this->user->plan_expiration_date)) > (new \DateTime())->modify('+10 years')): ?>
                                <?= l('plan.button.lifetime') ?>
                            <?php elseif(!$this->user->plan_trial_done && $plan->trial_days): ?>
                                <?= sprintf(l('plan.button.trial'), $plan->trial_days) ?>
                            <?php elseif($this->user->plan_id == $plan->plan_id): ?>
                                <?= l('plan.button.renew') ?>
                            <?php else: ?>
                                <?= l('plan.button.choose') ?>
                            <?php endif ?>
                        <?php else: ?>
                            <?php if($plan->trial_days): ?>
                                <?= sprintf(l('plan.button.trial'), $plan->trial_days) ?>
                            <?php else: ?>
                                <?= l('plan.button.choose') ?>
                            <?php endif ?>
                        <?php endif ?>
                    </a>
                </div>
            </div>
        </div>

    <?php endforeach ?>

    <?php ob_start() ?>
        <script>
            'use strict';

            let payment_frequency_handler = (event = null) => {

                let payment_frequency = null;

                if(event) {
                    payment_frequency = $(event.currentTarget).data('payment-frequency');
                } else {
                    payment_frequency = $('[name="payment_frequency"]:checked').closest('label').data('payment-frequency');
                }

                switch(payment_frequency) {
                    case 'monthly':
                        $(`[data-plan-payment-frequency="annual"]`).removeClass('d-inline-block').addClass('d-none');
                        $(`[data-plan-payment-frequency="lifetime"]`).removeClass('d-inline-block').addClass('d-none');

                        break;

                    case 'annual':
                        $(`[data-plan-payment-frequency="monthly"]`).removeClass('d-inline-block').addClass('d-none');
                        $(`[data-plan-payment-frequency="lifetime"]`).removeClass('d-inline-block').addClass('d-none');

                        break

                    case 'lifetime':
                        $(`[data-plan-payment-frequency="monthly"]`).removeClass('d-inline-block').addClass('d-none');
                        $(`[data-plan-payment-frequency="annual"]`).removeClass('d-inline-block').addClass('d-none');

                        break
                }

                $(`[data-plan-payment-frequency="${payment_frequency}"]`).addClass('d-inline-block');

                $(`[data-plan-${payment_frequency}="true"]`).removeClass('d-none').addClass('d-inline-block');
                $(`[data-plan-${payment_frequency}="false"]`).addClass('d-none').removeClass('d-inline-block');

            };

            $('[data-payment-frequency]').on('click', payment_frequency_handler);

            payment_frequency_handler();
        </script>
    <?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>

    <?php if(settings()->plan_custom->status == 1): ?>

        <div class="col-12 col-lg-6 col-xl-4 mb-4">
            <div class="card pricing-card h-100" style="<?= settings()->plan_custom->color ? 'border-color: ' . settings()->plan_custom->color : null ?>">
                <div class="card-body d-flex flex-column">

                    <div class="mb-3">
                        <div class="font-weight-bold text-center text-uppercase pb-2 text-muted border-bottom border-gray-200"><?= settings()->plan_custom->translations->{\Altum\Language::$name}->name ?: settings()->plan_custom->name ?></div>
                    </div>

                    <div class="mb-4 text-center">
                        <div class="h1">
                            <?= settings()->plan_custom->translations->{\Altum\Language::$name}->price ?: settings()->plan_custom->price ?>
                        </div>
                        <div>
                            <span class="text-muted"><?= settings()->plan_custom->translations->{\Altum\Language::$name}->description ?: settings()->plan_custom->description ?></span>
                        </div>
                    </div>

                    <?= include_view(THEME_PATH . 'views/partials/plans_plan_content.php', ['plan_settings' => settings()->plan_custom->settings]) ?>
                </div>

                <div class="p-3">
                    <a href="<?= settings()->plan_custom->custom_button_url ?>" class="btn btn-block btn-primary"><?= l('plan.button.contact') ?></a>
                </div>
            </div>
        </div>

    <?php endif ?>

    <?php endif ?>
</div>

<?php ob_start() ?>
<?php
/* Generate schema offers dynamically */
$offers = [];

if(settings()->plan_guest->status ?? null) {
    $offers[] = [
        '@type' => 'Offer',
        'name' => settings()->plan_guest->translations->{\Altum\Language::$name}->name ?: settings()->plan_guest->name,
        'availability' => 'https://schema.org/InStock',
        'url' => url('plan')
    ];
}

if(settings()->plan_free->status) {
    $offers[] = [
        '@type' => 'Offer',
        'name' => settings()->plan_free->translations->{\Altum\Language::$name}->name ?: settings()->plan_free->name,
        'availability' => 'https://schema.org/InStock',
        'url' => url('plan')
    ];
}

if(settings()->plan_custom->status) {
    $offers[] = [
        '@type' => 'Offer',
        'name' => settings()->plan_custom->translations->{\Altum\Language::$name}->name ?: settings()->plan_custom->name,
        'availability' => 'https://schema.org/InStock',
        'url' => url('plan')
    ];
}

if (settings()->payment->is_enabled) {
    foreach($plans as $plan) {
        if($plan->status != 1) continue;

        foreach(['monthly', 'annual', 'lifetime'] as $value) {
            if($plan->prices->{$value}->{currency()}) {
                $offers[] = [
                    '@type' => 'Offer',
                    'name' => $plan->translations->{\Altum\Language::$name}->name ?: $plan->name . ' - ' . l('plan.custom_plan.' . $value),
                    'price' => nr($plan->prices->{$value}->{currency()}, 2, false),
                    'priceCurrency' => currency(),
                    'availability' => 'https://schema.org/InStock',
                    'url' => url('pay/' . $plan->plan_id)
                ];
            }
        }
    }
}

?>
<script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "SoftwareApplication",
        "name": "<?= settings()->main->title ?>",
        "description": "<?= l('index.header') ?>",
        "applicationCategory": "WebApplication",
        "operatingSystem": "All",
        "url": "<?= url() ?>",
    <?php if(settings()->main->{'logo_' . \Altum\ThemeStyle::get()}): ?>
        "image": "<?= settings()->main->{'logo_' . \Altum\ThemeStyle::get() . '_full_url'} ?>",
        <?php endif ?>
    "offers": <?= json_encode($offers) ?>
    }
</script>

<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
