<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlaylistUserAttendanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('playlist_user_attendance', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('playlist_user_id')->index();
            $table->foreign('playlist_user_id')
                ->references('id')
                ->on('playlist_user')
                ->onDelete('cascade');
            $table->boolean('attended')->default(0);
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
        Schema::table('playlist_user_attendance', function (Blueprint $table) {
            //
        });
    }
}
