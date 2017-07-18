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
use Doctrine\ORM\Tools\Pagination\Paginator;

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

        $qb = $this->createQueryBuilder('n')
            ->select('n, e')
            ->leftJoin('AppBundle:Employee', 'e', 'WITH', $matchingCondition)
            ->orderBy('n.id');

        return $qb->getQuery()->getScalarResult();
    }

    /**
     * @param $matchingCondition
     * @param $isRequired
     * @param $condition
     *
     * @return void
     */
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