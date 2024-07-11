<?php

namespace App\Dtos;

class CiliaSendCsvResponseDto {

   /**
    * @var string Raw json response
    */
   public string $json;
   public int    $statusCode;

   public function __construct(string $json, int $statusCode) {
      $this->json = $json;
      $this->statusCode = $statusCode;
   }
}

