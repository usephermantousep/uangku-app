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
        Schema::create('mode_of_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('family_id')
                ->nullable()
                ->constrained()
                ->onDelete('cascade');
            $table->string('name');
            $table->string('description')->nullable();
            $table->boolean('is_transaction')->default(true);
            $table->timestamps();
            $table->unique(['family_id', 'name', 'is_transaction'], 'unique_family_mode_of_payment');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mode_of_payments');
    }
};
