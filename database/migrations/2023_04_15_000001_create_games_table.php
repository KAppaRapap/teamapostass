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
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('type'); // 'crash', 'dice', 'bombmine', etc.
            $table->decimal('min_bet', 10, 2)->default(0.01);
            $table->decimal('max_bet', 10, 2)->default(10000.00);
            $table->decimal('house_edge', 5, 2)->default(2.50);
            $table->boolean('is_active')->default(true);
            $table->string('image_url')->nullable();
            $table->json('settings')->nullable(); // Configurações específicas do jogo
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};