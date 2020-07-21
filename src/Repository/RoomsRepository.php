<?php

declare(strict_types=1);
namespace App\Repository;


use App\Helpers\JsonHelper;

class RoomsRepository
{
    /**
     * @var string
     */
    private $path;


    public function __construct()
    {
        $this->path = sprintf("%s/data/rooms.json", dirname(dirname(__DIR__)));
        if (!file_exists($this->path)) {
            $file = fopen($this->path, 'w');
            fclose($file);
        }
    }

    public function saveRoom(array $participants): void
    {
        $currentRooms = $this->findChatRoomsWithParticipantsData();
        $newRoom = ['roomName' => uniqid(), 'roomParticipants' => $participants];
        if (JsonHelper::alreadyExists(
                $currentRooms,
                function ($room) use ($newRoom) {
                    return $room['roomParticipants'][1]['id'] === $newRoom['roomParticipants'][1]['id']
                            && $room['roomParticipants'][2]['id'] === $newRoom['roomParticipants'][2]['id'];
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
}
