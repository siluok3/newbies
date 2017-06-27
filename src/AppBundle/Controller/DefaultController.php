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
            'SELECT e.firstname, e.lastname, e.nationality, e.gender, e.age, e.languages
            FROM AppBundle:Employee e
            JOIN AppBundle:Newbie n WITH e.nationality = n.nationality'
        );

        $newbiesQuery = $em->createQuery(
            'SELECT n.firstname, n.lastname, e.nationality, e.gender, e.age, e.languages
            FROM AppBundle:Employee e
            JOIN AppBundle:Newbie n WITH e.nationality = n.nationality'
        );

        $employees = $employeesQuery->getResult();
        $newbies = $newbiesQuery->getResult();

        $form = $this->createForm(FilterType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $nationality = $form->get('nationality')->getData();
            $age = $form->get('age')->getData();
            $gender =$form->get('gender')->getData();
            $languages = $form->get('languages')->getData();

            if($age == true) {
                $employeesQuery = $em->createQuery(
                    'SELECT e.firstname, e.lastname, e.age, e.nationality, e.gender, e.languages
                    FROM AppBundle:Employee e
                    JOIN AppBundle:Newbie n WITH abs(e.age-n.age)<:age'
                    )->setParameter('age', 5);



                $newbiesQuery = $em->createQuery(
                    'SELECT n.firstname, n.lastname, n.age, n.nationality, n.gender, n.languages
                    FROM AppBundle:Employee e
                    JOIN AppBundle:Newbie n WITH abs(e.age-n.age)<:age'
                    )->setParameter('age', 5);

                //$employees = $employeesQuery->getResult();
                //$newbies = $newbiesQuery->getResult();
            }
            if($gender == true) {
                $employeesQuery = $em->createQuery(
                    'SELECT e.firstname, e.lastname, e.age, e.nationality, e.gender, e.languages
                    FROM AppBundle:Employee e
                    JOIN AppBundle:Newbie n WITH e.gender=n.gender'
                );

                $newbiesQuery = $em->createQuery(
                    'SELECT n.firstname, n.lastname, n.age, n.nationality, n.gender, n.languages
                    FROM AppBundle:Employee e
                    JOIN AppBundle:Newbie n WITH e.gender=n.gender'
                );

                //$employees = $employeesQuery->getResult();
                //$newbies = $newbiesQuery->getResult();
            }
            $employees = $employeesQuery->getResult();
            $newbies = $newbiesQuery->getResult();

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
    public function matchAction(EntityManagerInterface $em, Request $request)
    {
        $employees = $em->getRepository('AppBundle:Employee')
            ->filterAllEmployees();

        $newbies = $em->getRepository('AppBundle:Newbie')
            ->filterAllNewbies();

        $form = $this->createForm(FilterType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $nationality = $form->get('nationality')->getData();
            $age = $form->get('age')->getData();
            $gender =$form->get('gender')->getData();
            $languages = $form->get('languages')->getData();

            if($age == true && $gender == true && $nationality == true && $languages == true){
                $employees = $em->getRepository('AppBundle:Employee')
                    ->filterAllEmployees();

                $newbies = $em->getRepository('AppBundle:Newbie')
                    ->filterAllNewbies();
            }
            else if($age == true && $gender == true && $nationality == false && $languages == true){
                $employees = $em->getRepository('AppBundle:Employee')
                    ->filterAllButNationalityEmployees();

                $newbies = $em->getRepository('AppBundle:Newbie')
                    ->filterAllButNationalityNewbies();
            }

            else if($age == true && $gender == false && $nationality == true && $languages == true){
                $employees = $em->getRepository('AppBundle:Employee')
                    ->filterAllButGenderEmployees();

                $newbies = $em->getRepository('AppBundle:Newbie')
                    ->filterAllButGenderNewbies();
            }

            else if($age == false && $gender == true && $nationality == true && $languages == true){
                $employees = $em->getRepository('AppBundle:Employee')
                    ->filterAllButAgeEmployees();

                $newbies = $em->getRepository('AppBundle:Newbie')
                    ->filterAllButAgeNewbies();
            }

            else if($age == true && $gender == true && $nationality == true && $languages == false){
                $employees = $em->getRepository('AppBundle:Employee')
                    ->filterAllButLanguagesEmployees();

                $newbies = $em->getRepository('AppBundle:Newbie')
                    ->filterAllButLanguagesNewbies();
            }
            else if($age == true && $gender == false && $nationality == true && $languages == false){
                $employees = $em->getRepository('AppBundle:Employee')
                    ->filterByNationalityAndAgeEmployees();

                $newbies = $em->getRepository('AppBundle:Newbie')
                    ->filterByNationalityAndAgeNewbies();
            }
            else if($age == false && $gender == true && $nationality == true && $languages == false){
                $employees = $em->getRepository('AppBundle:Employee')
                    ->filterByNationalityAndGenderEmployees();

                $newbies = $em->getRepository('AppBundle:Newbie')
                    ->filterByNationalityAndGenderNewbies();
            }
            else if($age == true && $gender == true && $nationality == false && $languages == false){
                $employees = $em->getRepository('AppBundle:Employee')
                    ->filterByAgeAndGenderEmployees();

                $newbies = $em->getRepository('AppBundle:Newbie')
                    ->filterByAgeAndGenderNewbies();
            }

            else if($age == true) {
                $employeesQuery = $em->createQuery(
                    'SELECT e.firstname, e.lastname, e.age, e.nationality, e.gender, e.languages
                    FROM AppBundle:Employee e
                    JOIN AppBundle:Newbie n WITH abs(e.age-n.age)<:age'
                )->setParameter('age', 5);



                $newbiesQuery = $em->createQuery(
                    'SELECT n.firstname, n.lastname, n.age, n.nationality, n.gender, n.languages
                    FROM AppBundle:Employee e
                    JOIN AppBundle:Newbie n WITH abs(e.age-n.age)<:age'
                )->setParameter('age', 5);
            }

            else if($gender == true) {
                $employeesQuery = $em->createQuery(
                    'SELECT e.firstname, e.lastname, e.age, e.nationality, e.gender, e.languages
                    FROM AppBundle:Employee e
                    JOIN AppBundle:Newbie n WITH e.gender=n.gender'
                );

                $newbiesQuery = $em->createQuery(
                    'SELECT n.firstname, n.lastname, n.age, n.nationality, n.gender, n.languages
                    FROM AppBundle:Employee e
                    JOIN AppBundle:Newbie n WITH e.gender=n.gender'
                );
            }
        }
        $success = 'Filters where applied!';

        return $this->render('default/match.html.twig', [
            'employees' => $employees,
            'newbies' => $newbies,
            'form' => $form->createView(),
            'success' => $success
        ]);
    }
}
