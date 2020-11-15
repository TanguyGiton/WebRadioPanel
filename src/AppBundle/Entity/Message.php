<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Message
 *
 * @ORM\Table(name="message")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MessageRepository")
 *
 *
 */
class Message
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
     *
     * @Assert\DateTime()
     * @Assert\NotNull()
     */
    private $sendAt;

    /**
     * @var string
     *
     * @ORM\Column(name="IPAddress", type="string", length=45)
     *
     * @Assert\NotBlank()
     * 
     */
    private $iPAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=30)
     *
     * @Assert\Length(min="3",minMessage="message.name.min_length",max="30",maxMessage="message.name.max_length")
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     *
     * @Assert\NotBlank(message="message.content.not_blank")
     */
    private $content;


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
     * @return Message
     */
    public function setSendAt($sendAt)
    {
        $this->sendAt = $sendAt;

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
     * Set name
     *
     * @param string $name
     *
     * @return Message
     */
    public function setName($name)
    {
        $this->name = $name;

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
     * Set content
     *
     * @param string $content
     *
     * @return Message
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
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
     * @return Message
     */
    public function setIPAddress($iPAddress)
    {
        $this->iPAddress = $iPAddress;

        return $this;
    }
}
