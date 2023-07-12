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
		Schema::create('quote_user', function (Blueprint $table) {
			$table->id();
			$table->boolean('likes')->default(false);
			$table->string('user_id')->references('id')->on('users')->onDelete('cascade');
			$table->string('quote_id')->references('id')->on('quotes')->onDelete('cascade');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('quote_user');
	}
};
