<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class assignRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            [
                'name' => 'ADMIN',
                'guard_name' => 'web',
            ],
            [
                'name' => 'SALES_MANAGER',
                'guard_name' => 'web',
            ],
            [
                'name' => 'ACCOUNT_MANAGER',
                'guard_name' => 'web',
            ],
            [
                'name' => 'SALES_EXCUETIVE',
                'guard_name' => 'web',
            ],
            [
                'name' => 'BUSINESS_DEVELOPMENT_MANAGER',
                'guard_name' => 'web',
            ],
            [
                'name' => 'BUSINESS_DEVELOPMENT_EXCECUTIVE',
                'guard_name' => 'web',
            ]
        ];

        foreach ($roles as $key => $value) {
            Role::create($value);
        }

    }
}
