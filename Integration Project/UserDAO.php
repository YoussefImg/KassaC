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
        array('fields'=>array('id','name', 'email', 'street','phone','mobile','city','create_date','x_UUID')));
    
   foreach($records as $user)
    {
       
        $list = new User($user["id"],$user["name"],$user["email"],$user["street"],$user["phone"],$user["mobile"],$user["create_date"],0,$user["city"]);
        $list->UUID = $user["x_UUID"];
        $list->toString();
        
    }
}

 function readCustomer($id)
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
        array('fields'=>array('id','name', 'email', 'street','phone','mobile','city','create_date'), 'limit'=>5));
    
   foreach($records as $list)
    {
       
        $user= new User($user["id"],$user["name"],$user["email"],$user["street"],$user["phone"],$user["mobile"],$user["create_date"],0,$user["city"]);
        $user->UUID = $list["x_UUID"];
        $user->toString();
        
    }
}
 function SetInactiveCustomer($id)
{
    // @TODO change to uuid
    global $url;
    global $db;
    global $username;
    global $password;
    global $common;
    global $uid;
    global $models;
    $models->execute_kw($db, $uid, $password, 'res.partner', 'write',
        array(array($id), array('active'=> FALSE)));
  
}


function CreateCustomer($Customer)
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
    $urlUUID = '10.3.51.41/api/v1/uuid';
    $params = array(

        'login'=> 'kassa',
        'password' => "bef01fae58ed6470ebd052da18b25077",
        'uniq'=> 'Marwan',
        'kind'=> 'VST'
   
    );
    $json =json_encode($params);
    $ch = curl_init( $urlUUID);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Content-Length: ' . strlen($json)));
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch,CURLOPT_CUSTOMREQUEST,"PUT");
    curl_setopt($ch,CURLOPT_POSTFIELDS,$json);

    $response = curl_exec($ch);
    $response = json_decode($response,true);
    $Customer->UUID = $response["StatusMessage"]["UUID"];
    echo $Customer->UUID;
    
    $userinfo = array('name' => $Customer->name,
        'mobile' => $Customer->mobile,
        'email' => $Customer->email,
        'phone' => $Customer->phone,
        'street' => $Customer->street,
        'city' => $Customer->city,
        'create_date' => $Customer->createDate,
        'x_UUID'=> $Customer->UUID,
        );
    
    // Product creation
    $customer_id = $models->execute_kw($db, $uid, $password, 'res.partner', 'create',
        array($userinfo));
   
}
function BlokkeerCustomer($id)
{
    // @TODO change to uuid
    global $url;
    global $db;
    global $username;
    global $password;
    global $common;
    global $uid;
    global $models;
    // blokeren niet op inactief zetten?
}
function UpdateCustomer($id, $Customer)
{
    // @TODO change to uuid
    global $url;
    global $db;
    global $username;
    global $password;
    global $common;
    global $uid;
    global $models;
    
    //IF WORKS WITH UUID,MUST FETCH FIRST TO GET ID
    $models->execute_kw($db, $uid, $password, 'res.partner', 'write',
        array(array($id),   array('name'=>$Customer->name,
                                  'mobile'=>$Customer->mobile,
                                  'phone'=>$Customer->phone,
                                  'street'=>$Customer->street,
                                  'email'=>$Customer->email)));
   
   
}
function SearchCustomerId($Customer)
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
        array(array(array('name', '=', $Customer->name),
                    array('email', '=', $Customer->email),
                    array('street', '=', $Customer->street),
                    array('phone', '=', $Customer->phone),
                    array('mobile', '=', $Customer->mobile))),
        array('fields'=>array('id')));
    
   foreach($records as $user)
    {
       //UUID OF ID
        echo $user["id"];        
    }
   
}



function deadtest()
{
    $user = new User("testchange","testchange@gmail.com","testchangeland",33333333,1444444);
UpdateCustomer(12, $user);
     $user = new User("testchange","testchange@gmail.com","testchangeland",33333333,1444444);
SearchCustomerId($user);
}

?>