<?php

namespace DentalClinicBundle\Controller;

use DentalClinicBundle\Entity\User;
use DentalClinicBundle\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UsersController extends Controller
{
    /**
     * @Route("/register", name="user_register")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse| \Symfony\Component\HttpFoundation\Response
     */
    public function registerAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted()){
            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPassword());

            $user->setPassword($password);

//            $role = $this
//                ->getDoctrine()
//                ->getRepository(Role::class)
//                ->findOneBy(['name' => 'ROLE_USER']);
//
//            $user->addRole($role);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute("homepage");
        }

        return $this->render('user/register.html.twig');
    }

    /**
     * @Route("/user/profile/{id}", name="user_profile")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewUserProfileAction($id)
    {
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($id);
        return $this->render('user/profile.html.twig', ['user' => $user]);
    }

    /**
     * @Route("/user/edit/{id}", name="user_edit")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editUserProfileAction($id, Request $request)
    {
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($id);
        if ($user === null) {
            return $this->redirectToRoute("homepage");
        }

//        $currentUser = $this->getUser();
//        if (!$currentUser->isAdmin()) //ili humanResourse
//        {
//            return $this->redirectToRoute("homepage");
//        }

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted()&&$form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('user_profile',
                array('id' => $user->getId()));
        }
        return $this->render('user/edit.html.twig',
            array('user' => $user,
                'form' => $form->createView()));
    }

    /**
     * @Route("/user/delete/{id}", name="user_delete")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function deleteUserProfileAction($id, Request $request)
    {
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($id);
        if ($user === null) {
            return $this->redirectToRoute('homepage');
        }

        //        $currentUser = $this->getUser();
//        if (!$currentUser->isAdmin()) //ili humanResourse
//        {
//            return $this->redirectToRoute("homepage");
//        }

        $form = $this->createForm(UserType::class, $user);
        $form ->handleRequest($request);
        if ($form->isSubmitted()&&$form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();
            return $this->redirectToRoute('homepage');
        }
        return $this->render('user/delete.html.twig',
            array('user' => $user,
                'form' => $form->createView()));
    }
}
