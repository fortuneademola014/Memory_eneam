<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('memoir_keywords', function (Blueprint $table) {
            $table->id();
            $table->foreignId('memoir_id')->constrained()->cascadeOnDelete();
            $table->string('keyword');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('memoir_keywords');
    }
};
