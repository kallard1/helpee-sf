<?php

declare(strict_types=1);

/**
 * This file is a part of Helpee.
 *
 * @author  Kevin Allard <contact@allard-kevin.fr>
 *
 * @license 2018-2019 - Helpee
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Community.
 *
 * @ORM\Entity(repositoryClass="App\Repository\CommunityRepository")
 * @ORM\Table(name="communities")
 */
class Community
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid")
     */
    private $id;

    /**
     * @ORM\Column(type="string", nullable=false)
     *
     * @Assert\NotNull(message="Veuillez renseigner un nom.")
     */
    private $name;

    /**
     * @Gedmo\Slug(fields={"name"})
     *
     * @ORM\Column(length=128, unique=true)
     */
    private $slug;

    /**
     * @ORM\Column(type="text", nullable=false)
     *
     * @Assert\NotNull(message="Veuillez renseigner une description.")
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="City")
     * @ORM\JoinColumn(name="city_id", referencedColumnName="id", nullable=false)
     */
    private $city;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_creator_id", referencedColumnName="id")
     */
    private $creator;

    /**
     * Many Groups have Many Users.
     *
     * @ORM\ManyToMany(targetEntity="User", inversedBy="communities", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="communities_users")
     */
    private $members;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Ad\Ad", mappedBy="community")
     */
    private $ads;

    /**
     * @ORM\Column(name="is_enabled", type="boolean", nullable=false)
     */
    private $enabled;

    /**
     * @Gedmo\Timestampable(on="create")
     *
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @Gedmo\Timestampable(on="update")
     *
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * Community constructor.
     */
    public function __construct()
    {
        $this->members = new ArrayCollection();
        $this->ads = new ArrayCollection();
        $this->enabled = true;
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
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @return string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getCreator()
    {
        return $this->creator;
    }

    /**
     * @param mixed $creator
     */
    public function setCreator($creator): void
    {
        $this->creator = $creator;
    }

    /**
     * @param \App\Entity\User $user
     *
     * @return $this
     */
    public function addMember(User $user)
    {
        $this->members[] = $user;

        return $this;
    }

    /**
     * @param \App\Entity\User $user
     */
    public function removeMember(User $user)
    {
        $this->members->removeElement($user);
    }

    /**
     * @return mixed
     */
    public function getMembers()
    {
        return $this->members;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @param bool $isEnabled
     */
    public function setEnabled(bool $isEnabled): void
    {
        $this->enabled = $isEnabled;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     */
    public function setCity($city): void
    {
        $this->city = $city;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }
}
