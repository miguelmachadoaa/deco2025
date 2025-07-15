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
        Schema::create('configuration', function (Blueprint $table) {
            $table->id();
            $table->string('negocio');
            $table->text('direccion');
            $table->string('telefono');
            $table->string('email');
            $table->string('whatsapp');
            $table->integer('show_precio')->default(1);
            $table->integer('show_disponibilidad')->default(1);
            $table->integer('show_categoria')->default(1);
            $table->integer('show_destino')->default(1);
            $table->integer('show_promociones')->default(1);
            $table->integer('show_productos')->default(1);    
            $table->integer('show_categorias')->default(1);    
            $table->integer('show_sliders')->default(1);    
            $table->integer('show_videos')->default(1);    
            $table->integer('show_blog')->default(1);    
            $table->string('moneda')->default(1);
            $table->string('logo')->default(1);
            $table->string('color_principal')->default(1);
            $table->string('color_secundario')->default(1);
            $table->string('color_terciario')->default(1);
            $table->string('color_texto')->default(1);
            $table->string('color_texto_secundario')->default(1);;
            $table->string('color_texto_terciario')->default(1);;
            $table->integer('user_id')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('configuration');
    }
};
