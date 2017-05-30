<?php

include "Sender.php";




//TIMERS
$relative_path = 'php/IP/info.txt';
$lastOrderID;
$lastCustomerID;
$lastcheck =array();
$RegistredIDs =array();
readSavedInfos();
function sendOrdersFromPointOfSale()
{
   global $lastOrderID;
    do
    {
       $response =  readOrder($lastOrderID);
        if($response != false)
        {
            
            $total = $response->total;
            $credit = getCustomerCreditByID($response->customerID);
            
            if($credit - $total < 0)
            {
                //Erroor , customer have not enough money
                UpdateCustomerAcceptedOrder($response->customerID,FALSE);
                echo ' not enough money';
            }
            else
            {
                // Order is accepted
                $newcredit = $credit - $total;
                UpdateCustomerCreditNegatif($response->customerID, $newcredit);
                UpdateCustomerAcceptedOrder($response->customerID,TRUE);
                
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
                echo "Order with uuid ". $response->UUID . " created  :
                Customer name : ". $response->name."
                Total : ".$response->total."
                ______________________________________________________________";
                
            }
             /**/
            
            
            //echo 'New sales order with uuid '.$response->UUID.' and customername '. $response->name .' send to monitoring';
            $lastOrderID++ ;
            writeSavedInfos();
        }
    }while ($response != false );
    
    
   
}

function sendRegistredUsers()
{
   global $RegistredIDs;
    
    $response =  readRegistredCustomers();
   
    foreach($response as  $list)
    {
       
        
        if (!in_array($list["id"], $RegistredIDs)) 
        {
            
            
            $user = new User($list["id"], $list["name"], $list["email"], $list["street"],$list["x_state"],$list["city"],$list["x_country"],$list["zip"], $list["phone"]);
            $user->credit = $list["x_credit"];
            $user->version = $list["x_version"];
            $user->UUID = $list["x_UUID"];
            $user->createDate = $list["create_date"];
            $user->registered = $list["x_registered"];
            
            echo "User with uuid ". $list["x_UUID"] . " is Regsitred
            ";
            $master = getMasterUUID($user->email);
            $user->version = $master["version"];
            $user->UUID = $master["UUID"];
            UpdateCustomerUUID($user->id,$user->UUID ,$user->version);
            sendMONITORINGRegistred($list["x_UUID"]);
            sendCRMRegistred( $user);
            sendFrontendRegistred($user);
            array_push($RegistredIDs, $list["id"]);
            writeSavedInfos();
            
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
                sendCreateUserMONITORING($response);
                sendCreateUserFRONTEND($response);
                
                 echo "Customer with uuid ". $response->UUID . " created in odoo gui with paramaters :
                 ______________________________________________________________";
                $response->toString();

            }
           
            $lastCustomerID++;
            writeSavedInfos();
        }
    }while ($response != false );
    
    
   
}


function checkUpdatedCredit()
{
    //This function check the users credit, if credit was added or not, if yes it sends a message to montoring
    global $lastcheck;
    

    
    $new = convertToArray(readCustomers());
   
    if(!($lastcheck == $new))
    {
       //IF NOT EQUAL THATS WHAT WE WANT
        foreach($new  as $key => $newitem)
        {
            
            if($newitem["credit"] >500)
            {
                
                echo 'Fraude of user with name '.$newitem["name"].' and uuid '.$newitem["uuid"].' his credit is now '.$newitem["credit"].'
                Amount of credit added to account : '.$creditadd;
               //SendFraude monitoring     
            }
            elseif(array_key_exists($key,$lastcheck)&& $newitem["credit"]> $lastcheck[$key]["credit"])
            {
               
                $creditadd = $newitem["credit"] - $lastcheck[$key]["credit"];
                sendMONITORINGCredit($newitem["uuid"],$newitem["name"],$newitem["credit"],$creditadd);
               
                
                
                echo 'Credit of user with name '.$newitem["name"].' and uuid '.$newitem["uuid"].' changed to '.$newitem["credit"].'
                Amount of credit added to account : '.$creditadd;
            }
        }
        $lastcheck = $new;
        writeSavedInfos();
    };
   
   
}
function convertToArray($toarray)
{
    //Convert customer result to a suitable array to check their credits
    $result = array();
    foreach($toarray as $user)
    {
        
        $info = array("uuid" =>$user["x_UUID"],"name" =>$user["name"],"credit" =>$user["x_credit"]);
        $result[$user["id"]] = $info;
        
    }
    return $result;
}

/**/
 //readSavedInfos();
 //read the saved info so you dont have to reinit them
/**/

while(true)
{
    
    sendOrdersFromPointOfSale();
    //sendRegistredUsers();
    //checkUpdatedCredit();
    //sendNewCustomers();
    
    sleep(5);
}

function readSavedInfos()
{
    global $relative_path;
    global $lastCustomerID;
    global $lastOrderID;
    global $RegistredIDs;
    global $lastcheck;
    
    $myfile = fopen($relative_path, "r") or die("Unable to open file!");
    $info = fread($myfile,filesize($relative_path));
    fclose($myfile);
    
    $jsoninfo = json_decode($info,true);
    
    $lastCustomerID = $jsoninfo["lastCustomerID"];
    $lastOrderID = $jsoninfo["lastOrderID"];
    $RegistredIDs = $jsoninfo["RegistredIDs"];
    $lastcheck = $jsoninfo["LastCreditCheck"];
    
}
function writeSavedInfos()
{
    global $relative_path;
    global $lastCustomerID;
    global $lastOrderID;
    global $RegistredIDs;
    global $lastcheck;

    
    $myfile = fopen($relative_path, "w") or die("Unable to open file!");
    $info = array(  "lastCustomerID" => $lastCustomerID,
                    "lastOrderID" => $lastOrderID,
                    "RegistredIDs" => $RegistredIDs,
                    "LastCreditCheck" => $lastcheck
    );

    fwrite($myfile, json_encode($info));
    fclose($myfile);

}



?>