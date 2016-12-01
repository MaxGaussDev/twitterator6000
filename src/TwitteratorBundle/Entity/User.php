<?php

namespace TwitteratorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table(name="user",options={"engine":"MyISAM"})
 * @ORM\Entity(repositoryClass="TwitteratorBundle\Repository\UserRepository")
 */
class User
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
     * @ORM\Column(name="twitterId", type="string", length=255, unique=true)
     */
    private $twitterId;

    /**
     * @var string
     *
     * @ORM\Column(name="screenName", type="string", length=255)
     */
    private $screenName;

    /**
     * @var string
     *
     * @ORM\Column(name="fullName", type="string", length=255)
     */
    private $fullName;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

    function __construct($twitterId, $screenName, $fullName, $createdAt)
    {
        $this->twitterId = $twitterId;
        $this->screenName = $screenName;
        $this->fullName = $fullName;
        $this->createdAt = $createdAt;
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
     * Set twitterId
     *
     * @param string $twitterId
     *
     * @return User
     */
    public function setTwitterId($twitterId)
    {
        $this->twitterId = $twitterId;

        return $this;
    }

    /**
     * Get twitterId
     *
     * @return string
     */
    public function getTwitterId()
    {
        return $this->twitterId;
    }

    /**
     * Set screenName
     *
     * @param string $screenName
     *
     * @return User
     */
    public function setScreenName($screenName)
    {
        $this->screenName = $screenName;

        return $this;
    }

    /**
     * Get screenName
     *
     * @return string
     */
    public function getScreenName()
    {
        return $this->screenName;
    }

    /**
     * Set fullName
     *
     * @param string $fullName
     *
     * @return User
     */
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;

        return $this;
    }

    /**
     * Get fullName
     *
     * @return string
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return User
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->createdAt->format('Y-m-d H:i:s');
    }


}

