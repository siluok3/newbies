<?php
/**
 * Created by PhpStorm.
 * User: kpapachristou
 * Date: 6/28/17
 * Time: 12:59 PM
 */

namespace AppBundle\DTO;


class MatchingRequirements
{
    private $age;
    private $nationality;
    private $gender;

    public function __construct($age, $gender, $nationality)
    {
    }

    /**
     * @return int
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * @return string|null
     */
    public function getNationality()
    {
        return $this->nationality;
    }

    /**
     * @return int
     */
    public function getGender()
    {
        return $this->gender;
    }

}