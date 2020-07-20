<?php


namespace App\Validators;


use App\Repository\ChatParticipantsRepository;
use App\Repository\ChatRoomsRepository;
use Ratchet\ConnectionInterface;
use SplObjectStorage;

class MessageDataValidator
{
    /**
     * @var ChatRoomsRepository
     */
    private $chatRoomsRepository;
    /**
     * @var ChatParticipantsRepository
     */
    private $chatParticipantsRepository;

    /**
     * MessageDataValidator constructor.
     * @param ChatRoomsRepository $chatRoomsRepository
     * @param ChatParticipantsRepository $chatParticipantsRepository
     */
    public function __construct(
            ChatRoomsRepository $chatRoomsRepository,
            ChatParticipantsRepository $chatParticipantsRepository
    ) {
        $this->chatRoomsRepository = $chatRoomsRepository;
        $this->chatParticipantsRepository = $chatParticipantsRepository;
    }

    /**
     * @param array $data
     * @param SplObjectStorage $clients
     * @param ConnectionInterface $from
     * @return bool
     */
    public function validateFirstMessageData(array $data, SplObjectStorage $clients, ConnectionInterface $from)
    {
        return $from->roomId === 0 && $from->id === 0 && !$this->isAlreadyInClients($data, $clients);
    }

    private function isAlreadyInClients(array $data, SplObjectStorage $clients)
    {
        foreach ($clients as $client) {
            if ((int)$data['roomId'] === $client->roomId && (int)$data['participantId'] === $client->id) {
                return true;
            }
        }
        return false;
    }

    public function validateMessageData(ConnectionInterface $from, array $data, SplObjectStorage $clients)
    {
        return $from->id === (int)$data['participantId'] && $from->roomId === (int)$data['roomId'];
    }

}