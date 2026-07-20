<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    


    public function up(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->foreignId('employer_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->text('description')->nullable();
            $table->string('location')->nullable();
            $table->string('salary')->nullable();
            $table->string('type')->default('Full-time');
        });
    }

    


    public function down(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->dropForeign(['employer_id']);
            $table->dropColumn(['employer_id', 'description', 'location', 'salary', 'type']);
        });
    }
};
