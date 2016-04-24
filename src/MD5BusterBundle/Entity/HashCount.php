<?php

namespace MD5BusterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HashCount
 *
 * @ORM\Table(name="hash_count")
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 */
class HashCount
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
     * @var int
     *
     * @ORM\Column(name="hash_count", type="bigint")
     */
    private $count;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated", type="datetime")
     */
    private $updated;

    /**
     * @ORM\PreUpdate()
     */
    public function updateDate()
    {
        $this->setUpdated( new \DateTime() );
    }

    /**
     * defaults
     */
    public function __construct()
    {
        $this->setUpdated( new \DateTime() )->setCount( 0 );
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
     * Set count
     *
     * @param integer $count
     *
     * @return HashCount
     */
    public function setCount($count)
    {
        $this->count = $count;

        return $this;
    }

    /**
     * Get count
     *
     * @return int
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     *
     * @return HashCount
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }
}

