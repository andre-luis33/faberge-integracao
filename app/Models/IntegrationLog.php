<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IntegrationLog extends Model
{
   use HasFactory;

   protected $fillable = [
      'linx_payload',
      'linx_response',
      'linx_status_code',
      'cilia_payload',
      'cilia_response',
      'cilia_status_code',
      'app_error',
      'forced_execution',
      'company_id'
   ];

}
