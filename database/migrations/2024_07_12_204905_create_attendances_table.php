<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    //SOFT DELETETION
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('status');
            $table->string('note');
            $table->date('date');
            $table->foreignId('lecture_id')->constrained('lectures');
            $table->foreignId('user_id')->constrained('users');
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['lecture_id', 'user_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
