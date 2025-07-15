<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\SoftDeletes;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->decimal('precio');
            $table->text('descripcion');
            $table->string('slug');
            $table->string('sku')->nullable();
            $table->string('imagen_principal')->nullable();
            $table->string('estatus');
            $table->string('disponible');
            $table->integer('views')->default(0);
            $table->integer('user_id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
