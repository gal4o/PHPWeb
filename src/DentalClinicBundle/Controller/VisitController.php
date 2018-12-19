<?php

namespace DentalClinicBundle\Controller;

use DentalClinicBundle\Entity\Patient;
use DentalClinicBundle\Entity\Tariff;
use DentalClinicBundle\Entity\Visit;
use DentalClinicBundle\Form\VisitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class VisitController extends Controller
{
    /**
     * @param Request $request
     * @param $id
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Route("/patient/profile/{id}/visit/create", name="visit_create")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createVisitAction(Request $request, $id)
    {
        $currentUser = $this->getUser();
        if (!$currentUser->isDentist())
        {
            $this->addFlash('info', "Access denied");
            return $this->redirectToRoute("homepage");
        }
        //dobavi manip
        $visit = new Visit();
        $form = $this->createForm(VisitType::class, $visit);
        $form->handleRequest($request);

        $patient = $this->getDoctrine()
            ->getRepository(Patient::class)
            ->find($id);
        $tariff = $this->getDoctrine()
            ->getRepository(Tariff::class)
            ->findAll();

        if ($form->isSubmitted() && $form->isValid()) {

            $visit->setDentist($this->getUser());
            $visit->setPatient($patient);
            $tariffRepo = $this->getDoctrine()->getRepository(Tariff::class);
            $addTariffs = $tariffRepo->findBy(['treatment' => $request->get('treatment')]);
//            var_dump($addTariff);exit();
            foreach ($addTariffs as $tariff) {
                $visit->addManipulations($tariff);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($visit);
            $em->flush();
            return $this->redirectToRoute("patient_profile",
                array('id' => $patient->getId(),
                    'patient' => $patient));
        }

        return $this->render('visit/create',
            array('form' => $form->createView(),
                'patient' => $patient,
                'tariff' => $tariff));
    }

    /**
     * @Route("/visit/{id}", name="visit_edit")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editPatientVisit($id, Request $request)
    {
        $currentUser = $this->getUser();
        if (!$currentUser->isDentist())
        {
            $this->addFlash('info', "Access denied");
            return $this->redirectToRoute("homepage");
        }
        $visit = $this->getDoctrine()
            ->getRepository(Visit::class)
            ->find(['id' => $id]);

        /** @var Patient $patient */
        $patient = $visit->getPatient();

        if ($visit === null) {

            return $this->redirectToRoute('patient_profile',

                array(['id' => $patient->getId()]));

        }

        $form = $this->createForm(VisitType::class, $visit);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($visit);
            $em->flush();
            return $this->redirectToRoute('patient_profile',
                array('id' => $patient->getId()));
        }
        return $this->render('visit/editPatientVisit.html.twig', ['visit' => $visit, 'form' => $form]);
    }
}
