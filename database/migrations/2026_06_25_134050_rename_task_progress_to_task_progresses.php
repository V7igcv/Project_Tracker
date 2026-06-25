<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::rename('task_progress', 'task_progresses');
    }

    public function down(): void
    {
        Schema::rename('task_progresses', 'task_progress');
    }
};