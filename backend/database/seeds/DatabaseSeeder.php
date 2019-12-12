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
        factory(Game::class, 10)->create();
        factory(Army::class, 50)->create();
    }
}
