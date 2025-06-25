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
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('notify_new_draws', 'notify_new_games');
            $table->renameColumn('notify_results', 'notify_winnings');
            // 'notify_group_activities' já existe e permanece igual
            $table->dropColumn('email_notifications');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('notify_new_games', 'notify_new_draws');
            $table->renameColumn('notify_winnings', 'notify_results');
            $table->boolean('email_notifications')->default(false);
        });
    }
}; 