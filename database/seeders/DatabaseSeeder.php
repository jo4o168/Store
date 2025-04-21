<?php

namespace Database\Seeders;

use App\Enum\Roles;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Couchbase\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Master',
            'email' => 'jvtdomingues+master@hotmail.com',
            'password' => Hash::make('123456'),
            'roles' => Roles::values()
        ]);

        User::factory()->create([
            'name' => 'Client',
            'email' => 'jvtdomingues+client@hotmail.com',
            'password' => Hash::make('123456'),
            'roles' => [Roles::CLIENT->value],
        ]);

        User::factory()->create([
            'name' => 'Seller',
            'email' => 'jvtdomingues+seller@hotmail.com',
            'password' => Hash::make('123456'),
            'roles' => [Roles::SELLER->value],
        ]);
    }
}
