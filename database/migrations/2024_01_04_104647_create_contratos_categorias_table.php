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
        Schema::create('contratos_categorias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('monto');
            $table->integer('estatus')->default(1);
            $table->integer('creado_por');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contratos_categorias');
    }
};
