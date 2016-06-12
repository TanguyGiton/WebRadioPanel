<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Vote
 *
 * @ORM\Table(name="vote")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\VoteRepository")
 */
class Vote
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
     * @var \DateTime
     *
     * @ORM\Column(name="sendAt", type="datetime")
     */
    private $sendAt;

    /**
     * @var string
     *
     * @ORM\Column(name="IPAddress", type="string", length=45)
     */
    private $iPAddress;

    /**
     * @var bool
     *
     * @ORM\Column(name="positive", type="boolean")
     */
    private $positive;

    /**
     * @var Song
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Song")
     * @ORM\JoinColumn(nullable=false)
     */
    private $song;

    /**
     * Vote constructor.
     */
    public function __construct()
    {
        if ($this->getSendAt() === null) {
            $this->setSendAt(new \DateTime());
        }
    }

    /**
     * Get sendAt
     *
     * @return \DateTime
     */
    public function getSendAt()
    {
        return $this->sendAt;
    }

    /**
     * Set sendAt
     *
     * @param \DateTime $sendAt
     *
     * @return Vote
     */
    public function setSendAt($sendAt)
    {
        $this->sendAt = $sendAt;

        return $this;
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
     * Get iPAddress
     *
     * @return string
     */
    public function getIPAddress()
    {
        return $this->iPAddress;
    }

    /**
     * Set iPAddress
     *
     * @param string $iPAddress
     *
     * @return Vote
     */
    public function setIPAddress($iPAddress)
    {
        $this->iPAddress = $iPAddress;

        return $this;
    }

    /**
     * Is positive
     *
     * @return bool
     */
    public function isPositive()
    {
        return $this->positive;
    }

    /**
     * Get positive
     *
     * @return boolean
     */
    public function getPositive()
    {
        return $this->positive;
    }

    /**
     * Set positive
     *
     * @param bool $positive
     *
     * @return Vote
     */
    public function setPositive($positive)
    {
        $this->positive = $positive;

        return $this;
    }

    /**
     * Is negative
     *
     * @return bool
     */
    public function isNegative()
    {
        return !$this->positive;
    }

    /**
     * Get song
     *
     * @return Song
     */
    public function getSong()
    {
        return $this->song;
    }

    /**
     * Set song
     *
     * @param Song $song
     *
     * @return Vote
     */
    public function setSong(Song $song)
    {
        $this->song = $song;

        return $this;
    }
}
