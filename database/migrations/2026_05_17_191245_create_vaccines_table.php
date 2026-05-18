<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vaccines', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('manufacturer');
            $table->text('description');
            $table->integer('doses_required')->default(1);
            $table->integer('days_between_doses')->default(0);
            $table->enum('status', ['available', 'unavailable'])->default('available');
            $table->integer('stock')->default(100);
            $table->decimal('price', 8, 2)->default(0.00);
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vaccines');
    }
};
