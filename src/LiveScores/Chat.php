<?php

declare(strict_types=1);

namespace App\LiveScores;


use App\Config\MessageTypes;
use App\Repository\MessageRepository;
use App\Validators\MessageDataValidator;
use DI\ContainerBuilder;
use Exception;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
use SplObjectStorage;

class Chat implements MessageComponentInterface
{
    /**
     * @var MessageDataValidator
     */
    private $validator;

    /**
     * @var SplObjectStorage
     */
    private $clients;

    /**
     * @var MessageRepository
     */
    private $messageRepository;

    public function __construct()
    {
        $this->clients = new SplObjectStorage();
        $this->init();
    }

    private function init(): void
    {
        $container = (new ContainerBuilder())->useAutowiring(true)->build();
        $this->validator = $container->get(MessageDataValidator::class);
        $this->messageRepository = $container->get(MessageRepository::class);
    }

    public function onOpen(ConnectionInterface $conn): void
    {
        $conn->roomId = 0;
        $conn->id = 0;
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
            switch ($data['type']) {
                case MessageTypes::INIT:
                    if ($this->validator->validateFirstMessageData($data, $this->clients, $from)) {
                        $from->token = uniqid();
                        $from->roomId = (int)$data['roomId'];
                        $from->id = (int)$data['participantId'];
                        $returnMessage['type'] = MessageTypes::INIT;
                        $returnMessage['token'] = $from->token;
                        $from->send(json_encode($returnMessage, 0, 512));
                    } else {
                        $from->close();
                    }
                    break;
                case MessageTypes::GET_MESSAGES :
                    if ($this->validator->validateMessageData($from, $data, $this->clients)) {
                        $messages = $this->messageRepository->getMessagesByRoom($from->roomId);
                        $returnMessage['type'] = MessageTypes::GET_MESSAGES;
                        $returnMessage['token'] = $from->token;
                        $returnMessage['messages'] = $messages;
                        $from->send(json_encode($returnMessage, 0, 512));
                    }
                    break;
                case MessageTypes::MESSAGE:
                    if ($this->validator->validateMessageData($from, $data, $this->clients)) {
                        foreach ($this->clients as $client) {
                            if ($client->roomId === $from->roomId) {
                                $client->send($msg);
                            }
                        }
                        $this->messageRepository->saveMessage($from->roomId, $from->id, $data['text']);
                    }
                    break;
                default :
                    $from->close();
                    break;
            }
        } else {
            $from->close();
        }
    }
}
