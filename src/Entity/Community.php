<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Class Community
 *
 * @package App\Entity
 * @ORM\Entity(repositoryClass="App\Repository\CommunityRepository")
 * @ORM\Table(name="communities")
 */
class Community {
    /**
     * @var
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $name;

    /**
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(length=128, unique=true)
     */
    private $slug;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=false)
     */
    private $description;

    /**
     * @var string
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_creator_id", referencedColumnName="id")
     */
    private $creator;

    /**
     * Many Groups have Many Users.
     * @ORM\ManyToMany(targetEntity="User", mappedBy="communities")
     */
    private $members;

    /**
     * @var boolean
     * @ORM\Column(name="is_enabled", type="boolean", nullable=false)
     */
    private $enabled;

    /**
     * @ORM\ManyToOne(targetEntity="City")
     * @ORM\JoinColumn(name="city_id", referencedColumnName="id")
     */
    private $city;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    public function __construct()
    {
        $this->members = new ArrayCollection();
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
    public function getName(): string
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
    public function getDescription(): string
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
     * @return string
     */
    public function getCreator(): string
    {
        return $this->creator;
    }

    /**
     * @param string $creator
     */
    public function setCreator(string $creator): void
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
    public function removeMember(User $user) {
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
     * @param bool $is_enabled
     */
    public function setEnabled(bool $is_enabled): void
    {
        $this->enabled = $is_enabled;
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
