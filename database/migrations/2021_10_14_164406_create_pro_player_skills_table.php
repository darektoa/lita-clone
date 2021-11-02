<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProPlayerSkillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pro_player_skills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('player_id')->constrained();
            $table->foreignId('game_id')->constrained();
            $table->foreignId('tier_id')->nullable()->constrained();
            $table->string('game_user_id', 20);
            $table->string('game_tier', 50);
            $table->string('game_roles');
            $table->smallInteger('game_level');
            $table->float('rate', 3, 2)->default(0);
            $table->string('bio')->nullable();
            $table->text('voice')->nullable();
            $table->smallInteger('status')->default(0);
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
        Schema::dropIfExists('pro_player_skills');
    }
}
