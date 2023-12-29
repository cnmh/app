<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class MaintenanceSocialAuthorization extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $serviceSocial = User::where('name', 'service social')->first();

        if ($serviceSocial) {
            $permissionNames = [
                'index-Patient',
                'edit-Patient',
                'update-Patient',
            ];

            foreach ($permissionNames as $permissionName) {
                Permission::firstOrCreate(['name' => $permissionName]);
            }

            $permissions = Permission::whereIn('name', $permissionNames)->get();

            $serviceSocial->givePermissionTo($permissions);
        }
    }
}
