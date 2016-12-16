<?php

namespace ArchBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserMessage
 *
 * @ORM\Table(name="user_messages")
 * @ORM\Entity(repositoryClass="ArchBundle\Repository\UserMessageRepository")
 */
class UserMessage
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
     * @var mixed
     *
     * @ORM\Column(name="send", type="datetime")
     */
    private $send;
    /**
     * @var string
     *
     * @ORM\Column(name="recipient",type="string")
     */
    private $recipient;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var bool
     *
     * @ORM\Column(name="isRead", type="boolean")
     */
    private $isRead;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="ArchBundle\Entity\User",inversedBy="sendMessages")
     * @ORM\JoinTable(name="sender_id")
     */
    private $sender;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="ArchBundle\Entity\User",inversedBy="receivedMessages")
     * @ORM\JoinTable(name="receiver_id")
     */
    private $receiver;

    /**
     * @return User
     */
    public function getSender(): User
    {
        return $this->sender;
    }

    /**
     * @param User $sender
     */
    public function setSender(User $sender)
    {
        $this->sender = $sender;
    }

    /**
     * @return User
     */
    public function getReceiver(): User
    {
        return $this->receiver;
    }

    /**
     * @param User $receiver
     */
    public function setReceiver(User $receiver)
    {
        $this->receiver = $receiver;
    }




    /**
     * @return string
     */
    public function getRecipient()
    {
        return $this->recipient;
    }

    /**
     * @param string $recipient
     */
    public function setRecipient($recipient)
    {
        $this->recipient = $recipient;
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
     * Set send
     *
     * @param mixed $send
     *
     * @return UserMessage
     */
    public function setSend($send)
    {
        $this->send = $send;

        return $this;
    }

    /**
     * Get send
     *
     * @return mixed
     */
    public function getSend()
    {
        return $this->send;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return UserMessage
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
     * Set isRead
     *
     * @param boolean $isRead
     *
     * @return UserMessage
     */
    public function setIsRead($isRead)
    {
        $this->isRead = $isRead;

        return $this;
    }

    /**
     * Get isRead
     *
     * @return bool
     */
    public function getIsRead()
    {
        return $this->isRead;
    }
}

