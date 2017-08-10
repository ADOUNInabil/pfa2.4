<?php

namespace Usine\MachineBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Stock
 *
 * @ORM\Table(name="stock")
 * @ORM\Entity(repositoryClass="Usine\MachineBundle\Repository\StockRepository")
 */
class Stock
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
     * @ORM\Column(name="nomArticle", type="string", length=50)
     */
    private $nomArticle;

    /**
     * @var int
     *
     * @ORM\Column(name="quantite", type="integer")
     */
    private $quantite;

    /**
     * @var string
     *
     * @ORM\Column(name="prix", type="decimal", precision=20, scale=3)
     */
    private $prix;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateentre", type="date")
     */
    private $dateentre;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datesortie", type="date")
     */
    private $datesortie;




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
     * Set nomArticle
     *
     * @param string $nomArticle
     *
     * @return Stock
     */
    public function setNomArticle($nomArticle)
    {
        $this->nomArticle = $nomArticle;

        return $this;
    }

    /**
     * Get nomArticle
     *
     * @return string
     */
    public function getNomArticle()
    {
        return $this->nomArticle;
    }

    /**
     * Set quantite
     *
     * @param integer $quantite
     *
     * @return Stock
     */
    public function setQuantite($quantite)
    {
        $this->quantite = $quantite;

        return $this;
    }

    /**
     * Get quantite
     *
     * @return int
     */
    public function getQuantite()
    {
        return $this->quantite;
    }

    /**
     * Set prix
     *
     * @param string $prix
     *
     * @return Stock
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * Get prix
     *
     * @return string
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * Set dateentre
     *
     * @param \DateTime $dateentre
     *
     * @return Stock
     */
    public function setDateentre($dateentre)
    {
        $this->dateentre = $dateentre;

        return $this;
    }

    /**
     * Get dateentre
     *
     * @return \DateTime
     */
    public function getDateentre()
    {
        return $this->dateentre;
    }

    /**
     * Set datesortie
     *
     * @param \DateTime $datesortie
     *
     * @return Stock
     */
    public function setDatesortie($datesortie)
    {
        $this->datesortie = $datesortie;

        return $this;
    }

    /**
     * Get datesortie
     *
     * @return \DateTime
     */
    public function getDatesortie()
    {
        return $this->datesortie;
    }

}
