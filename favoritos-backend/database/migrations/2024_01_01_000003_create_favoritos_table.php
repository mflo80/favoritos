<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFavoritosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('favoritos', function (Blueprint $table) {
            $table->id();
            $table->ipAddress('clienteIP');
            $table->string('url');
            $table->string('nombre', 40);
            $table->string('imagenFondo')->nullable();
            $table->string('colorFondoA')->nullable();
            $table->string('colorFondoB')->nullable();
            $table->string('colorTexto')->nullable();
            $table->enum('tipo', ['imagen', 'color', 'predef'])->default('imagen');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('favoritos');
    }
}
