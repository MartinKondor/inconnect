<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @Assert\File(mimeTypes={ "image/*" })
     */
    private $image;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="You can't leave the post field blank.")
     */
    private $content;

    /**
     * @ORM\Column(type="text", length=255, nullable=true)
     */
    private $holder_type = 'user';

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $post_publicity = 'public';

    // Non database properties for template
    private $uploader;
    private $uploader_profile_pic;
    private $uploader_link;

    // From action table
    private $isUpvotedByUser = false;
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

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image): self
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

    public function getHolderType(): string
    {
        return $this->holder_type;
    }

    public function setHolderType(string $holder_type): ?self
    {
        $this->holder_type = $holder_type;
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

    public function setUpvotedByUser(bool $isUpvotedByUser): self
    {
        $this->isUpvotedByUser = $isUpvotedByUser;
        return $this;
    }

    public function isUpvotedByUser(): bool
    {
        return $this->isUpvotedByUser;
    }

    public function getPostPublicity(): ?string
    {
        return $this->post_publicity;
    }

    public function setPostPublicity(?string $post_publicity): self
    {
        $this->post_publicity = $post_publicity;

        return $this;
    }
}
