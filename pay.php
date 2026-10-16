<?php
include './config.php';
include './functions.php';
include './buttons.php';

$MySQLi = new mysqli('localhost',$DB['username'],$DB['password'],$DB['dbname']);
$MySQLi->query("SET NAMES 'utf8'");
$MySQLi->set_charset('utf8mb4');
if ($MySQLi->connect_error) die;

if(!isset($_REQUEST['user']) or !isset($_REQUEST['p'])){
echo 'Error.';
ToDie($MySQLi);
}

$user = $_REQUEST['user'];
$plan = $_REQUEST['p'];

$userDB = mysqli_fetch_assoc(mysqli_query($MySQLi, "SELECT * FROM `user` WHERE `id` = '{$user}' LIMIT 1"));
if(!$userDB){
echo 'USER NOT FOUND !';
ToDie($MySQLi);
}

switch($plan){
case 20 :
    $price = 50000;
break;
case 50 :
    $price = 100000;
break;
case 120 :
    $price = 200000;
break;
case 500 :
    $price = 500000;
break;
default :
    echo 'Error.';
    ToDie($MySQLi);
}
$order_id = time();
$params = array(
  'order_id' => $order_id,
  'amount' => $price,
  'callback' => $webAddress.'/back.php?user='.$user.'&p='.$plan,
);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.idpay.ir/v1/payment');
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
  'Content-Type: application/json',
  'X-API-KEY: '.$merchantID,
  'X-SANDBOX: 1',
));

$result = curl_exec($ch);
curl_close($ch);


$result = json_decode($result);
if (empty($result) || empty($result->link)) {
echo $result->error_message;
ToDie($MySQLi);
}
$MySQLi->query("UPDATE `user` SET `pID` = $order_id WHERE `id` = '{$user}' LIMIT 1");
header('Location:' . $result->link);
ToDie($MySQLi);