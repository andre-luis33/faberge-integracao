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
      Schema::create('integration_settings', function (Blueprint $table) {
         $table->unsignedInteger('id', true);
         $table->tinyInteger('interval')->default(true);
         $table->string('linx_user', 50)->default(true);
         $table->string('linx_password', 500)->default(true);
         $table->string('cilia_token', 500)->default(true);
         $table->unsignedInteger('user_id');
         $table->boolean('enabled')->default(true);
         $table->timestamps();

         $table->foreign('user_id')->references('id')->on('users');
      });
   }

   /**
    * Reverse the migrations.
    */
   public function down(): void
   {
      Schema::dropIfExists('integration_settings');
   }
};
