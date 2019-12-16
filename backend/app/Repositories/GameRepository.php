<?php


namespace App\Repositories;


use Illuminate\Support\Facades\DB;

class GameRepository extends Repository
{
    /**
     * get all active games and games which pending enough number of armies
     * @return mixed
     */
    public function getGames()
    {
        $games = $this->model
            ->where('status', '!=' ,'finished')
            ->get();

        return $games;
    }


    /**
     * create game and set game status to pending
     * @return GameRepository|object
     */
    public function createGame()
    {
        $data = [
            'status' => 'pending'
        ];

        return $this->create($data);
    }

    public function getGameStatus($id)
    {
        $game = DB::table('games')
                   ->leftJoin('armies', 'games.id', '=', 'armies.game_id')
                    ->select(
                        'games.id as gameId',
                        'games.status as gameStatus',
                        'armies.id as armyId',
                        'armies.name as armyName',
                        'armies.units',
                        'armies.strategy',
                        'armies.can_attack'
                    )
                   ->where('games.id', '=', $id)
                  ->get();
        $status = [
          'gameId' =>  $game->first()->gameId,
          'status' => $game->first()->gameStatus,
          'armies' => []
        ];

        foreach ($game as $army) {
            if ($army->armyName) {
                $status['armies'][] = [
                    'id' =>  $army->armyId,
                    'name' =>  $army->armyName,
                    'units' =>  $army->units,
                    'strategy' =>  $army->strategy,
                    'canAttack' => $army->can_attack
                ];
            } else {
                $status['armies'] = null;
            }
        }

        return $status;
    }

    public function getArmiesCount($id)
    {
        $armies = DB::table('games')
            ->join('armies', 'games.id', '=', 'armies.game_id')
            ->where('games.id', '=', $id)
            ->select(
                'armies.id'
            )
            ->get();

        return count($armies);
    }

    public function setGameStatus($gameId)
    {
        DB::table('games')
            ->where('id', '=', $gameId)
            ->update(['status' => 'finished']);
    }

    /**
     * check did all armies attack and if did update can_attack col to 1
     * @param $id
     */
    public function checkAttackStatus($id): void
    {
        $attackStatus = DB::table('games')
            ->leftJoin('armies', 'games.id', '=', 'armies.game_id')
            ->select(
                'armies.can_attack'
            )
            ->where('games.id', '=', $id)
            ->get();
        $canAttackArr = $attackStatus->pluck('can_attack')->toArray();
        $canAttackArr = array_sum($canAttackArr);
        if (!$canAttackArr) {
            DB::table('armies')
                ->where('game_id', $id)
                ->where('units', '>', '0')
                ->update(['can_attack' => 1]);
        }
    }
}
