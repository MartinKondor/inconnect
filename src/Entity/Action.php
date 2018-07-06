<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ActionRepository")
 */
class Action
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $action_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $entity_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $user_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $to_user_id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $action_date;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $entity_type;
    // Can be: post ...

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $action_type;
    // Can be: upvote, comment ...

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $seen_by_user;

    // Comment properties and only for templates
    private $commenterLink;
    private $commenterProfile;
    private $commenter;

    public function getActionId()
    {
        return $this->action_id;
    }

    public function getEntityId(): ?int
    {
        return $this->entity_id;
    }

    public function setEntityId(int $entity_id): self
    {
        $this->entity_id = $entity_id;

        return $this;
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

    public function getToUserId(): ?int
    {
        return $this->to_user_id;
    }

    public function setToUserId(int $to_user_id): self
    {
        $this->to_user_id = $to_user_id;

        return $this;
    }

    public function getActionDate(): ?\DateTimeInterface
    {
        return $this->action_date;
    }

    public function setActionDate(\DateTimeInterface $action_date): self
    {
        $this->action_date = $action_date;

        return $this;
    }

    public function getEntityType(): ?string
    {
        return $this->entity_type;
    }

    public function setEntityType(string $entity_type): self
    {
        $this->entity_type = $entity_type;

        return $this;
    }

    public function getActionType(): ?string
    {
        return $this->action_type;
    }

    public function setActionType(string $action_type): self
    {
        $this->action_type = $action_type;

        return $this;
    }

    public function getSeenByUser(): ?string
    {
        return $this->seen_by_user;
    }

    public function setSeenByUser(string $seen_by_user): self
    {
        $this->seen_by_user = $seen_by_user;
        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCommenterLink(): ?string
    {
        return $this->commenterLink;
    }

    public function setCommenterLink(?string $commenterLink): self
    {
        $this->commenterLink = $commenterLink;
        return $this;
    }

    public function getCommenterProfile(): ?string
    {
        return $this->commenterProfile;
    }

    public function setCommenterProfile(?string $commenterProfile): self
    {
        $this->commenterProfile = $commenterProfile;
        return $this;
    }

    public function getCommenter(): ?string
    {
        return $this->commenter;
    }

    public function setCommenter(?string $commenter): self
    {
        $this->commenter = $commenter;
        return $this;
    }
}
