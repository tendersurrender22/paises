<?php

namespace InicioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use InicioBundle\Form\UserType;
use InicioBundle\Entity\User;
use InicioBundle\Entity\State;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
    	$form = $this->createForm(UserType::class);
        return $this->render('InicioBundle:Default:index.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/new", name="user_new")
     */
    public function newAction(Request $request)
    {
      $user = new User();
      $form = $this->createForm(UserType::class);
      $form->handleRequest($request);

      if($form->isSubmitted() and $form->isValid()){
          $em = $this->getDoctrine()->getManager();
          $em->persist($user);
          $em->flush();
          return $this->redirectToAction('view');
      }

      return $this->render('InicioBundle:Default:new.html.twig', array(
          'form' => $form->createView(),
       ));
    }

    /**
     * @Route("/view", name="Inicio_listado")
     */
     public function viewAction()
     {
     	$datos = $this->getDoctrine()
                    ->getRepository('InicioBundle:User')
                    ->findALL();
         return $this->render('InicioBundle:Default:view.html.twig',compact("datos"));
     }

    /**
     * @Route(
     *      "/ajax-form",
     *      name="user_ajax_form",
     *      condition="request.isXmlHttpRequest()"
     * )
     */
    public function ajaxFormAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(new UserType());
        $form->handleRequest($request);

        return $this->render('InicioBundle:Default:new.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
