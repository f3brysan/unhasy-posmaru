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
        Schema::create('master_activities', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->integer('year')->nullable();
            $table->date('registration_start_date')->nullable();
            $table->date('registration_end_date')->nullable();
            $table->date('activity_start_date')->nullable();
            $table->date('activity_end_date')->nullable();
            $table->smallInteger('is_active')->nullable()->default(0);
            $table->time('student_report_start', 6)->nullable();
            $table->time('student_report_end', 6)->nullable();
            $table->timestamps(6);
            $table->string('updated_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_activities');
    }
};
