<?php

namespace DentalClinicBundle\Controller;

use DentalClinicBundle\Entity\Material;
use DentalClinicBundle\Form\MaterialType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MaterialController extends Controller
{
    /**
     * @Route("/material/index", name="material_index")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewMaterialsAction($page =1)
    {
        $limit = 10;
        $thisPage = $page;

        $materials = $this->getDoctrine()
            ->getRepository(Material::class)
            ->getAllMaterials($thisPage);

        $maxPages = ceil($materials->count() / $limit);

        return $this->render('material/index.html.twig',
            ['materials' => $materials,
                'maxPages' => $maxPages,
                'thisPage' => $thisPage]);
    }

    /**
     * @param Request $request
     * @Route("/material/crreate", name="material_create")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createMaterialAction(Request $request)
    {
        $currentUser = $this->getUser();
        if (!$currentUser->isAdmin()&&!$currentUser->isFinancier())
        {
            $this->addFlash('info', "Access denied");
            return $this->redirectToRoute("homepage");
        }
        $material = new Material();
        $form = $this->createForm(MaterialType::class, $material);
        $form->handleRequest($request);

        if ($form->isSubmitted()&&$form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($material);
            $em->flush();
            return $this->redirectToRoute('material_index');
        }

        return $this->render('material/create.html.twig',
            array('form' => $form->createView()));
    }

    /**
     * @Route("/material/edit/{id}", name="material_edit")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editMaterialAction($id, Request $request)
    {
        $currentUser = $this->getUser();
        if (!$currentUser->isAdmin()&&!$currentUser->isFinancier())
        {
            $this->addFlash('info', "Access denied");
            return $this->redirectToRoute("homepage");
        }

        $material = $this->getDoctrine()
            ->getRepository(Material::class)
            ->find($id);

        if ($material === null) {
            return $this->redirectToRoute('material_index');
        }

        $form = $this->createForm(MaterialType::class, $material);
        $form->handleRequest($request);

        if ($form->isSubmitted()&&$form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($material);
            $em->flush();
            return $this->redirectToRoute('material_index',
                array('id' => $material->getId()));
        }
        return $this->render('material/edit.html.twig',
            ['material' => $material, 'form' => $form->createView()]);
    }

    /**
     * @Route("/material/delete/{id}", name="material_delete")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function deleteMaterialAction($id, Request $request)
    {
        $currentUser = $this->getUser();
        if (!$currentUser->isAdmin()&&!$currentUser->isFinancier())
        {
            $this->addFlash('info', "Access denied");
            return $this->redirectToRoute("homepage");
        }

        $material = $this->getDoctrine()
            ->getRepository(Material::class)
            ->find($id);

        if ($material === null) {
            return $this->redirectToRoute('material_index');
        }

        $form = $this->createForm(MaterialType::class, $material);
        $form ->handleRequest($request);

        if ($form->isSubmitted()&&$form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($material);
            $em->flush();
            return $this->redirectToRoute('material_index');
        }
        return $this->render('material/delete.html.twig',
            array('material' => $material,
                'form' => $form->createView()));
    }

}
