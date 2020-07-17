<?php

declare(strict_types=1);

namespace App\LiveScores;


use App\Config\MessageTypes;
use App\Models\ChatParticipant;
use App\Models\ChatRoom;
use App\Models\Message;
use App\Repository\ChatRoomsRepository;
use DateTime;
use Exception;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
use SplObjectStorage;

class Chat implements MessageComponentInterface
{

    private $clients;
    private $chatRooms = [];

    public function __construct()
    {
        $this->clients = new SplObjectStorage;
        $this->init();
    }

    private function init()
    {
        $repo = new ChatRoomsRepository();
        $rooms = $repo->findChatRooms();
        foreach ($rooms as $room) {
            $tempRoom = new ChatRoom();
            $tempRoom->setChatId($room['id']);
            $particips = $repo->findParticipantsByRoomId($tempRoom->getId());
            $participObjects = [];
            foreach ($particips as $particip) {
                $tempParticip = new ChatParticipant();
                $tempParticip->setId($particip['paticip_id'])->setName($particip['name']);
                $messages = $repo->findMessagesByParticipId($tempParticip->getId());
                $messageObjects = [];
                foreach ($messages as $message) {
                    $tempMessage = new Message();
                    $tempMessage->setText($message['text'])->setDate(new DateTime($message['message_date']));
                    array_push($messageObjects, $tempMessage);
                }
                $tempParticip->setMessages($messageObjects);
                array_push($participObjects, $tempParticip);
            }
            $tempRoom->setChatParticipant($participObjects);
            $this->chatRooms[sprintf("%s", $tempRoom->getId())] = $tempRoom;
        }
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
    }

    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
    }

    public function onError(ConnectionInterface $conn, Exception $e)
    {
        $conn->close();
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $data = json_decode($msg, true);
        if ($data["type"] === MessageTypes::INIT) {
            $roomId = $data["roomId"];
            $currentRoom = $this->chatRooms[sprintf("%s", $roomId)];
            print_r($currentRoom);
        }
//            if ($currentRoom != null) {
//                $participId = $data['myId'];
//                $currentParticip = array_filter(
//                    $currentRoom->getChatParticipants(),
//                    function ($v) use ($participId) {
//                        return $v->getId() == $participId;
//                    },
//                    ARRAY_FILTER_USE_BOTH
//                )[0];
//                if ($currentParticip != null) {
//                    foreach ($currentParticip->getMessages() as $message) {
//                        $from->send(json_encode((array)$message, 0, 500));
//                    }
//                }
//            }
//        }
//        if ($data["type"] === MessageTypes::MESSAGE)
//        {
//        }
//        $getId = $data['id'];
//        $data = ['type' => 'msg', 'from' => $getId, 'text' => $data['ms']];
//        foreach ($this->clients as $client) {
//            $client->send(json_encode($data));
//        }
    }
}