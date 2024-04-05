<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClienteUsuarioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cliente_usuario', function (Blueprint $table) {
            $table->id();

            // $table->unsignedBigInteger("cliente_id");
            $table->foreignId("cliente_id")
                ->nullable()
                ->constrained("clientes")
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->foreignId("user_id")
                ->nullable()
                ->constrained("users")
                ->cascadeOnUpdate()
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cliente_usuario');
    }
}
