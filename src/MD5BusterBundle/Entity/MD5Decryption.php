<?php

namespace MD5BusterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MD5Decryption
 *
 * @ORM\Table(name="md5_decryption",indexes={@ORM\Index(name="search_idx", columns={"hash"})})
 * @ORM\Entity()
 */
class MD5Decryption
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer") // todo: convert to bigint
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="hash", type="string", length=32)
     */
    private $hash;

    /**
     * @var string
     *
     * @ORM\Column(name="decryption", type="string", length=255)
     */
    private $decryption;

    /**
     * @var boolean
     *
     * @ORM\Column(name="u_a", type="boolean")
     */
    private $userAdded;

    public function __construct()
    {
        $this->setUserAdded(false);
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
     * Set hash
     *
     * @param string $hash
     *
     * @return MD5Decryption
     */
    public function setHash($hash)
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * Get hash
     *
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * Set decryption
     *
     * @param string $decryption
     *
     * @return MD5Decryption
     */
    public function setDecryption($decryption)
    {
        $this->decryption = $decryption;

        return $this;
    }

    /**
     * Get decryption
     *
     * @return string
     */
    public function getDecryption()
    {
        return $this->decryption;
    }

    /**
     * Set userAdded
     *
     * @param boolean $userAdded
     *
     * @return MD5Decryption
     */
    public function setUserAdded($userAdded)
    {
        $this->userAdded = $userAdded;

        return $this;
    }

    /**
     * Get userAdded
     *
     * @return boolean
     */
    public function getUserAdded()
    {
        return $this->userAdded;
    }
}
