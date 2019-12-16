<?php


namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class ArmyRepository extends Repository
{
    public function createArmy($data)
    {
        $army = $this->create($data);
        $gameArmies = DB::table('games')
                ->leftJoin('armies', 'games.id', '=', 'armies.game_id')
                ->select(
                'games.id as gameId',
                'games.status as gameStatus',
                'armies.id as armyId',
                'armies.name as armyName',
                'armies.units',
                'armies.strategy'
            )
            ->where('games.id', '=', $data['game_id'])
            ->get();
        // check number of players and update game status
        if (count($gameArmies) > 4 && $gameArmies->pluck('gameStatus')->first() === 'pending') {
            DB::table('games')
              ->where('id', $data['game_id'])
              ->update(['status' => 'active']);
        }

        return $army;
    }

    public function resetArmyUnits($armies){
        foreach ($armies as $army) {
            DB::table('armies')
                ->where('id', $army['id'])
                ->update(['units' => 100]);
        }

        return true;
    }

    public function deleteArmies($armies)
    {
        foreach ($armies as $army) {
            DB::table('armies')
                ->where('id', $army['id'])
                ->delete();
        }
    }
}
