<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBettingSlipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('betting_slips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained()->onDelete('cascade');
            $table->json('numbers');
            $table->boolean('is_system')->default(false); // For system bets (desdobramentos)
            $table->json('system_details')->nullable(); // Details of the system bet
            $table->decimal('total_cost', 10, 2);
            $table->decimal('winnings', 15, 2)->default(0);
            $table->boolean('is_checked')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('betting_slips');
    }
}
