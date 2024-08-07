<?php

namespace App\Services;
use App\Dtos\CiliaSendCsvResponseDto;
use Exception;
use InvalidArgumentException;

class CiliaService extends BaseService {

   const HOMOLOG_MODE = 'hml';
   const PRODUCTION_MODE = 'prod';
   private string $baseUrl;
   private string $authToken;


   public function __construct(string $mode) {
      parent::__construct();

      if($mode === 'prod') {
         $this->baseUrl = "https://sistema.cilia.com.br";
      } else {
         $this->baseUrl = "https://qa.cilia.com.br";
      }

      $this->configureHttpClient($this->baseUrl, 30);
   }

   public function setAuthToken(string $authToken): void {
      $this->authToken = $authToken;
   }

   /**
    * @param bool $override if true, all supply pieces will be overridden
    */
   public function sendCsv(string $csvPath, bool $override = false) {

      $response = $this->httpClient->post("{$this->baseUrl}/api/integration/ext/supply_pieces/batch/file", [
         'multipart' => [
            [
               'name'     => 'keep_only_present_in_file',
               'contents' => $override ? 'true' : 'false',
            ],
            [
               'name'     => 'auth_token',
               'contents' => $this->authToken,
            ],
            [
               'name'     => 'file',
               'contents' => fopen($csvPath, 'r'),
               'filename' => 'estoque.csv',
               'headers' => [
                  'Content-Type' => 'text/csv'
               ]
            ],
         ]
      ]);

      return new CiliaSendCsvResponseDto($response->getBody(), $response->getStatusCode());
   }


}
