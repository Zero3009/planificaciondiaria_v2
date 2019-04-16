<?php

use Illuminate\Database\Seeder;


class AdminSeeder extends Seeder
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
    }
}