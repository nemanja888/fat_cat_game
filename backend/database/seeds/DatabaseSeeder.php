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
        factory(Game::class, 5)->create()->each(function ($game) {
            $armyNum = mt_rand(1, 4);
            for ($i = 1; $i <= $armyNum; $i++){
                $game->armies()->save(factory(App\Army::class)->make());
            }
        });
    }
}
