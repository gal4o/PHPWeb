<?php

namespace DentalClinicBundle\Controller;

use DentalClinicBundle\Entity\Material;
use DentalClinicBundle\Entity\Order;
use DentalClinicBundle\Form\OrderType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends Controller
{
    /**
     * @param Request $request
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Route("/order/create", name="order_create")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createOrderAction(Request $request)
    {
        $order = new Order();
        $form = $this->createForm(OrderType::class, $order);
        $form->handleRequest($request);
        $materials = $this->getDoctrine()
            ->getRepository(Material::class)
            ->findAll();

        if ($form->isSubmitted() && $form->isValid()) {
            $order->setDentist($this->getUser());
            $materialRepo = $this->getDoctrine()
                ->getRepository(Material::class);
            $material = $materialRepo
                ->findOneBy(['name' => $request->get('material')]);
            $order->setMaterial($material);
            $order->setStatus('waiting');
            $em = $this->getDoctrine()->getManager();
            $em->persist($order);
            $em->flush();
            return $this->redirectToRoute("user_profile",
                ['id' =>$this->getUser()->getId()]);
        }

        return $this->render('order/create',
            array('form' => $form->createView(),
                'materials' => $materials));
    }

    /**
     * @Route("/order/view", name="order_view")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function viewOrderAction()
    {
        $currentUser = $this->getUser();
        if (!$currentUser->isAdmin()&&!$currentUser->isFinancier())
        {
            $this->addFlash('info', "Access denied");
            return $this->redirectToRoute("homepage");
        }

        if (
            isset($_POST['filter_supplier'])||
            isset($_POST['filter_status'])) {

            $filter_suppl = $_POST['filter_supplier'];
            $filter_status = $_POST['filter_status'];
            $parameters = array('supplier' => $filter_suppl,
                'status' => $filter_status);
            $orders = $this->getDoctrine()
                ->getRepository(Order::class)
                ->findByFilters($parameters);
        } else {
            $orders = $this->getDoctrine()
                ->getRepository(Order::class)
                ->findAll();
        }

        $needMaterials = [];
        foreach ($orders as $order) {
            if (array_key_exists($order->getMaterial()->getName(), $needMaterials)) {
                $needMaterials[$order->getMaterial()->getName()] += $order->getQuantity();
            } else {
                $needMaterials[$order->getMaterial()->getName()] = $order->getQuantity();
            }
        }
        return $this->render('order/view.html.twig', [
            'orders' => $orders,
                'needMaterials' => $needMaterials]);
    }

    /**
     * @Route("/order/view/{id}", name="order_view_my")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewMyOrderAction($id)
    {
        $currentUser = $this->getUser();
        if (!$currentUser->isAdmin()&&!$currentUser->isFinancier()&&$currentUser->getId()!=$id)
        {
            $this->addFlash('info', "Access denied");
            return $this->redirectToRoute("homepage");
        }
        $orders = $this->getDoctrine()
            ->getRepository(Order::class)
            ->findBy(['dentist' => $id]);
        return $this->render('order/viewMy.html.twig', ['orders' => $orders]);
    }

    /**
     * @Route("/order/processing/{id}", name="order_processing")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function processingOrderAction($id)
    {
        $currentUser = $this->getUser();
        if (!$currentUser->isAdmin() && !$currentUser->isFinancier()) {
            $this->addFlash('info', "Access denied");
            return $this->redirectToRoute("homepage");
        }

        $order = $this->getDoctrine()
            ->getRepository(Order::class)
            ->find($id);

        if ($order === null) {
            $this->addFlash('info', "This order does not exist.");
            return $this->redirectToRoute('order_view');
        }

        $order->setStatus('processing');
        $em = $this->getDoctrine()->getManager();
        $em->persist($order);
        $em->flush();
        return $this->redirectToRoute('order_view');
    }

    /**
     * @Route("/order/ready/{id}", name="order_ready")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function readyOrderAction($id)
    {
        $currentUser = $this->getUser();
        if (!$currentUser->isAdmin() && !$currentUser->isFinancier()) {
            $this->addFlash('info', "Access denied");
            return $this->redirectToRoute("homepage");
        }

        $order = $this->getDoctrine()
            ->getRepository(Order::class)
            ->find($id);

        if ($order === null) {
            $this->addFlash('info', "This order does not exist.");
            return $this->redirectToRoute('order_view');
        }

        $order->setStatus('ready');
        $em = $this->getDoctrine()->getManager();
        $em->persist($order);
        $em->flush();
        return $this->redirectToRoute('order_view');
    }


    /**
     * @Route("/order/denied/{id}", name="order_denied")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function deniedOrderAction($id)
    {
        $currentUser = $this->getUser();
        if (!$currentUser->isAdmin() && !$currentUser->isFinancier()) {
            $this->addFlash('info', "Access denied");
            return $this->redirectToRoute("homepage");
        }

        $order = $this->getDoctrine()
            ->getRepository(Order::class)
            ->find($id);

        if ($order === null) {
            $this->addFlash('info', "This order does not exist.");
            return $this->redirectToRoute('order_view');
        }

        $order->setStatus('denied');
        $em = $this->getDoctrine()->getManager();
        $em->persist($order);
        $em->flush();
        return $this->redirectToRoute('order_view');
    }


    /**
     * @Route("/order/edit/{id}", name="order_edit")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editOrderAction($id, Request $request)
    {
        $currentUser = $this->getUser();
        if (!$currentUser->isAdmin()&&!$currentUser->isFinancier())
        {
            $this->addFlash('info', "Access denied");
            return $this->redirectToRoute("homepage");
        }

        $order = $this->getDoctrine()
            ->getRepository(Order::class)
            ->find($id);

        if ($order === null) {
            $this->addFlash('info', "This order does not exist.");
            return $this->redirectToRoute('order_view');
        }

        $form = $this->createForm(OrderType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted()&&$form->isValid())
        {
        $em = $this->getDoctrine()->getManager();
        $em->persist($order);
        $em->flush();
        return $this->redirectToRoute('order_view');
        }

        return $this->render('order/edit.html.twig',
            ['order' => $order, 'form' => $form->createView()]);
    }
}
