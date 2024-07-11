<?php

namespace App\Dtos;
use Illuminate\Support\Collection;

class LinxAuthResponseDto {

   public string $json;
   public int    $statusCode;

   public function __construct(string $json, int $statusCode) {
      $this->json = $json;
      $this->statusCode = $statusCode;
   }

}

