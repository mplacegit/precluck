<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UserProfile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
		Schema::create('user_profiles', function (Blueprint $table) {
		$table->increments('id');
		$table->integer('user_id');
		$table->float('balance')->nullable();
		$table->string('name');
		$table->string('firstname');
		$table->string('lastname');
		$table->string('email',255)->unique();
		$table->integer('manager')->nullable();
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
        //
		Schema::dropIfExists('user_profiles');
    }
}
