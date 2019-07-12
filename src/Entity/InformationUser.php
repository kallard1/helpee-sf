<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Class InformationUser
 *
 * @package App\Entity
 * @ORM\Entity()
 * @ORM\Table(name="informations_users")
 */
class InformationUser
{
    /**
     * @var
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid")
     */
    private $id;

    /**
     * One User has One Address.
     * @ORM\OneToOne(targetEntity="User", inversedBy="informationUser", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @var float
     * @ORM\Column(type="float", nullable=false)
     */
    private $uev = 0.00;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $address;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $address_1;

    /**
     * @ORM\ManyToOne(targetEntity="City")
     * @ORM\JoinColumn(name="city_id", referencedColumnName="id")
     */
    private $city;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $phone;

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
     * @return float
     */
    public function getUev(): float
    {
        return $this->uev;
    }

    /**
     * @param float $uev
     */
    public function setUev(float $uev): void
    {
        $this->uev = $uev;
    }

    /**
     * @return string
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * @param string $address
     */
    public function setAddress(string $address): void
    {
        $this->address = $address;
    }

    /**
     * @return string
     */
    public function getAddress1(): ?string
    {
        return $this->address_1;
    }

    /**
     * @param string $address_1
     */
    public function setAddress1(string $address_1): void
    {
        $this->address_1 = $address_1;
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
     * @return string
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
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
