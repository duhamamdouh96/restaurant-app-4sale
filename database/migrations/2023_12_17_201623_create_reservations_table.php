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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable()->constrained()->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('table_id')->nullable()->constrained()->cascadeOnUpdate()->nullOnDelete();
            $table->timestamp('from_date_time')->nullable();
            $table->timestamp('to_date_time')->nullable();
            $table->integer('guests_count')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('reservations');
        Schema::enableForeignKeyConstraints();
    }
};
