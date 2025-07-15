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
        Schema::create('saldo', function (Blueprint $table) {
            $table->id();
            $table->integer('cliente_id');
            $table->date('fecha');
            $table->decimal('monto');
            $table->integer('tipo');
            $table->text('observaciones')->nullable();
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
        Schema::dropIfExists('saldo');
    }
};
