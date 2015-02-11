<?php

namespace Yap\SpeedrunBundle\Entity;

use DCS\RatingBundle\Entity\Rating as BaseRating;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Yap\SpeedrunBundle\Entity\RatingRepository")
 * @ORM\ChangeTrackingPolicy("DEFERRED_EXPLICIT")
 */
class Rating extends BaseRating
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="Yap\SpeedrunBundle\Entity\Vote", mappedBy="rating")
     */
    protected $votes;
}