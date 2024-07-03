<?php

namespace App\Dtos;

class SessionCompanyDto {

   public int    $id;
   public string $name;
   public string $cnpj;

   public function __construct(int $id, string $name, string $cnpj) {
      $this->id = $id;
      $this->name = $name;
      $this->cnpj = $cnpj;
   }
}
