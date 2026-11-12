<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
   public function up(): void
   {
      Schema::create('queues', function (Blueprint $table) {
         $table->id();
         $table->integer('queue_number');
         $table->date('queue_date')->index();
         $table->enum('status', ['menunggu', 'dilayani', 'selesai', 'dilewati'])
            ->default('menunggu')
            ->index();
         $table->foreignId('served_by')
            ->nullable()
            ->constrained('users')
            ->nullOnDelete();
         $table->timestamp('called_at')->nullable();
         $table->timestamps();

         $table->unique(['queue_date', 'queue_number'], 'unique_daily_queue');
      });
   }

   public function down(): void
   {
      Schema::dropIfExists('queues');
   }
};