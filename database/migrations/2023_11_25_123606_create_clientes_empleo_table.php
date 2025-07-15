<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('clientes_empleo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes');
            $table->string('nombre_empresa');
            $table->string('direccion_empresa');
            $table->string('ciudad_empresa');
            $table->string('estado_empresa');
            $table->string('telefono_empresa');
            $table->string('cargo');
            $table->string('tiempo')->nullable();
            $table->string('salario');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes_empleo');
    }
};
