<?php
include "UserDAO.php";
include "OrderDAO.php";

require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection('10.3.51.32', 5672, 'kassa', 'Student1');





function sendCRM($Customer)
{
  
    $connection = new AMQPStreamConnection('10.3.51.32', 5672, 'kassa', 'Student1');
    $channel = $connection->channel();
    $channel->queue_declare('CRMQueue', true, false, false, false);
    $arrayCred = array( 'login' => "admin",
                        'password' => "ekahov3");//$response = array();
    
    $arrayBody =array(
        "uuid" => $Customer->UUID,
        "version" =>$Customer->version,
        "name" => $Customer->name,
        "surname" => "",
        "tel" => $Customer->phone,
        "email" => $Customer->email,
        "street" => $Customer->street,
        "city" => $Customer->city,
        "zip" => $Customer->zip,
        "state"=> $Customer->state,
        "country" => $Customer->country,
        "present" => $Customer->registered,
        "breakfast" => NULL,
        "cable" => NULL,
        "fraude" => NULL
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
    $channel->basic_publish($msg, '', 'CRMQueue');
        $channel->close();
    $connection->close();
*/

}
function sendCreateUserCRM($Customer)
{
  
    $connection = new AMQPStreamConnection('10.3.51.32', 5672, 'kassa', 'Student1');
    $channel = $connection->channel();
    $channel->queue_declare('CRMQueue', true, false, false, false);
    $arrayCred = array( 'login' => "kassa",
                        'password' => md5("S2DnPgVM"));//$response = array();
    
    $arrayBody =array(
        "uuid" => $Customer->UUID,
        "version" =>$Customer->version,
        "name" => strtok($Customer->name, '_'),
        "surname" => substr($Customer->name, strpos($Customer->name, "_") + 1),
        "tel" => (string)$Customer->phone,
        "email" => $Customer->email,
        "street" => $Customer->street,
        "city" => $Customer->city,
        "zip" => $Customer->zip,
        "state"=> $Customer->state,
        "country" => $Customer->country,
        "present" => $Customer->registered,
        "breakfast" => NULL,
        "cable" => NULL,
        "fraude" => NULL
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
    $channel->basic_publish($msg, '', 'CRMQueue');
    $channel->close();
    $connection->close();

}
function sendCreateUserMONITORING($Customer)
{

    $connection = new AMQPStreamConnection('10.3.51.32', 5672, 'kassa', 'Student1');
    $channel = $connection->channel();
    $channel->queue_declare('MonitoringLogQueue', true, false, false, false);
    $arrayCred = array( 'login' => "admin",
                        'password' => "ekahov3");//$response = array();
    
    $arrayBody =array(
        "uuid" => $Customer->UUID,
        "version" =>$Customer->version,
        "name" => strtok($Customer->name, '_'),
        "surname" => substr($Customer->name, strpos($Customer->name, "_") + 1),
        "tel" => $Customer->phone,
        "email" => $Customer->email,
        "street" => $Customer->street,
        "city" => $Customer->city,
        "zip" => $Customer->zip,
        "state"=> $Customer->state,
        "country" => $Customer->country,
        "present" => $Customer->registered,
        "breakfast" => NULL,
        "cable" => NULL,
        "fraude" => NULL
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
    $channel->basic_publish($msg, '', 'MonitoringLogQueue');
    $channel->close();
    $connection->close();

}
function sendCreateUserFRONTEND($Customer)
{

    $connection = new AMQPStreamConnection('10.3.51.32', 5672, 'kassa', 'Student1');
    $channel = $connection->channel();
    $channel->queue_declare('FrontendQueue', true, false, false, false);
    $arrayCred = array( 'login' => "admin",
                        'password' => "ekahov3");//$response = array();
    
    $arrayBody =array(
        "uuid" => $Customer->UUID,
        "version" =>$Customer->version,
        "name" => strtok($Customer->name, '_'),
        "surname" => substr($Customer->name, strpos($Customer->name, "_") + 1),
        "tel" => $Customer->phone,
        "email" => $Customer->email,
        "street" => $Customer->street,
        "city" => $Customer->city,
        "zip" => $Customer->zip,
        "state"=> $Customer->state,
        "country" => $Customer->country,
        "present" => $Customer->registered,
        "breakfast" => NULL,
        "cable" => NULL,
        "fraude" => NULL
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
    $channel->basic_publish($msg, '', 'FrontendQueue');
    $channel->close();
    $connection->close();

}
function sendMONITORINGOrders($order ,$detaillines)
{
   
    $connection = new AMQPStreamConnection('10.3.51.32', 5672, 'kassa', 'Student1');
    $channel = $connection->channel();
    $channel->queue_declare('MonitoringLogQueue', true, false, false, false);
    $arrayCred = array( 'login' => "kassa",
                        'password' => md5("S2DnPgVM"));//$response = array();
    
    $arrayBody =array(
            "uuid" => $order->UUID,
            "version" =>$order->version,
            "name" =>(string)$order->name,
            "productlines"=>$detaillines
            );
    $JSON = array(
        "Type" => "Request",
        "Method" => "POST",
        "Sender" => "KAS",
        "Receiver" => "MON",
        "ObjectType" => "ORD",
        'Credentials' => $arrayCred, 
        'Body' => $arrayBody);
    
    $msg = new AMQPMessage(json_encode($JSON));
    
    $channel->basic_publish($msg, '', 'MonitoringLogQueue');
    $channel->close();
    $connection->close();

}
function sendMONITORINGCredit($uuid,$name,$newcred,$amountadd)
{
   
    $connection = new AMQPStreamConnection('10.3.51.32', 5672, 'kassa', 'Student1');
    $channel = $connection->channel();
    $channel->queue_declare('MonitoringLogQueue', true, false, false, false);
    $arrayCred = array( 'login' => "kassa",
                        'password' => md5("S2DnPgVM"));//$response = array();
    
    $arrayBody =array(
            "uuid" => $uuid,
            "name" =>$name,
            "newcredit" =>$newcred,
            "amount"=>$amountadd
            );
    $JSON = array(
        "Type" => "Request",
        "Method" => "POST",
        "Sender" => "KAS",
        "Receiver" => "MON",
        "ObjectType" => "TRS",
        'Credentials' => $arrayCred, 
        'Body' => $arrayBody);
    
    $msg = new AMQPMessage(json_encode($JSON));
    
    $channel->basic_publish($msg, '', 'MonitoringLogQueue');
    $channel->close();
    $connection->close();

}
function sendMONITORINGCheckSystem($uuid,$timestampsnd,$version, $var)
{
   
    $connection = new AMQPStreamConnection('10.3.51.32', 5672, 'kassa', 'Student1');
    $channel = $connection->channel();
    $channel->queue_declare('HeartBeatQueue', true, false, false, false);
    $arrayCred = array( 'login' => "kassa",
                        'password' => md5("TLs3j9"));//$response = array();
    
    $date = new DateTime();
    $arrayBody =array(
            "UUID" => $uuid,
            "version" => $version,
            "timestampsnd" =>$timestampsnd,
            "timestampres" =>$date->getTimestamp(),
            "var"=>$var*$var
            );
    $JSON = array(
        "Type" => "Answer",
        "Method" => "POST",
        "Sender" => "KAS",
        "Receiver" => "MON",
        "ObjectType" => "HBT",
        'Credentials' => $arrayCred, 
        'Body' => $arrayBody);
    
    $msg = new AMQPMessage(json_encode($JSON));
    
    $channel->basic_publish($msg, '', 'HeartBeatQueue');
    $channel->close();
    $connection->close();

}
function sendMONITORINGRegistred($uuid)
{

    $connection = new AMQPStreamConnection('10.3.51.32', 5672, 'kassa', 'Student1');
    $channel = $connection->channel();
   $channel->queue_declare('MonitoringLogQueue', true, false, false, false);
    $arrayCred = array( 'login' => "kassa",
                        'password' => md5("S2DnPgVM"));//$response = array();
    
     $arrayBody =array(
         "uuid" => $uuid,
         "time" =>(new \DateTime())->format('Y-m-d H:i:s')
       
    );
    $JSON = array(
        "Type" => "Request",
        "Method" => "PUT",
        "Sender" => "KAS",
        "Receiver" => "MON",
        "ObjectType" => "REG",
        'Credentials' => $arrayCred, 
        'Body' => $arrayBody);
    
    $msg = new AMQPMessage(json_encode($JSON));
    $channel->basic_publish($msg, '', 'MonitoringLogQueue');
    $channel->close();
    $connection->close();

}
function sendCRMRegistred($Customer)
{

    $connection = new AMQPStreamConnection('10.3.51.32', 5672, 'kassa', 'Student1');
    $channel = $connection->channel();
   $channel->queue_declare('CRMQueue', true, false, false, false);
    $arrayCred = array( 'login' => "kassa",
                        'password' => md5("S2DnPgVM"));//$response = array();
    
     $arrayBody =array(
        "uuid" => $Customer->UUID,
        "version" =>$Customer->version,
        "name" => strtok($Customer->name, '_'),
        "surname" => substr($Customer->name, strpos($Customer->name, "_") + 1),
        "tel" => $Customer->phone,
        "email" => $Customer->email,
        "street" => $Customer->street,
        "city" => $Customer->city,
        "zip" => $Customer->zip,
        "state"=> $Customer->state,
        "country" => $Customer->country,
        "present" => $Customer->registered,
        "breakfast" => NULL,
        "cable" => NULL,
        "fraude" => NULL
    );
    $JSON = array(
        "Type" => "Request",
        "Method" => "PUT",
        "Sender" => "KAS",
        "Receiver" => "MON",
        "ObjectType" => "VST",
        'Credentials' => $arrayCred, 
        'Body' => $arrayBody);
    
    $msg = new AMQPMessage(json_encode($JSON));
    $channel->basic_publish($msg, '', 'CRMQueue');
    $channel->close();
    $connection->close();

}
function sendFrontendRegistred($Customer)
{

    $connection = new AMQPStreamConnection('10.3.51.32', 5672, 'kassa', 'Student1');
    $channel = $connection->channel();
    $channel->queue_declare('FrontendQueue', true, false, false, false);
    $arrayCred = array( 'login' => "kassa",
                        'password' => md5("S2DnPgVM"));//$response = array();
    
      $arrayBody =array(
        "uuid" => $Customer->UUID,
        "version" =>$Customer->version,
        "name" => strtok($Customer->name, '_'),
        "surname" => substr($Customer->name, strpos($Customer->name, "_") + 1),
        "tel" => $Customer->phone,
        "email" => $Customer->email,
        "street" => $Customer->street,
        "city" => $Customer->city,
        "zip" => $Customer->zip,
        "state"=> $Customer->state,
        "country" => $Customer->country,
        "present" => $Customer->registered,
        "breakfast" => NULL,
        "cable" => NULL,
        "fraude" => NULL
    );
    $JSON = array(
        "Type" => "Request",
        "Method" => "PUT",
        "Sender" => "KAS",
        "Receiver" => "MON",
        "ObjectType" => "VST",
        'Credentials' => $arrayCred, 
        'Body' => $arrayBody);
    
    $msg = new AMQPMessage(json_encode($JSON));
    $channel->basic_publish($msg, '', 'FrontendQueue');
    $channel->close();
    $connection->close();

}
function sendFRONTEND($JSON)
{

    global $connection;
    $channel = $connection->channel();

    $msg = new AMQPMessage($JSON);
    $channel->basic_publish($msg, '', 'FRONTEND');


}
function sendPLANNING($JSON)
{

    global $connection;
    $channel = $connection->channel();

    $msg = new AMQPMessage($JSON);
    $channel->basic_publish($msg, '', '¨PLANNING');
    $channel->close();
    $connection->close();

}




// TIMER FOR SALESORDERS



function sendKassa($Customer)
{
    global $connection;
    $channel = $connection->channel();
    
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
    $channel->close();
    $connection->close();

}
/*
 $user= new User("1","daoud","Daoud@gmail.com","my street",035234234,32423562,(new \DateTime())->format('Y-m-d H:i:s'),0,"brussel");
sendMONITORING($user);
*/
/*
   $user = new User( null, "zedtest", "update@up", "upp","upstate","upcity","upcountry",2344, 0234234);
sendKassa($user);
*/


?>



