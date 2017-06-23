<?php

namespace AppBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/newbies", name="newbies_list")
     */
    public function listNewbiesAction()
    {;
        $newbies = $this->getDoctrine()->getRepository('AppBundle:Newbie')->findAll();

        return $this->render('newbie/list.html.twig', [
            'newbies' => $newbies
        ]);
    }

    /**
     * @Route("/employees", name="employees_list")
     */
    public function listEmployeesAction()
    {
        $employees = $this->getDoctrine()->getRepository('AppBundle:Employee')->findAll();

        return $this->render('employee/list.html.twig', [
            'employees' => $employees
        ]);
    }

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {

        return $this->render('default/index.html.twig');

    }

    /**
     * @Route("/match_nationality", name="match_by_nationality")
     */
    public function matchByNationalityAction(EntityManagerInterface $em)
    {

        $employeesQuery = $em->createQuery(
            'SELECT e.firstname, e.lastname, e.nationality
            FROM AppBundle:Employee e
            JOIN AppBundle:Newbie n WITH e.nationality = n.nationality'
        );

        $newbiesQuery = $em->createQuery(
            'SELECT n.firstname, n.lastname, e.nationality
            FROM AppBundle:Employee e
            JOIN AppBundle:Newbie n WITH e.nationality = n.nationality'
        );

        $employees = $employeesQuery->getResult();
        $newbies = $newbiesQuery->getResult();

        return $this->render('default/matchNationality.html.twig', [
            'employees' => $employees,
            'newbies' => $newbies
        ]);
    }

    /**
     * @Route("/match_languages", name="match_by_languages")
     */
    public function matchByLanguagesAction(EntityManagerInterface $em)
    {
        $employeeLanguagesQuery = $em->createQuery(
            'select e.languages from AppBundle:Employee e'
        );
        $employeeLanguages = $employeeLanguagesQuery->getArrayResult();

        $newbieLanguagesQuery = $em->createQuery(
            'select n.languages from AppBundle:Newbie n'
        );
        $newbieLanguages = $newbieLanguagesQuery->getArrayResult();

        $employeesQuery = $em->createQuery(
            'SELECT e.firstname, e.lastname, e.languages
            FROM AppBundle:Employee e
            JOIN AppBundle:Newbie n WHERE e.languages IN (:languages)'
        )->setParameter('languages', $newbieLanguages);

        $newbiesQuery = $em->createQuery(
            'SELECT n.firstname, n.lastname, n.languages
            FROM AppBundle:Employee e
            JOIN AppBundle:Newbie n WHERE n.languages IN (:languages)'
        )->setParameter('languages', $employeeLanguages);;

        $employees = $employeesQuery->getResult();
        $newbies = $newbiesQuery->getResult();

        return $this->render('default/matchLanguages.html.twig', [
            'employees' => $employees,
            'newbies' => $newbies
        ]);
    }

    /**
     * @Route("/match_age", name="match_by_age")
     */
    public function matchByAgeAction(EntityManagerInterface $em)
    {

        $employeesQuery = $em->createQuery(
            'SELECT e.firstname, e.lastname, e.age
            FROM AppBundle:Employee e
            JOIN AppBundle:Newbie n WITH abs(e.age-n.age)<:age'
        )->setParameter('age', 5);



        $newbiesQuery = $em->createQuery(
            'SELECT n.firstname, n.lastname, n.age
            FROM AppBundle:Employee e
            JOIN AppBundle:Newbie n WITH abs(e.age-n.age)<:age'
        )->setParameter('age', 5);

        $employees = $employeesQuery->getResult();
        $newbies = $newbiesQuery->getResult();

        return $this->render('default/matchAge.html.twig', [
            'employees' => $employees,
            'newbies' => $newbies
        ]);
    }

    /**
     * @Route("/match", name="match")
     */
    public function matchAction(EntityManagerInterface $em)
    {

        $employeesQuery = $em->createQuery(
            'SELECT e.firstname, e.lastname, e.nationality, e.age, e.gender
            FROM AppBundle:Employee e
            JOIN AppBundle:Newbie n WITH abs(e.age-n.age)<:age OR e.nationality = n.nationality OR e.gender = n.gender'
        )->setParameter('age', 5);

        $newbiesQuery = $em->createQuery(
            'SELECT n.firstname, n.lastname, n.nationality, n.age, n.gender
            FROM AppBundle:Employee e
            JOIN AppBundle:Newbie n WITH abs(e.age-n.age)<:age OR e.nationality = n.nationality OR e.gender = n.gender'
        )->setParameter('age', 5);

        $employees = $employeesQuery->getResult();
        $newbies = $newbiesQuery->getResult();

        return $this->render('default/match.html.twig', [
            'employees' => $employees,
            'newbies' => $newbies
        ]);
    }

    public function filterByNationalityAction(EntityManagerInterface $em) {

        $employeesQuery = $em->createQuery(
            'SELECT e.firstname, e.lastname, e.nationality, e.gender, e.age
            FROM AppBundle:Employee e
            JOIN AppBundle:Newbie n WITH e.nationality = n.nationality'
        );

        $newbiesQuery = $em->createQuery(
            'SELECT n.firstname, n.lastname, e.nationality, n.gender, n.age
            FROM AppBundle:Employee e
            JOIN AppBundle:Newbie n WITH e.nationality = n.nationality'
        );

        $employees = $employeesQuery->getResult();
        $newbies = $newbiesQuery->getResult();

        return $this->render('default/match.html.twig', [
            'employees' => $employees,
            'newbies' => $newbies
        ]);

    }

    public function filterByAgeAction(EntityManagerInterface $em) {

        $employeesQuery = $em->createQuery(
            'SELECT e.firstname, e.lastname, e.age
            FROM AppBundle:Employee e
            JOIN AppBundle:Newbie n WITH abs(e.age-n.age)<:age'
        )->setParameter('age', 5);



        $newbiesQuery = $em->createQuery(
            'SELECT n.firstname, n.lastname, n.age
            FROM AppBundle:Employee e
            JOIN AppBundle:Newbie n WITH abs(e.age-n.age)<:age'
        )->setParameter('age', 5);

        $employees = $employeesQuery->getResult();
        $newbies = $newbiesQuery->getResult();

        return $this->render('default/matchAge.html.twig', [
            'employees' => $employees,
            'newbies' => $newbies
        ]);
    }

}
