<?php
/**
 * Created by PhpStorm.
 * User: kpapachristou
 * Date: 6/28/17
 * Time: 1:02 PM
 */

namespace AppBundle\Repository;

use AppBundle\DTO\MatchingRequirements;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;

class MatchRepository extends EntityRepository
{
    public function findByEmployee(MatchingRequirements $requirements)
    {
        $matchingCondition = '';

        if ($age == true) {
            $matchingCondition .= 'ON e.age = n.age';
        }

        if ($nationality == true) {
            $matchingCondition .= 'ON e.nationality = n.nationality';
        }

        if ($gender == true) {
            $matchingCondition .= 'ON abs(e.age-n.age) <= 5';
        }

        $qbEmployee = $this->createQueryBuilder('e');
        $qbEmployee->select('e')
            ->from('AppBundle:Employee', 'e')
            ->join('AppBundle:Newbie', 'n', Join::WITH, $matchingCondition);

        return $qbEmployee->getQuery()->execute();

    }

    public function findByNewbie($nationality, $age, $gender)
    {
        $matchingCondition = '';

        if ($age == true) {
            $matchingCondition .= 'ON e.age = n.age';
        }

        if ($nationality == true) {
            $matchingCondition .= 'ON e.nationality = n.nationality';
        }

        if ($gender == true) {
            $matchingCondition .= 'ON abs(e.age-n.age) <= 5';
        }

        $qbNewbie = $this->createQueryBuilder('n');
        $qbNewbie->select('n')
            ->from('AppBundle:Newbie', 'n')
            ->join('AppBundle:Employee', 'e', Join::WITH, $matchingCondition);

        return $qbNewbie->getQuery()->execute();

    }
}