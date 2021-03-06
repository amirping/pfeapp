<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Vote
 *
 * @ORM\Table(name="votes")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\VoteRepository")
 */
class Vote
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
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }



     /**
     * @ORM\ManyToOne(targetEntity="Projet", inversedBy="votes")
     * @ORM\JoinColumn(name="projet_id", referencedColumnName="id")
     */
    private $projet;

    public function getProjet()
   {
     return $this->projet;
   }

   public function setProjet(Projet $projet)
    {   
        $this->projet = $projet;
    }


    /**
     * @ORM\ManyToOne(targetEntity="Citoyen", inversedBy="votes")
     * @ORM\JoinColumn(name="citoyen_id", referencedColumnName="id")
     */
    private $citoyen;
    
    public function getCitoyen()
   {
     return $this->citoyen;
   }

   public function setCitoyen(Citoyen $citoyen)
    {   
        $this->citoyen = $citoyen;
    }
}

