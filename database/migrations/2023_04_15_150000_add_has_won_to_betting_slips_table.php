<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHasWonToBettingSlipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('betting_slips', function (Blueprint $table) {
            $table->boolean('has_won')->default(false)->after('is_checked');
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
            $table->dropColumn('has_won');
        });
    }
}
