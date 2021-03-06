<?php 
include "OrderClass.php";

require_once('ripcord-master/ripcord.php');


$url = "http://10.3.51.22:8069";
$db = "odoo";
$username = "ipkassac@gmail.com";
$password = "Kassa123";
$common = ripcord::client("$url/xmlrpc/2/common");
$uid = $common->authenticate($db, $username, $password, array());
$models = ripcord::client("$url/xmlrpc/2/object");

function  readOrders()
{
    global $url;
    global $db;
    global $username;
    global $password;
    global $common;
    global $uid;
    global $models;
    $records = $models->execute_kw($db, $uid, $password,
        /*Database table*/       'pos.order',
        /*Action on table*/      'search_read',
        array(array()),
        array('fields'=>array('id', 'name', 'date_order','partner_id','amount_total','lines')));
    
   foreach($records as $list)
    {
       
        $order = new Order($list["id"],$list["name"],$list["partner_id"][1],$list["partner_id"][0],$list["amount_total"],$list["date_order"],$list["lines"]);
        $order->toString();
        
    }
    
    
}
function readOrder($id)
{
    global $url;
    global $db;
    global $username;
    global $password;
    global $common;
    global $uid;
    global $models;
    $records = $models->execute_kw($db, $uid, $password,
        /*Database table*/       'pos.order',
        /*Action on table*/      'search_read',
        array(array(array('id', '=', $id))),
        array('fields'=>array('id', 'name', 'date_order','partner_id','amount_total','lines')));
    
    if(count($records) == 0)
    {
        return false;
    }
   
   foreach($records as $list)
    {
     
        $order = new Order($list["id"],$list["name"],$list["partner_id"][1],$list["partner_id"][0],$list["amount_total"],$list["date_order"],$list["lines"]);
        
        
        return $order;
        
    }
    
    
}
function readOrderdetail($id)
{
    global $url;
    global $db;
    global $username;
    global $password;
    global $common;
    global $uid;
    global $models;
    $records = $models->execute_kw($db, $uid, $password,
        /*Database table*/       'pos.order.line',
        /*Action on table*/      'search_read',
        array(array(array('id', '=', $id))),
        array('fields'=>array('id', 'price_unit', 'qty','product_id')));
    
    if(count($records) == 0)
    {
        return false;
    }
   
   return $records;
    
    
}
function readOrderBetween($startDate, $endDate)
{
    global $url;
    global $db;
    global $username;
    global $password;
    global $common;
    global $uid;
    global $models;
    $records = $models->execute_kw($db, $uid, $password,
        /*Database table*/       'pos.order',
        /*Action on table*/      'search_read',
        array(array(array('date_order', '>', $startDate),
                   array('date_order', '<', $endDate))),
        array('fields'=>array('id', 'name', 'date_order','partner_id','amount_total','lines')));
    
   foreach($records as $list)
    {
       
        $order = new Order($list["id"],$list["name"],$list["partner_id"][1],$list["partner_id"][0],$list["amount_total"],$list["date_order"],$list["lines"]);
        $order->toString();
        
    }
    
   
}
function  readLastOrders()
{
    global $url;
    global $db;
    global $username;
    global $password;
    global $common;
    global $uid;
    global $models;
    $records = $models->execute_kw($db, $uid, $password,
        /*Database table*/       'pos.order',
        /*Action on table*/      'search_read',
        array(array()),
        array('fields'=>array('id', 'name', 'date_order','partner_id','amount_total','lines'), 'limit'=>10));
    
   foreach($records as $list)
    {
       
        $order = new Order($list["id"],$list["name"],$list["partner_id"][1],$list["partner_id"][0],$list["amount_total"],$list["date_order"],$list["lines"]);
        $order->toString();
        
    }
    
    
}
function UpdateOrderUUID($id,$UUID,$version)
{
    
    global $url;
    global $db;
    global $username;
    global $password;
    global $common;
    global $uid;
    global $models;
   
   
    $models->execute_kw($db, $uid, $password, 'pos.order', 'write',
        array(array($id),   array('x_UUID'=>$UUID
                                 ,'x_version'=>$version)));
}
function getMasterUUIDOrder($odername)
{
    $urlUUID = '10.3.51.41/api/v1/uuid';
    $params = array(

        'login'=> 'kassa',
        'password' => "bef01fae58ed6470ebd052da18b25077",
        'uniq'=> $odername,
        'kind'=> 'ORD'
   
    );
    $json =json_encode($params);
    $ch = curl_init( $urlUUID);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Content-Length: ' . strlen($json)));
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch,CURLOPT_CUSTOMREQUEST,"PUT");
    curl_setopt($ch,CURLOPT_POSTFIELDS,$json);

    $response = curl_exec($ch);
    $response = json_decode($response,true);
   
    $masterinfo = array( 'UUID'=>$response["StatusMessage"]["UUID"],
                    'version'=> $response["StatusMessage"]["Version"]);
    return $masterinfo;
}



?>
