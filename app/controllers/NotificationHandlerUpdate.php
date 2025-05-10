<?php
/*
 * Copyright (c) 2025 AltumCode (https://altumcode.com/)
 *
 * This software is licensed exclusively by AltumCode and is sold only via https://altumcode.com/.
 * Unauthorized distribution, modification, or use of this software without a valid license is not permitted and may be subject to applicable legal actions.
 *
 * 🌍 View all other existing AltumCode projects via https://altumcode.com/
 * 📧 Get in touch for support or general queries via https://altumcode.com/contact
 * 📤 Download the latest version via https://altumcode.com/downloads
 *
 * 🐦 X/Twitter: https://x.com/AltumCode
 * 📘 Facebook: https://facebook.com/altumcode
 * 📸 Instagram: https://instagram.com/altumcode
 */

namespace Altum\Controllers;

use Altum\Alerts;

defined('ALTUMCODE') || die();

class NotificationHandlerUpdate extends Controller {

    public function index() {

        \Altum\Authentication::guard();

        /* Team checks */
        if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('update.notification_handlers')) {
            Alerts::add_info(l('global.info_message.team_no_access'));
            redirect('notification-handlers');
        }

        $notification_handler_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        if(!$notification_handler = db()->where('notification_handler_id', $notification_handler_id)->where('user_id', $this->user->user_id)->getOne('notification_handlers')) {
            redirect('notification-handlers');
        }
        $notification_handler->settings = json_decode($notification_handler->settings ?? '');

        if(!empty($_POST)) {
            $_POST['type'] = array_key_exists($_POST['type'], require APP_PATH . 'includes/notification_handlers.php') ? input_clean($_POST['type']) : null;
            $_POST['name'] = input_clean($_POST['name']);
            $_POST['is_enabled'] = (int) isset($_POST['is_enabled']);

            //ALTUMCODE:DEMO if(DEMO) if($this->user->user_id == 1) Alerts::add_error('Please create an account on the demo to test out this function.');

            /* Check for any errors */
            $required_fields = ['type', 'name'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {
                $settings = [];
                switch($_POST['type']) {
                    case 'telegram':
                        $settings['telegram'] = input_clean($_POST['telegram'], 512);
                        $settings['telegram_chat_id'] = input_clean($_POST['telegram_chat_id'], 512);
                        break;

                    case 'whatsapp':
                        $settings['whatsapp'] = (int) input_clean($_POST['whatsapp'], 32);
                        break;

                    case 'twilio':
                    case 'twilio_call':
                        $settings[$_POST['type']] = input_clean($_POST[$_POST['type']], 32);
                        break;

                    case 'x':
                        $settings['x_consumer_key'] = input_clean($_POST['x_consumer_key'], 512);
                        $settings['x_consumer_secret'] = input_clean($_POST['x_consumer_secret'], 512);
                        $settings['x_access_token'] = input_clean($_POST['x_access_token'], 512);
                        $settings['x_access_token_secret'] = input_clean($_POST['x_access_token_secret'], 512);
                        break;

                    default:
                        $settings[$_POST['type']] = input_clean($_POST[$_POST['type']], 512);
                        break;
                }
                $settings = json_encode($settings);

                /* Database query */
                db()->where('notification_handler_id', $notification_handler_id)->update('notification_handlers', [
                    'type' => $_POST['type'],
                    'name' => $_POST['name'],
                    'settings' => $settings,
                    'is_enabled' => $_POST['is_enabled'],
                    'last_datetime' => get_date(),
                ]);

                /* Set a nice success message */
                Alerts::add_success(sprintf(l('global.success_message.update1'), '<strong>' . $_POST['name'] . '</strong>'));

                /* Clear the cache */
                cache()->deleteItem('notification_handlers?user_id=' . $this->user->user_id);

                redirect('notification-handler-update/' . $notification_handler_id);
            }
        }

        /* Prepare the view */
        $data = [
            'notification_handler' => $notification_handler,
        ];

        $view = new \Altum\View('notification-handler-update/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
