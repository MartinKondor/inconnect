<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PageRepository")
 */
class Page
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $page_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $creator_user_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $page_name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $page_type;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_of_creation;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $since_date;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $contact_email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $contact_address;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $contact_postal_code;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $contact_phone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $page_pic;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $page_cover_pic;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $about;

    public function getPageId()
    {
        return $this->page_id;
    }

    public function getCreatorUserId(): ?int
    {
        return $this->creator_user_id;
    }

    public function setCreatorUserId(int $creator_user_id): self
    {
        $this->creator_user_id = $creator_user_id;

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

    public function getPageName(): ?string
    {
        return $this->page_name;
    }

    public function setPageName(string $page_name): self
    {
        $this->page_name = $page_name;

        return $this;
    }

    public function getPageType(): ?string
    {
        return $this->page_type;
    }

    public function setPageType(string $page_type): self
    {
        $this->page_type = $page_type;

        return $this;
    }

    public function getDateOfCreation(): ?\DateTimeInterface
    {
        return $this->date_of_creation;
    }

    public function setDateOfCreation(\DateTimeInterface $date_of_creation): self
    {
        $this->date_of_creation = $date_of_creation;

        return $this;
    }

    public function getSinceDate(): ?\DateTimeInterface
    {
        return $this->since_date;
    }

    public function setSinceDate(?\DateTimeInterface $since_date): self
    {
        $this->since_date = $since_date;

        return $this;
    }

    public function getContactEmail(): ?string
    {
        return $this->contact_email;
    }

    public function setContactEmail(string $contact_email): self
    {
        $this->contact_email = $contact_email;

        return $this;
    }

    public function getContactAddress(): ?string
    {
        return $this->contact_address;
    }

    public function setContactAddress(?string $contact_address): self
    {
        $this->contact_address = $contact_address;

        return $this;
    }

    public function getContactPostalCode(): ?string
    {
        return $this->contact_postal_code;
    }

    public function setContactPostalCode(?string $contact_postal_code): self
    {
        $this->contact_postal_code = $contact_postal_code;

        return $this;
    }

    public function getContactPhone(): ?string
    {
        return $this->contact_phone;
    }

    public function setContactPhone(?string $contact_phone): self
    {
        $this->contact_phone = $contact_phone;

        return $this;
    }

    public function getPagePic(): ?string
    {
        return $this->page_pic;
    }

    public function setPagePic(?string $page_pic): self
    {
        $this->page_pic = $page_pic;

        return $this;
    }

    public function getPageCoverPic(): ?string
    {
        return $this->page_cover_pic;
    }

    public function setPageCoverPic(?string $page_cover_pic): self
    {
        $this->page_cover_pic = $page_cover_pic;

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
}
