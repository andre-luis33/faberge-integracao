<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

   public function findIntegrationsToRun() {
      $now = date('Y-m-d H:i:s');

      return $this
         ->select(['company_id'])
         ->whereRaw("
               DATEDIFF(MINUTE,
                  (SELECT MAX(il.created_at)
                  FROM integration_executions AS il
                  WHERE il.company_id = integration_settings.company_id), ?) >= integration_settings.interval
         ", [$now])
         ->get();
   }
}
