<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('setup_diagrams', function (Blueprint $table) {
            $table->id();
            $table->string('use_case_path')->nullable();
            $table->string('class_diagram_path')->nullable();
            $table->text('student_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('setup_diagrams');
    }
};