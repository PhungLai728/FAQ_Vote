<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('votes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('voteable_id');
            $table->string('voteable_type');
            $table->enum('type', ['up', 'down']);
//            $table->string('status');
//            $table->softDeletes();
            $table->timestamps();
//            $table->unique(['user_id', 'votable_id', 'votable_type']);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');//->onUpdate('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('votes');
    }
}
