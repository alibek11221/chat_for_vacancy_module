<?php

declare(strict_types=1);

namespace App\Models;


class ChatParticipant
{
    private $id;

    private $name;

    private $messages;

    /**
     * @param mixed $name
     * @return ChatParticipant
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return (int)$this->id;
    }

    /**
     * @param mixed $id
     * @return ChatParticipant
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return array
     */
    public function getMessages(): array
    {
        return $this->messages;
    }

    /**
     * @param mixed $messages
     * @return ChatParticipant
     */
    public function setMessages($messages): ChatParticipant
    {
        $this->messages = $messages;
        return $this;
    }
}