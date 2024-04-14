<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAjustesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ajustes', function (Blueprint $table) {
            $table->ipAddress('clienteIP')->primary();
            $table->string('imagenFondo')->nullable();
            $table->string('colorFondoA')->nullable();
            $table->string('colorFondoB')->nullable();
            $table->enum('tipo', ['imagen', 'color', 'predef'])->default('imagen');
            $table->string('boxSize')->nullable();
            $table->string('boxColor')->nullable();
            $table->datetime('fechaCreacion');
            $table->datetime('fechaActualizacion')->nullable();
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
        Schema::dropIfExists('ajustes');
    }
}
