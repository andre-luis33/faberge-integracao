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
      Schema::create('delivery_times', function (Blueprint $table) {
         $table->unsignedInteger('id', true);
         $table->string('uf', 2);
         $table->unsignedTinyInteger('days')->nullable();
         $table->unsignedInteger('user_id');
         $table->timestamps();

         $table->unique(['uf', 'user_id']);
         $table->foreign('user_id')->references('id')->on('users');
      });
   }

   /**
    * Reverse the migrations.
    */
   public function down(): void
   {
      Schema::dropIfExists('delivery_time');
   }
};
