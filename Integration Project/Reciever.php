<?php
include "Sender.php";


require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
$connection = new AMQPStreamConnection('10.3.51.32', 5672, 'kassa', 'Student1');


$channel = $connection->channel();
$channel->queue_declare('KassaQueue', true, false, false, false);

echo ' [*] Waiting for messages. To exit press CTRL+C', "\n";

$callback = function($msg) 
{
    $json = json_decode($msg->body,true);
    if($json["Receiver"] == "KAS")
    {
      
       if($json["Type"] == "HBT")
        {
            $uuid = $json["Body"]["uuid"];
            $timestampsnd = $json["Body"]["timestampsnd"];
            $version = $json["Body"]["version"];
            $var = $json["Body"]["var"];
            
            sendMONITORINGCheckSystem($uuid,$timestampsnd,$version, $var);
            echo 'Check system done
            ';
        }
        elseif($json["Method"] == "POST")
        {
            $name = $json["Body"]["name"];
            $name .= '_'.$json["Body"]["surname"];
            $email =  $json["Body"]["email"];
            $street =  $json["Body"]["street"];
            $phone = $json["Body"]["tel"];
            $state = $json["Body"]["state"];
            $city = $json["Body"]["city"];
            $country = $json["Body"]["country"];
            $zip = $json["Body"]["zip"];
        
            if (readCustomerByEmail($email) == NULL)
            {
                $user = new User(  null,$name,$email,$street,$state,$city,$country,$zip,$phone);
                $user->credit = 0;
                if($json["Sender"] == "FRE")
                {
                    $user->registered = FALSE;
                } 
                else
                {
                    $user->registered = TRUE;
                }
                if(!isset($json["Body"]["id"]) || !isset($json["Body"]["uuid"]))
                {

                       
                        $user = CreateCustomerWithoutUUID($user);
                        echo "Customer created with uuid ". $user->UUID . " with paramaters :
                        ______________________________________________________________";
                        $user->toString();
                        /*
                        if($user->id > 0)
                        {
                           print_r($user);
                            sendCreateUserCRM($user);
                            sendMONITORINGLog($user);
                        };
                        */
                }
                else
                {
                       
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
                    
                        echo "Customer created with uuid ". $user->UUID . " with paramaters :
                        ______________________________________________________________";
                        $user->toString();
                        /*
                        if($user->id > 0)
                        {
                            print_r($user);
                            sendCreateUserCRM($user);
                            sendMONITORINGLog($user);
                        };
                        */
                }
            }
            else
            {
                //ACCOUNTN ALREADY EXIST
            }
            
        }
        elseif($json["Method"] == "PUT")
        {
            
            $user = new User(  null,$name,$email,$street,$state,$city,$country,$zip,$phone);
            $user->version = $json["Body"]["version"];
            $user->UUID = $json["Body"]["uuid"];
            UpdateCustomer($user);
            
            echo "Customer updated with uuid ". $user->UUID . " with paramaters :
            ______________________________________________________________";
            $user->toString();

        }
        elseif($json["Method"] == "GET")
        {
            if($json["ObjectType"] == "VST")
            {
                //TE ZIEN
            }

        }
        elseif($json["Method"] == "DELETE")
        {
            $UUID = $json["Body"]["uuid"];
            SetInactiveCustomer($UUID);
            echo "Customer with uuid " . $user->UUID . " set inactif(Blocked) ";
        }
        
    }
    else
    {
        //error
    }
    
    
};




/*

$user = readCustomerById(120);
UpdateCustomerCreditPositif($user->UUID,500000);
$user = readCustomerById(120);
var_dump($user );
*/

$channel->basic_consume('KassaQueue', '', true, true, false, false, $callback);
while(count($channel->callbacks)) 
{
    $channel->wait();
}

/**/

?>


