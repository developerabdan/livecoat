<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::query()->firstOrCreate([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ], [
            'password' => 'password',
        ]);
        $role = Role::query()->firstOrCreate([
            'name' => 'Super Admin',
        ], [
            'guard_name' => 'web',
        ]);
        $user->assignRole($role);
    }
}
