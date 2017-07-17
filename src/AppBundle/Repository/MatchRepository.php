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
    const AND_K = ' AND ';

    /**
     * @param MatchingRequirements $requirements
     *
     * @return array
     */
    public function findByPerson(MatchingRequirements $requirements)
    {
        $matchingCondition = '';

        $this->appendCondition(
            $matchingCondition,
            $requirements->isAgeRequirement(),
            ' ABS(e.age-n.age) <= 5 '
        );

        $this->appendCondition(
            $matchingCondition,
            $requirements->isNationalityRequirement(),
            ' n.nationality = e.nationality '
        );

        $this->appendCondition(
            $matchingCondition,
            $requirements->isGenderRequirement(),
            ' n.gender = e.gender '
        );

        $this->appendCondition(
            $matchingCondition,
            $requirements->isLanguagesRequirement(),
            ' n.languages = e.languages '
        );

        $qb =$this->createQueryBuilder('n')
            ->select('n, e')
            ->leftJoin('AppBundle:Employee', 'e', 'WITH', $matchingCondition )
            ->orderBy('n.id')
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
            ->leftJoin(
                'AppBundle:Employee',
                'e',
                'WITH',
                'abs(e.age-n.age) <= 5
                  AND e.nationality = n.nationality
                  AND e.gender = n.gender
                  AND n.languages = e.languages'
            )
            ->getQuery()
            ->getScalarResult();

        return $qb;
    }

    private function appendCondition(&$matchingCondition, $isRequired, $condition)
    {
        if($isRequired)
        {
            if(!empty($matchingCondition))
            {
                $matchingCondition .= static::AND_K;
            }
            $matchingCondition .= $condition;
        }
    }
}