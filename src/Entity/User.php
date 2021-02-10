<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
/* What’s this UserInterface?¶
So far, this is just a normal entity. But to use this class in the security system, it must implement Symfony\Component\Security\Core\User\UserInterface. This forces the class to have the five following methods: getRoles(), getPassword(), getSalt(), getUsername(), eraseCredentials() */

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface, \Serializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $username;
    
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @ORM\OneToOne(targetEntity="Address", mappedBy="user")
     */
    private $homeAddress;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getSalt() {}
    public function eraseCredentials() {}
    public function serialize()
    {
        return serialize([
            $this->id,
            $this->username,
            $this->email,
            $this->password
        ]);
    }
    public function unserialize($string)
    {
        list(
            $this->id,
            $this->username,
            $this->email,
            $this->password
        ) = unserialize($string, ['allowed_classes' => false]);
    }

    public function getHomeAddress(): ?Address
    {
        return $this->homeAddress;
    }

    public function setHomeAddress(?Address $homeAddress): self
    {
        // unset the owning side of the relation if necessary
        if ($homeAddress === null && $this->homeAddress !== null) {
            $this->homeAddress->setUser(null);
        }

        // set the owning side of the relation if necessary
        if ($homeAddress !== null && $homeAddress->getUser() !== $this) {
            $homeAddress->setUser($this);
        }

        $this->homeAddress = $homeAddress;

        return $this;
    }

}
