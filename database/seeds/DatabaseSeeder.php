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
         $this->call(UsersTableSeeder::class);
         $this->call(RUsersTableSeeder::class);
         $this->call(PostsTableSeeder::class);
         $this->call(GroupsTableSeeder::class);
    }
}
