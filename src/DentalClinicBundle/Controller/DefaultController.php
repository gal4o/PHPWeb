<?php

namespace DentalClinicBundle\Controller;

use DentalClinicBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        $users = $this->getDoctrine()
            ->getRepository(User::class)
            ->findBy([],['fullName' => 'ASC']);

        return $this->render('default/index.html.twig', ['users' => $users]);
    }
}
