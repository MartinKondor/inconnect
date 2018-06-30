<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PostRepository")
 */
class Post
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $post_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $user_id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_of_upload;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    // Non database properties for template
    private $uploader;
    private $uploader_profile_pic;
    private $uploader_link;

    // From action table
    private $comments;
    private $upvotes;

    public function getPostId()
    {
        return $this->post_id;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getDateOfUpload(): ?\DateTimeInterface
    {
        return $this->date_of_upload;
    }

    public function setDateOfUpload(\DateTimeInterface $date_of_upload): self
    {
        $this->date_of_upload = $date_of_upload;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    // Non database related functions

    public function getUploader(): string
    {
        return $this->uploader;
    }

    public function setUploader(string $uploader): self
    {
        $this->uploader = $uploader;
        return $this;
    }

    public function getUploaderLink(): string
    {
        return $this->uploader_link;
    }

    public function setUploaderLink(string $uploader_link): self
    {
        $this->uploader_link = $uploader_link;
        return $this;
    }

    public function getUploaderProfilePic(): string
    {
        return $this->uploader_profile_pic;
    }

    public function setUploaderProfilePic(string $uploader_profile_pic): self
    {
        $this->uploader_profile_pic = $uploader_profile_pic;
        return $this;
    }

    public function getComments(): ?array 
    {
        return $this->comments;
    }

    public function setComments(?array $comments): self
    {
        $this->comments = $comments;
        return $this;
    }

    public function getUpvotes(): ?string 
    {
        return $this->upvotes;
    }

    public function setUpvotes(string $upvotes): self
    {
        $this->upvotes = $upvotes;
        return $this;
    }

    public function isUpvotedByUser(): bool
    {
        return true;
    }
}
