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
     * @Route("/match_languages", name="match_by_languages")
     */
    public function matchByLanguagesAction(EntityManagerInterface $em)
    {
        $employeeLanguagesQuery = $em->createQuery(
            'select e.languages from AppBundle:Employee e'
        );
        $employeeLanguages = $employeeLanguagesQuery->getArrayResult();
        //print_r($employeeLanguages);

        $newbieLanguagesQuery = $em->createQuery(
            'select n.languages from AppBundle:Newbie n'
        );
        $newbieLanguages = $newbieLanguagesQuery->getArrayResult();
        //print_r($newbieLanguages);

        $employeesQuery = $em->createQuery(
            'SELECT e.firstname, e.lastname, e.languages
            FROM AppBundle:Employee e
            LEFT JOIN AppBundle:Newbie n WHERE e.languages IN (:languages)'
        )->setParameter('languages', $newbieLanguages);

        $newbiesQuery = $em->createQuery(
            'SELECT n.firstname, n.lastname, n.languages
            FROM AppBundle:Newbie n
            LEFT JOIN AppBundle:Employee e WHERE n.languages IN (:languages)'
        )->setParameter('languages', $employeeLanguages);

        $employees = $employeesQuery->getResult();
        //print_r($employees);
        $newbies = $newbiesQuery->getResult();
        //print_r($newbies);

        return $this->render('default/matchLanguages.html.twig', [
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

            if($this->getAllConditions($age,$gender,$nationality,$languages)){
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

            else if($age == true && $gender == false && $nationality == false && $languages == false) {
                $employees = $em->getRepository('AppBundle:Employee')
                    ->filterOnlyByAgeEmployees();

                $newbies = $em->getRepository('AppBundle:Newbie')
                    ->filterOnlyByAgeNewbies();
            }

            else if($age == false && $gender == true && $nationality == false && $languages == false) {
                $employees = $em->getRepository('AppBundle:Employee')
                    ->filterOnlyByGenderEmployees();

                $newbies = $em->getRepository('AppBundle:Newbie')
                    ->filterOnlyByGenderNewbies();
            }

            else if($age == false && $gender == false && $nationality == true && $languages == false) {
                $employees = $em->getRepository('AppBundle:Employee')
                    ->filterOnlyByNationalityEmployees();

                $newbies = $em->getRepository('AppBundle:Newbie')
                    ->filterOnlyByNationalityNewbies();
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

    /**
     * @Route("/match_debug", name="match_debug_route")
     */
    public function matchDebugAction(EntityManagerInterface $em, Request $request)
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
            $gender = $form->get('gender')->getData();
            //$languages = $form->get('languages')->getData();

            $employees = $em->getRepository('AppBundle:Employee')
                ->findByEmployee($nationality, $age, $gender);

            $newbies = $em->getRepository('AppBundle:Newbie')
                ->findByNewbie($nationality, $age, $gender);
        }

        $success = 'Filters where applied!';

        return $this->render('default/match.html.twig', [
            'employees' => $employees,
            'newbies' => $newbies,
            'form' => $form->createView(),
            'success' => $success
        ]);
    }

    /**
     * @Route("/match_debug_age", name="match_debug_age")
     */
    public function matchDebugAgeAction(EntityManagerInterface $em, Request $request)
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
            $gender = $form->get('gender')->getData();
            $languages = $form->get('languages')->getData();

            $matchingCondition = ' ';

            if ($age == true) {
                $matchingCondition .= ' abs(e.age-n.age) <= 5 ';
            }

            if ($nationality == true) {
                $matchingCondition .= ' e.nationality = n.nationality ';
            }

            if ($gender == true) {
                $matchingCondition .= ' e.gender = n.gender ';
            }

            if ($languages == true){

                $joinLanguagesQuery= $em->createQuery(
                    'select e.languages from AppBundle:Employee e'
                );
                $languagesQuery = $joinLanguagesQuery->getArrayResult();

                $matchingCondition .= ' (n.languages IN e.languages) ';
            }

            $qb = $em->createQueryBuilder();
            $qb->select('e')
                ->from('AppBundle:Newbie', 'n')
                ->innerjoin('AppBundle:Employee', 'e', 'WHERE', $matchingCondition );
            $query = $qb->getQuery();
            $employees = $query->getResult();

            $qb2 = $em->createQueryBuilder();
            $qb2->select('n')
                ->from('AppBundle:Newbie', 'n')
                ->innerjoin('AppBundle:Employee', 'e', 'WHERE', $matchingCondition );
            $query2 = $qb2->getQuery();
            $newbies = $query2->getResult();
        }

        $success = 'Filters where applied!';

        return $this->render('default/debug.html.twig', [
            'employees' => $employees,
            'newbies' => $newbies,
            'form' => $form->createView(),
            'success' => $success
        ]);
    }

    private function getAllConditions($age,$gender,$nationality,$languages)
    {
        return $age == true && $gender == true && $nationality == true && $languages == true;
    }
}
