<?php

declare(strict_types=1);

namespace App\Entity\Salon;

use App\Entity\City\City;
use App\Entity\Document\Document;
use App\Entity\Model\IdTrait;
use App\Entity\Model\Timestampable\TimestampableInterface;
use App\Entity\Model\Timestampable\TimestampableTrait;
use App\Entity\User\User;
use App\Repository\Salon\SalonRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SalonRepository::class)
 * @ORM\Table(name="salon")
 * @ORM\HasLifecycleCallbacks()
 */
class Salon implements TimestampableInterface
{
    use IdTrait, TimestampableTrait {
        IdTrait::__construct as initId;
        TimestampableTrait::__construct as initTimestamps;
    }

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private User $owner;

    /**
     * @ORM\ManyToOne(targetEntity=City::class)
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private City $city;

    /**
     * @ORM\Column(type="string")
     */
    private string $address;

    /**
     * @ORM\Column(type="string")
     */
    private string $name;

    /**
     * @ORM\Column(type="string", length=2000)
     */
    private string $description;

    /**
     * @ORM\Column(type="string")
     */
    private string $email;

    /**
     * @ORM\Column(type="string")
     */
    private string $phoneNumber;

    /**
     * @ORM\OneToOne(targetEntity=Document::class, cascade={"PERSIST"}, fetch="EAGER")
     */
    private Document $image;

    public function __construct(
        User $owner,
        City $city,
        Document $image,
        string $address,
        string $name,
        string $description,
        string $email,
        string $phoneNumber
    ) {
        $this->owner = $owner;
        $this->city = $city;
        $this->image = $image;
        $this->address = $address;
        $this->name = $name;
        $this->description = $description;
        $this->email = $email;
        $this->phoneNumber = $phoneNumber;
        $this->initId();
        $this->initTimestamps();
    }

    public function getOwner(): User
    {
        return $this->owner;
    }

    public function setOwner(User $owner): void
    {
        $this->owner = $owner;
    }

    public function getCity(): City
    {
        return $this->city;
    }

    public function setCity(City $city): void
    {
        $this->city = $city;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function setAddress(string $address): void
    {
        $this->address = $address;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }

    public function getImage(): Document
    {
        return $this->image;
    }

    public function setImage(Document $image): void
    {
        $this->image = $image;
    }

    public function getImageFilename(): ?string
    {
        return $this->getImage()->getName();
    }
}