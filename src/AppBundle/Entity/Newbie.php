<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Newbie
 *
 * @ORM\Table(name="newbie")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\NewbieRepository")
 */
class Newbie extends Person
{

    /**
     * @ORM\ManyToOne(targetEntity = "Employee", inversedBy="newbie")
     * @ORM\JoinColumn(name="employee_id", referencedColumnName = "id")
     */
    private $employee;


    /**
     * Set employee
     *
     * @param \AppBundle\Entity\Employee $employee
     *
     * @return Newbie
     */
    public function setEmployee(\AppBundle\Entity\Employee $employee = null)
    {
        $this->employee = $employee;

        return $this;
    }

    /**
     * Get employee
     *
     * @return \AppBundle\Entity\Employee
     */
    public function getEmployee()
    {
        return $this->employee;
    }

    /**
     * @param ExecutionContextInterface $context
     * @Assert\Callback
     */
    /*public function checkboxValidation(ExecutionContextInterface $context) {

        if($this->age == false && $this->gender == false && $this->nationality == false && $this->languages == false) {
            $context->buildViolation('Check something you idiot!')
                ->atPath('gender')
                ->addViolation();
        }
    } */
}
