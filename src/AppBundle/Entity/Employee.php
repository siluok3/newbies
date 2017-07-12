<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Employee
 *
 * @ORM\Table(name="employee")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EmployeeRepository")
 */
class Employee extends Person
{
    /**
     * @ORM\OneToMany(targetEntity="Newbie", mappedBy="employee")
     */
    private $newbie;


    /**
     * Set newbie
     *
     * @param \AppBundle\Entity\Newbie $newbie
     *
     * @return Employee
     */
    public function setNewbie(\AppBundle\Entity\Newbie $newbie = null)
    {
        $this->newbie = $newbie;

        return $this;
    }

    /**
     * Get newbie
     *
     * @return \AppBundle\Entity\Newbie
     */
    public function getNewbie()
    {
        return $this->newbie;
    }

    /**
     * @param ExecutionContextInterface $context
     * @Assert\Callback
     */
    /*public function checkboxValidation(ExecutionContextInterface $context, $payload) {

        if($this->age == false && $this->gender == false && $this->nationality == false && $this->languages == false) {
            $context->buildViolation('Check something you idiot!')
                ->atPath('gender')
                ->addViolation();
        }
    } */

}
