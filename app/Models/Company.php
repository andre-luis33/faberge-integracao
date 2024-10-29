<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
   use HasFactory;

   protected $fillable = [
      'cnpj',
      'name',
      'user_id',
      'primary',
      'active'
   ];

   protected $casts = [
      'primary' => 'boolean',
      'active'  => 'boolean',
   ];

   public function creator() {
      return $this->belongsTo(User::class);
   }

   public function integrationSettings() {
      return $this->hasOne(IntegrationSettings::class);
   }
}
