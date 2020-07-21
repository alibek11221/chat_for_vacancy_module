<?php

declare(strict_types=1);
namespace App\Repository;


use App\Config\Dbo;
use App\Helpers\JsonHelper;

class ChatRoomsRepository
{
    /**
     * @var Dbo
     */
    private $dbo;

    /**
     * @var string
     */
    private $path;


    public function __construct(Dbo $dbo)
    {
        $this->dbo = $dbo;
        $this->path = sprintf("%s/data/rooms.json", dirname(dirname(__DIR__)));
        if (!file_exists($this->path)) {
            $file = fopen($this->path, 'w');
            fclose($file);
        }
    }

    public function saveRoom(array $participants): void
    {
        $currentRooms = $this->findChatRoomsWithParticipantsData();
        $newRoom = ['room_name' => uniqid(), 'room_participants' => $participants];
        if (JsonHelper::alreadyExists(
                $currentRooms,
                function () use ($newRoom) {
                }
        )) {
            $currentRooms[] = $newRoom;
        }
        file_put_contents($this->path, json_encode($currentRooms, JSON_PRETTY_PRINT, 512));
    }

    public function findChatRoomsWithParticipantsData(): array
    {
        $rooms = json_decode(file_get_contents($this->path), true);
        return isset($rooms) ? $rooms : [];
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


    public function findParticipantsByRoomId(
            int $roomId
    ): array {
        $stmt = $this->dbo->prepare(
                "SELECT chrp.participant_id, PD.name 
                        FROM chat_rooms_participants chrp 
                        JOIN Particips_Directors PD ON chrp.participant_id = PD.id 
                        WHERE chrp.room_id = :rId"
        );
        $stmt->execute([':rId' => $roomId]);
        return $stmt->fetchAll();
    }
}
