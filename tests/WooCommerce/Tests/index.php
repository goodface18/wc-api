<?php
require __DIR__ . '/vendor/autoload.php';

use Automattic\WooCommerce\Client;

$woocommerce = new Client(
    'http://example.com',
    'ck_XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX',
    'cs_XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX',
    [
        'version' => 'wc/v3',
    ]
);
