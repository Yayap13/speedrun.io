<?php

namespace Yap\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Article
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Yap\BlogBundle\Entity\ArticleRepository")
 */
class Article
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
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     * @Assert\DateTime()
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     * @Assert\Length(min = "10")
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     * @Assert\NotBlank()
     */
    private $content;

    /**
    * @ORM\Column(name="publication", type="boolean")
    */
    private $publication;

    /**
    * @ORM\OneToOne(targetEntity="Yap\BlogBundle\Entity\Image", cascade={"persist", "remove"})
    * @Assert\Valid()
    */
    private $image;

    /**
    * @ORM\ManyToMany(targetEntity="Yap\BlogBundle\Entity\Category", cascade={"persist"})
    */
    private $categories;

    /**
    * @Gedmo\Slug(fields={"title"})
    * @ORM\Column(length=128, unique=true)
    */
    private $slug;

    /**
    * @ORM\OneToMany(targetEntity="Yap\BlogBundle\Entity\Comment", mappedBy="article")
    */
    private $comments;

    /**
     * @var integer
     *
     * @ORM\Column(name="nbcomments", type="integer")
     */
    private $nbComments;

    /**
    * @ORM\ManyToOne(targetEntity="Yap\UserBundle\Entity\User")
    */
    private $user;

    public function __construct()
    {
        $this->date = new \DateTime();
        $this->publication = true;
        $this->nbComments = 0;
        $this->categories = new \Doctrine\Common\Collections\ArrayCollection();
        $this->comments = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set date
     *
     * @param \DateTime $date
     * @return Article
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
     * Set title
     *
     * @param string $title
     * @return Article
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Article
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set publication
     *
     * @param boolean $publication
     * @return Article
     */
    public function setPublication($publication)
    {
        $this->publication = $publication;

        return $this;
    }

    /**
     * Get publication
     *
     * @return boolean 
     */
    public function getPublication()
    {
        return $this->publication;
    }

    /**
     * Set image
     *
     * @param \Yap\BlogBundle\Entity\Image $image
     * @return Article
     */
    public function setImage(\Yap\BlogBundle\Entity\Image $image = null)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return \Yap\BlogBundle\Entity\Image 
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Add categories
     *
     * @param \Yap\BlogBundle\Entity\Category $categories
     * @return Article
     */
    public function addCategory(\Yap\BlogBundle\Entity\Category $categories)
    {
        $this->categories[] = $categories;

        return $this;
    }

    /**
     * Remove categories
     *
     * @param \Yap\BlogBundle\Entity\Category $categories
     */
    public function removeCategory(\Yap\BlogBundle\Entity\Category $categories)
    {
        $this->categories->removeElement($categories);
    }

    /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Add comments
     *
     * @param \YapBlogBundle\Entity\Comment $comments
     * @return Article
     */
    public function addComment(\YapBlogBundle\Entity\Comment $comments)
    {
        $this->comments[] = $comments;
        $comments->setArticle($this);

        return $this;
    }

    /**
     * Remove comments
     *
     * @param \YapBlogBundle\Entity\Comment $comments
     */
    public function removeComment(\YapBlogBundle\Entity\Comment $comments)
    {
        $this->comments->removeElement($comments);
    }

    /**
     * Get comments
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set nbComments
     *
     * @param integer $nbComments
     * @return Article
     */
    public function setNbComments($nbComments)
    {
        $this->nbComments = $nbComments;

        return $this;
    }

    /**
     * Get nbComments
     *
     * @return integer 
     */
    public function getNbComments()
    {
        return $this->nbComments;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Article
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
     * Set user
     *
     * @param \Yap\UserBundle\Entity\User $user
     * @return Article
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
}
