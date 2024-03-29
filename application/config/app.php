<?php

defined('BASEPATH') OR exit('No direct script access allowed');

//don't change or add new config in this file

$config['app_version'] = '1.43.214';

define("TOKEN_EXPIRY", 43200); //12hrs

$config['app_update_url'] = 'http://update.erpat.app/latest/';

$config['updates_path'] = './updates/';

$config['app_csrf_exclude_uris'] = array(
    "notification_processor/create_notification",
    "paypal_ipn", "paypal_ipn/index",
    "stripe_redirect", "stripe_redirect/index",
    "pay_invoice", "pay_invoice/index",
    "google_api/save_access_token", "google_api/save_access_token_of_calendar",
    "webhooks_listener.*+",
    "cron"
);