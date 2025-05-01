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
        Schema::create('later_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('family_id')
                ->nullable()
                ->constrained()
                ->onDelete('cascade');
            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->onDelete('cascade');
            $table->foreignId('category_id')
                ->constrained()
                ->onDelete('cascade');
            $table->string('description')
                ->nullable();
            $table->integer('amount')
                ->default(0);
            $table->foreignId('mode_of_payment_id')
                ->nullable()
                ->constrained()
                ->onDelete('cascade');
            $table->date('transaction_date')
                ->default(now());
            $table->integer('periods')
                ->default(1);
            $table->integer('number_period')
                ->default(1);
            $table->boolean('is_paid')
                ->default(false);
            $table->date('paid_at')
                ->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('later_transactions');
    }
};
