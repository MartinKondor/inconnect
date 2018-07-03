<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FriendRepository")
 */
class Friend
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $friend_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $user1_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $user2_id;

    /**
     * @ORM\Column(type="string")
     */
    private $status;
    // Can be friends, request ...

    public function getFriendId()
    {
        return $this->friend_id;
    }

    public function getUser1Id(): ?int
    {
        return $this->user1_id;
    }

    public function setUser1Id(int $user1_id): self
    {
        $this->user1_id = $user1_id;
        return $this;
    }

    public function getUser2Id(): ?int
    {
        return $this->user2_id;
    }

    public function setUser2Id(int $user2_id): self
    {
        $this->user2_id = $user2_id;
        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }
}

