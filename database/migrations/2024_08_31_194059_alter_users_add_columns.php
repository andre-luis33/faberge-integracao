<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {


   public function up(): void
   {
      Schema::table('users', function(Blueprint $table) {
         $table->string('logo_url', 500)->nullable();
         $table->boolean('admin')->default(false);
         $table->boolean('active')->default(true);
      });
   }

   /**
    * Reverse the migrations.
    */
   public function down(): void
   {
      Schema::table('users', function(Blueprint $table) {
         $table->dropColumn(['admin', 'logo_url', 'active']);
      });
   }
};
