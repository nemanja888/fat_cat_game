<?php

namespace App\Http\Controllers;

use App\Army;
use App\Http\Requests\CreateArmyRequest;
use App\Repositories\ArmyRepository;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ArmyController extends Controller
{
    use ApiResponser;

    protected $armyRepository;

    /**
     * ArmyController constructor.
     * @param Army $army
     */
    public function __construct(Army $army)
    {
        //set army repository
        $this->armyRepository = new ArmyRepository($army);
    }

    public function store(CreateArmyRequest $request)
    {
        $army = $this->armyRepository->createArmy($request->all());

        return $this->successResponse($army->getModel(), Response::HTTP_CREATED);
    }

    public function show(int $id)
    {
        return $this->successResponse($this->armyRepository->get($id)->getModel());
    }
}
