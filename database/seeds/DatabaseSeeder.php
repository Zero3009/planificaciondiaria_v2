<?php

use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RolesTableSeeder::class);
    	//$user = config('roles.defaultUserModel')::find(1);
    	//$user->attachRole(1);
    }
}
