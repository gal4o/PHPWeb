<?php

namespace DentalClinicBundle\Controller;

use DentalClinicBundle\Entity\ClinicBranch;
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
        if (isset($_POST['filter'])) {

            $filter = $_POST['filter'];
            $branch = $this->getDoctrine()
                ->getRepository(ClinicBranch::class)
                ->findOneBy(['name' => $filter]);
            $users = $this->getDoctrine()
                ->getRepository(User::class)
                ->findBy(['clinic' => $branch->getId(), 'role' => '1'],['fullName' => 'ASC']);
        } else {
            $users = $this->getDoctrine()
                ->getRepository(User::class)
                ->findBy(['role' => '1'],['fullName' => 'ASC']);
            $branch = $this->getDoctrine()
                ->getRepository(ClinicBranch::class)
                ->findAll();
        }

        return $this->render('default/index.html.twig',
            ['users' => $users, 'branch' => $branch]);
    }

}
