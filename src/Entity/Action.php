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
    // Can be: upvote ...

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $content;

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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }
}
