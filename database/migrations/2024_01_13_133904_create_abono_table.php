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
        Schema::create('abono', function (Blueprint $table) {
            $table->id();
            $table->integer('cliente_id');
            $table->date('fecha');
            $table->integer('forma_pago');
            $table->string('referencia');
            $table->decimal('monto');
            $table->string('archivo');
            $table->text('observaciones')->nullable();
            $table->integer('estatus')->default(0);
            $table->integer('creado_por');
            $table->integer('actualizado_por')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('abono');
    }
};
