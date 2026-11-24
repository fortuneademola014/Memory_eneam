<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reading_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('memoir_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('last_page')->default(1);
            $table->unsignedSmallInteger('total_pages')->default(1);
            $table->timestamp('last_read_at')->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'memoir_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reading_histories');
    }
};
