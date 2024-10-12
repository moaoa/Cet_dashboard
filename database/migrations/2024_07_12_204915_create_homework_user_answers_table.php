<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    //NEEDS SOFT DELETION
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('homework_user_answers', function (Blueprint $table) {
            $table->id();
            $table->json('attachments');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('homework_id')->constrained('homework')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('homework_user_answers');
    }
};
