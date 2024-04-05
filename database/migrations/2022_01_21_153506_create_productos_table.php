<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();

            $table->string("nombre", 160);
            $table->string("linea", 160);
            $table->double('precio1', 15, 2);
            $table->double('precio2', 15, 2);
            $table->double('precio3', 15, 2);
            $table->double('precio4', 15, 2);
            $table->double('importacion', 15, 2);
            $table->integer("estado")->length(1);

            $table->timestamps();
        });
    }

    //  

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('productos');
    }
}
