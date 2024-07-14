<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
   /**
    * Run the migrations.
    */
   public function up(): void
   {
      Schema::create('integration_executions', function (Blueprint $table) {
         $table->unsignedInteger('id', true);
         $table->text('linx_payload')->nullable();
         $table->text('linx_response')->nullable();
         $table->unsignedInteger('linx_status_code')->nullable();
         $table->text('cilia_payload')->nullable();
         $table->text('cilia_response')->nullable();
         $table->unsignedInteger('cilia_status_code')->nullable();
         $table->boolean('is_internal_error');
         $table->text('error')->nullable();
         $table->boolean('forced_execution');
         $table->datetime('start_time');
         $table->datetime('end_time');
         $table->unsignedInteger('company_id');
         $table->timestamps();

         $table->foreign('company_id')->references('id')->on('companies');
      });
   }

   /**
    * Reverse the migrations.
    */
   public function down(): void
   {
      Schema::dropIfExists('integration_executions');
   }
};
