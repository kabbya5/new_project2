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
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->foreignId('provider_id')->nullable()->constrained('providers')->onDelete('cascade');
            $table->string('english_name');
            $table->string('slug');
            $table->string('bangla_name');
            $table->string('hindi_name');
            $table->integer('position')->nullable();
            $table->integer('popularity')->default(1);
            $table->string('url');
            $table->string('thumbnail')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
