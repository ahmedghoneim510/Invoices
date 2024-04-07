<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    private $permissions = [
        'role-list',
        'role-create',
        'role-edit',
        'role-delete',
        'product-list',
        'product-create',
        'product-edit',
        'product-delete'
    ];
    public function run(): void
    {
        $this->call([
            Premissions::class,
            UserSeeder::class,
        ]);
//        foreach ($this->permissions as $permission) {
//            Permission::create(['name' => $permission]);
//        }

        // Create admin User and assign the role to him.

    }

    /**
     * Seed the application's database.
     */
   // public function run(): void
 //   {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
   // }
}
