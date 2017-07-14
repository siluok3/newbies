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
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\ResultSetMapping;

class MatchRepository extends EntityRepository
{
    /**
     * @param MatchingRequirements $requirements
     *
     * @return array
     */
    public function findByPerson(MatchingRequirements $requirements)
    {
        $matchingCondition = '';
        $and = ' AND ';

        if($requirements->isAgeRequirement())
        {
            $matchingCondition .= ' ABS(e.age-n.age) <= 5 ';
        }

        if($requirements->isNationalityRequirement())
        {
            $condition = ' e.nationality = n.nationality ';
            if(empty($matchingCondition))
            {
                $matchingCondition .= $condition;
            }
            else
            {
                $matchingCondition .= $and . $condition;
            }
        }

        if($requirements->isGenderRequirement())
        {
            $condition = ' e.gender = n.gender ';
            if(empty($matchingCondition))
            {
                $matchingCondition .= $condition;
            }
            else
            {
                $matchingCondition .= $and . $condition;
            }
        }

        if($requirements->isLanguagesRequirement())
        {
            $condition = ' n.languages = e.languages';
            if(empty($matchingCondition))
            {
                $matchingCondition .= $condition;
            }
            else
            {
                $matchingCondition .= $and . $condition;
            }
        }

        $qb =$this->createQueryBuilder('e')
            ->select('n, e')
            ->leftJoin('AppBundle:Newbie', 'n', 'WITH', $matchingCondition )
            //->groupBy('e')
            ->getQuery()
            ->getScalarResult();

        return $qb;

    }

    /**
     * @return array
     */
    public function filterAllJoinedNewbies()
    {
        $qb = $this->createQueryBuilder('n')
            ->select('e, n')
            ->leftJoin('AppBundle:Employee', 'e', 'WITH', 'abs(e.age-n.age) <= 5 AND e.nationality = n.nationality AND e.gender = n.gender AND n.languages = e.languages' )
            ->getQuery()
            ->getResult();

        return $qb;
    }
}