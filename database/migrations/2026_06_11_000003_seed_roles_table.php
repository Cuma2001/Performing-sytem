<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('roles')) {
            $roles = [
                ['name' => 'Superadmin', 'slug' => 'superadmin', 'description' => 'Super Administrator', 'is_system_role' => true, 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'CEO/HR', 'slug' => 'ceo-hr', 'description' => 'Chief Executive Officer / Human Resources', 'is_system_role' => true, 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Salesperson', 'slug' => 'salesperson', 'description' => 'Salesperson', 'is_system_role' => true, 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Supervisor', 'slug' => 'supervisor', 'description' => 'Supervisor', 'is_system_role' => true, 'created_at' => now(), 'updated_at' => now()],
            ];

            foreach ($roles as $role) {
                DB::table('roles')->updateOrInsert(
                    ['name' => $role['name']],
                    $role
                );
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('roles')) {
            DB::table('roles')->whereIn('slug', ['superadmin', 'ceo-hr', 'salesperson', 'supervisor'])->delete();
        }
    }
};
