<?php

namespace Yap\SpeedrunBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * Platform
 *
 * @ORM\Table(name="spr_platform")
 * @ORM\Entity(repositoryClass="Yap\SpeedrunBundle\Entity\PlatformRepository")
 * @ExclusionPolicy("all") 
 */
class Platform
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
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     * @Expose
     */
    private $description;

    /**
    * @ORM\OneToMany(targetEntity="Yap\SpeedrunBundle\Entity\Linker", mappedBy="platform", cascade={"persist"})
    */
    private $linkers;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->linkers = null;
    }

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
     * @return Platform
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
     * Set description
     *
     * @param string $description
     * @return Platform
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Add linkers
     *
     * @param \Yap\SpeedrunBundle\Entity\Linker $linkers
     * @return Game
     */
    public function addLinker(\Yap\SpeedrunBundle\Entity\Linker $linkers)
    {
        $this->linkers[] = $linkers;

        return $this;
    }

    /**
     * Remove linkers
     *
     * @param \Yap\SpeedrunBundle\Entity\Linker $linkers
     */
    public function removeLinker(\Yap\SpeedrunBundle\Entity\Linker $linkers)
    {
        $this->linkers->removeElement($linkers);
    }

    /**
     * Get linkers
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getLinkers()
    {
        return $this->linkers;
    }
}
