<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $existingAdmin = Admin::where('email', 'admin@example.com')->first();

        if (!$existingAdmin) {
            $admin = new Admin();
            $admin->email = 'admin@example.com';
            $admin->password = Hash::make('nagoyameshi');
            $admin->save();
        }
    }
}
