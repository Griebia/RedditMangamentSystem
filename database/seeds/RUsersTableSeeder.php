<?php

use Illuminate\Database\Seeder;

class RUsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\RUser::class,30)->create();
    }
}
