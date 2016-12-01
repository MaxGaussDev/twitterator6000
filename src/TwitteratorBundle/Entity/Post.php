<?php

namespace TwitteratorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Post
 *
 * @ORM\Table(name="post",options={"engine":"MyISAM"})
 * @ORM\Entity(repositoryClass="TwitteratorBundle\Repository\PostRepository")
 */
class Post
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
     * @ORM\Column(name="content", type="string", length=255)
     */
    private $content;

    /**
     * @var int
     *
     * @ORM\Column(name="userIdFk", type="integer")
     */
    private $userIdFk;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

    /**
     * @var string
     *
     * @ORM\Column(name="postedAt", type="string", length=255)
     */
    private $postedAt;


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
     * Set content
     *
     * @param string $content
     *
     * @return Post
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
     * Set userIdFk
     *
     * @param integer $userIdFk
     *
     * @return Post
     */
    public function setUserIdFk($userIdFk)
    {
        $this->userIdFk = $userIdFk;

        return $this;
    }

    /**
     * Get userIdFk
     *
     * @return int
     */
    public function getUserIdFk()
    {
        return $this->userIdFk;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Post
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set postedAt
     *
     * @param string $postedAt
     *
     * @return Post
     */
    public function setPostedAt($postedAt)
    {
        $this->postedAt = $postedAt;

        return $this;
    }

    /**
     * Get postedAt
     *
     * @return string
     */
    public function getPostedAt()
    {
        return $this->postedAt;
    }
}

