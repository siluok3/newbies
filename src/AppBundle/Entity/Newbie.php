<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Newbie
 *
 * @ORM\Table(name="newbie")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\NewbieRepository")
 */
class Newbie extends Person
{
}
