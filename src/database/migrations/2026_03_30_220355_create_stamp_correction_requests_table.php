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
        Schema::create('stamp_correction_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attendance_id')->constrained()->cascadeOnDelete();
            $table->time('clock_in')->nullable();
            $table->time('clock_out')->nullable();
            $table->text('note');
            $table->string('status')->default('pending'); // pending / approved
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stamp_correction_requests');
    }
};
