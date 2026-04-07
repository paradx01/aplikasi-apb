<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $apotekerRole = Role::create([
            'name' => 'apoteker'
        ]);
        
        $buyerRole = Role::create([
            'name' => 'buyer'
        ]);

        $user = User::create([
            'name' => 'Ibu Nurul Pemilik',
            'email' => 'nurul@owner.com',
            'gender' => 'P',
            'password' => bcrypt('123456')
        ]);

        $user->assignRole($apotekerRole);

        $user2 = User::create([
            'name' => 'Danny Toharuddin Ali',
            'email' => 'danny@buyer.com',
            'password' => bcrypt('11111111')
        ]);

        $user2->assignRole($buyerRole);
    }
}
