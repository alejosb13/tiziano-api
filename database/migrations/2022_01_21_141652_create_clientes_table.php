<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();

            $table->string("nombre", 80);
            $table->string("apellido", 80);
            $table->string("telefono")->length(20);
            $table->string("email", 60);
            $table->string("codigo", 20);
            $table->string("clasificacion", 120);
            $table->string("descripcion_clasificacion", 120);

            $table->integer("estado")->length(1)->default(1);
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
        Schema::dropIfExists('clientes');
    }
}
