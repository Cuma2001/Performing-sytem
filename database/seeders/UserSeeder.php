<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Seed the default admin user.
     */
    public function run(): void
    {
        $roleId = DB::table('roles')->where('slug', 'superadmin')->value('id');

        User::updateOrCreate(
            ['email' => 'ayabonga.maqashu@ictchoice.com'],
            [
                'name' => 'Ayabonga Maqashu',
                'password' => 'password', // hashed by the User model's 'hashed' cast
                'role' => 'Superadmin',
                'role_id' => $roleId,
            ]
        );
    }
}
