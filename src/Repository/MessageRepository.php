<?php


namespace App\Repository;


class MessageRepository
{

    /**
     * @var string
     */
    private $path;

    /**
     * @return array
     */
    public function getMessagesByRoom(): array
    {
        $messages = json_decode(file_get_contents($this->path), true);
        return isset($messages) ? $messages : [];
    }

    public function saveMessage(int $roomId, int $participantId, string $text): void
    {
        $messages = json_decode(file_get_contents($this->path), true);
        $message = ['room' => $roomId, 'particp' => $participantId, 'text' => $text];
        $messages[] = $message;
        file_put_contents($this->path, json_encode($messages, JSON_PRETTY_PRINT, 512));
    }

    /**
     * @param string $path
     * @return MessageRepository
     */
    public function setPath(string $path)
    {
        $this->path = sprintf("%s/Data/Messages/%s.json", dirname(dirname(__DIR__)), $path);
        if (!file_exists($path)) {
            $file = fopen($this->path, 'w');
            fclose($file);
        }
        return $this;
    }
}