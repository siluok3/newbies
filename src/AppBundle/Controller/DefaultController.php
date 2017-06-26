<?php

namespace AppBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Id;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\FilterType;

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
    public function matchByNationalityAction(EntityManagerInterface $em, Request $request)
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

        $form = $this->createForm(FilterType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $nationality = $form->get('nationality')->getData();
            $age = $form->get('age')->getData();
            $gender =$form->get('gender')->getData();
            $languages = $form->get('languages')->getData();

            if($age == true) {
                $employeesQuery = $em->createQuery(
                    'SELECT e.firstname, e.lastname, e.age, e.nationality
                    FROM AppBundle:Employee e
                    JOIN AppBundle:Newbie n WITH abs(e.age-n.age)<:age'
                    )->setParameter('age', 5);



                $newbiesQuery = $em->createQuery(
                    'SELECT n.firstname, n.lastname, n.age, n.nationality
                    FROM AppBundle:Employee e
                    JOIN AppBundle:Newbie n WITH abs(e.age-n.age)<:age'
                    )->setParameter('age', 5);

                $employees = $employeesQuery->getResult();
                $newbies = $newbiesQuery->getResult();
            }

        }

        return $this->render('default/matchNationality.html.twig', [
            'employees' => $employees,
            'newbies' => $newbies,
            'form' => $form->createView()
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
        print_r($employeeLanguages);
        //$test1 = json_encode($employeeLanguages);
        //echo $test1;

        $newbieLanguagesQuery = $em->createQuery(
            'select n.languages from AppBundle:Newbie n'
        );
        $newbieLanguages = $newbieLanguagesQuery->getArrayResult();
        print_r($newbieLanguages);
        //$test2 = json_encode($newbieLanguages);
        //echo $test2;

        $employeesQuery = $em->createQuery(
            'SELECT e.firstname, e.lastname, e.languages
            FROM AppBundle:Employee e
            JOIN AppBundle:Newbie n WHERE e.languages IN (:languages)'
        )->setParameter('languages', $newbieLanguages);

        $newbiesQuery = $em->createQuery(
            'SELECT n.firstname, n.lastname, n.languages
            FROM AppBundle:Newbie n
            JOIN AppBundle:Employee e WHERE n.languages IN (:languages)'
        )->setParameter('languages', $employeeLanguages);

        $employees = $employeesQuery->getResult();
        print_r($employees);
        $newbies = $newbiesQuery->getResult();
        print_r($newbies);

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
            JOIN AppBundle:Newbie n WITH abs(e.age-n.age)<:age AND e.nationality = n.nationality AND e.gender = n.gender'
        )->setParameter('age', 5);

        $newbiesQuery = $em->createQuery(
            'SELECT n.firstname, n.lastname, n.nationality, n.age, n.gender
            FROM AppBundle:Employee e
            JOIN AppBundle:Newbie n WITH abs(e.age-n.age)<:age AND e.nationality = n.nationality AND e.gender = n.gender'
        )->setParameter('age', 5);

        $employees = $employeesQuery->getResult();
        $newbies = $newbiesQuery->getResult();

        $form = $this->createForm(FilterType::class);

        return $this->render('default/match.html.twig', [
            'employees' => $employees,
            'newbies' => $newbies,
            'form' => $form->createView()
        ]);
    }

    public function filterByNationalityAction(EntityManagerInterface $em, Request $request) {

        $this->$request->request->get('match');

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

    public function filterByGenderAction(EntityManagerInterface $em) {

        $employeesQuery = $em->createQuery(
            'SELECT e.firstname, e.lastname, e.nationality, e.age, e.gender
            FROM AppBundle:Employee e
            JOIN AppBundle:Newbie n WITH e.gender = n.gender'
        );



        $newbiesQuery = $em->createQuery(
            'SELECT n.firstname, n.lastname, n.nationality, n.age, n.gender
            FROM AppBundle:Employee e
            JOIN AppBundle:Newbie n WITH e.gender = n.gender'
        );

        $employees = $employeesQuery->getResult();
        $newbies = $newbiesQuery->getResult();

        return $this->render('default/match.html.twig', [
            'employees' => $employees,
            'newbies' => $newbies
        ]);
    }

}
