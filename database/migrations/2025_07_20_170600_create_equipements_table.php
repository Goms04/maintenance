<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEquipementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('equipements', function (Blueprint $table) {
    $table->id();
    $table->foreignId('agency_id')->constrained()->onDelete('cascade');
    $table->string('type'); // 'onduleur', 'serveur', 'switch', etc.
    $table->string('brand');
    $table->string('model');
    $table->string('serial_number')->unique();
    $table->string('part_number')->nullable();
    $table->date('installation_date');
    $table->date('warranty_end_date')->nullable();
    $table->enum('status', ['actif', 'maintenance', 'hors_service']);
    $table->timestamps();
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
        Schema::dropIfExists('equipements');
    }
}
