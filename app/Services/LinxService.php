<?php

namespace App\Services;
use App\Dtos\LinxAuthResponseDto;
use App\Dtos\LinxStockPartDto;
use App\Dtos\LinxStockResponseDto;
use Exception;
use GuzzleHttp\Exception\ConnectException;
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

      $i = 0;
      $retryCount = 3;
      $secondsRetryDelay = 1;

      while($i < $retryCount) {

         try {

            $response = $this->httpClient->post("{$this->baseUrl}/api-seguranca/token", [
               'headers' => [
                  'Ocp-Apim-Subscription-Key' => $subscriptionKey
               ],
               'form_params' => [
                  'username' => $username,
                  'password' => $password
               ]
            ]);

            break;

         } catch (ConnectException $e) {

            if ($i < $retryCount - 1) {
               usleep($secondsRetryDelay);
               $i++;
               continue;
            }

            throw $e;
         }

      }

      $json = json_decode($response->getBody());
      $this->accessToken = $json->access_token;

      return new LinxAuthResponseDto($response->getBody(), $response->getStatusCode());
   }

   public function getStock(string $subscriptionKey): LinxStockResponseDto {

      $response = $this->httpClient->post("{$this->baseUrl}/pecas-balcao/ConsultaPecaGerencial", [
         'headers' => [
            'Ocp-Apim-Subscription-Key' => $subscriptionKey,
            'Authorization' => "Bearer {$this->accessToken}",
         ],
         'json' => [
            'ConfiguracaoBase' => [
               'Empresa' => 1,
               'Revenda' => 1,
               'Usuario' => 0,
               'CodigoOrigem' => 0,
               'IdentificadorOrigem' => ''
            ],
            'TextoPesquisa' => '',
            'GruposPecas' => '',
            'Marcas' => '',
            'TipoTransacao' => 'P21',
            'RetiraPrecoMarkup' => false,
            'RecallFCA' => false,
            'Movimentados' => true,
            'Consultados' => true,
            'TipoPesquisa' => 'I',
            'CodigoItemParcial' => '',
            'SomenteDisponiveis' => true
         ]
      ]);

      $body = $response->getBody();
      $statusCode = $response->getStatusCode();

      if($response->getStatusCode() !== 200) {
         return new LinxStockResponseDto($body, $statusCode, null);
      }

      $stockArray = json_decode($body, true);
      $stockCollection = collect();

      foreach($stockArray as $data) {
         $part = LinxStockPartDto::fromArray($data);
         $stockCollection->push($part);
      }

      return new LinxStockResponseDto($body, $statusCode, $stockCollection);
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
