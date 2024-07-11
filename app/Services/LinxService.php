<?php

namespace App\Services;
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
      $this->configureHttpClient($this->baseUrl);
      $this->mockJsonPath = app_path('/Mocks/stock.json');
   }

   public function auth(string $subscriptionKey, string $username, string $password): void {

      if(Cache::has($this->cacheKey)) {
         $this->accessToken = Cache::get($this->cacheKey);
         return;
      }

      $response = $this->httpClient->post("{$this->baseUrl}/api-seguranca/token", [
         'headers' => [
            'Ocp-Apim-Subscription-Key' => $subscriptionKey
         ],
         'form_params' => [
            'username' => $username,
            'password' => $password
         ]
      ]);

      if($response->getStatusCode() !== 200)
         throw new Exception($response->getBody(), $response->getStatusCode());

      $body  = json_decode($response->getBody());
      $token = $body->access_token;

      Cache::put($this->cacheKey, $token, $this->cacheTTL);
      $this->accessToken = $token;
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
