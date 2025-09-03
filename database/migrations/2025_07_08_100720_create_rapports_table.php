<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRapportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('rapports', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('intervention_id');
        $table->string('site');
        $table->string('materiel');
        $table->text('observations')->nullable();
        $table->text('recommandations')->nullable();
        $table->timestamps();
         $table->foreign('intervention_id')->references('id')->on('interventions')->onDelete('cascade');
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rapports');
    }
}
