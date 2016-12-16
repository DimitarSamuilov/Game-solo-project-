<?php

namespace ArchBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * User
 *
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="ArchBundle\Repository\UserRepository")
 */
class User implements UserInterface
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
     * @ORM\Column(name="username", type="string", length=255, unique=true)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     */
    private $email;

    /**
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="ArchBundle\Entity\Role",inversedBy="users")
     * @ORM\JoinTable(name="user_roles",
     *     joinColumns={@ORM\JoinColumn(name="user_id",referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="role_id",referencedColumnName="id")}
     *     )
     */
    private $roles;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="ArchBundle\Entity\Base",mappedBy="user")
     */
    private $bases;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="ArchBundle\Entity\BattleLog",mappedBy="user")
     *
     */
    private $battleLogs;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="ArchBundle\Entity\UserMessage",mappedBy="sender")
     */
    private $sendMessages;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="ArchBundle\Entity\UserMessage",mappedBy="receiver")
     */
    private $receivedMessages;


    public function __construct()
    {
        $this->sendMessages=new ArrayCollection();
        $this->receivedMessages=new ArrayCollection();
        $this->battleLogs=new ArrayCollection();
        $this->bases= new ArrayCollection();
        $this->bases = new ArrayCollection();
        $this->roles = new ArrayCollection();
    }
    public function addSendMessages($message)
    {
        $this->sendMessages[]=$message;
        return $this;
    }

    public function addReceivedMessages($message)
    {

        $this->receivedMessages[]=$message;
        return $this;
    }
    /**
     * @return ArrayCollection
     */
    public function getSendMessages()
    {
        return $this->sendMessages;
    }

    /**
     * @param ArrayCollection $sendMessages
     */
    public function setSendMessages( $sendMessages)
    {
        $this->sendMessages = $sendMessages;
    }

    /**
     * @return ArrayCollection
     */
    public function getReceivedMessages()
    {
        return $this->receivedMessages;
    }

    /**
     * @param ArrayCollection $receivedMessages
     */
    public function setReceivedMessages( $receivedMessages)
    {
        $this->receivedMessages = $receivedMessages;
    }



    function __toString()
    {
        return $this->getUsername();
    }

    public function addBattleLog($battleLog)
    {
        $this->battleLogs[]=$battleLog;
        return $this;
    }
    /**
     * @return ArrayCollection
     */
    public function getBattleLogs()
    {
        return $this->battleLogs;
    }

    /**
     * @param ArrayCollection $battleLogs
     */
    public function setBattleLogs($battleLogs)
    {
        $this->battleLogs = $battleLogs;
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
     * Set username
     *
     * @param string $username
     *
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Returns the roles granted to the user.
     *
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return (Role|string)[] The user roles
     */
    public function getRoles()
    {
        $stringRoles = [];
        foreach ($this->roles as $role) {
            /**
             * @var $role Role
             */
            $stringRoles[] = is_string($role) ? $role : $role->getRole();
        }
        return $stringRoles;
    }


    public function addRoles(Role $role)
    {
        $this->roles[] = $role;
        return $this;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function setRoles(array $roles)
    {
        $this->roles = $roles;
        return $this;
    }

    public function isAdmin()
    {
        return in_array('ROLE_ADMIN', $this->getRoles());
    }

    public function getBases()
    {

        return $this->bases;
    }

    public function setBases($bases)
    {
        $this->bases = $bases;
    }

    public function addBase(Base $base)
    {
        $this->bases->add($base);

        return $this;
    }
}


