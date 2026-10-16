<?php
include './config.php';
include './functions.php';
include './buttons.php';

$MySQLi = new mysqli('localhost',$DB['username'],$DB['password'],$DB['dbname']);
$MySQLi->query("SET NAMES 'utf8'");
$MySQLi->set_charset('utf8mb4');
if ($MySQLi->connect_error) die;

if(!isset($_REQUEST['user']) or !isset($_REQUEST['p']) or !isset($_REQUEST['status']) or !isset($_REQUEST['track_id']) or !isset($_REQUEST['order_id'])){
echo '<h1 style="text-align: center;margin-top:30px">خطا رخ داده است</h1>';
ToDie($MySQLi);
}

    
$user = $_REQUEST['user'];
$plan = $_REQUEST['p'];

switch($_REQUEST['status']){
case 1:
$ERROR = 'پرداخت انجام نشده است';
break;
case 2:
$ERROR = 'پرداخت ناموفق بوده است';
break;
case 3:
$ERROR = 'خطا رخ داده است';
break;
case 7:
$ERROR = 'انصراف از پرداخت';
break;
case 101:
$ERROR = 'پرداخت قبلا تایید شده است';
break;
case 200:
$ERROR = 'به دریافت کننده واریز شد';
break;
}
if($_REQUEST['status'] != 100){
GoldDev('sendMessage',[
'chat_id'=>$user,
'text'=> "پردخت ناموفق ❌

$ERROR",
'parse_mode'=>"HTML",
]);
echo $ERROR;
ToDie($MySQLi);
}


$params = array(
  'id' => $_REQUEST['id'],
  'order_id' => $_REQUEST['order_id'],
);
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.idpay.ir/v1.1/payment/inquiry');
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

$track_id = $result->track_id;
$amount = $result->amount;
$name = $result->payer->name?:'-';
$phone = $result->payer->phone?:'-';
$card_no = $result->payment->card_no?:'-';


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


if($price != $amount) ToDie($MySQLi);

$HisDataBase = mysqli_fetch_assoc(mysqli_query($MySQLi, "SELECT * FROM `user` WHERE `id` = '{$user}' LIMIT 1"));

if($HisDataBase['pID'] != $_REQUEST['order_id']) ToDie($MySQLi);

$coin = $HisDataBase['coin'] + $plan;
$MySQLi->query("UPDATE `user` SET `coin` = $coin WHERE `id` = '{$user}' LIMIT 1");
$MySQLi->query("UPDATE `user` SET `pID` = null WHERE `id` = '{$user}' LIMIT 1");
$MenTionUser = "[کاربر پرداخت کننده](tg://user?id=$user)";

LampStack('sendMessage',[
'chat_id'=> $payments_log ,
'text'=> "پرداخت موفقیت آمیز ✅

نام پرداخت کننده : $name
شماره پرداخت کننده : $phone
شماره کارت پرداخت کننده : $card_no
شناسه عددی تلگرام پرداخت کننده : $user
مبلغ پرداختی : $amount ریال
کد رهگیری : $track_id

$plan سکه به سکه های $MenTionUser اضاف شد ✅",
'parse_mode'=>"MarkDown",
]);

LampStack('sendMessage',[
'chat_id'=>$user,
'text'=> "پرداخت با موفقیت انجام شد ✅

$plan سکه به سکه های شما اضافه شد.",
'parse_mode'=>"html",
]);

echo '<h1 style="text-align: center;margin-top:30px">تراکنش موفقیت آمیز بود.</h1>';

ToDie($MySQLi);