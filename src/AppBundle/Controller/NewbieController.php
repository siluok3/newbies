<?php
/**
 * Created by PhpStorm.
 * User: kpapachristou
 * Date: 6/21/17
 * Time: 9:57 AM
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Newbie;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Persistence\ManagerRegistry;

class NewbieController extends Controller
{
    /**
     * @param EntityManagerInterface $em
     *
     * @Route("/create_newbie", name="create_newbie_route")
     *
     * @return Response
     */
    public function createNewbieAction(EntityManagerInterface $em)
    {
        $newbie = new Newbie();
        $newbie->setFirstname('Carla');
        $newbie->setLastname('Llull');
        $newbie->setNationality('Spanish');
        $newbie->setGender(1);
        $newbie->setLanguages(array('german','spanish'));
        $newbie->setAge(23);

        $em->persist($newbie);
        $em->flush();

        return new Response('Saved new newbie with id '.$newbie->getId());
    }

}