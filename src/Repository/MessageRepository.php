<?php


namespace App\Repository;


use Ratchet\ConnectionInterface;

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

    /**
     * @param ConnectionInterface $from
     * @param string $text
     *
     * @return array
     */
    public function saveMessage(ConnectionInterface $from, string $text): array
    {
        $messages = json_decode(file_get_contents($this->path), true);
        $message = [
                'room' => $from->roomId,
                'participantId' => $from->id,
                'name' => $from->name,
                'text' => $text,
                'date' => time()
        ];
        $messages[] = $message;
        file_put_contents($this->path, json_encode($messages, JSON_PRETTY_PRINT, 512));
        return $message;
    }

    /**
     * @param string $path
     * @return MessageRepository
     */
    public function setPath(string $path)
    {
        $this->path = sprintf("%s/Data/Messages/%s.json", dirname(dirname(__DIR__)), $path);
        if (!file_exists($this->path)) {
            $file = fopen($this->path, 'w');
            fclose($file);
        }
        return $this;
    }
}