<?php
/**
 * Created by PhpStorm.
 * User: kpapachristou
 * Date: 6/30/17
 * Time: 11:50 AM
 */

namespace AppBundle\Controller;

use AppBundle\DTO\MatchingRequirements;
use AppBundle\Repository\MatchRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Id;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\FilterType;

class MatchController extends Controller
{
    /**
     * @Route("/", name="homepage")
     *
     * @param EntityManagerInterface $em
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(EntityManagerInterface $em, Request $request)
    {
        /**
         * @var MatchRepository $repository
         */
        $repository = $em->getRepository('AppBundle:Newbie');

        $form = $this->createForm(FilterType::class);
        $form->handleRequest($request);

        $nationality = $form->get('nationality')->getData();
        $age = $form->get('age')->getData();
        $gender = $form->get('gender')->getData();
        $languages = $form->get('languages')->getData();

        if($form->isSubmitted() && $form->isValid())
        {
            $matchingRequirements = new MatchingRequirements($age, $gender, $nationality, $languages);
            $matches = $repository->findByPerson($matchingRequirements);

            $success = 'Filters where applied!';

            return $this->render('default/joined.html.twig', [
                'matches' => $matches,
                'form' => $form->createView(),
                'success' => $success,
            ]);
        }

        return $this->render('default/index.html.twig', [
            'form' => $form->createView(),
        ]);

    }

    /**
     * @Route("/newbies", name="newbies_list")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listNewbiesAction()
    {
        $newbies = $this->getDoctrine()->getRepository('AppBundle:Newbie')->findAll();

        return $this->render('newbie/list.html.twig', [
            'newbies' => $newbies
        ]);
    }

    /**
     * @Route("/employees", name="employees_list")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listEmployeesAction()
    {
        $employees = $this->getDoctrine()->getRepository('AppBundle:Employee')->findAll();

        return $this->render('employee/list.html.twig', [
            'employees' => $employees
        ]);
    }

    /**
     * @param $em
     * @param $request
     *
     * @Route("/match", name="match")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function matchAction(EntityManagerInterface $em, Request $request)
    {
        /**
         * @var MatchRepository $repository
         */
        $repository = $em->getRepository('AppBundle:Newbie');

        $form = $this->createForm(FilterType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $nationality = $form->get('nationality')->getData();
            $age = $form->get('age')->getData();
            $gender = $form->get('gender')->getData();
            $languages = $form->get('languages')->getData();

            $matchingRequirements = new MatchingRequirements($age, $gender, $nationality, $languages);

            $matches = $repository->findByPerson($matchingRequirements);
        }

        $success = 'Filters where applied!';

        return $this->render('default/joined.html.twig', [
            'matches' => $matches,
            'form' => $form->createView(),
            'success' => $success,
        ]);
    }
}