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
        DB::table('task_statuses')->insert([
            ['name' => 'new'],
            ['name' => 'working'],
            ['name' => 'testing'],
            ['name' => 'completed'],
        ]);
        $this->call(UsersTableSeeder::class);
        $this->call(TagsTableSeeder::class);
        //$this->call(TaskStatusesTableSeeder::class);
        $this->call(TaskTableSeeder::class);
    }
}
