<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   /**
    * Run the migrations.
    */
   public function up(): void
   {
      Schema::create('part_groups', function (Blueprint $table) {
         $table->unsignedInteger('id', true);
         $table->string('category', 100);
         $table->string('type', 13);
         $table->unsignedInteger('user_id');
         $table->timestamps();

         $table->foreign('user_id')->references('id')->on('users');
      });
   }

   /**
    * Reverse the migrations.
    */
   public function down(): void
   {
      Schema::dropIfExists('part_groups');
   }
};
