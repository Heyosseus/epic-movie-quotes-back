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
		Schema::create('notifications', function (Blueprint $table) {
			$table->id();
			$table->string('to');
			$table->string('from');
			$table->boolean('read')->default(false);
			$table->string('type');
			//			$table->unsignedBigInteger('notifiable_id');
			//			$table->string('notifiable_type');
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
		Schema::dropIfExists('notifications');
	}
};
