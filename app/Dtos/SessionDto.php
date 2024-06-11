<?php

namespace App\Dtos;

class SessionDto {

   public int    $userId;
   public string $userName;
   public string $userEmail;

   public function __construct(int $userId, string $userName, string $userEmail) {
      $this->userId = $userId;
      $this->userName = $userName;
      $this->userEmail = $userEmail;
   }
}
