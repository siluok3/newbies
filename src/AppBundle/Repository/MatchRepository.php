<?php
/**
 * Created by PhpStorm.
 * User: kpapachristou
 * Date: 6/28/17
 * Time: 1:02 PM
 */

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;

class MatchRepository extends EntityRepository
{
    public function match(MatchingRequirements $matchingRequirements)
    {
        $matchingCondition = 'mydefaultcondition';

        if (!is_null($matchingRequirements->getAge())) {
            $matchingCondition .= 'ON e.age = n.age';
        }

        if (!is_null($matchingRequirements->getNationality())) {
            $matchingCondition .= 'ON e.nationality = n.nationality';
        }

        if (!is_null($matchingRequirements->getGender())) {
            $matchingCondition .= 'ON abs(e.age-n.age) <= 5';
        }

        $qbEmployee = $this->createQueryBuilder();
        $qbEmployee->select('e')
            ->from('Employee', 'e')
            ->join('Newbie', 'n', Join::WITH, $matchingCondition);

        $qbNewbie = $this->createQueryBuilder();
        $qbNewbie->select('n')
            ->from('Newbie', 'n')
            ->join('Employee', 'e', Join::WITH, $matchingCondition);

        $resultEmployee = $qbEmployee->getResult();
        $resultNewbie = $qbNewbie->getResult();

        return [
            'employees' => $resultEmployee,
            'newbies' => $resultNewbie
        ];
    }
}