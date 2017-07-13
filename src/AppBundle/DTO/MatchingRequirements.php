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
    private $languages;

    /**
     * MatchingRequirements constructor.
     * @param $age
     * @param $gender
     * @param $nationality
     * @param $languages
     */
    public function __construct($age, $gender, $nationality, $languages)
    {
        $this->age = $age;
        $this->gender = $gender;
        $this->nationality = $nationality;
        $this->languages = $languages;
    }

    /**
     * @return int
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * @return bool | If Data "Age" passed from the Form is true
     */
    public function isAgeRequirement()
    {
        return $this->getAge() == true;
    }

    /**
     * @return string|null
     */
    public function getNationality()
    {
        return $this->nationality;
    }

    /**
     * @return bool | If Data "Nationality" passed from the Form is true
     */
    public function isNationalityRequirement()
    {
        return $this->getNationality() == true;
    }

    /**
     * @return int
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @return bool | If Data "gender" passed from the Form is true
     */
    public function isGenderRequirement()
    {
        return $this->getGender() == true;
    }

    /**
     * @return array
     */
    public function getLanguages()
    {
        return $this->languages;
    }

    /**
     * @return bool | If Data "Languages" passed from the Form is true
     */
    public function isLanguagesRequirement()
    {
        return $this->getLanguages() == true;
    }
}