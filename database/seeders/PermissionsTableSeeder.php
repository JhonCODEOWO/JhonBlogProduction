<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            ["name" => "view"],
            ["name" => "read"],
            ["name" => "write"],
            ["name" => "update"],
            ["name" => "delete"],
            ["name" => "manage_users"],
            ["name" => "manage_roles"],
            ["name" => "admin_access"]
        ];

        foreach ($permissions as $permssion) {
            Permission::create($permssion);
        }
    }
}
