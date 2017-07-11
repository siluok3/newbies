<?php
/**
 * Created by PhpStorm.
 * User: kpapachristou
 * Date: 6/30/17
 * Time: 11:50 AM
 */

namespace AppBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Id;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\FilterType;

class MatchController extends Controller
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
    public function indexAction(Request $request)
    {
        return $this->render('default/index.html.twig');

    }


    /**
     * @Route("/joined", name="joined")
     */
    public function matchJoinAction(EntityManagerInterface $em, Request $request) {

        $newbies = $em->getRepository('AppBundle:Newbie')
            ->filterAllJoinedNewbies();

        $form = $this->createForm(FilterType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $nationality = $form->get('nationality')->getData();
            $age = $form->get('age')->getData();
            $gender = $form->get('gender')->getData();
            $languages = $form->get('languages')->getData();

            $newbies = $em->getRepository('AppBundle:Newbie')
                ->filterJoinedNewbie($age, $nationality, $languages, $gender);
        }

        $success = 'Filters were applied!';

        //var_dump($newbies);

        return $this->render('default/debug.html.twig', [
            'newbies' => $newbies,
            'form' => $form->createView(),
            'success' => $success
        ]);
    }

    /**
     * @Route("/dynamic_match", name="dynamic_match")
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
        //$result = $this->elementsArray($newbies);
        //print_r($result);

        //Distinct Results on the newbies array
        //$distinctNewbies = array_map("unserialize", array_unique(array_map("serialize", $newbies)));

        return $this->render('default/match.html.twig', [
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
}