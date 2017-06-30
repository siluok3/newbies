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
        $result = $this->elementsArray($newbies);
        print_r($result);

        //Distinct Results on the newbies array
        //$input = array_map("unserialize", array_unique(array_map("serialize", $newbies)));

        return $this->render('default/match.html.twig', [
            'employees' => $employees,
            'newbies' => $newbies,
            'form' => $form->createView(),
            'success' => $success
        ]);
    }

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