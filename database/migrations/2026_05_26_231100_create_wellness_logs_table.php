<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wellness_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appointment_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->boolean('fever')->default(false);
            $table->boolean('soreness')->default(false);
            $table->boolean('headache')->default(false);
            $table->boolean('fatigue')->default(false);
            $table->text('other_symptoms')->nullable();
            $table->timestamp('logged_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wellness_logs');
    }
};
