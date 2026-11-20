<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('title', 140);
            $table->text('description')->nullable();
            $table->string('status', 24)->default('todo'); // todo | in_progress | done
            $table->string('priority', 16)->default('medium'); // low | medium | high
            $table->date('due_date')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status', 'priority']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
