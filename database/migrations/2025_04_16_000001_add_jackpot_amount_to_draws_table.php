<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('draws', function (Blueprint $table) {
            // Adiciona a coluna jackpot_amount se não existir
            if (!Schema::hasColumn('draws', 'jackpot_amount')) {
                $table->decimal('jackpot_amount', 18, 2)->nullable()->after('draw_date');
            }
            // Opcional: migrar valores antigos de jackpot para jackpot_amount
            if (Schema::hasColumn('draws', 'jackpot')) {
                $table->renameColumn('jackpot', 'jackpot_old');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('draws', function (Blueprint $table) {
            if (Schema::hasColumn('draws', 'jackpot_amount')) {
                $table->dropColumn('jackpot_amount');
            }
            if (Schema::hasColumn('draws', 'jackpot_old')) {
                $table->renameColumn('jackpot_old', 'jackpot');
            }
        });
    }
};
