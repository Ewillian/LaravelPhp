<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\User;
use App\Company;
use App\Job;

class UsersRolesSeeders extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        Role::create(['name' => 'Student']);
        Role::create(['name' => 'Company']);
        Permission::create(['name' => 'CreateJob']);
        Permission::create(['name' => 'ApplyJob']);

        factory(User::class, 50)->create()->each(function ($user) {
            $user->companies()->saveMany(factory(App\Company::class, 1)->make());
            $user->assignRole('Company');
            $user->givePermissionTo('CreateJob');
        });

        $myCompanies = Company::all();
        foreach ($myCompanies as $company){
            factory(Job::class, 1)->create([
                'company_id' => $company->id,
            ]);
        }

        factory(User::class, 50)->create()->each(function ($user) {
            $user->assignRole('Student');
            $user->givePermissionTo('ApplyJob');
        });
    }
}
