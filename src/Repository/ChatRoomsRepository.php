<?php

declare(strict_types=1);

namespace App\Repository;


use App\Config\Dbo;

class ChatRoomsRepository
{
    private $dbo;

    public function __construct(Dbo $dbo)
    {
        $this->dbo = $dbo;
    }

    public function findChatRoomsWithParticipantsData(): array
    {
        $stmt = $this->dbo->query('SELECT * FROM chat_rooms');
        $rooms = $stmt->fetchAll();
        foreach ($rooms as &$room) {
            $room['participants'] = $this->findParticipantsByRoomId((int)$room['id']);
        }
        return $rooms;
    }


    public function findParticipantsByRoomId(int $roomId): array
    {
        $stmt = $this->dbo->prepare(
                "SELECT chrp.participant_id, PD.name 
                        FROM chat_rooms_participants chrp 
                        JOIN Particips_Directors PD ON chrp.participant_id = PD.id 
                        WHERE chrp.room_id = :rId"
        );
        $stmt->execute([':rId' => $roomId]);
        return $stmt->fetchAll();
    }

    public function findMessagesByParticipantId(int $participantId): array
    {
        $stmt = $this->dbo->prepare('SELECT * FROM chat_messages WHERE participant_id = :pid');
        $stmt->execute([':pid' => $participantId]);
        return $stmt->fetchAll();
    }

    public function findChatRoomById(int $id): array
    {
        $stmt = $this->dbo->query(sprintf("SELECT * FROM chat_rooms WHERE id =%d", $id));
        return $stmt->fetchAll();
    }
}
