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
   private string $baseUrl = "https://auto-gwsmartapi.linx.com.br";
   private int    $cacheTTL = 900;
   private string $cacheKey = "linx_access_token";
   private string $accessToken;
   /**
    * This is not used for dev/stage/prod purposes, its actually a linx api rule
    */
   private string $environment;

   public function __construct() {
      parent::__construct();
      $this->configureHttpClient($this->baseUrl, 10);
      $this->mockJsonPath = app_path('/Mocks/stock.json');
   }

   public function setEnvironment(string $environment): void {
      $this->environment = $environment;
   }

   public function auth(string $subscriptionKey, string $username, string $password): LinxAuthResponseDto {

      $i = 0;
      $retryCount = 3;
      $secondsRetryDelay = 1;

      while($i < $retryCount) {

         try {

            $response = $this->httpClient->post("{$this->baseUrl}/api-seguranca/token", [
               'headers' => [
                  'Ocp-Apim-Subscription-Key' => $subscriptionKey,
                  'Ambiente' => $this->environment
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

         } catch (Exception $e) {
            if ($i < $retryCount - 1) {
               usleep($secondsRetryDelay);
               $i++;
               continue;
            }

            throw $e;

         }

      }

      $statusCode = $response->getStatusCode();
      $body = $response->getBody();

      if($statusCode !== 200)
         return new LinxAuthResponseDto($body, $statusCode);

      $json = json_decode($body);
      $this->accessToken = $json->access_token;

      return new LinxAuthResponseDto($body, $statusCode);
   }

   public function getStock(string $subscriptionKey, string $company, string $resale): LinxStockResponseDto {

      $response = $this->httpClient->post("{$this->baseUrl}/api-pecas-balcao/ConsultaPecaGerencial", [
         'headers' => [
            'Ocp-Apim-Subscription-Key' => $subscriptionKey,
            'Ambiente' => $this->environment,
            'Authorization' => "Bearer {$this->accessToken}",
         ],
         'json' => [
            'ConfiguracaoBase' => [
               'Empresa' => $company,
               'Revenda' => $resale,
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
