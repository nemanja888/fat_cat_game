<?php

namespace App\Traits;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

trait AttackTrait {

    use ApiResponser;

    /**
     * use current army strategy define target and attack target
     * @param array $attacker
     * @param array $targets
     * @return array|string
     */
    public function attack(array $attacker, array $targets)
    {
        switch($attacker['strategy']) {
            case 'weakest':
                return $this->attackWeakest($attacker, $targets);
                break;
            case 'strongest':
                return $this->attackStrongest($attacker, $targets);
                break;
            case 'random':
                return $this->attackRandom($attacker, $targets);
                break;
            default:
                return $data['error'] = 'Attack strategy error';
        }

    }

    /**
     * @param array $attacker
     * @param array $targets
     * @return array
     */
    protected function attackWeakest(array $attacker, array $targets): array
    {
        $targetNum = count($targets);
        $formTargets = array_values($targets);
        $weakest = $formTargets[0];

        for ($i = 1; $i < $targetNum; $i++) {
            if ($weakest['units'] > $formTargets[$i]['units']) {
                $weakest = $formTargets[$i];
            }
        }

        return $this->attackForceCalc($attacker, $weakest);
    }

    /**
     * find strongest target in array and attack
     * @param $attacker
     * @param $targets
     * @return array
     */
    protected function attackStrongest(array $attacker,array $targets): array
    {
        $targetNum = count($targets);
        $formTargets = array_values($targets);
        $strongest = $formTargets[0];

        for ($i = 1; $i < $targetNum; $i++) {
            if ($strongest['units'] < $formTargets[$i]['units']) {
                $strongest = $formTargets[$i];
            }
        }

        return $this->attackForceCalc($attacker, $strongest);
    }

    /**
     * find random target and attack
     * @param array $attacker
     * @param array $targets
     * @return array
     */
    protected function attackRandom(array $attacker, array $targets): array
    {
        $target = $targets[array_rand($targets,1)];

        return $this->attackForceCalc($attacker, $target);
    }

    /**
     * @param array $attacker
     * @param array $target
     * @return array
     * @throws \Exception
     */
    protected function attackForceCalc(array $attacker, array $target): array
    {
        //set attack success rate
        $successNum = round($attacker['units'] / 10, 0);
        $success = random_int(0, $successNum);
        //if attack is successful
        if ($success > 0) {
            //calculate damage
            $targetHealth = $target['units'];
            $attackForce = $attacker['units'] * 0.5;
            //set new army unit value for targeted army
            $newTargetHealth = $targetHealth - $attackForce;
            $data = [
                'hasUnit' => $targetHealth,
                'attackForce' => $attackForce,
            ];
            //if unit is alive update database with new army unit value
            if ($newTargetHealth > 0) {
                DB::table('armies')
                    ->where('id', '=', $target['id'])
                    ->update(['units' => $newTargetHealth]);
                $this->disableAttack($attacker['id']);
                //get updated army data
                $updateArmy = DB::table('armies')
                    ->where('id', '=', $target['id'])
                    ->get();
                $data['armyData'] = $updateArmy;

                return $data;
            }
            //if army is dead set unit value to 0
            DB::table('armies')
                ->where('id', '=', $target['id'])
                ->update(['units' => 0, 'can_attack' => 0]);
            $this->disableAttack($attacker['id']);

            $updateArmy = DB::table('armies')
                ->where('id', '=', $target['id'])
                ->get();
            $data['armyData'] = $updateArmy;

            return $data;
        }
        $this->disableAttack($attacker['id']);
        $data = [
            'hasUnit' => $target['units'],
            'attackForce' => 'miss',
        ];
        $data['armyData'] = $target;

        return $data;
    }

    /**
     * disable attack functionality for army
     * @param $attackerId
     * @return bool
     */
    protected function disableAttack($attackerId): bool
    {
        DB::table('armies')
            ->where('id', '=', $attackerId)
            ->update(['can_attack' => 0]);

        return true;
    }
}
