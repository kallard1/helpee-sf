<?php

declare(strict_types=1);

/**
 * This file is a part of Helpee
 *
 * @author  Kevin Allard <contact@allard-kevin.fr>
 *
 * @license 2018
 */

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Serializable;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class User.
 *
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User implements UserInterface, Serializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=75, nullable=false)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=75, nullable=false)
     */
    private $lastname;

    /**
     * @var string
     */
    private $plainPassword;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $email;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $personalImage;

    /**
     * @ORM\Column(name="is_verified", type="boolean", nullable=true)
     */
    private $verified;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $verificationToken;

    /**
     * @ORM\Column(name="is_banned", type="boolean", nullable=true)
     */
    private $banned;

    /**
     * @ORM\Column(type="json", nullable=false)
     */
    private $roles = [];

    /**
     * @ORM\OneToOne(targetEntity="InformationUser", mappedBy="user", cascade={"persist", "remove"})
     */
    private $informationUser;

    /**
     * @ORM\ManyToMany(targetEntity="Hobby")
     * @ORM\JoinTable(name="hobbies_users",
     *     joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="hobby_id", referencedColumnName="id")}
     * )
     */
    private $hobbies;

    /**
     * Many Users have Many Groups.
     *
     * @ORM\ManyToMany(targetEntity="Community", mappedBy="members")
     */
    private $communities;

    /**
     * One user has many orders. This is the inverse side.
     *
     * @ORM\OneToMany(targetEntity="Order", mappedBy="user")
     */
    private $orders;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Ad\Message\Thread", mappedBy="createdBy")
     */
    private $threads;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Ad\Message\Message", mappedBy="sender")
     */
    private $messages;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     *
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     *
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->communities = new ArrayCollection();
        $this->hobbies = new ArrayCollection();
        $this->orders = new ArrayCollection();
        $this->threads = new ArrayCollection();
        $this->messages = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    /**
     * @param string $firstname
     */
    public function setFirstname(string $firstname): void
    {
        $this->firstname = $firstname;
    }

    /**
     * @return string
     */
    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    /**
     * @param string $lastname
     */
    public function setLastname(string $lastname): void
    {
        $this->lastname = $lastname;
    }

    /**
     * @return string
     */
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    /**
     * @param string $plainPassword
     */
    public function setPlainPassword(string $plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }

    /**
     * @return string
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPersonalImage(): ?string
    {
        return $this->personalImage;
    }

    /**
     * @param string $personalImage
     */
    public function setPersonalImage(string $personalImage): void
    {
        $this->personalImage = $personalImage;
    }

    /**
     * @return bool
     */
    public function isVerified(): ?bool
    {
        return $this->verified;
    }

    /**
     * @param bool $isVerified
     */
    public function setVerified(bool $isVerified): void
    {
        $this->verified = $isVerified;
    }

    /**
     * @return string
     */
    public function getVerificationToken(): ?string
    {
        return $this->verificationToken;
    }

    /**
     * @param string $verificationToken
     */
    public function setVerificationToken(string $verificationToken): void
    {
        $this->verificationToken = $verificationToken;
    }

    /**
     * @return bool
     */
    public function isBanned(): ?bool
    {
        return $this->banned;
    }

    /**
     * @param bool $isBanned
     */
    public function setBanned(bool $isBanned): void
    {
        $this->banned = $isBanned;
    }

    /**
     * Returns the roles granted to the user.
     *
     *     public function getRoles()
     *     {
     *         return ['ROLE_USER'];
     *     }
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return array (Role|string)[] The user roles
     */
    public function getRoles(): array
    {
        $userRoles = $this->roles;

        if (empty($userRoles)) {
            $userRoles[] = 'ROLE_USER';
        }

        return array_unique($userRoles);
    }

    /**
     * @param array $roles
     *
     * @return \App\Entity\User
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getInformationUser()
    {
        return $this->informationUser;
    }

    /**
     * @param mixed $informationUser
     */
    public function setInformationUser(InformationUser $informationUser): void
    {
        $this->informationUser = $informationUser;
        $informationUser->setUser($this);
    }

    /**
     * @param Hobby $hobby
     *
     * @return $this
     */
    public function addHobby(Hobby $hobby)
    {
        $this->hobbies[] = $hobby;

        return $this;
    }

    /**
     * @param Hobby $hobby
     */
    public function removeHobby(Hobby $hobby)
    {
        $this->hobbies->removeElement($hobby);
    }

    /**
     * @return mixed
     */
    public function getHobbies()
    {
        return $this->hobbies;
    }

    /**
     * @param \App\Entity\Community $community
     *
     * @return $this
     */
    public function addCommunity(Community $community)
    {
        $this->communities[] = $community;

        return $this;
    }

    /**
     * @param \App\Entity\Community $community
     */
    public function removeCommunity(Community $community)
    {
        $this->communities->removeElement($community);
    }

    /**
     * @return mixed
     */
    public function getCommunities()
    {
        return $this->communities;
    }

    /**
     * @param \App\Entity\Order $order
     *
     * @return $this
     */
    public function addOrder(Order $order)
    {
        $this->orders[] = $order;
        $order->setUser($this);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getOrders()
    {
        return $this->orders;
    }

    /**
     * Get updated date document.
     *
     * @return \DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * Get updated date document.
     *
     * @return \DateTime
     */
    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    /**
     * String representation of object.
     *
     * @see   https://php.net/manual/en/serializable.serialize.php
     *
     * @return string the string representation of the object or null
     *
     * @since 5.1.0
     */
    public function serialize()
    {
        return serialize([$this->id, $this->email, $this->password]);
    }

    /**
     * Constructs the object.
     *
     * @param string $serialized <p>
     *                           The string representation of the object.
     *                           </p>
     *
     * @return null
     *
     * @since 5.1.0
     *
     * @see   https://php.net/manual/en/serializable.unserialize.php
     *
     */
    public function unserialize($serialized)
    {
        [$this->id, $this->email, $this->password] = unserialize($serialized, ['allowed_classes' => false]);
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
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername()
    {
        return null;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     *
     * @return void
     */
    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }
}
