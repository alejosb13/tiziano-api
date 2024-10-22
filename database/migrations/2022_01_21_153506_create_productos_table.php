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

            $table->string("descripcion", 160);
            $table->integer('cantidad');
            $table->string("codigo", 20);
            $table->string("color", 20);

            $table->double('precio', 15, 2);
            $table->integer("estado")->length(1)->default(1);

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
