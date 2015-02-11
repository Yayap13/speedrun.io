<?php

namespace Yap\SpeedrunBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Time
 *
 * @ORM\Table(name="spr_time")
 * @ORM\Entity(repositoryClass="Yap\SpeedrunBundle\Entity\TimeRepository")
 */
class Time
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
     * @var integer
     *
     * @ORM\Column(name="time", type="integer")
     */
    private $time;

    /**
     * @var string
     *
     * @ORM\Column(name="note", type="text")
     */
    private $note;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     * @Assert\DateTime()
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="video", type="string", length=255)
     */
    private $video;

    /**
    * @var boolean
    *
    * @ORM\Column(name="pb", type="boolean")
    */
    private $pb;

    /**
    * @var boolean
    *
    * @ORM\Column(name="wr", type="boolean")
    */
    private $wr;

    /**
    * @var boolean
    *
    * @ORM\Column(name="oldwr", type="boolean")
    */
    private $oldwr;

    /**
    * @ORM\ManyToOne(targetEntity="Yap\SpeedrunBundle\Entity\Level", inversedBy="levels")
    * @ORM\JoinColumn(nullable=false)
    */
    private $level;

    /**
    * @ORM\ManyToOne(targetEntity="Yap\SpeedrunBundle\Entity\Linker", inversedBy="times")
    * @ORM\JoinColumn(nullable=false)
    */
    private $linker;

    /**
    * @ORM\ManyToOne(targetEntity="Yap\UserBundle\Entity\User")
    */
    private $user;


    public function __construct()
    {
        $this->date = new \DateTime();
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
     * Set level
     *
     * @param \Yap\SpeedrunBundle\Entity\Level $level
     * @return Time
     */
    public function setLevel(\Yap\SpeedrunBundle\Entity\Level $level = null)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level
     *
     * @return \Yap\SpeedrunBundle\Entity\Level 
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Set note
     *
     * @param string $note
     * @return Time
     */
    public function setNote($note)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Get note
     *
     * @return string 
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * Set user
     *
     * @param \Yap\UserBundle\Entity\User $user
     * @return Time
     */
    public function setUser(\Yap\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Yap\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set linker
     *
     * @param \Yap\SpeedrunBundle\Entity\Linker $linker
     * @return Time
     */
    public function setLinker(\Yap\SpeedrunBundle\Entity\Linker $linker)
    {
        $this->linker = $linker;

        return $this;
    }

    /**
     * Get linker
     *
     * @return \Yap\SpeedrunBundle\Entity\Linker 
     */
    public function getLinker()
    {
        return $this->linker;
    }

    /**
     * Set video
     *
     * @param string $video
     * @return Time
     */
    public function setVideo($video)
    {
        $this->video = $video;

        return $this;
    }

    /**
     * Get video
     *
     * @return string 
     */
    public function getVideo()
    {
        return $this->video;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Time
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set time
     *
     * @param integer $time
     * @return Time
     */
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Get time
     *
     * @return integer 
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set pb
     *
     * @param boolean $pb
     * @return Time
     */
    public function setPb($pb)
    {
        $this->pb = $pb;

        return $this;
    }

    /**
     * Get pb
     *
     * @return boolean 
     */
    public function getPb()
    {
        return $this->pb;
    }

    /**
     * Set wr
     *
     * @param boolean $wr
     * @return Time
     */
    public function setWr($wr)
    {
        $this->wr = $wr;

        return $this;
    }

    /**
     * Get wr
     *
     * @return boolean 
     */
    public function getWr()
    {
        return $this->wr;
    }

    /**
     * Set oldwr
     *
     * @param boolean $oldwr
     * @return Time
     */
    public function setOldwr($oldwr)
    {
        $this->oldwr = $oldwr;

        return $this;
    }

    /**
     * Get oldwr
     *
     * @return boolean 
     */
    public function getOldwr()
    {
        return $this->oldwr;
    }
}
