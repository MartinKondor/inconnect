<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity("email", message="This email address is already in use.")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $user_id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="The first name field cannot be blank.")
     */
    private $first_name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="The last name field cannot be blank.")
     */
    private $last_name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="The email field cannot be blank.")
     * @Assert\Email(message="Email must be a real email address.")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(message="Please provide your birth date.")
     */
    private $birth_date;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Please provide your gender.")
     */
    private $gender;

    /**
     * @ORM\Column(type="string", length=255, options={"default" : "default.png"})
     */
    private $profile_pic = 'default.png';

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $about;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $permalink;

    private $roles;

    /**
     * @Assert\NotBlank(message="You cannot sign up without a password.")
     * @Assert\Length(max=4096)
     */
    private $plain_password;

    public function getUserId()
    {
        return $this->user_id;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): self
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): self
    {
        $this->last_name = $last_name;

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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birth_date;
    }

    public function setBirthDate(\DateTimeInterface $birth_date): self
    {
        $this->birth_date = $birth_date;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getProfilePic(): ?string
    {
        return $this->profile_pic;
    }

    public function setProfilePic(string $profile_pic): self
    {
        $this->profile_pic = $profile_pic;

        return $this;
    }

    public function getAbout(): ?string
    {
        return $this->about;
    }

    public function setAbout(?string $about): self
    {
        $this->about = $about;

        return $this;
    }

    public function getPermalink(): ?string
    {
        return $this->permalink;
    }

    public function setPermalink(string $permalink): self
    {
        $this->permalink = $permalink;

        return $this;
    }

    public function getPlainPassword(): ?string 
    {
        return $this->plain_password;
    }

    public function setPlainPassword(string $plain_password)
    {
        $this->plain_password = $plain_password;
        return $this;
    }

    public function getUsername()
    {
        return $this->email;
    }

    public function getSalt()
    {
        return null;
    }

    public function getRoles()
    {
        return [ 'ROLE_USER' ];
    }

    public function eraseCredentials()
    {}
}
