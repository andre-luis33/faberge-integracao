<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryTime extends Model
{
   use HasFactory;

   protected $fillable = [
      'uf',
      'days',
      'company_id',
   ];

   function findStatesWithDeliveryByCompanyId(int $companyId) {
      return $this
         ->select(['uf', 'days'])
         ->where([
            'company_id' => $companyId,
            ['days', '!=', null]
         ])
         ->get();
   }
}
