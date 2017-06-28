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
     * @Route("/create_employee", name="create_employee_route")
     */
    public function createEmployeeAction(EntityManagerInterface $em)
    {
        $employee = new Employee();
        $employee->setFirstname('Franz');
        $employee->setLastname('Beckensbauer');
        $employee->setNationality('German');
        $employee->setGender(0);
        $employee->setLanguages(array('german'));
        $employee->setAge(29);

        $em->persist($employee);
        $em->flush();

        return new Response('Saved new employee with id '.$employee->getId());
    }

}