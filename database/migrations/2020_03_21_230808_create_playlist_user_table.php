<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlaylistUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('playlist_user', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger("playlist_id")->index();
            $table->foreign("playlist_id")->references("id")->on("playlists")->onDelete("cascade");

            $table->unsignedInteger("user_id")->index();
            $table->foreign("user_id")->references("id")->on("users")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('playlist_user', function (Blueprint $table) {
            //
        });
    }
}
