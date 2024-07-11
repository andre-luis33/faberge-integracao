<?php

namespace App\Dtos;

class CiliaStockItemDto {

   public string $brand;
   public string $code;
   public string $name;
   public float $price;
   public int $deliveryTime;
   public int $stockQuantity;
   public string $state;
   public string $sku;
   public string $partGroupType;

   public function __construct(
      string $brand,
      string $code,
      string $name,
      float $price,
      int $stockQuantity,
      string $sku,
      string $state,
      int $deliveryTime,
      string $partGroupType
   ) {
      $this->brand = $brand;
      $this->code = $code;
      $this->name = $name;
      $this->price = $price;
      $this->deliveryTime = $deliveryTime;
      $this->stockQuantity = $stockQuantity;
      $this->state = $state;
      $this->sku = $sku;
      $this->partGroupType = $partGroupType;
   }
}
