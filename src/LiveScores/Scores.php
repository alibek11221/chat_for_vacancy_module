<?php


namespace App\LiveScores;


use Exception;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
use SplObjectStorage;

class Scores implements MessageComponentInterface
{
	
	private $clients;
	private $ids = [];
	
	public function __construct()
	{
		$this->clients = new SplObjectStorage;
	}
	
	
	public function onOpen(ConnectionInterface $conn)
	{
		$this->clients->attach($conn);
		$id = uniqid('', true);
		$this->ids[] = $id;
		$data = ['type' => 'init', 'text' => $id];
		$conn->send(json_encode($data));
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
		$getId = $data['id'];
		$data = ['type' => 'msg', 'from' => $getId, 'text' => $data['ms']];
		foreach ($this->clients as $client) {
			$client->send(json_encode($data));
		}
	}
}