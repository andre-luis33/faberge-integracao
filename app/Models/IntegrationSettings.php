<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IntegrationSettings extends Model
{
   use HasFactory;

   protected $fillable = [
      'interval',
      'enabled',
      'company_id',
      'linx_user',
      'linx_password',
      'cilia_token',
   ];

   protected $casts = [
      'enabled' => 'boolean'
   ];

   public function existsByCompanyId(int $companyId): bool {
      return $this->where('company_id', $companyId)->count() > 0;
   }
}
