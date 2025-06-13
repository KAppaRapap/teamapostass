<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('group_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('type');
            $table->string('description');
            $table->json('data')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
            $table->index(['group_id', 'created_at']);
            $table->index('type');
        });
    }

    public function down()
    {
        Schema::dropIfExists('activities');
    }
}; 