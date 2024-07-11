<?php

namespace App\Services;
use App\Dtos\LinxAuthResponseDto;
use App\Dtos\LinxStockPartDto;
use App\Dtos\LinxStockResponseDto;
use Exception;
use Illuminate\Support\Facades\Cache;

class LinxService extends BaseService {

   private string $mockJsonPath;
   private string $baseUrl = "https://linxdmsapi.azure-api.net";
   private int    $cacheTTL = 900;
   private string $cacheKey = "linx_access_token";
   private string $accessToken;


   public function __construct() {
      parent::__construct();
      $this->configureHttpClient($this->baseUrl, 10);
      $this->mockJsonPath = app_path('/Mocks/stock.json');
   }

   public function auth(string $subscriptionKey, string $username, string $password): LinxAuthResponseDto {

      // if(Cache::has($this->cacheKey)) {
      //    $this->accessToken = Cache::get($this->cacheKey);
      //    return new LinxAuthResponseDto('cache', 200);
      // }

      $response = $this->httpClient->post("{$this->baseUrl}/api-seguranca/token", [
         'headers' => [
            'Ocp-Apim-Subscription-Key' => $subscriptionKey
         ],
         'form_params' => [
            'username' => $username,
            'password' => $password
         ]
      ]);

      $responseDto = new LinxAuthResponseDto($response->getBody(), $response->getStatusCode());
      if($response->getStatusCode() !== 200)
         return $responseDto;

      $body  = json_decode($response->getBody());
      $token = $body->access_token;

      // Cache::put($this->cacheKey, $token, $this->cacheTTL);
      $this->accessToken = $token;
      return $responseDto;
   }

   public function getMockStock(): LinxStockResponseDto {
      $json = file_get_contents($this->mockJsonPath);
      $array = json_decode($json, true);

      $stock = collect();

      foreach($array as $data) {
         $part = LinxStockPartDto::fromArray($data);
         $stock->push($part);
      }

      return new LinxStockResponseDto($json, 200, $stock);
   }

}
