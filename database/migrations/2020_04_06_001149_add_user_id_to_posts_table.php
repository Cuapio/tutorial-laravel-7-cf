<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserIdToPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->bigInteger('user_id')->unsigned();//tiene que ser biginteger ya que esta por defecto en un campo id de la clave id en la tabla users
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');//Agregamos una relacion al id de la tabla users constrint onDelete para que se borre en todas las tablas que los contenga desde el superior
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
    }
}
