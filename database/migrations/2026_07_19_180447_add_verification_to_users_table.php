<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    


    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('ktp_path')->nullable();
            $table->enum('verification_status', ['unverified', 'pending', 'verified', 'rejected'])->default('unverified');
        });
    }

    


    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['ktp_path', 'verification_status']);
        });
    }
};
