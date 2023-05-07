<?php

use App\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'get teachers']);
        Permission::create(['name' => 'store teacher']);
        Permission::create(['name' => 'delete teacher']);
        Permission::create(['name' => 'get prompts']);
        Permission::create(['name' => 'store prompt']);
        Permission::create(['name' => 'delete prompt']);
        Permission::create(['name' => 'store note']);
        Permission::create(['name' => 'get marks']);
        Permission::create(['name' => 'get students']);
        Permission::create(['name' => 'store student']);
        Permission::create(['name' => 'delete student']);
        Permission::create(['name' => 'accept student']);
        Permission::create(['name' => 'get user information']);

        Permission::create(['name' => 'store book']);
        Permission::create(['name' => 'get books']);
        Permission::create(['name' => 'store mark']);

        Permission::create(['name' => 'get schools']);
        Permission::create(['name' => 'store hobby']);
        Permission::create(['name' => 'get subject books']);
        Permission::create(['name' => 'get subjects']);
        Permission::create(['name' => 'get subject homeworks']);
        Permission::create(['name' => 'get subject mark']);
        Permission::create(['name' => 'get school information']);
        Permission::create(['name' => 'register student at school']);
        Permission::create(['name' => 'change password']);

        Permission::create(['name' => 'store homework']);

        // create roles and assign created permissions

        Role::create(['name' => 'Manager'])
            ->givePermissionTo(['get teachers', 'store teacher', 'delete teacher', 'get prompts',
                'store prompt', 'delete prompt', 'store note', 'get marks', 'get students',
                'store student', 'delete student', 'accept student', 'get user information']);

        Role::create(['name' => 'Prompt'])
            ->givePermissionTo(['store book', 'get books', 'store mark', 'get user information',
                'get marks', 'store note', 'get students']);

        Role::create(['name' => 'Teacher'])
            ->givePermissionTo(['get user information', 'store homework', 'store mark',
                'get subject homeworks']);

        Role::create(['name' => 'Student'])
            ->givePermissionTo(['get user information', 'get schools', 'store hobby',
                'get subject books', 'get subjects', 'get subject homeworks', 'get subject mark',
                'get school information', 'register student at school', 'change password']);

//        $role = Role::create(['name' => 'super-admin']);
//        $role->givePermissionTo(Permission::all());

        User::create([
            'name' => 'Admin Manager',
            'email' => 'admin@email.com',
            'password' => bcrypt('123456789')
        ])->assignRole('Manager');
    }
}
