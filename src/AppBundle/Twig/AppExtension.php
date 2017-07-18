<?php
/**
 * Created by PhpStorm.
 * User: siluo
 * Date: 09-Jul-17
 * Time: 8:38 PM
 */

namespace AppBundle\Twig;


class AppExtension extends \Twig_Extension
{
    /**
     * @return array
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('genderDecision', array($this, 'isMale')),
        );
    }

    /**
     * @param $value
     *
     * @return string
     */
    public function isMale($value)
    {
        if($value == 0) {
            $genderDecision =  "Male";
        }
        else {
            $genderDecision = "Female";
        }

        return $genderDecision;
    }
}