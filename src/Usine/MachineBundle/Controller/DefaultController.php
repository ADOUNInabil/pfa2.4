<?php

namespace Usine\MachineBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Usine\MachineBundle\MachineBundle;
use Symfony\Component\HttpFoundation\JsonResponse;
use Usine\MachineBundle\Entity\Alert;


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
        $date = new \DateTime('-1 days');
        $date->format('Y-m-d');
        $time = new \DateTime();
        $time->format('Y-m-d');
        $block = $this->getDoctrine()->getRepository('MachineBundle:Block')->findAll();
        foreach($block as $b){
            $id = $b->getId();
            $nb_pieces = 0;
            $repository = $this->getDoctrine()->getRepository("MachineBundle:Machine");
            $machines =$repository->findBy(array('block' => $id,'date'=>$time));
            if($machines){
            foreach($machines as $machine){
                $nb_pieces += $machine->getNbPieceBonne();

            }
            $em = $this->getDoctrine()->getManager();
            $bl = $em->getRepository('MachineBundle:Block')->find($id);

            $bl->setNbPieceTotale($nb_pieces);
            $bl->setDate($time);
            $em->flush();
            }

        }
        $blocks = $this->getDoctrine()->getRepository('MachineBundle:Block')->findBy(array('date'=>$time));
        return $this->render('MachineBundle:admin:production.html.twig',array('blocks' => $blocks));
    }

    /**
     * @Route("/alert", name="alert")
     */
    public function alertAction()
    {


        $alerts= $this->getDoctrine()->getRepository('MachineBundle:Alert')->findAll();
        return $this->render('MachineBundle:admin:alert.html.twig',array('alerts'=>$alerts));
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




        $date = new \DateTime('-1 days');
        $date->format('Y-m-d');
        $time = new \DateTime();
        $time->format('Y-m-d');
        //$machine = $repository->findOneBy(array('nomMachine' => 'Unité Assemblage 1','date'=>$time));
        $repository = $this->getDoctrine()->getRepository("MachineBundle:Machine");

        $machines =$repository->findBy(array('block' => $nom,'date'=>$time),array('id' => 'asc'));
        $blockactuel = $this->getDoctrine()->getRepository('MachineBundle:Block')->find($nom);
        $blocknom=$blockactuel->getNomBlock();
        //$blocktotal=$blockactuel->getNbPieceTotale();
        $block = $this->getDoctrine()->getRepository('MachineBundle:Block')->findOneBy(array('nomBlock'=>$blocknom,'date'=>$date));
        $nb_pieces = 0;
      //$m = $block->getMachine();
       foreach($machines as $machine){
      $nb_pieces += $machine->getNbPieceBonne();

        }


        if($nom==1){
        return $this->render('MachineBundle:admin:detailsU.html.twig', array('blockactuel' => $blockactuel,'machines'=>$machines, 'date'=>$time,'nbP'=>$nb_pieces,'block'=>$block));
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


    /**
     * @Route("/notification", name="notification")
     */
    public function notificationAction(){
        $time = new \DateTime();
        $time->format('Y-m-d');
        $machines= $this->getDoctrine()->getRepository('MachineBundle:Machine')->findBy(array('date'=>$time));


        foreach ($machines as $machine) {
            $nbpb=$machine->getNbPieceBonne();
            $nbpm=$machine->getNbPieceMovaise();
            $id=$machine->getId();
            $efficacite=($nbpb*100)/($nbpm+$nbpb);
            $alert= $this->getDoctrine()->getRepository('MachineBundle:Alert')->findOneBy(array('date'=>$time,'machine'=>$id));
            if ($alert){
                $id=$alert->getId();
                if (($efficacite<=90)&&($efficacite>=80)){

                    $em=$this->getDoctrine()->getManager();
                    $al = $em->getRepository('MachineBundle:Alert')->find($id);
                    $al->setType('active');
                    $nom=$machine->getNomMachine();
                    $ch=$nom . " efficacité inférieur a 90% ";
                    $alert->setDescription($ch);
                    $em->flush();
                }
                elseif (($efficacite<80)&&($efficacite>=70)){
                    $em=$this->getDoctrine()->getManager();
                    $al = $em->getRepository('MachineBundle:Alert')->find($id);
                    $al->setType('warning');
                    $nom=$machine->getNomMachine();
                    $ch=$nom . " efficacité inférieur a 80% ";
                    $al->setDescription($ch);
                    $em->flush();
                }
                elseif ($efficacite<=70) {
                    $em=$this->getDoctrine()->getManager();
                    $al = $em->getRepository('MachineBundle:Alert')->find($id);
                    $al->setType('danger');
                    $nom=$machine->getNomMachine();
                    $ch=$nom . " efficacité inférieur a 70% !";
                    $al->setDescription($ch);
                    $em->flush();
                }
                else {
                    $em=$this->getDoctrine()->getManager();
                    $al = $em->getRepository('MachineBundle:Alert')->find($id);
                    $em->remove($al);
                    $em->flush();
                }

            }
            else {


                if (($efficacite<=90)&&($efficacite>=80)){
                    $alert=new Alert();
                    $alert->setType('active');
                    $alert->setDate($time);
                    $alert->setEmplacement($machine->getNomMachine());
                    $alert->setBlock($machine->getBlock()->getNomBlock());
                    $nom=$machine->getNomMachine();
                    $ch=$nom . " efficacité inférieur a 90% ";
                    $alert->setDescription($ch);
                    $alert->setMachine($machine);
                    $em=$this->getDoctrine()->getManager();
                    $em->persist($alert);
                    $em->flush();
                }
                elseif (($efficacite<80)&&($efficacite>=70)){
                    $alert=new Alert();
                    $alert->setType('warning');
                    $alert->setDate($time);
                    $alert->setEmplacement($machine->getNomMachine());
                    $alert->setBlock($machine->getBlock()->getNomBlock());
                    $nom=$machine->getNomMachine();
                    $ch=$nom . " efficacité inférieur a 80% ";
                    $alert->setDescription($ch);
                    $alert->setMachine($machine);
                    $em=$this->getDoctrine()->getManager();
                    $em->persist($alert);
                    $em->flush();
                }
                elseif ($efficacite<70) {
                    $alert=new Alert();
                    $alert->setType('danger');
                    $alert->setDate($time);
                    $alert->setEmplacement($machine->getNomMachine());
                    $alert->setBlock($machine->getBlock()->getNomBlock());
                    $nom=$machine->getNomMachine();
                    $ch=$nom . " efficacité inférieur a 70% ";
                    $alert->setDescription($ch);
                    $alert->setMachine($machine);
                    $em=$this->getDoctrine()->getManager();
                    $em->persist($alert);
                    $em->flush();
                }
            }
        }
        $notifications= $this->getDoctrine()->getRepository('MachineBundle:Alert')->findAll();
        $qb = $em->createQueryBuilder();
        $qb->select('count(Alert)');
        $qb->from('MachineBundle:Alert','Alert');

        $count = $qb->getQuery()->getSingleScalarResult();
        return $this->render('MachineBundle:admin:notification.html.twig',array('notifications'=>$notifications,'count'=>$count));
    }

    /**
     * @Route("/notif/{id}", name="notifdelete")
     */
    public function notifdeleteAction($id){
        $em = $this->getDoctrine()->getManager();
        $notif = $em->getRepository('MachineBundle:Alert')->find($id);
        $em->remove($notif);
        $em->flush();
        return $this->render('MachineBundle:Default:welcome.html.twig');
    }

    /**
     * @Route("/s", name="stock")
     */
    public function stockAction(){

        return $this->render('MachineBundle:admin:stock.html.twig');
    }
}



