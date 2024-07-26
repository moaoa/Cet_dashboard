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
        Schema::create('quiz_groups', function (Blueprint $table) {
            $table->id();
            $table->timestamp('end_time');
            $table->timestamp('start_time');
            $table->foreignId('group_id');
            $table->foreignId('quiz_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_groups');
    }
};
