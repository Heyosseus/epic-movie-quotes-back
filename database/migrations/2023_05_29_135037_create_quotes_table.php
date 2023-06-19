<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('quotes', function (Blueprint $table) {
			$table->id();
			$table->json('body')->nullable();
			$table->string('thumbnail')->nullable();
			$table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
			$table->foreignId('movie_id')->constrained('movies')->cascadeOnDelete();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('quotes');
	}
};
