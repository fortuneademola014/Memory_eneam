<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('memoirs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('author_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('supervisor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('filiere_id')->constrained('filieres')->cascadeOnDelete();
            $table->foreignId('academic_year_id')->constrained('academic_years')->cascadeOnDelete();
            $table->string('title');
            $table->text('abstract');
            $table->string('pdf_path')->nullable();
            $table->string('cover_path')->nullable();
            $table->unsignedSmallInteger('pages')->default(0);
            $table->enum('status', ['soumis', 'verification', 'valide', 'rejete', 'publie'])->default('soumis');
            $table->text('rejection_reason')->nullable();
            $table->date('embargo_until')->nullable();
            $table->boolean('download_disabled')->default(true);
            $table->boolean('restricted_access')->default(false);
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('validated_at')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->unsignedBigInteger('reads_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('memoirs');
    }
};
