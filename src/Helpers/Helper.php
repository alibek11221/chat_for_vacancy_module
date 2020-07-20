<?php

declare(strict_types=1);

namespace App\Helpers;

use App\Config\MessageTypes;
use App\Repository\ChatRoomsRepository;
use Ratchet\ConnectionInterface;

class Helper
{
    /**
     * @var ChatRoomsRepository
     */
    private $repository;

    public function __construct(ChatRoomsRepository $repository)
    {
        $this->repository = $repository;
    }

    public function validateFirstMessageData(ConnectionInterface $from, $msg): bool
    {
        if ($data = json_decode($msg, true)) {
            return false;
        }
        if ($from->msgCount === 0 && $data['type'] !== MessageTypes::INIT) {
            return false;
        }

    }
}
