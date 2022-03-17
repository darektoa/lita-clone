<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProPlayerOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pro_player_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('player_id')->constrained();
            $table->foreignId('pro_player_skill_id')->constrained();
            $table->foreignId('pro_player_service_id')->nullable()->constrained();
            $table->bigInteger('coin');
            $table->bigInteger('balance');
            $table->smallInteger('status');
            $table->smallInteger('quantity');
            $table->string('rejected_reason')->nullable();
            $table->smallInteger('expiry_duration');
            $table->smallInteger('play_duration')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->timestamp('expired_at')->nullable();
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
        Schema::dropIfExists('pro_player_orders');
    }
}
