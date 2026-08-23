<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        Admin::firstOrCreate([
            'email' => 'admin@admin.com',
        ], [
            'name'     => 'Admin',
            'password' => bcrypt('password'),
        ]);

        Admin::firstOrCreate([
            'email' => 'Nourhan@admin.com',
        ], [
            'name'     => 'Nourhan',
             'password' => bcrypt('password'),
        ]);
    }
}
