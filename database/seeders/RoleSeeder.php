<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $roles = ['Super Admin', 'Administrator', 'Case Manager', 'Data Entry Clerk', 'Guest'];

        foreach ($roles as $role) {
            Role::create(['name' => $role]);
        }
    }
}
