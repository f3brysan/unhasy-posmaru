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
            $table->integer('id')->primary();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->integer('year')->nullable();
            $table->date('implementation_date')->nullable();
            $table->date('registration_start_date')->nullable();
            $table->date('registration end_date')->nullable();
            $table->date('activity_start_date')->nullable();
            $table->date('activity_end_date')->nullable();
            $table->time('student_report_time')->nullable();
            $table->smallInteger('is_active')->nullable();
            $table->timestamp('created_at', 6)->nullable();
            $table->timestamp('updated_at', 6)->nullable();
            $table->string('created_by')->nullable();
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
