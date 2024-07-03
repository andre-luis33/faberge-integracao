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
      Schema::create('companies', function (Blueprint $table) {
         $table->id();
         $table->string('cnpj', 14);
         $table->string('name', 100);
         $table->boolean('primary')->default(false);
         $table->boolean('active')->default(true);
         $table->unsignedInteger('user_id');
         $table->timestamps();

         $table->unique(['user_id', 'cnpj']);
         $table->foreign('user_id')->references('id')->on('users');
      });
   }

   /**
    * Reverse the migrations.
    */
   public function down(): void
   {
      Schema::dropIfExists('companies');
   }
};
