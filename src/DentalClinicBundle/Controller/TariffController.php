<?php

namespace DentalClinicBundle\Controller;

use DentalClinicBundle\Entity\Tariff;
use DentalClinicBundle\Form\TariffType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TariffController extends Controller
{
    /**
     * @Route("/tariff/index", name="tariff_index")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewTariffsAction()
    {
        $tariffs = $this->getDoctrine()
            ->getRepository(Tariff::class)
            ->findAll();
        return $this->render('tariff/index.html.twig', ['tariffs' => $tariffs]);
    }

    /**
     * @param Request $request
     * @Route("/tariff/crreate", name="tariff_create")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createTariffAction(Request $request)
    {
        $tariff = new Tariff();
        $form = $this->createForm(TariffType::class, $tariff);
        $form->handleRequest($request);

        if ($form->isSubmitted()&&$form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($tariff);
            $em->flush();
            return $this->redirectToRoute('tariff_index');
        }

        return $this->render('tariff/create.html.twig',
            array('form' => $form->createView()));
    }

    /**
     * @Route("/tariff/edit/{id}", name="tariff_edit")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editTariffAction($id, Request $request)
    {
        $tariff = $this->getDoctrine()
            ->getRepository(Tariff::class)
            ->find($id);

        if ($tariff === null) {
            return $this->redirectToRoute('tariff_index');
        }

//        $currentUser = $this->getUser();
//        if (!$currentUser->isAdmin()&&!$currentUser->isDentist() )
//        {
//            return $this->redirectToRoute('patient_index');
//        }

        $form = $this->createForm(TariffType::class, $tariff);
        $form->handleRequest($request);

        if ($form->isSubmitted()&&$form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($tariff);
            $em->flush();
            return $this->redirectToRoute('tariff_index',
                array('id' => $tariff->getId()));
        }
        return $this->render('tariff/edit.html.twig',
            ['tariff' => $tariff, 'form' => $form->createView()]);
    }

    /**
     * @Route("/tariff/delete/{id}", name="tariff_delete")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function deleteTariffAction($id, Request $request)
    {
        $tariff = $this->getDoctrine()
            ->getRepository(Tariff::class)
            ->find($id);

        if ($tariff === null) {
            return $this->redirectToRoute('tariff_index');
        }

        $form = $this->createForm(TariffType::class, $tariff);
        $form ->handleRequest($request);

        if ($form->isSubmitted()&&$form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($tariff);
            $em->flush();
            return $this->redirectToRoute('tariff_index');
        }
        return $this->render('tariff/delete.html.twig',
            array('tariff' => $tariff,
                'form' => $form->createView()));
    }

}
