<?php

namespace App\Dtos;

class SessionDto {

   public int    $userId;
   public string $userName;
   public string $userEmail;
   public bool   $sidebarClosed;
   public SessionCompanyDto $company;

   /** @var SessionCompanyDto[] */
   public Array $companies;

   /**
    * @param SessionCompanyDto[] $companies
    */
   public function __construct(int $userId, string $userName, string $userEmail, SessionCompanyDto $company, array $companies, bool $sidebarClosed = false) {
      $this->userId        = $userId;
      $this->userName      = $userName;
      $this->userEmail     = $userEmail;
      $this->sidebarClosed = $sidebarClosed;
      $this->company       = $company;
      $this->companies     = $companies;
   }
}
