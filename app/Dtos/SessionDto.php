<?php

namespace App\Dtos;

class SessionDto {

   public int    $userId;
   public string $userName;
   public string $userEmail;
   public string $logoUrl;
   public bool   $admin;
   public bool   $sidebarClosed;
   public SessionCompanyDto $company;

   /** @var SessionCompanyDto[] */
   public Array $companies;

   /**
    * @param SessionCompanyDto[] $companies
    */
   public function __construct(int $userId, string $userName, string $userEmail, string $logoUrl, SessionCompanyDto|null $company, array|null $companies, bool $admin, bool $sidebarClosed = false) {
      $this->userId        = $userId;
      $this->userName      = $userName;
      $this->userEmail     = $userEmail;
      $this->logoUrl       = $logoUrl;
      $this->admin         = $admin;
      $this->sidebarClosed = $sidebarClosed;

      if($company)
         $this->company = $company;

      if($companies)
         $this->companies = $companies;
   }
}
