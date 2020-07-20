<?php

declare(strict_types=1);

namespace App\Repository;


use App\Config\Dbo;

class ChatParticipantsRepository
{
    /**
     * @var Dbo
     */
    private $dbo;

    /**
     * ChatParticipantsRepository constructor.
     * @param Dbo $dbo
     */
    public function __construct(Dbo $dbo)
    {
        $this->dbo = $dbo;
    }

    /**
     * @param int $roomId
     * @param int $participantId
     * @return array
     */
    public function findChatParticipantDataBy(int $roomId, int $participantId): array
    {
        $stmt = $this->dbo->prepare(
                'SELECT * FROM chat_rooms_participants WHERE room_id = :rid AND participant_id = :pid'
        );
        $stmt->execute(['rid' => $roomId, 'pid' => $participantId]);
        return $stmt->fetchAll();
    }
}