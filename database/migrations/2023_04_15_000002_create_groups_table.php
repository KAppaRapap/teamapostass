<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('game_id')->constrained()->onDelete('cascade');
            $table->string('city')->nullable();
            $table->string('region')->nullable();
            $table->foreignId('admin_id')->constrained('users')->onDelete('cascade');
            $table->boolean('is_public')->default(true);
            $table->integer('max_members')->default(0); // 0 means unlimited
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
        Schema::dropIfExists('groups');
    }
}
