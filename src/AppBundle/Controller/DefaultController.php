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
     * @Route("/match_age", name="match_age")
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

            $employees = $em->getRepository('AppBundle:Employee')
                ->filterByEmployee($age, $nationality, $languages, $gender);
            $newbies = $em->getRepository('AppBundle:Newbie')
                ->filterByNewbie($age, $nationality, $languages, $gender);
        }

        $success = 'Filters where applied!';

        //Array that saves the times each Newbie is appearing in the table
        $result = $this->elementsArray($newbies);
        print_r($result);

        //Distinct Results on the newbies array
        //$input = array_map("unserialize", array_unique(array_map("serialize", $newbies)));

        return $this->render('default/matchAge.html.twig', [
            'employees' => $employees,
            'newbies' => $newbies,
            'form' => $form->createView(),
            'success' => $success
        ]);
    }

    //Function to count how many times a newbie is returned when matched with different employees
    public function elementsArray($rows) {

        $lastname = '';
        $times = 1;
        $array = array();

        foreach($rows as $row) {
            if($row['lastname'] == $lastname) {
                $times++;
            }
            else {
                array_push($array, $times);
                $lastname = $row['lastname'];
                $times =1;
            }
        }
        unset($array[0]);
        return $array;
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


    private function getAllConditions($age,$gender,$nationality,$languages)
    {
        return $age == true && $gender == true && $nationality == true && $languages == true;
    }
}
