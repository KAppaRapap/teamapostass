<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('betting_slips', function (Blueprint $table) {
            $table->decimal('virtual_amount', 10, 2)->default(0)->after('numbers');
        });
    }

    public function down()
    {
        Schema::table('betting_slips', function (Blueprint $table) {
            $table->dropColumn('virtual_amount');
        });
    }
};
