<?php

namespace App\Entity\User;

use App\Entity\Model\IdTrait;
use App\Entity\Model\Timestampable\TimestampableInterface;
use App\Entity\Model\Timestampable\TimestampableTrait;
use App\Repository\User\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="user")
 * @ORM\HasLifecycleCallbacks()
 */
class User implements UserInterface, TimestampableInterface
{
    use IdTrait, TimestampableTrait {
        IdTrait::__construct as initId;
        TimestampableTrait::__construct as initTimestamps;
    }

    /**
     * @ORM\Column(type="string", length=50, unique=true)
     */
    private string $email;

    /**
     * @ORM\Column(type="json")
     */
    private array $roles;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private string $firstName;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private string $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $password;

    public function __construct(string $email, string $firstName, string $lastName, string $password, array $roles = [])
    {
        $this->email = $email;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->password = $password;
        $roles[] = 'ROLE_USER';
        $this->roles = array_unique($roles);
        $this->initId();
        $this->initTimestamps();
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getUsername(): string
    {
        return $this->email;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getSalt(): void
    {
        // not needed when using bycript or argon
    }

    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }
}
