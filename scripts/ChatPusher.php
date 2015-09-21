<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ChatPusher
 *
 * @author José González <maangx@gmail.com>
 */
namespace GroupFinder;

use Ratchet\ConnectionInterface;
use Ratchet\Wamp\WampServerInterface;

class ChatPusher implements WampServerInterface {
    protected $clients;
    
    public function __construct() {
        $this->clients = new \SplObjectStorage();
    }
    
    public function onSubscribe(ConnectionInterface $conn, $conversation) {
        $this->conversations[$conversation->conversation_id] = $conversation;
    }
    public function onUnSubscribe(ConnectionInterface $conn, $conversation) {
    }
    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);
        $this->send($conn, "fetch", $this->fetchMessages());
        $this->checkOnliners();
        echo "New connection! ({$conn->resourceId})n";
    }
    public function onClose(ConnectionInterface $conn) {
    }
    public function onCall(ConnectionInterface $conn, $id, $topic, array $params) {
        // In this application if clients send data it's because the user hacked around in console
        $conn->callError($id, $topic, 'You are not allowed to make calls')->close();
    }
    public function onPublish(ConnectionInterface $conn, $topic, $event, array $exclude, array $eligible) {
        // In this application if clients send data it's because the user hacked around in console
        $conn->close();
    }
    public function onError(ConnectionInterface $conn, \Exception $e) {
        log_message('debug', $e->getMessage());
    }
    
    public function onChatMessage($entry) {
        $entryData = json_decode($entry, true);
        
        foreach ($this->clients as $client) {
            $this->send($client, "single", array("name" => $user, "msg" => $msg, "posted" => date("Y-m-d H:i:s")));
        }
    }
}
