<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUtilisateursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return 
     */
public function up()
    {
        Schema::create('utilisateurs', function (Blueprint $table) {
            $table->id();
$table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('password');

            $table->date('birth_date')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->boolean('terms')->default(false); 
            $table->timestamps();
   });
    }

    /**
     * Reverse the migrations.
     *  \
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('utilisateurs');
    }
}
