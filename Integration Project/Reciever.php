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
        $name = $json["Body"]["name"];
        $name .= '_'.$json["Body"]["surname"];
        $email =  $json["Body"]["email"];
        $street =  $json["Body"]["street"];
        $phone = $json["Body"]["tel"];
        $state = $json["Body"]["state"];
        $city = $json["Body"]["city"];
        $country = $json["Body"]["country"];
        $zip = $json["Body"]["zip"];
        
        if($json["Method"] == "POST")
        {
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
        if($json["Method"] == "PUT")
        {
            
            $user = new User(  null,$name,$email,$street,$state,$city,$country,$zip,$phone);
            $user->version = $json["Body"]["version"];
            $user->UUID = $json["Body"]["uuid"];
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





//TIMERS
$lastOrderID= 7;
$lastCustomerID = 113;
function sendOrdersFromPointOfSale()
{
   global $lastOrderID;
    do
    {
       $response =  readOrder($lastOrderID);
        if($response != false)
        {
             /*
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
            */
            
            $masterinfo = getMasterUUIDOrder($response->ordername);
            $response->UUID = $masterinfo["UUID"];
            $response->version = $masterinfo["version"];
            UpdateOrderUUID($response->id,$response->UUID,$response->version);
            $detaillines = array();
            foreach($response->productIDs as $id)
            {
                $detail = readOrderdetail($id);
                
                $detailline = array("id" => $detail[0]["id"],"name" =>substr($detail[0]["product_id"][1], strpos($detail[0]["product_id"][1], "]") + 2),"price" => $detail[0]["price_unit"],"quantity" =>$detail[0]["qty"]) ;
                array_push($detaillines, $detailline);
            }
            
            
            
            sendMONITORINGOrders($response,$detaillines);
            echo 'New sales order with uuid '.$response->UUID.' and customername '. $response->name .' send to monitoring';
            $lastOrderID++ ;
        }
    }while ($response != false );
    
    
   
}
$RegistredIDs = array();
function sendRegistredUsers()
{
   global $RegistredIDs;
    
    $response =  readRegistredCustomers();
    foreach($response as  $list)
    {
       
        
        if (!in_array($list["id"], $RegistredIDs)) 
        {
            
            /*
            $user = new User($list["id"], $list["name"], $list["email"], $list["street"],$list["x_state"],$list["city"],$list["x_country"],$list["zip"], $list["phone"]);
            $user->credit = $list["credit"];
            $user->version = $list["x_version"];
            $user->UUID = $list["x_UUID"];
            $user->createDate = $list["create_date"];
            $user->bar = $list["barcode"];
            */
            sendMONITORINGRegistred($list["x_UUID"]);
            array_push($RegistredIDs, $list["id"]);
            
        }   
        
        
        
    }
    
    
   
}
function sendNewCustomers()
{
   global $lastCustomerID;
    do
    {
        
       $response =  readCustomerById($lastCustomerID);
        if($response != false)
        {
            
            $response->toString();
            if($response->UUID == false)
            {
                /**/
                $master = getMasterUUID($response->email);
                $UUID = $master["UUID"];
                $version = $master["version"];
                UpdateCustomerUUID($lastCustomerID, $UUID,$version);
                $response->UUID = $UUID;
                $response->version = $version;
                $response->toString();
                sendCreateUserCRM($response);
                sendMONITORINGLog($response);
                
            }
           
           $lastCustomerID++; 
        }
    }while ($response != false );
    
    
   
}

/**/
 //readSavedInfos();
 //read the saved info so you dont have to reinit them

while(true)
{
    
    sendOrdersFromPointOfSale();
    sleep(5);
}
/*
*/

/*
$channel->basic_consume('KassaQueue', '', true, true, false, false, $callback);
while(count($channel->callbacks)) {
    $channel->wait();
}

*/
$relative_path = 'php/testit/info.txt';
function readSavedInfos()
{
    global $relative_path;
    global $lastCustomerID;
    global $lastOrderID;
    global $RegistredIDs;
    
    $myfile = fopen($relative_path, "r") or die("Unable to open file!");
    $info = fread($myfile,filesize($relative_path));
    fclose($myfile);
    
    $jsoninfo = json_decode($info);
    $lastCustomerID = $jsoninfo["lastCustomerID"];
    $lastOrderID = $jsoninfo["lastOrderID"];
    $RegistredIDs = $jsoninfo["RegistredIDs"];
}
function writeSavedInfos()
{
    global $relative_path;
    global $lastCustomerID;
    global $lastOrderID;
    global $RegistredIDs;
    
    $myfile = fopen($relative_path, "w") or die("Unable to open file!");
    $info = array(  "lastCustomerID" => $lastCustomerID,
                    "lastOrderID" => $lastOrderID,
                    "RegistredIDs" => $RegistredIDs
    );

    fwrite($myfile, json_encode($info));
    fclose($myfile);

}
?>


