<?php

declare(strict_types=1);

namespace App\LiveScores;


use App\Config\MessageTypes;
use Exception;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
use SplObjectStorage;

class Chat implements MessageComponentInterface
{

    private $clients;

    public function __construct()
    {
        $this->clients = new SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn): void
    {
        $conn->msgCount = 0;
        $this->clients->attach($conn);
    }

    public function onClose(ConnectionInterface $conn): void
    {
        $this->clients->detach($conn);
    }

    public function onError(ConnectionInterface $conn, Exception $e): void
    {
        $conn->close();
    }

    public function onMessage(ConnectionInterface $from, $msg): void
    {
        if ($data = json_decode($msg, true)) {
//            if ($from->msgCount === 0 && $data['type'] === MessageTypes::INIT) {
//
//            }
        } else {
            $from->close();
        }
//        $data = json_decode($msg, true);
//        if ($data["type"] === MessageTypes::INIT) {
//            $from->roomId = $data['roomId'];
//            $from->id = $data['myId'];
//            $this->clients->attach($from);
//            foreach ($this->clients as $client) {
//                $client->send($from->id);
//            }
//        }
    }
}
