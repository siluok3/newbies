<?php

namespace AppBundle\Repository;

/**
 * NewbieRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class NewbieRepository extends \Doctrine\ORM\EntityRepository
{
    public function filterJoinedNewbie($age, $nationality, $languages, $gender) {

        $matchingCondition = ' ';
        $flag = 0;

        if ($age == true) {
            $matchingCondition .= ' (ABS(e.age-n.age) <= 5) ';
            $flag++;
        }

        if ($gender == true) {
            if($flag == 0) $matchingCondition .= ' e.gender = n.gender ';
            else $matchingCondition .= ' AND e.gender = n.gender ';
            $flag++;
        }

        if ($nationality == true) {
            if($flag == 0) $matchingCondition .= ' n.nationality = e.nationality ';
            else $matchingCondition .= ' AND n.nationality = e.nationality';
            $flag++;
        }

        if ($languages == true){
            if($flag == 0) $matchingCondition .= ' n.languages = e.languages ';
            else $matchingCondition .= ' AND n.languages = e.languages ';
        }

        $qb = $this->createQueryBuilder('n')
            ->select('e,n')
            ->leftJoin('AppBundle:Employee', 'e', 'WITH', $matchingCondition)
            ->getQuery()
            ->getResult();

        return $qb;
    }

    public function filterAllJoinedNewbies() {

        $qb = $this->createQueryBuilder('n')
            ->select('e,n')
            ->leftJoin('AppBundle:Employee', 'e', 'WITH', 'abs(e.age-n.age) <= 5 AND e.nationality = n.nationality AND e.gender = n.gender AND n.languages = e.languages' )
            ->getQuery()
            ->getResult();

        return $qb;
    }
}
