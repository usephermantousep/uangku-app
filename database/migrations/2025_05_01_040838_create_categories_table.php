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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('family_id')
                ->nullable()
                ->constrained()
                ->onDelete('cascade');
            $table->string('name');
            $table->string('description')->nullable();
            $table->boolean('is_income')->default(false);
            $table->timestamps();
            $table->unique(['family_id', 'name', 'is_income'], 'unique_family_category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
