<?php

namespace Yap\SpeedrunBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * Difficulty
 *
 * @ORM\Table(name="spr_difficulty")
 * @ORM\Entity(repositoryClass="Yap\SpeedrunBundle\Entity\DifficultyRepository")
 * @ExclusionPolicy("all") 
 */
class Difficulty
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Expose
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     * @Expose
     */
    private $name;

    /**
    * @ORM\ManyToOne(targetEntity="Yap\SpeedrunBundle\Entity\Game", inversedBy="difficulties")
    * @ORM\JoinColumn(nullable=false)
    */
    private $game;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Difficulty
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set game
     *
     * @param \Yap\SpeedrunBundle\Entity\Game $game
     * @return Difficulty
     */
    public function setGame(\Yap\SpeedrunBundle\Entity\Game $game)
    {
        $this->game = $game;

        return $this;
    }

    /**
     * Get game
     *
     * @return \Yap\SpeedrunBundle\Entity\Game 
     */
    public function getGame()
    {
        return $this->game;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        
    }
}
