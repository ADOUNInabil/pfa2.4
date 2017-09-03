<?php

namespace Usine\MachineBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Usine\MachineBundle\MachineBundle;
use Symfony\Component\HttpFoundation\JsonResponse;
use Usine\MachineBundle\Entity\Alert;
use Usine\MachineBundle\Entity\Block;


class DefaultController extends Controller
{
    /**
     * @Route("/a")
     */
    public function indexAction()
    {
        return $this->render('MachineBundle:Default:index.html.twig');
    }

    /**
     * @Route("/")
     */
    public function welcomeAction()
    {
        //fonction pour réinstaller la date machine pour aujourd'hui (pour le dev)
        $date =date('Y-m-d',strtotime("-1 days"));

        $time = new \DateTime();
        $time->format('Y-m-d');
        $repository = $this->getDoctrine()->getRepository("MachineBundle:Machine");
        $machines =$repository->findAll();
        foreach($machines as $machine){
            $datem =$machine->getDate();
            $datemachine=$datem->format('Y-m-d');
          // if ($datemachine == $date){

                $id = $machine->getId();
               $em=$this->getDoctrine()->getManager();
               $m = $em->getRepository('MachineBundle:Machine')->find($id);
                $m->setDate($time);
                $em->flush();
            //}
        }

        return $this->render('MachineBundle:Default:welcome.html.twig');
    }

    /**
     * @Route("/production", name="production")
     */
    public function productionAction()
    {
        //$date = strftime("%y-%m-%d", mktime( date('m'), date('d')-1, date('y')));
        $datebd = new \DateTime('-1 days');
        $datebd->format('Y-m-d');
        $date =date('Y-m-d',strtotime("-1 days"));
        $time = new \DateTime();
        $time->format('Y-m-d');
        $block = $this->getDoctrine()->getRepository('MachineBundle:Block')->findAll();
        foreach($block as $b){
            $nom =$b->getNomBlock();
            $datbl =$b->getDate();
            $dateblock=$datbl->format('Y-m-d');
            $block_exist = $this->getDoctrine()->getRepository('MachineBundle:Block')->findBy(array('nomBlock'=>$nom,'date'=>$datebd));
            if($block_exist){}
            else {
                $blockhier = new Block() ;
                $blockhier->setNomBlock($nom);
                $random = random_int(100, 180);
                $blockhier->setNbPieceTotale($random);
                $blockhier->setObjectif($b->getObjectif());
                $blockhier->setStatu($b->getStatu());
                $blockhier->setDate($datebd);
                $em=$this->getDoctrine()->getManager();
                $em->persist($blockhier);
                $em->flush();
            }
            /*
            $nom =$b->getNomBlock();
            $datbl =$b->getDate();
            $dateblock=$datbl->format('Y-m-d');
            $block_exist = $this->getDoctrine()->getRepository('MachineBundle:Block')->findBy(array('nomBlock'=>$nom,'date'=>$time));
            if($block_exist){}
            else {
                if ($dateblock == $date){

                    $blockhier = new Block() ;
                    $blockhier->setNomBlock($b->getNomBlock());
                    $random = random_int(100, 180);
                    $blockhier->setNbPieceTotale($random);
                    $blockhier->setObjectif($b->getObjectif());
                    $blockhier->setStatu($b->getStatu());
                    $blockhier->setDate($b->getDate());
                    $em=$this->getDoctrine()->getManager();
                    $em->persist($blockhier);
                    $em->flush();

                }}
            */
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


        //$alerts= $this->getDoctrine()->getRepository('MachineBundle:Alert')->findAll(array(),array('date' => 'DESC'));
        $em = $this->getDoctrine()->getManager();
        $alert= $this->getDoctrine()->getRepository('MachineBundle:Alert')->findAll();
        $qb = $em->createQueryBuilder();
        $qb->select('Alert');
        $qb->from('MachineBundle:Alert','Alert');
        $qb->orderBy('Alert.date','DESC');

        $alerts = $qb->getQuery()->getResult();
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
        //$blockshistory = $this->getDoctrine()->getRepository('MachineBundle:Block')->findBy(array('nomBlock'=>$blocknom));
        $nb_pieces = 0;
      //$m = $block->getMachine();
       foreach($machines as $machine){
      $nb_pieces += $machine->getNbPieceBonne();

        }

        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('Block')
            ->from('MachineBundle:Block','Block')
            ->where('Block.nomBlock = :nom')
            ->orderBy('Block.date', 'ASC')
            ->setParameter('nom', $blocknom);
        $blockshistory = $qb->getQuery()->getResult();

        if($nom==1){
        return $this->render('MachineBundle:admin:detailsU.html.twig', array('blockhistory'=>$blockshistory ,'blockactuel' => $blockactuel,'machines'=>$machines, 'date'=>$time,'nbP'=>$nb_pieces,'block'=>$block));
    }
    else if($nom==2) {

        return $this->render('MachineBundle:admin:detailsL.html.twig', array('blockhistory'=>$blockshistory ,'blockactuel' => $blockactuel,'machines'=>$machines, 'date'=>$time,'nbP'=>$nb_pieces,'block'=>$block));
    }
    else if($nom==3){
        return $this->render('MachineBundle:admin:detailsellipse.html.twig');
    }
    else if($nom==4){
        return $this->render('MachineBundle:admin:detailsO.html.twig');
    }
    else if($nom==5){
        return $this->render('MachineBundle:admin:detailsL16.html.twig', array('blockactuel' => $blockactuel,'machines'=>$machines, 'date'=>$time,'nbP'=>$nb_pieces,'block'=>$block));
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
                    $al->setDescription($ch);
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
                    $ale=new Alert();
                    $ale->setType('active');
                    $ale->setDate($time);
                    $ale->setEmplacement($machine->getNomMachine());
                    $ale->setBlock($machine->getBlock()->getNomBlock());
                    $nom=$machine->getNomMachine();
                    $ch=$nom . " efficacité inférieur a 90% ";
                    $ale->setDescription($ch);
                    $ale->setMachine($machine);
                    $em=$this->getDoctrine()->getManager();
                    $em->persist($ale);
                    $em->flush();
                }
                elseif (($efficacite<80)&&($efficacite>=70)){
                    $ale=new Alert();
                    $ale->setType('warning');
                    $ale->setDate($time);
                    $ale->setEmplacement($machine->getNomMachine());
                    $ale->setBlock($machine->getBlock()->getNomBlock());
                    $nom=$machine->getNomMachine();
                    $ch=$nom . " efficacité inférieur a 80% ";
                    $ale->setDescription($ch);
                    $ale->setMachine($machine);
                    $em=$this->getDoctrine()->getManager();
                    $em->persist($ale);
                    $em->flush();
                }
                elseif ($efficacite<70) {
                    $ale=new Alert();
                    $ale->setType('danger');
                    $ale->setDate($time);
                    $ale->setEmplacement($machine->getNomMachine());
                    $ale->setBlock($machine->getBlock()->getNomBlock());
                    $nom=$machine->getNomMachine();
                    $ch=$nom . " efficacité inférieur a 70% ";
                    $ale->setDescription($ch);
                    $ale->setMachine($machine);
                    $em=$this->getDoctrine()->getManager();
                    $em->persist($ale);
                    $em->flush();
                }
            }
        }



        $produits=$this->getDoctrine()->getRepository('MachineBundle:Stock')->findAll();
        foreach ($produits as $produit) {
            $dateprod=$produit->getDatesortie();
            $idprod=$produit->getId();
            if($dateprod < $time) {
                $alertprod=$this->getDoctrine()->getRepository('MachineBundle:Alert')->findOneBy(array('stock'=>$idprod));
                    if($alertprod){

                    }
                    else {
                        $alert=new Alert();
                        $alert->setType('danger');
                        $alert->setDate($time);
                        $alert->setEmplacement("-");
                        $alert->setBlock("-");
                        $ch="La date du stock ".$produit->getNomArticle()." a expiré !" ;
                        $alert->setDescription($ch);
                        $alert->setStock($produit);
                        $em=$this->getDoctrine()->getManager();
                        $em->persist($alert);
                        $em->flush();
                    }

            } else {
                $alertprod=$this->getDoctrine()->getRepository('MachineBundle:Alert')->findOneBy(array('stock'=>$idprod));

                if($alertprod){
                    $idalrp = $alertprod->getId();
                    $em=$this->getDoctrine()->getManager();
                    $alp = $em->getRepository('MachineBundle:Alert')->find($idalrp);
                    $em->remove($alp);
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
        $response = new JsonResponse();
        return $response->setData("OK");

    }

    /**
     * @Route("/s", name="stock")
     */
    public function stockAction(){

        return $this->render('MachineBundle:admin:stock.html.twig');
    }
}



