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
        Schema::create('blog_categories', function(Blueprint $table)
		{
			$table->increments('id');
            $table->string('titulo');
            $table->timestamps();
            $table->softDeletes();
		});

		Schema::create('blogs', function(Blueprint $table)
		{
			$table->increments('id');
			$table->unsignedInteger('blog_category_id');
			$table->unsignedInteger('user_id');
			$table->string('titulo');
			$table->string('slug');
			$table->text('contenido');
			$table->string('idioma')->default('es');
			$table->string('imagen')->nullable();
			$table->integer('views')->default(0);
			$table->timestamps();
			$table->softDeletes();
		});

		Schema::create('blog_comments', function(Blueprint $table)
		{
			$table->increments('id');
			$table->unsignedInteger('blog_id');
			$table->string('name');
			$table->string('email');
			$table->string('website')->nullable();
			$table->text('comment');
			$table->timestamps();
			$table->softDeletes();
		});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blogs');
        Schema::dropIfExists('blog_comments');
        Schema::dropIfExists('blog_categories');
    }
};
