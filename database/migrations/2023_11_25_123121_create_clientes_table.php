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
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('apellido');
            $table->string('email')->unique();
            $table->string('tipo_documento');
            $table->string('documento');
            $table->string('estado_civil');
            $table->string('profesion');
            $table->string('sexo');
            $table->date('fecha_nacimiento');
            $table->string('estado')->default('verde');
            $table->integer('usuario_relacionado');
            $table->integer('creado_por');
            $table->integer('estatus')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
