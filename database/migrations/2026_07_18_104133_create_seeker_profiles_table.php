<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seeker_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title')->nullable(); 
            $table->text('about')->nullable();
            $table->text('skills')->nullable();
            $table->string('resume')->nullable(); 
            $table->string('avatar')->nullable();
            $table->integer('profile_completion')->default(10);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seeker_profiles');
    }
};
