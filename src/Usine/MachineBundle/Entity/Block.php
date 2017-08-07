<?php

namespace Usine\MachineBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Block
 *
 * @ORM\Table(name="block")
 * @ORM\Entity(repositoryClass="Usine\MachineBundle\Repository\BlockRepository")
 */
class Block
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nomBlock", type="string", length=30)
     */
    private $nomBlock;

    /**
     * @var int
     *
     * @ORM\Column(name="nb_piece_totale", type="integer")
     */
    private $nbPieceTotale;

    /**
     * @var int
     *
     * @ORM\Column(name="objectif", type="integer")
     */
    private $objectif;

    /**
     * @var string
     *
     * @ORM\Column(name="statu", type="string", length=20)
     */
    private $statu;

    /**
     * @var date
     *
     * @ORM\Column(name="date", type="date")
     */
    private $date;

    /**
     * @ORM\OneToMany(targetEntity="Machine",mappedBy="block")
     */
    private $machine ;

    /**
     * @return date
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param date $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nomBlock
     *
     * @param string $nomBlock
     *
     * @return Block
     */
    public function setNomBlock($nomBlock)
    {
        $this->nomBlock = $nomBlock;

        return $this;
    }

    /**
     * Get nomBlock
     *
     * @return string
     */
    public function getNomBlock()
    {
        return $this->nomBlock;
    }

    /**
     * Set nbPieceTotale
     *
     * @param integer $nbPieceTotale
     *
     * @return Block
     */
    public function setNbPieceTotale($nbPieceTotale)
    {
        $this->nbPieceTotale = $nbPieceTotale;

        return $this;
    }

    /**
     * Get nbPieceTotale
     *
     * @return int
     */
    public function getNbPieceTotale()
    {
        return $this->nbPieceTotale;
    }

    /**
     * Set objectif
     *
     * @param integer $objectif
     *
     * @return Block
     */
    public function setObjectif($objectif)
    {
        $this->objectif = $objectif;

        return $this;
    }

    /**
     * Get objectif
     *
     * @return int
     */
    public function getObjectif()
    {
        return $this->objectif;
    }

    /**
     * Set statu
     *
     * @param string $statu
     *
     * @return Block
     */
    public function setStatu($statu)
    {
        $this->statu = $statu;

        return $this;
    }

    /**
     * Get statu
     *
     * @return string
     */
    public function getStatu()
    {
        return $this->statu;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->machine = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add machine
     *
     * @param \Usine\MachineBundle\Entity\Machine $machine
     *
     * @return Block
     */
    public function addMachine(\Usine\MachineBundle\Entity\Machine $machine)
    {
        $this->machine[] = $machine;

        return $this;
    }

    /**
     * Remove machine
     *
     * @param \Usine\MachineBundle\Entity\Machine $machine
     */
    public function removeMachine(\Usine\MachineBundle\Entity\Machine $machine)
    {
        $this->machine->removeElement($machine);
    }

    /**
     * Get machine
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMachine()
    {
        return $this->machine;
    }
}
