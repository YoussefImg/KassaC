<?php

include "UserDAO.php";
require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
$connection = new AMQPStreamConnection('10.3.51.32', 5672, 'kassa', 'Student1');


$channel = $connection->channel();
$channel->queue_declare('Kassa', false, false, false, false);

echo ' [*] Waiting for messages. To exit press CTRL+C', "\n";

$callback = function($msg) 
{
    $json = json_decode($msg->body,true);
  
    if($json["Method"] == "PUT")
    {
        $name = $json["Body"]["name"];
        $email =  $json["Body"]["email"];
        $street =  $json["Body"]["street"];
        $phone = $json["Body"]["phone"];
        $mobile =  $json["Body"]["mobile"];
        $city = $json["Body"]["city"];
        $user = new User( null,$name,$email,$street,$phone,$mobile,0, $city);
        CreateCustomer($user);
    }
    echo "hello";
    
};
$channel->basic_consume('Kassa', '', false, true, false, false, $callback);

while(count($channel->callbacks)) {
    $channel->wait();
}



?>


