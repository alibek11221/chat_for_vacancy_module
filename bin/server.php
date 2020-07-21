<?php

use App\LiveScores\Chat;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;


require dirname(__DIR__) . '/vendor/autoload.php';

$server = IoServer::factory(
        new HttpServer(
                new WsServer(
                        new Chat()
                )
        ),
        8090
);

$server->run();
