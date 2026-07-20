<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    


    public function run(): void
    {
        \App\Models\User::factory()->create([
            'name' => 'Admin Utama',
            'email' => 'admin@gawein.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        \App\Models\User::factory()->create([
            'name' => 'PT Pemberi Kerja',
            'email' => 'employer@gawein.com',
            'password' => bcrypt('password'),
            'role' => 'employer',
        ]);

        \App\Models\User::factory()->create([
            'name' => 'Pencari Kerja Aktif',
            'email' => 'jobseeker@gawein.com',
            'password' => bcrypt('password'),
            'role' => 'jobseeker',
        ]);

    
    }
}
