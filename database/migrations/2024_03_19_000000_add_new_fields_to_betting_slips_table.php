<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('betting_slips', function (Blueprint $table) {
            $table->string('status')->default('pending')->after('total_cost');
            $table->string('bet_type')->default('single')->after('status');
            $table->decimal('odds', 10, 4)->nullable()->after('bet_type');
            $table->json('validation_errors')->nullable()->after('odds');
            $table->json('stars')->nullable()->after('numbers');
            $table->json('predictions')->nullable()->after('stars');
            
            // Índices para melhor performance
            $table->index('status');
            $table->index('bet_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('betting_slips', function (Blueprint $table) {
            $table->dropColumn([
                'status',
                'bet_type',
                'odds',
                'validation_errors',
                'stars',
                'predictions'
            ]);
            
            $table->dropIndex(['status']);
            $table->dropIndex(['bet_type']);
        });
    }
}; 