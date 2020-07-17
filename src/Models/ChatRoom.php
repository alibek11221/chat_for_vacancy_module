<?php

declare(strict_types=1);

namespace App\Models;


class ChatRoom
{
    private $chatId;
    private $chatParticipants;

    /**
     * @param array $chatParticipants
     * @return ChatRoom
     */
    public function setChatParticipant(array $chatParticipants): ChatRoom
    {
        $this->chatParticipants = $chatParticipants;
        return $this;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return (int)$this->chatId;
    }

    /**
     * @param string $chatId
     * @return ChatRoom
     */
    public function setChatId($chatId): ChatRoom
    {
        $this->chatId = $chatId;
        return $this;
    }

    /**
     * @return array
     */
    public function getChatParticipants(): array
    {
        return $this->chatParticipants;
    }
}