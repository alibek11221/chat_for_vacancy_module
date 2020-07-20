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

    public function findMessagesByParticipId(int $participId): array
    {
        $stmt = $this->dbo->query('SELECT * FROM chat_messages WHERE particip_id = ${participId}');
        return $stmt->fetchAll();
    }

    public function findParticipantsByRoomId(int $roomId): array
    {
        $stmt = $this->dbo->query(
            'SELECT chrp.paticip_id, PD.name FROM chat_rooms_particips chrp JOIN Particips_Directors PD on chrp.paticip_id = PD.id WHERE chrp.room_id = ${roomId}'
        );

        return $stmt->fetchAll();
    }

    public function findChatRooms(): array
    {
        $stmt = $this->dbo->query("SELECT * FROM chat_rooms");

        return $stmt->fetchAll();
    }

    public function findChatRoomById(int $id): array
    {
        $stmt = $this->dbo->prepare("SELECT * FROM chat_rooms WHERE id = :id");
        $stmt->execute([':id'=>$id]);
        return $stmt->fetchAll();
    }
}
