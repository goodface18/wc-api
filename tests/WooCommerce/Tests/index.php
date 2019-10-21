<?php
require __DIR__ . '/../../../vendor/autoload.php';

use Automattic\WooCommerce\Client;
use Automattic\WooCommerce\HttpClient\HttpClientException;

$woocommerce = new Client(
    'http://woocommerce.webexpertdev.us/',
    'ck_4630789d73c2c8334df7f772237c4297f6b66455',
    'cs_18dac969f5d4de3c4f2dbfe2eb95e0c3d8958d38',
    [
        'version' => '/v3',
        'wp_api_prefix' => '/wc-api'
    ]
);

// print_r(phpinfo());

try {
    // Array of response results.
    $results = $woocommerce->get('customers/count');
    $count = $results->count;
    $woocommerce = new Client(
        'http://woocommerce.webexpertdev.us/',
        'ck_4630789d73c2c8334df7f772237c4297f6b66455',
        'cs_18dac969f5d4de3c4f2dbfe2eb95e0c3d8958d38',
        [
            'version' => 'wc/v3',
        ]
    );
    $results = $woocommerce->getWithCount('customers');
    echo(count($result))
    var_dump($results);
} catch (HttpClientException $e) {
    echo '<pre><code>' . print_r( $e->getMessage(), true ) . '</code><pre>'; // Error message.
    echo '<pre><code>' . print_r( $e->getRequest(), true ) . '</code><pre>'; // Last request data.
    echo '<pre><code>' . print_r( $e->getResponse(), true ) . '</code><pre>'; // Last response data.
}
