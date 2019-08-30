<?php

declare(strict_types=1);

/**
 * This file is a part of Helpee.
 *
 * @author  Kevin Allard <contact@allard-kevin.fr>
 *
 * @license 2018-2019 - Helpee
 */

namespace App\Entity\Ad\Message;

use App\Entity\User;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Class Thread.
 *
 * @ORM\Entity(repositoryClass="App\Repository\Ad\ThreadRepository")
 * @ORM\Table(name="ads_thread")
 */
class Thread
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid")
     */
    private $id;

    /**
     * Many messages have one ad.
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Ad\Ad", inversedBy="threads")
     * @ORM\JoinColumn(name="ad_id", referencedColumnName="id", nullable=false)
     */
    private $ad;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", cascade={"persist"})
     * @ORM\JoinTable(name="ads_users_threads")
     */
    private $participants;

    /**
     * One thread has many messages.
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Ad\Message\Message", mappedBy="thread")
     */
    private $messages;

    /**
     * Many messages have one ad.
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="threads")
     * @ORM\JoinColumn(name="user_creator_id", referencedColumnName="id", nullable=false)
     */
    private $createdBy;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isDeleted = false;

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
     * Thread constructor.
     */
    public function __construct()
    {
        $this->participants = new ArrayCollection();
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
     * @return mixed
     */
    public function getAd()
    {
        return $this->ad;
    }

    /**
     * @param mixed $ad
     */
    public function setAd($ad): void
    {
        $this->ad = $ad;
    }

    /**
     * @return mixed
     */
    public function getParticipants()
    {
        return $this->participants;
    }

    /**
     * @param \App\Entity\User $user
     *
     * @return $this
     */
    public function addParticipant(User $user)
    {
        $this->participants[] = $user;

        return $this;
    }

    /**
     * @param array $participants
     *
     * @return $this
     */
    public function addParticipants(array $participants)
    {
        if (!\is_array($participants) && !$participants instanceof \Traversable) {
            throw new \InvalidArgumentException('Participants must be an array or instance of Traversable');
        }
        foreach ($participants as $participant) {
            $this->addParticipant($participant);
        }

        return $this;
    }

    /**
     * @param \App\Entity\User $user
     *
     * @return mixed
     */
    public function isParticipant(User $user)
    {
        return $this->getParticipants()->contains($user);
    }

    /**
     * @return mixed
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * @return mixed
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * @param mixed $createdBy
     */
    public function setCreatedBy($createdBy): void
    {
        $this->createdBy = $createdBy;
    }

    /**
     * @return bool
     */
    public function isDeleted(): bool
    {
        return $this->isDeleted;
    }

    /**
     * @param bool $isDeleted
     */
    public function setIsDeleted(bool $isDeleted): void
    {
        $this->isDeleted = $isDeleted;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }
}
