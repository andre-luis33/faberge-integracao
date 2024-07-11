<?php

namespace App\Dtos;
use Illuminate\Support\Collection;

class LinxStockResponseDto {

   /**
    * @var string Raw json response
    */
   public string $json;
   public int    $statusCode;

   /**
    * @var Collection<LinxStockPartDto>|null
    */
   public Collection|null $stockParts;

   public function __construct(string $json, int $statusCode, Collection|null $stockParts) {
      $this->json = $json;
      $this->statusCode = $statusCode;
      $this->stockParts = $stockParts;
   }

}

