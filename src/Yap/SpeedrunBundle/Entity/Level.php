<?php

namespace Yap\SpeedrunBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Level
 *
 * @ORM\Table(name="spr_level")
 * @ORM\Entity(repositoryClass="Yap\SpeedrunBundle\Entity\LevelRepository")
 */
class Level
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
    * @Gedmo\Slug(fields={"name"})
    * @ORM\Column(length=128, unique=true)
    */
    private $slug;

    /**
    * @ORM\ManyToOne(targetEntity="Yap\SpeedrunBundle\Entity\Game", inversedBy="levels")
    * @ORM\JoinColumn(nullable=false)
    */
    private $game;

    /**
    * @ORM\OneToMany(targetEntity="Yap\SpeedrunBundle\Entity\Time", mappedBy="level", cascade={"persist"})
    */
    private $levels;


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
     * @return Level
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
     * @return Level
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
     * Set slug
     *
     * @param string $slug
     * @return Level
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->levels = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add levels
     *
     * @param \Yap\SpeedrunBundle\Entity\Time $levels
     * @return Level
     */
    public function addLevel(\Yap\SpeedrunBundle\Entity\Time $levels)
    {
        $this->levels[] = $levels;

        return $this;
    }

    /**
     * Remove levels
     *
     * @param \Yap\SpeedrunBundle\Entity\Time $levels
     */
    public function removeLevel(\Yap\SpeedrunBundle\Entity\Time $levels)
    {
        $this->levels->removeElement($levels);
    }

    /**
     * Get levels
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getLevels()
    {
        return $this->levels;
    }
}
