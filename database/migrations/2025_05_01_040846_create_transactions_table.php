<?php

use App\Models\User;
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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('family_id')
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
            $table->boolean(('is_income'))
                ->default(false);
            $table->foreignIdFor(User::class, 'created_by')
                ->nullable()
                ->constrained()
                ->onDelete('cascade');
            $table->foreignIdFor(User::class, 'updated_by')
                ->nullable()
                ->constrained()
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
