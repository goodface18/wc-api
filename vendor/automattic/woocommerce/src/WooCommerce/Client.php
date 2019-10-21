<?php
/**
 * WooCommerce REST API Client
 *
 * @category Client
 * @package  Automattic/WooCommerce
 */

namespace Automattic\WooCommerce;

use Automattic\WooCommerce\HttpClient\HttpClient;

/**
 * REST API Client class.
 *
 * @package Automattic/WooCommerce
 */
class Client
{

    /**
     * WooCommerce REST API Client version.
     */
    const VERSION = '3.0.0';

    /**
     * HttpClient instance.
     *
     * @var HttpClient
     */
    public $http;

    /**
     * Initialize client.
     *
     * @param string $url            Store URL.
     * @param string $consumerKey    Consumer key.
     * @param string $consumerSecret Consumer secret.
     * @param array  $options        Options (version, timeout, verify_ssl).
     */
    public function __construct($url, $consumerKey, $consumerSecret, $options = [])
    {
        $this->http = new HttpClient($url, $consumerKey, $consumerSecret, $options);
    }

    /**
     * POST method.
     *
     * @param string $endpoint API endpoint.
     * @param array  $data     Request data.
     *
     * @return array
     */
    public function post($endpoint, $data)
    {
        return $this->http->request($endpoint, 'POST', $data);
    }

    /**
     * PUT method.
     *
     * @param string $endpoint API endpoint.
     * @param array  $data     Request data.
     *
     * @return array
     */
    public function put($endpoint, $data)
    {
        return $this->http->request($endpoint, 'PUT', $data);
    }

    /**
     * GET method.
     *
     * @param string $endpoint   API endpoint.
     * @param array  $parameters Request parameters.
     *
     * @return array
     */
    public function get($endpoint, $parameters = [])
    {
        return $this->http->request($endpoint, 'GET', [], $parameters);
    }

    /**
     * GET with count method.
     *
     * @param string $endpoint   API endpoint.
     * @param array  $parameters Request parameters.
     *
     * @return array
     */
    public function getWithCount($endpoint, $count = 100)
    {
        $page_count = $count / 100;
        $nodes = array();
        $results_arr = array();
        $curl_arr = array();
        $master = curl_multi_init();
        $httpArray = array();
        for($i=0; $i<$page_count; $i++){
            $nodes[i] = array('page' => $i + 1, 'per_page' => 100);
            $httpArray[i] = new HttpClient($this->http->url, $this->http->consumerKey, $this->http->consumerSecret, $this->http->options);
            $httpArray[i]->multiRequest($endpoint, 'GET', [], $nodes[i], $master);
        }
        do {
            curl_multi_exec($master,$running);
        } while($running > 0);

        for($i = 0; $i < $page_count; $i++)
        {
            $results_arr[] = curl_multi_getcontent  ( $httpArray[i]->ch  );
        }
        return $results_arr;
    }

    /**
     * GETALL method.
     *
     * @param string $endpoint   API endpoint.
     * @param array  $parameters Request parameters.
     *
     * @return array
     */
    public function getAll($endpoint)
    {
        $page = 1;
        $items = [];
        $all_items = [];
        do{
          try {
            $items = $this->get($endpoint,array('per_page' => 100, 'page' => $page));
          }catch(HttpClientException $e){
            die("Can't get items: $e");
          }
          $all_items = array_merge($all_items,$items);
          $page++;
        } while (count($items) > 0);
        return $all_items;
    }

    /**
     * DELETE method.
     *
     * @param string $endpoint   API endpoint.
     * @param array  $parameters Request parameters.
     *
     * @return array
     */
    public function delete($endpoint, $parameters = [])
    {
        return $this->http->request($endpoint, 'DELETE', [], $parameters);
    }

    /**
     * OPTIONS method.
     *
     * @param string $endpoint API endpoint.
     *
     * @return array
     */
    public function options($endpoint)
    {
        return $this->http->request($endpoint, 'OPTIONS', [], []);
    }
}
