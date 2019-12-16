<?php

use App\Army;
use App\Game;
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
        factory(Game::class, 5)->create();
        factory(Army::class, 15)->create();
    }
}
