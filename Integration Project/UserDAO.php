<?php
include "UserClass.php";

require_once('ripcord-master/ripcord.php');


$url = "http://10.3.51.22:8069";
$db = "odoo";
$username = "ipkassac@gmail.com";
$password = "Kassa123";
$common = ripcord::client("$url/xmlrpc/2/common");
$uid = $common->authenticate($db, $username, $password, array());
$models = ripcord::client("$url/xmlrpc/2/object");

 function readCustomers()
{
    global $url;
    global $db;
    global $username;
    global $password;
    global $common;
    global $uid;
    global $models;
    $records = $models->execute_kw($db, $uid, $password,
        /*Database table*/       'res.partner',
        /*Action on table*/      'search_read',
        array(array(array('active', '=', true))),
        array('fields'=>array('id','name', 'email', 'street','phone','create_date','x_UUID','city','zip','x_state','x_country',"credit","x_version")));
 
   foreach($records as  $list)
    {
       
        $user = new User( $list["id"], $list["name"], $list["email"], $list["street"],$list["x_state"],$list["city"],$list["x_country"],$list["zip"], $list["phone"]);
        $user->credit = $list["credit"];
        $user->UUID = $list["x_UUID"];
        $user->version = $list["x_version"];
        $user->createDate = $list["create_date"];
        $user->toString();
        
        
    }
}

 function readCustomerById($id)
{
    // @TODO change to uuid
    global $url;
    global $db;
    global $username;
    global $password;
    global $common;
    global $uid;
    global $models;
    $records = $models->execute_kw($db, $uid, $password,
        /*Database table*/       'res.partner',
        /*Action on table*/      'search_read',
        array(array(array('id', '=', $id))),
        array('fields'=>array('id','name', 'email', 'street','phone','create_date','x_UUID','city','zip','x_state','x_country',"credit","x_version"), 'limit'=>5));
    var_dump($records);
    if(count($records) == 0)
    {
        return false;
    }
   foreach($records as $list)
    {
        $user = new User($list["id"], $list["name"], $list["email"], $list["street"],$list["x_state"],$list["city"],$list["x_country"],$list["zip"], $list["phone"]);
        $user->credit = $list["credit"];
        $user->version = $list["x_version"];
        $user->UUID = $list["x_UUID"];
        $user->createDate = $list["create_date"];
        
        return $user;
    }
}
 function readCustomerByUUID($UUID)
{
    // @TODO change to uuid
    global $url;
    global $db;
    global $username;
    global $password;
    global $common;
    global $uid;
    global $models;
    $records = $models->execute_kw($db, $uid, $password,
        /*Database table*/       'res.partner',
        /*Action on table*/      'search_read',
        array(array(array('x_UUID', '=', $UUID))),
        array('fields'=>array('id','name', 'email', 'street','phone','create_date','x_UUID','city','zip','x_state','x_country',"credit","x_version"), 'limit'=>5));
    
   foreach($records as $list)
    {
       
         $user = new User( $list["id"], $list["name"], $list["email"], $list["street"],$list["x_state"],$list["city"],$list["x_country"],$list["zip"], $list["phone"]);
        $user->credit = $list["credit"];
        $user->version = $list["x_version"];
        $user->UUID = $list["x_UUID"];
        $user->createDate = $list["create_date"];
        
        return $user;
    }
}
 function SetInactiveCustomer($UUID)
{
    // @TODO change to uuid
    global $url;
    global $db;
    global $username;
    global $password;
    global $common;
    global $uid;
    global $models;
    $id =SearchCustomerIdByUUID($UUID);
    $models->execute_kw($db, $uid, $password, 'res.partner', 'write',
        array(array($id), array('active'=> FALSE)));
  
}


function CreateCustomerWithoutUUID($Customer)
{
    // @TODO change to uuid
    global $url;
    global $db;
    global $username;
    global $password;
    global $common;
    global $uid;
    global $models;
    
    
/*  For an image => here
    $im = file_get_contents('lion.jpg');
    $imdata = base64_encode($im);
*/
    $masterinfo = getMasterUUID($Customer->email);
    $Customer->UUID = $masterinfo["UUID"] ;
    $Customer->version = $masterinfo["version"] ;
    
    
    $userinfo = array(
         'name' => $Customer->name,
        'email' => $Customer->email,
        'phone' => $Customer->phone,
        'street' => $Customer->street,
        'x_state' => $Customer->state,
        'x_country' => $Customer->street,
        'zip' => $Customer->street,
        'city' => $Customer->street,
        'create_date' => $Customer->createDate,
        'x_UUID'=> $Customer->UUID,
        'credit'=> $Customer->credit,
        'x_version' =>$Customer->version,
        'barcode'=> $Customer->bar,
        );
    
    // Product creation
    $customer_id = $models->execute_kw($db, $uid, $password, 'res.partner', 'create',
        array($userinfo));
    $Customer->id = $customer_id;
    echo $customer_id;
    return $Customer;
   
}
function CreateCustomerWithUUID($Customer)
{
    // @TODO change to uuid
    global $url;
    global $db;
    global $username;
    global $password;
    global $common;
    global $uid;
    global $models;
    
    
/*  For an image => here
    $im = file_get_contents('lion.jpg');
    $imdata = base64_encode($im);
*/
    
    
    
    $userinfo = array(
        'name' => $Customer->name,
        'email' => $Customer->email,
        'phone' => $Customer->phone,
        'street' => $Customer->street,
        'x_state' => $Customer->state,
        'x_country' => $Customer->street,
        'zip' => $Customer->street,
        'city' => $Customer->street,
        'create_date' => $Customer->createDate,
        'x_UUID'=> $Customer->UUID,
        'credit'=> $Customer->credit,
        'x_version' =>$Customer->version,
        'barcode'=> $Customer->bar,
        );
    
    // Product creation
    $customer_id = $models->execute_kw($db, $uid, $password, 'res.partner', 'create',
        array($userinfo));
    $Customer->id = $customer_id;
    return $Customer;
   
}
function UpdateCustomer($Customer)

{
    // @TODO change to uuid
    global $url;
    global $db;
    global $username;
    global $password;
    global $common;
    global $uid;
    global $models;
    $id = SearchCustomerIdByUUID($Customer->$UUID);
    $userinfo = array(
        'name' => $Customer->name,
        'email' => $Customer->email,
        'phone' => $Customer->phone,
        'street' => $Customer->street,
        'x_state' => $Customer->state,
        'x_country' => $Customer->street,
        'zip' => $Customer->street,
        'city' => $Customer->street,
        'x_UUID'=> $Customer->UUID,
        'x_version' =>$Customer->version,
        );
    //IF WORKS WITH UUID,MUST FETCH FIRST TO GET ID
    $models->execute_kw($db, $uid, $password, 'res.partner', 'write',
        array(array($id),  $userinfo)));

}
function UpdateCustomerCreditPositif($UUID, $credit)
{
   // Recieve the uuid of customer and credit to add to the current credit
    global $url;
    global $db;
    global $username;
    global $password;
    global $common;
    global $uid;
    global $models;
    
    $id =SearchCustomerIdByUUID($UUID);
    $cred = getCustomerCreditByUUID($UUID);
    $cred = $cred + $credit;
    
    
    $models->execute_kw($db, $uid, $password, 'res.partner', 'write',
        array(array($id),   array('credit'=>$cred)));
   
   
}
function UpdateCustomerCreditNegatif($id, $credit)
{
    // Recieve an id of customer and a new credit to set , calculation made elsewhere
    global $url;
    global $db;
    global $username;
    global $password;
    global $common;
    global $uid;
    global $models;
    
    
    
    $models->execute_kw($db, $uid, $password, 'res.partner', 'write',
        array(array($id),   array('credit'=>$credit)));
   
   
}
function getCustomerCreditByUUID($UUID)
{
    // @TODO change to uuid
    global $url;
    global $db;
    global $username;
    global $password;
    global $common;
    global $uid;
    global $models;
    
    $records = $models->execute_kw($db, $uid, $password,
        /*Database table*/       'res.partner',
        /*Action on table*/      'search_read',
        array(array(array('x_UUID', '=', $UUID))),
        array('fields'=>array('credit')));
    
   foreach($records as $user)
    {
       
        echo $user["credit"];        
    }
   return $user["credit"];
}
function getCustomerCreditByID($id)
{
    // @TODO change to uuid
    global $url;
    global $db;
    global $username;
    global $password;
    global $common;
    global $uid;
    global $models;
    
    $records = $models->execute_kw($db, $uid, $password,
        /*Database table*/       'res.partner',
        /*Action on table*/      'search_read',
        array(array(array('id', '=', $id))),
        array('fields'=>array('credit')));
    
   foreach($records as $user)
    {
       
        echo $user["credit"];        
    }
   return $user["credit"];
}

function SearchCustomerIdByUUID($UUID)
{
    // @TODO change to uuid
    global $url;
    global $db;
    global $username;
    global $password;
    global $common;
    global $uid;
    global $models;
    
    $records = $models->execute_kw($db, $uid, $password,
        /*Database table*/       'res.partner',
        /*Action on table*/      'search_read',
        array(array(array('x_UUID', '=', $UUID))),
        array('fields'=>array('id')));
    
   foreach($records as $user)
    {
       
        echo $user["id"];        
    }
   return $user["id"];
}


function getMasterUUID($email)
{
    $urlUUID = '10.3.51.41/api/v1/uuid';
    $params = array(

        'login'=> 'kassa',
        'password' => "bef01fae58ed6470ebd052da18b25077",
        'uniq'=> $email,
        'kind'=> 'VST'
   
    );
    $json =json_encode($params);
    $ch = curl_init( $urlUUID);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Content-Length: ' . strlen($json)));
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch,CURLOPT_CUSTOMREQUEST,"POST");
    curl_setopt($ch,CURLOPT_POSTFIELDS,$json);

    $response = curl_exec($ch);
    $response = json_decode($response,true);
    $masterinfo = array( 'UUID'=>$response["StatusMessage"]["UUID"],
                    'version'=> $response["StatusMessage"]["Version"]);
    return $masterinfo;
}
function UpdateCustomerUUID($id,$UUID,$version)
{
    // THIS FUNCTION IS ONLY USED WHEN CUSTOMERS ARE MADE IN THE GUI DO NOT USE ELSEWHERE
    global $url;
    global $db;
    global $username;
    global $password;
    global $common;
    global $uid;
    global $models;
   
    //IF WORKS WITH UUID,MUST FETCH FIRST TO GET ID
    $models->execute_kw($db, $uid, $password, 'res.partner', 'write',
        array(array($id),   array('x_UUID'=>$UUID
                                 ,'x_version'=>$version
                                 ,'barcode'=>1)));
}
function deadtest()
{
    $user = new User("testchange","testchange@gmail.com","testchangeland",33333333,1444444);
UpdateCustomer(12, $user);
     $user = new User("testchange","testchange@gmail.com","testchangeland",33333333,1444444);
SearchCustomerId($user);
}

?>