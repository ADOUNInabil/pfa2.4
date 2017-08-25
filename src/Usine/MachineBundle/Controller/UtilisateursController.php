<?php

namespace Usine\MachineBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

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
}
