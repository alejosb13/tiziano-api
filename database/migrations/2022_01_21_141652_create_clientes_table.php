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
            
            $table->string("nombreCompleto",160);
            $table->string("correo",160);
            $table->unsignedBigInteger("telefono")->length(18);
            $table->string("direccion",180);
            $table->string("persona_contacto",180)->nullable();

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
