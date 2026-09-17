<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleSeeder::class);

        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'full_name' => 'Administrator',
                'password' => bcrypt('password'),
                'is_active' => true,
                'role_id' => $adminRole->id,
                'email_verified_at' => now(),
            ]
        );

        $this->call([
            BlogSeeder::class,
            ProjectSeeder::class,
            TestimonialSeeder::class,
        ]);
    }
}
