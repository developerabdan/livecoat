<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\PermissionGroup;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = Role::query()
                    ->createOrFirst([
                        'name' => 'Super Admin',
                        'guard_name' => 'web',
                    ]);
        $groupPermission = PermissionGroup::query()
                                    ->create([
                                        'category' => 'users-and-role-based-access',
                                        'name' => 'Permission Groups',
                                        'slug' => 'permission-groups',
                                        'icon' => 'lucide-group',
                                        'description' => 'Manage all permission groups',
                                    ]);
        $groupPermission->permissions()->createMany([
            [
                'name' => 'View Permission Groups',
                'description' => 'View available permission groups',
                'guard_name' => 'web',
            ],
            [
                'name' => 'Create Permission Groups',
                'description' => 'Add new permission group to the system',
                'guard_name' => 'web',
            ],
            [
                'name' => 'Edit Permission Groups',
                'description' => 'Edit specific permission group in the system',
                'guard_name' => 'web',
            ],
            [
                'name' => 'Delete Permission Groups',
                'description' => 'Delete specific permission group from the system',
                'guard_name' => 'web',
            ],
        ]);
        $permissions = PermissionGroup::query()
                                    ->create([
                                        'category' => 'users-and-role-based-access',
                                        'name' => 'Permissions',
                                        'slug' => 'permissions',
                                        'icon' => 'lucide-door-open',
                                        'description' => 'Manage all permissions',
                                    ]);
        $permissions->permissions()->createMany([
            [
                'name' => 'View Permissions',
                'description' => 'View available permissions',
                'guard_name' => 'web',
            ],
            [
                'name' => 'Create Permissions',
                'description' => 'Add new permission to the system',
                'guard_name' => 'web',
            ],
            [
                'name' => 'Edit Permissions',
                'description' => 'Edit specific permission in the system',
                'guard_name' => 'web',
            ],
            [
                'name' => 'Delete Permissions',
                'description' => 'Delete specific permission from the system',
                'guard_name' => 'web',
            ],
        ]);
        $roles = PermissionGroup::query()
                                    ->create([
                                        'category' => 'users-and-role-based-access',
                                        'name' => 'Roles',
                                        'slug' => 'roles',
                                        'icon' => 'lucide-shield-plus',
                                        'description' => 'Manage all roles and capabilities',
                                    ]);
        $roles->permissions()->createMany([
            [
                'name' => 'View Roles',
                'description' => 'View available roles',
                'guard_name' => 'web',
            ],
            [
                'name' => 'Create Roles',
                'description' => 'Add new role to the system',
                'guard_name' => 'web',
            ],
            [
                'name' => 'Edit Roles',
                'description' => 'Edit specific role in the system',
                'guard_name' => 'web',
            ],
            [
                'name' => 'Delete Roles',
                'description' => 'Delete specific role from the system',
                'guard_name' => 'web',
            ],
        ]);
    }
}
