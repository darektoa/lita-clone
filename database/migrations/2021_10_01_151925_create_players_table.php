<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlayersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->bigInteger('coin')->default(0);
            $table->bigInteger('balance')->default(0);
            $table->float('rate', 3, 2)->default(0);
            $table->text('voice')->nullable();
            $table->smallInteger('is_pro_player')->default(0);
            $table->string('referral_code', 10)->unique();
            $table->string('referral_code_join', 10)->nullable();
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
        Schema::dropIfExists('players');
    }
}
