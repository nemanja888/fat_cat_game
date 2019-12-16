<?php

namespace App\Http\Controllers;

use App\Army;
use App\Game;
use App\Repositories\ArmyRepository;
use App\Repositories\GameRepository;
use App\Traits\ApiResponser;
use App\Traits\AttackTrait;
use Illuminate\Http\Response;

class GameController extends Controller
{
    use ApiResponser;
    use AttackTrait;

    protected $gameRepository;
    protected $armyRepository;

    /**
     * GameController constructor.
     * @param Game $game
     */
    public function __construct(Game $game, Army $army)
    {
        //set game repository
        $this->gameRepository = new GameRepository($game);
        //set army repository
        $this->armyRepository = new ArmyRepository($army);
    }

    /**
     * get all active games and games which pending enough number of armies
     * @return object
     */
    public function index()
    {
        $games = $this->gameRepository->getGames();

        return $this->successResponse($games);
    }

    /**
     * create game and set game status to pending
     * @return object
     */
    public function store()
    {
        $game = $this->gameRepository->createGame();

        return $this->successResponse($game->getModel(), Response::HTTP_CREATED);
    }

    /**
     * show game status
     * @param $id
     * @return object
     */
    public function show($id)
    {
        return $this->successResponse($this->gameRepository->getGameStatus($id));
    }

    /**
     * @param $id
     * @return object
     */
    public function runAttack($gameId, $armyId)
    {
        $gameStatus = $this->gameRepository->getGameStatus($gameId);
       // if game don't have added armies return pending response
        if (!$gameStatus['armies']) {

            return $this->errorResponse(['error' => 'Pending for players'], Response::HTTP_OK);
        }
        // if game game has status finished return pending response
        if ($gameStatus['status'] === 'finished') {

            return $this->errorResponse(['error' => 'Pending for players'], Response::HTTP_OK);
        }

        //check does game has at least 5 armies joined and game status isn't finished
        if (count($gameStatus['armies']) >= 5 && $gameStatus['status'] === 'active') {
            //set enemy armies array
            $targets = array_filter($gameStatus['armies'], function ($army) use ($armyId) {

                return $army['id'] != $armyId && $army['units'] > 0;
            });
            //set attacker
            $attacker = array_filter($gameStatus['armies'], function ($army) use ($armyId) {

                return $army['id'] == $armyId;
            });
            //set attacker array key to 0
            $attacker = array_values($attacker);
            //check does attacker exist
            if (count($attacker) < 1) {

                return $this->errorResponse('Undefined army', Response::HTTP_BAD_REQUEST);
            }
            //prevent player to attack more than one time
            if (!$attacker[0]['canAttack']) {

                return $this->successResponse(['error' => 'Wait your turn!!!'], Response::HTTP_OK);
            }
            //if at least one target are available run attack
            if (count($targets) > 0) {
                sleep(0.01);
                $data = $this->attack($attacker[0], $targets, $gameStatus['gameId']);
                //update attack status for armies
                $this->gameRepository->checkAttackStatus($gameId);

                return $this->successResponse($data, Response::HTTP_OK);
            }
            $this->gameRepository->setGameStatus($gameId);

            return $this->successResponse(['success' => 'You are the winner'], Response::HTTP_OK);
        }

        return $this->errorResponse(['error' => 'Game need minimum 5 armies to star'], Response::HTTP_OK);
    }

    /**
     * reset current game
     * @param $id
     * @return object
     */
    public function reset($id)
    {
        $game = $this->gameRepository->getGameStatus($id);
        $armies = $game['armies'];
        //if game is active reset armies units to 100
        if ($game['status'] === 'active') {
            $this->armyRepository->resetArmyUnits($armies);

            return $this->successResponse($this->gameRepository->getGameStatus($id));
        }
        // if game in status pending or finished remove existing armies and set status to pending
        if ($armies) {
            $this->armyRepository->deleteArmies($armies);
        }
        $game = $this->gameRepository->get($id)->update(['status' => 'pending']);

        return $this->successResponse($game->getModel());
    }
}
