<?php

use Illuminate\Database\Seeder;
use App\User;
use jeremykenedy\LaravelRoles\Models\Role;

class MigracionUsuariosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = Role::where('slug', '=', 'administracion')->first();
        $developer = Role::where('slug', '=', 'developer')->first();
        $area = Role::where('slug', '=', 'area')->first();

        $developer = User::find(1);
        $ari = User::find(2);
        $fab = User::find(26);
        $magio = User::find(24);
        $mans = User::find(34);
        
        $ari->attachRole($admin);
        $fab->attachRole($admin);
        $magio->attachRole($developer);
        $mans->attachRole($developer);
        $developer->attachRole($developer);

        $users = User::all()->except([1,2,26,24,34]);

        foreach ($users as $user) {
            $user->attachRole($area);
        }
    }
}
