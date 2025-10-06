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
        Schema::create('game_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('game_id')->constrained('games')->onDelete('cascade');
            $table->string('provider');
            $table->string('game_type');
            $table->decimal('bet', 12, 2);
            $table->decimal('win', 12, 2);
            $table->decimal('before_balance', 12, 2);
            $table->decimal('after_balance', 12, 2);
            $table->string('transaction_id')->unique();
            $table->string('round_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_transactions');
    }
};
