<?php

namespace Usine\MachineBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Usine\MachineBundle\MachineBundle;
use Symfony\Component\HttpFoundation\JsonResponse;


class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        return $this->render('MachineBundle:Default:index.html.twig');
    }

    /**
     * @Route("/welcome")
     */
    public function welcomeAction()
    {
        //fonction pour réinstaller la date pour aujourd'hui (pour le dev)
        $date =date('Y-m-d',strtotime("-1 days"));

        $time = new \DateTime();
        $time->format('Y-m-d');
        $repository = $this->getDoctrine()->getRepository("MachineBundle:Machine");
        $machines =$repository->findAll();
        foreach($machines as $machine){
            $datem =$machine->getDate();
            $datemachine=$datem->format('Y-m-d');
           if ($datemachine == $date){

                $id = $machine->getId();
               $em=$this->getDoctrine()->getManager();
               $m = $em->getRepository('MachineBundle:Machine')->find($id);
                $m->setDate($time);
                $em->flush();
            }
        }

        return $this->render('MachineBundle:Default:welcome.html.twig');
    }

    /**
     * @Route("/production", name="production")
     */
    public function productionAction()
    {
        //$date = strftime("%y-%m-%d", mktime( date('m'), date('d')-1, date('y')));

        $time = new \DateTime();
        $time->format('Y-m-d');
        $block = $this->getDoctrine()->getRepository('MachineBundle:Block')->findAll();
        foreach($block as $b){
            $id = $b->getId();
            $nb_pieces = 0;
            $repository = $this->getDoctrine()->getRepository("MachineBundle:Machine");
            $machines =$repository->findBy(array('block' => $id,'date'=>$time));
            foreach($machines as $machine){
                $nb_pieces += $machine->getNbPieceBonne();

            }
            $em = $this->getDoctrine()->getManager();
            $bl = $em->getRepository('MachineBundle:Block')->find($id);

            $bl->setNbPieceTotale($nb_pieces);
            $bl->setDate($time);
            $em->flush();


        }
        $blocks = $this->getDoctrine()->getRepository('MachineBundle:Block')->findBy(array('date'=>$time));
        return $this->render('MachineBundle:admin:production.html.twig',array('blocks' => $blocks));
    }

    /**
     * @Route("/alert", name="alert")
     */
    public function alertAction()
    {
        return $this->render('MachineBundle:admin:alert.html.twig');
    }

    /**
     * @Route("/utilisateur", name="utilisateur")
     */
    public function utilisateurAction()
    {
        return $this->render('MachineBundle:admin:utilisateur.html.twig');
    }

    /**
     * @Route("/details/{nom}", name="details")
     */
    public function detailsAction($nom)
    {

        $time = new \DateTime();
        $time->format('Y-m-d');
        //$machine = $repository->findOneBy(array('nomMachine' => 'Unité Assemblage 1','date'=>$time));
        $repository = $this->getDoctrine()->getRepository("MachineBundle:Machine");

        $machines =$repository->findBy(array('block' => $nom,'date'=>$time),array('id' => 'asc'));
        $block = $this->getDoctrine()->getRepository('MachineBundle:Block')->find($nom);
        $nb_pieces = 0;
      //$m = $block->getMachine();
       foreach($machines as $machine){
      $nb_pieces += $machine->getNbPieceBonne();

        }


        if($nom==1){
        return $this->render('MachineBundle:admin:detailsU.html.twig', array('blocknom' => $nom,'machines'=>$machines, 'date'=>$time,'nbP'=>$nb_pieces));
    }
    else if($nom==2) {

        return $this->render('MachineBundle:admin:detailsL.html.twig', array('blocknom' => $nom,'machines'=>$machines, 'date'=>$time,'nbP'=>$nb_pieces));
    }
    else if($nom==3){
        return $this->render('MachineBundle:admin:detailsellipse.html.twig');
    }
    else if($nom==4){
        return $this->render('MachineBundle:admin:detailsO.html.twig');
    }
    else if($nom==5){
        return $this->render('MachineBundle:admin:detailsL16.html.twig', array('blocknom' => $nom,'machines'=>$machines, 'date'=>$time,'nbP'=>$nb_pieces));
    }
    }

    /**
     * @Route("/machine/{id}", name="machinedetail")
     */
    public function detailmachineAction($id){
        $em = $this->getDoctrine()->getManager();
        $machinedetail = $bl = $em->getRepository('MachineBundle:Machine')->find($id);
        $machinename=$machinedetail->getNomMachine();
        $lien=$machinedetail->getLien();
        $obj=$machinedetail->getObjectif();
        $nbpb=$machinedetail->getNbPieceBonne();
        $nbpm=$machinedetail->getNbPieceMovaise();
        $efficacite=($nbpb*100)/($nbpm+$nbpb);
        $efficacite=number_format((float)$efficacite, 2, '.', '');
        $response = new JsonResponse();
        return $response->setData(array('nom'=>$machinename , 'lien'=>$lien , 'obj'=>$obj ,'efficacite'=>$efficacite));
    }
}



