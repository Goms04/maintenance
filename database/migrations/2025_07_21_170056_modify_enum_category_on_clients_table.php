<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
class ModifyEnumCategoryOnClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::table('clients', function (Blueprint $table) {
    DB::statement("ALTER TABLE clients MODIFY COLUMN category ENUM(
        'banque', 'assurance', 'telecom', 'gouvernement', 'prive',
        'Support', 'Maintenance', 'Installation', 'Formation', 'Consultation', 'Réparation', 'Autre'
    )");
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
        //
    }
}
