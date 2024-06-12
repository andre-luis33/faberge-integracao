<?php

namespace App\Dtos;

class SessionDto {

   public int    $userId;
   public string $userName;
   public string $userEmail;
   public bool   $sidebarClosed;

   public function __construct(int $userId, string $userName, string $userEmail, bool $sidebarClosed = false) {
      $this->userId = $userId;
      $this->userName = $userName;
      $this->userEmail = $userEmail;
      $this->sidebarClosed = $sidebarClosed;
   }
}
