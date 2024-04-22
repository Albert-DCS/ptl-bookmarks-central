<?php

namespace App\Service;

class ApiHelper
{
    private $apiKey;
    private $baseUrl;

    // public function __construct($apiKey, $baseUrl) {
    //     $this->apiKey = $apiKey;
    //     $this->baseUrl = $baseUrl;
    // }

    public function setParameters($api_key, $base_url) {
        $this->apiKey = $api_key;
        $this->baseUrl = $base_url;
    }

    public function test() {
        return $this->apiKey;
    }

    public function get($endpoint, $params = []) {
        // Implement GET request logic
    }

}