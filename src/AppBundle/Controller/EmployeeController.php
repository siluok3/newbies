<?php
/**
 * Created by PhpStorm.
 * User: kpapachristou
 * Date: 6/21/17
 * Time: 10:31 AM
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Employee;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Persistence\ManagerRegistry;

class EmployeeController extends Controller
{
    /**
     * @param EntityManagerInterface $em
     *
     * @Route("/create_employee", name="create_employee_route")
     *
     * @return Response
     */
    public function createEmployeeAction(EntityManagerInterface $em)
    {
        $employee = new Employee();
        $employee->setFirstname('Delilah');
        $employee->setLastname('Akhbar');
        $employee->setNationality('Arabian');
        $employee->setGender(1);
        $employee->setLanguages(array('english'));
        $employee->setAge(29);

        $em->persist($employee);
        $em->flush();

        return new Response('Saved new employee with id '.$employee->getId());
    }

}