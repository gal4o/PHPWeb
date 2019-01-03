<?php

namespace DentalClinicBundle\Controller;

use DateTime;
use DentalClinicBundle\Entity\ClinicBranch;
use DentalClinicBundle\Entity\Role;
use DentalClinicBundle\Entity\User;
use DentalClinicBundle\Entity\Visit;
use DentalClinicBundle\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UsersController extends Controller
{
    /**
     * @param $page
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/user/index{page}", name="user_index")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function viewUsersAction($page = 1)
    {
        $limit = 5;
        $thisPage = $page;

        $users = $this->getDoctrine()
            ->getRepository(User::class)
            ->getAllUsers($thisPage);

        $maxPages = ceil($users->count()/$limit);

        return $this->render('user/index.html.twig',
            ['users' => $users, 'maxPages' =>$maxPages, 'thisPage' => $thisPage]);
    }

    /**
     * @Route("/user/register", name="user_register")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse| \Symfony\Component\HttpFoundation\Response
     */
    public function registerAction(Request $request)
    {
        $currentUser = $this->getUser();
        if (!$currentUser->isAdmin()&&!$currentUser->isHr())
        {
            $this->addFlash('info', "Access denied");
            return $this->redirectToRoute("homepage");
        }

        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        $roles = $this->getDoctrine()
            ->getRepository(Role::class)
            ->findAll();
        /** @var ClinicBranch $clinics */
        $clinics = $this->getDoctrine()
            ->getRepository(ClinicBranch::class)
            ->findAll();

        if($form->isSubmitted()){
            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPassword());

            $user->setPassword($password);

            if ($form->getData()->getPhoto()!==null) {
                /** @var UploadedFile $file */
                $file = $form->getData()->getPhoto();
                $fileName = md5(uniqid()) . '.' . $file->guessExtension();
                try {
                    $file->move($this->getParameter(
                        'users_images_directory'), $fileName);
                    $user->setPhoto($fileName);
                } catch (FileException $ex) {

                }
            }

            $role = $this
                ->getDoctrine()
                ->getRepository(Role::class)
                ->findOneBy(['name' => $request->get('role')]);

            $clinic = $this
                ->getDoctrine()
                ->getRepository(ClinicBranch::class)
                ->findOneBy(['name' => $request->get('clinic')]);
            $user->setClinic($clinic);
            $user->setRole($role);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute("user_index");
        }

        return $this->render('user/register.html.twig',
            array('roles' =>$roles, 'clinics' => $clinics));
    }

    /**
     * @Route("/user/profile/{id}", name="user_profile")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function viewUserProfileAction($id)
    {

        $currentUser = $this->getUser();

        if (($currentUser->getId()!=$id)&&
            (!$currentUser->isAdmin()&&!$currentUser->isFinancier()))
        {
            $this->addFlash('info', "Access denied");
            return $this->redirectToRoute("homepage");
        }

        if (isset($_POST['start']) || isset($_POST['end'])) {

            $startDay = $_POST['start'];
            $start = new DateTime($startDay);
            $endDay = $_POST['end'];
            $end = new DateTime($endDay);
        } else {
        $start = DateTime::createFromFormat('Y-m-d', "2018-11-01");
        $end = new DateTime('now');
        }
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($id);

        $visits = $this->getDoctrine()
            ->getRepository(Visit::class)
            ->getManipulations($user, $start, $end->modify('+1day'));

        $sum = 0;
        /** @var Visit $visit */
        foreach ($visits as $visit) {
            foreach ($visit->getManipulations() as $manipulation) {
                 $sum+=$manipulation->getPrice();
             }
        }

        return $this->render('user/profile.html.twig', ['user' => $user, 'visits' => $visits, 'sum' => $sum]);
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
        $currentUser = $this->getUser();
        if (!$currentUser->isAdmin()&&!$currentUser->isHr())
        {
            $this->addFlash('info', "Access denied");
            return $this->redirectToRoute("homepage");
        }

        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($id);
        if ($user === null) {
            $this->addFlash('info', "This user does not exist.");
            return $this->redirectToRoute("homepage");
        }

        $roles = $this->getDoctrine()
            ->getRepository(Role::class)
            ->findAll();

        $clinics = $this->getDoctrine()
            ->getRepository(ClinicBranch::class)
            ->findAll();

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted()&&$form->isValid())
        {
            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPassword());
            $user->setPassword($password);
            $clinic = $this
                ->getDoctrine()
                ->getRepository(ClinicBranch::class)
                ->findOneBy(['name' => $request->get('clinic')]);
            $user->setClinic($clinic);

            if ($form->getData()->getPhoto()!==null) {
                /** @var UploadedFile $file */
                $file = $form->getData()->getPhoto();
                $fileName = md5(uniqid()) . '.' . $file->guessExtension();
                try {
                    $file->move($this->getParameter(
                        'users_images_directory'), $fileName);
                    $user->setPhoto($fileName);
                } catch (FileException $ex) {

                }
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('user_profile',
                array('id' => $user->getId()));
        }

        return $this->render('user/edit.html.twig',
            array('user' => $user,
                'roles' => $roles,
                'form' => $form->createView(),
                'clinics' => $clinics
            ));
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
        $currentUser = $this->getUser();
        if (!$currentUser->isAdmin()&&!$currentUser->isHr())
        {
            $this->addFlash('info', "Access denied");
            return $this->redirectToRoute("homepage");
        }

        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($id);
        if ($user === null) {
            $this->addFlash('info', "This user does not exist.");
            return $this->redirectToRoute('homepage');
        }

        $form = $this->createForm(UserType::class, $user);
        $form ->handleRequest($request);
        if ($form->isSubmitted()&&$form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();
            return $this->redirectToRoute('user_index');

        }
        return $this->render('user/delete.html.twig',
            array('user' => $user,
                'form' => $form->createView()));
    }

}
