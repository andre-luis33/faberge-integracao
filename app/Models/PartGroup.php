<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartGroup extends Model
{
   use HasFactory;

   protected $fillable = [
      'category',
      'type',
      'company_id',
   ];
}
