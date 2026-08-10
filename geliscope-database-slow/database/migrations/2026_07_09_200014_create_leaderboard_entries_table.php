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
        Schema::create('leaderboard_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
            $table->string('academic_year');
            $table->tinyInteger('term');
            $table->decimal('total_score', 7, 2);
            $table->unsignedInteger('rank');
            $table->timestamps();

            $table->index(['subject_id', 'academic_year', 'term'], 'lb_subj_yr_term_idx');
            $table->unique(['student_id', 'subject_id', 'academic_year', 'term'], 'lb_stud_subj_yr_term_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leaderboard_entries');
    }
};
