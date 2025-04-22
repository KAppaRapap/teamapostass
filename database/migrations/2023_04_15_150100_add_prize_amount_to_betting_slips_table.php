<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPrizeAmountToBettingSlipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('betting_slips', function (Blueprint $table) {
            $table->decimal('prize_amount', 15, 2)->default(0)->after('winnings');
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
            $table->dropColumn('prize_amount');
        });
    }
}
