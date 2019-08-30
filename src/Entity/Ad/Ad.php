<?php

declare(strict_types=1);

/**
 * This file is a part of Helpee.
 *
 * @author  Kevin Allard <contact@allard-kevin.fr>
 *
 * @license 2018-2019 - Helpee
 */

namespace App\Entity\Ad;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Class Ad.
 *
 * @ORM\Entity(repositoryClass="App\Repository\Ad\AdRepository")
 * @ORM\Table(name="ads")
 */
class Ad
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Category", cascade={"persist", "remove"})
     *
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", nullable=false)
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", cascade={"persist", "remove"})
     *
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $title;

    /**
     * @Gedmo\Slug(fields={"title"})
     *
     * @ORM\Column(length=128, unique=true)
     */
    private $slug;

    /**
     * @ORM\Column(type="text", nullable=false)
     */
    private $description;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $enabled;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    private $uev;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Community", inversedBy="ads")
     *
     * @ORM\JoinColumn(name="community_id", referencedColumnName="id")
     */
    private $community;

    /**
     * One ad has many messages.
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Ad\Message\Thread", mappedBy="ad")
     */
    private $threads;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $transactionDate;

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
     * Ad constructor.
     */
    public function __construct()
    {
        $this->enabled = true;
        $this->threads = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     */
    public function setCategory($category): void
    {
        $this->category = $category;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user): void
    {
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
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
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @param bool $enabled
     */
    public function setEnabled(bool $enabled): void
    {
        $this->enabled = $enabled;
    }

    /**
     * @return int
     */
    public function getUev(): ?int
    {
        return $this->uev;
    }

    /**
     * @param int $uev
     */
    public function setUev(int $uev): void
    {
        $this->uev = $uev;
    }

    /**
     * @return mixed
     */
    public function getCommunity()
    {
        return $this->community;
    }

    /**
     * @param mixed $community
     */
    public function setCommunity($community): void
    {
        $this->community = $community;
    }

    /**
     * @return mixed
     */
    public function getThreads()
    {
        return $this->threads;
    }

    /**
     * @return mixed
     */
    public function getTransactionDate()
    {
        return $this->transactionDate;
    }

    /**
     * @param mixed $transactionDate
     */
    public function setTransactionDate($transactionDate): void
    {
        $this->transactionDate = $transactionDate;
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
