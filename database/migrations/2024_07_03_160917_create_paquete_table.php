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
        Schema::create('paquete', function (Blueprint $table) {
            $table->id();
            $table->integer('destino_id');
            $table->string('titulo');
			$table->string('slug');
			$table->text('descripcion');
			$table->string('dias');
			$table->string('include');
			$table->string('noinclude');
			$table->string('informacion');
            $table->string('imagen')->nullable();
            $table->string('pais')->nullable();
            $table->string('idioma')->default('es');
			$table->integer('views')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paquete');
    }
};
