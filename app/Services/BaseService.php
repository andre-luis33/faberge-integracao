<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class BaseService {

   protected Client $httpClient;

   public function __construct() {
      $this->httpClient = app('GuzzleHttp\Client');
   }

   /**
    * Sets global configurations on `self::$httpClient` attribute
    */
   public function configureHttpClient(string $baseUri, float $timeout = 5.0, bool $httpErrors = false) {

      $this->httpClient = new Client([
         'base_uri' => $baseUri,
         'timeout' => $timeout,
         'http_errors' => $httpErrors
      ]);

   }

}
