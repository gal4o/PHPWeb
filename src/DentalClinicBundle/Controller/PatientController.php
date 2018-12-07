<?php

namespace DentalClinicBundle\Controller;

use DentalClinicBundle\Entity\Patient;
use DentalClinicBundle\Entity\Visit;
use DentalClinicBundle\Form\PatientType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;


class PatientController extends Controller
{
    /**
     * @Route("/patient/index", name="patient_index")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewPatientsAction()
    {
        $patients = $this->getDoctrine()
            ->getRepository(Patient::class)
            ->findBy([], ['fullName' => 'ASC']);
        return $this->render('patient/index.html.twig', ['patients' => $patients]);
    }

    //trq li security?
    /**
     * @param Request $request
     * @Route("/patient/crreate", name="patient_create")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function createPatientAction(Request $request)
    {
        $patient = new Patient();
        $form = $this->createForm(PatientType::class, $patient);
        $form->handleRequest($request);

        if ($form->isSubmitted()&&$form->isValid()) {

            if ($form->getData()->getPhoto()!==null) {
                /** @var UploadedFile $file */
                $file = $form->getData()->getPhoto();
                $fileName = md5(uniqid()) . '.' . $file->guessExtension();
                try {
                    $file->move($this->getParameter(
                        'images_directory'), $fileName);
                    $patient->setPhoto($fileName);
                } catch (FileException $ex) {

                }
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($patient);
            $em->flush();
            return $this->redirectToRoute("patient_index");
        }

        return $this->render('patient/create.html.twig',
            array('form' => $form->createView()));
    }

    /**
     * @Route("/patient/profile/{id}", name="patient_profile")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewPatientAction($id)
    {
        $patient = $this->getDoctrine()
            ->getRepository(Patient::class)
            ->find($id);
        $visits = $this->getDoctrine()
            ->getRepository(Visit::class)
            ->findBy(['patient' => $patient],['date' => 'desc']);

        return $this->render('patient/profile.html.twig', ['patient' => $patient, 'visits' => $visits]);
    }

    /**
     * @Route("/patient/edit/{id}", name="patient_edit")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editPatientAction($id, Request $request)
    {
        $patient = $this->getDoctrine()
            ->getRepository(Patient::class)
            ->find($id);

        if ($patient === null) {
            return $this->redirectToRoute('patient_index');
        }

//        $currentUser = $this->getUser();
//        if (!$currentUser->isAdmin()&&!$currentUser->isDentist() )
//        {
//            return $this->redirectToRoute('patient_index');
//        }

        $form = $this->createForm(PatientType::class, $patient);
        $form->handleRequest($request);

        if ($form->isSubmitted()&&$form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($patient);
            $em->flush();
            return $this->redirectToRoute('patient_profile',
                array('id' => $patient->getId()));
        }
        return $this->render('patient/edit.html.twig',
            ['patient' => $patient, 'form' => $form->createView()]);
    }

    /**
     * @Route("patient/delete/{id}", name="patient_delete")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function deletePatientAction($id, Request $request)
    {
        $patient = $this->getDoctrine()
            ->getRepository(Patient::class)
            ->find($id);
        if ($patient === null) {
            return $this->redirectToRoute('patient_index');
        }

        $form = $this->createForm(PatientType::class, $patient);
        $form ->handleRequest($request);
        if ($form->isSubmitted()&&$form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($patient);
            $em->flush();
            return $this->redirectToRoute('patient_index');
        }
        return $this->render('patient/delete.html.twig',
            array('patient' => $patient,
                'form' => $form->createView()));
    }

}
