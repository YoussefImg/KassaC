<?php
include"UserDAO.php";
include "OrderDAO.php";


require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
$connection = new AMQPStreamConnection('10.3.51.32', 5672, 'kassa', 'Student1');


$channel = $connection->channel();
$channel->queue_declare('KassaQueue', false, false, false, false);

echo ' [*] Waiting for messages. To exit press CTRL+C', "\n";

$callback = function($msg) 
{
    $json = json_decode($msg->body,true);
    if($json["Reciever"] == "KAS")
    {
        $name = $json["Body"]["name"];
        $name .= $json["Body"]["surname"];
        $email =  $json["Body"]["email"];
        $street =  $json["Body"]["street"];
        $phone = $json["Body"]["tel"];
        $state = $json["Body"]["state"];
        $city = $json["Body"]["city"];
        $country = $json["Body"]["country"];
        $zip = $json["Body"]["zip"];
        
        if($json["Method"] == "POST")
        {

            $user = new User(  null,$name,$email,$street,$state,$city,$country,$zip,$phone);
            $user->credit = 0;
            if($json["Sender"] == "FRE")
            {
                $user->bar = 0;
                if(!isset($json["Body"]["id"]) || !isset($json["Body"]["uuid"]))
                {

                    echo 'no id';
                    $user = CreateCustomerWithoutUUID($user);
                    if($user->id > 0)
                    {
                        //sendCRM($user);
                    };
                }
                else
                {
                   echo"id";
                    if(!isset($json["Body"]["id"]))
                    {
                        $user->UUID = $json["Body"]["id"];
                    }
                    else
                    {
                        $user->UUID = $json["Body"]["uuid"];
                    }
                    $version =$json["Body"]["version"];
                    $user->version = $version;
                    $user = CreateCustomerWithUUID($user);

                    if($user->id > 0)
                    {
                        sendCRM($user);
                    };
                }

            }
        }
        if($json["Method"] == "PUT")
        {
            $user->version = $json["Body"]["version"];
            $user->UUID = $json["Body"]["uuid"];
            $user = new User(  null,$name,$email,$street,$state,$city,$country,$zip,$phone);
            UpdateCustomer($user);

        }
        if($json["Method"] == "GET")
        {
            if($json["ObjectType"] == "VST")
            {
                //TE ZIEN
            }

        }
        if($json["Method"] == "DELETE")
        {
            $UUID = $json["Body"]["uuid"];
            SetInactiveCustomer($UUID);
        }
    }
    else
    {
        //error
    }
    
    
};

$channel->basic_consume('Kassa', '', false, true, false, false, $callback);

while(true)
{
    sendOrdersFromPointOfSale();
    sleep(5);
}
while(count($channel->callbacks)) {
    $channel->wait();
}

//TIMERS
$lastOrderID= 1;
$lastCustomerID = 105;
function sendOrdersFromPointOfSale()
{
   global $lastOrderID;
    do
    {
       $response =  readOrder($lastOrderID);
        if($response != false)
        {
             $lastOrderID++ ;
            $total = $response->total;
            $credit = getCustomerCreditByID($response->customerID);
            if($credit - $total < 0)
            {
                //Erroor
            }
            else
            {
                $newcredit = $credit - $total;
                UpdateCustomerCreditNegatif($response->customerID, $newcredit);
            }
            
        }
       
    }while ($response != false );
    
    
   
}
function sendNewCustomers()
{
   global $lastCustomerID;
    do
    {
       $response =  readCustomerById($lastCustomerID);
        if($response != false)
        {
            
             
            if($response->UUID == false)
            {
                $master = getMasterUUID($response->email);
                $UUID = $master["UUID"];
                $version = $master["version"];
                UpdateCustomerUUID($lastCustomerID, $UUID,$version);
                $response->UUID = $UUID;
                $response->version = $version;
                //sendCRM($response);
            }
           
           $lastCustomerID++; 
        }
    }while ($response != false );
    
    
   
}




?>


