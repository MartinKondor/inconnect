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
    private $from_user_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $to_user_id;

    /**
     * @ORM\Column(type="string")
     */
    private $status;
    // Can be friends, request ...

    // Non database values
    private $linkToFriend;
    private $friendProfilePic;
    private $friendName;

    public function getFriendId()
    {
        return $this->friend_id;
    }

    public function getFromUserId(): ?int
    {
        return $this->from_user_id;
    }

    public function setFromUserId(int $from_user_id): self
    {
        $this->from_user_id = $from_user_id;
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

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function getFriendName(): ?string
    {
        return $this->friendName;
    }

    public function setFriendName(string $friendName): self
    {
        $this->friendName = $friendName;
        return $this;
    }

    public function getLinkToFriend(): string
    {
        return $this->linkToFriend;
    }

    public function setLinkToFriend(string $linkToFriend): self
    {
        $this->linkToFriend = $linkToFriend;
        return $this;
    }

    public function getFriendProfilePic(): ?string
    {
        return $this->friendProfilePic;
    }

    public function setFriendProfilePic(string $friendProfilePic): self
    {
        $this->friendProfilePic = $friendProfilePic;
        return $this;
    }
}

