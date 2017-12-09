<?php

namespace Usine\MachineBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Usine\MachineBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

class UtilisateursController extends Controller
{
    /**
     * @Route("/admin",name="admin")
     */
    public function listuserAction()
    {
        $userManager = $this->get('fos_user.user_manager');
        $users = $userManager->findUsers();

        //$em =$this->getDoctrine()->getManager();
        //$user = $em ->getRepository('MachineBundle:User')->find(5);
        //$em->remove($user);
        //$em->flush();


        return $this->render('MachineBundle:admin:listuser.html.twig', array('users' =>   $users));
    }


    /**
     * @Route("/userlistnotif",name="userlistnotif")
     */
    public function userlistnotifAction()
    {
        $userManager = $this->get('fos_user.user_manager');
        $users = $userManager->findUsers();

        $em=$this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('count(User)');
        $qb->from('MachineBundle:User','User');

        $countuser = $qb->getQuery()->getSingleScalarResult();
        return $this->render('MachineBundle:admin:utilisateur.html.twig', array('users' =>   $users ,'countuser'=>$countuser));
    }

    /**
     * @Route("/user/{id}", name="userdelete")
     */
    public function userdeleteAction($id){
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('MachineBundle:User')->find($id);
        $em->remove($user);
        $em->flush();
        $response = new JsonResponse();
        return $response->setData("OK");

    }

    /**
     * Creates a new stock entity.
     *
     * @Route("/newuser", name="new_user")
     * @Method({"GET", "POST"})
     */
    public function newuserAction(Request $request)
    {
        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->createUser();
        //$user = new User();
        $form = $this->createForm('Usine\MachineBundle\Form\RegistrationType', $user);
        $form->handleRequest($request);
        $userManager = $this->get('fos_user.user_manager');
        $users = $userManager->findUsers();

        if ($form->isSubmitted() && $form->isValid()) {
           // $em = $this->getDoctrine()->getManager();

            //$em->persist($user);
            //$em->flush();
            //$userManager = $this->get('fos_user.user_manager');
            $userManager->updateUser($user);
           // $users = $userManager->findUsers();

            return $this->render('MachineBundle:admin:listuser.html.twig', array('users' =>   $users));
        }

        return $this->render('MachineBundle:admin:newuser.html.twig', array(
            'user' => $user,
            'form' => $form->createView(),
        ));
    }

}
