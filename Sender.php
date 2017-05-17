<?php
include "UserDAO.php";
include "OrderDAO.php";

require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection('10.3.51.32', 5672, 'kassa', 'Student1');
$channel = $connection->channel();




function sendCRM($Customer)
{
  
    global $connection;
    global $channel;
    
    $arrayCred = array( 'login' => "admin",
                        'password' => "ekahov3");//$response = array();
    
    $arrayBody =array(
    );
    $JSON = array(
        "Type" => "Request",
        "Method" => "POST",
        "Sender" => "KAS",
        "Receiver" => "CRM",
        "ObjectType" => "VST",
        'Credentials' => $arrayCred, 
        'Body' => $arrayBody);
      /*
    $msg = new AMQPMessage(json_encode($JSON));
    $channel->basic_publish($msg, '', 'Kassa');
*/

}
function sendCreateUserCRM($Customer)
{
  
    global $connection;
    global $channel;
    
    $arrayCred = array( 'login' => "admin",
                        'password' => "ekahov3");//$response = array();
    
    $arrayBody =array(
    );
    $JSON = array(
        "Type" => "Request",
        "Method" => "POST",
        "Sender" => "KAS",
        "Receiver" => "CRM",
        "ObjectType" => "VST",
        'Credentials' => $arrayCred, 
        'Body' => $arrayBody);
  
    $msg = new AMQPMessage(json_encode($JSON));
    $channel->basic_publish($msg, '', 'Kassa');


}
function sendMONITORING($Customer)
{

    global $connection;
    global $channel;
   
    $arrayCred = array( 'login' => "admin",
                        'password' => "ekahov3");//$response = array();
    
    $arrayBody =array(
    );
    $JSON = array(
        "Type" => "Request",
        "Method" => "POST",
        "Sender" => "KAS",
        "Receiver" => "MON",
        "ObjectType" => "VST",
        'Credentials' => $arrayCred, 
        'Body' => $arrayBody);
    
    $msg = new AMQPMessage(json_encode($JSON));
    $channel->basic_publish($msg, '', 'ControlRoom');


}
function sendFRONTEND($JSON)
{

    global $connection;
    global $channel;

    $msg = new AMQPMessage($JSON);
    $channel->basic_publish($msg, '', 'FRONTEND');


}
function sendPLANNING($JSON)
{

    global $connection;
    global $channel;

    $msg = new AMQPMessage($JSON);
    $channel->basic_publish($msg, '', '¨PLANNING');


}
/*
 $user= new User("1","daoud","Daoud@gmail.com","my street",035234234,32423562,(new \DateTime())->format('Y-m-d H:i:s'),0,"brussel");
sendMONITORING($user);
*/



// TIMER FOR SALESORDERS



function sendKassa($Customer)
{
    global $connection;
    global $channel;
    
    $arrayCred = array( 'login' => "admin",
                        'password' => "ekahov3");//$response = array();
    
    
    $arrayBody =array(
                
                    "name" =>$Customer->name,
                    "surname" =>"surname",
                    "email"=>$Customer->email,
                   
                    "tel"=>$Customer->phone,

                    "street"=>$Customer->street,
                    "state"=>$Customer->state,
                    "country"=>$Customer->country,
                    "city"=>$Customer->city,
                    "zip"=>$Customer->zip,
    );
    $JSON = array(
        "Type" => "Request",
        "Method" => "POST",
        "Sender" => "Kas",
        "Receiver" => "Kas",
        "ObjectType" => "VST",
        'Credentials' => $arrayCred, 
        'Body' => $arrayBody);
    
    $msg = new AMQPMessage(json_encode($JSON));
    $channel->basic_publish($msg, '', 'Kassa');


}

   $user = new User( null, "zedtest", "update@up", "upp","upstate","upcity","upcountry",2344, 0234234);
sendKassa($user);
$channel->close();
$connection->close();

?>


