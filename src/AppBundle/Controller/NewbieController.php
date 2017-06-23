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
     * @Route("/create_newbie", name="create_newbie_route")
     */
    public function createNewbieAction(EntityManagerInterface $em)
    {
        $newbie = new Newbie();
        $newbie->setFirstname('Lof');
        $newbie->setLastname('Pedersen');
        $newbie->setNationality('Danish');
        $newbie->setGender(0);
        $newbie->setLanguages(array('english','danish'));

        $em->persist($newbie);
        $em->flush();

        return new Response('Saved new newbie with id '.$newbie->getId());
    }

}