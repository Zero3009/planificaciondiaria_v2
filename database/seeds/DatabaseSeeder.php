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
    	$user = config('roles.defaultUserModel')::find(1);
    	$user->attachRole(1);
         //$this->call(DefaultRolesTableSeeder::class);
    }
}
