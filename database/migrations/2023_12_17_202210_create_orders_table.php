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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable()->constrained()->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('reservation_id')->nullable()->constrained()->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('table_id')->nullable()->constrained()->cascadeOnUpdate()->nullOnDelete();
            $table->float('total')->nullable();
            $table->float('paid_amount')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->string('unique_id')->nullable();
            $table->string('checkout_method')->nullable();
            $table->float('tax_percentage')->nullable();
            $table->float('service_percentage')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('orders');
        Schema::enableForeignKeyConstraints();
    }
};
