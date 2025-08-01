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
        Schema::create('cliente_imagenes', function (Blueprint $table) {
            $table->id();
            $table->integer('id_cliente');
            $table->string('imagen');
            $table->string('order');
            $table->string('title');
            $table->string('alt');
            $table->integer('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cliente_imagenes');
    }
};
