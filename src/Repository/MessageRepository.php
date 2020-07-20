<?php


namespace App\Repository;


use App\Config\Dbo;

class MessageRepository
{
    /**
     * @var Dbo
     */
    private $dbo;

    /**
     * MessageRepository constructor.
     * @param Dbo $dbo
     */
    public function __construct(Dbo $dbo)
    {
        $this->dbo = $dbo;
    }

    /**
     * @param int $id
     * @return array
     */
    public function getMessagesByRoom(int $id): array
    {
        $stmt = $this->dbo->query(sprintf("SELECT * FROM chat_messages WHERE room_id = %s", $id));
        return $stmt->fetchAll();
    }

    public function saveMessage(int $roomId, int $participantId, string $text): void
    {
        $stmt = $this->dbo->prepare(
                'INSERT INTO chat_messages(room_id, participant_id, text) VALUES (:rId, :pId, :text)'
        );
        $stmt->execute([':rId' => $roomId, ':pId' => $participantId, ':text' => $text]);
    }
}