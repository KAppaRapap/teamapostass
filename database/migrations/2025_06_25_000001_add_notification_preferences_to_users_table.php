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
            $table->boolean('notify_new_draws')->default(false);
            $table->boolean('notify_results')->default(false);
            $table->boolean('notify_group_activities')->default(false);
            $table->boolean('email_notifications')->default(false);
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
            $table->dropColumn(['notify_new_draws', 'notify_results', 'notify_group_activities', 'email_notifications']);
        });
    }
}; 