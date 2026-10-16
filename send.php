<?php
include ('config.php');
include ('functions.php');
$MySQLi = new mysqli('localhost',$DB['username'],$DB['password'],$DB['dbname']);
$MySQLi->query("SET NAMES 'utf8'");
$MySQLi->set_charset('utf8mb4');
if ($MySQLi->connect_error) die;
$MainDB = mysqli_fetch_assoc(mysqli_query($MySQLi, "SELECT * FROM `cronjob` LIMIT 1"));
if(!$MainDB) ToDie($MySQLi);
$get = $MainDB['count'];
$plus = $get + 150;
$AllUserID = mysqli_fetch_all(mysqli_query($MySQLi,"SELECT `id` FROM `user` LIMIT 150 OFFSET {$get}"));
$MySQLi->query("UPDATE `cronjob` SET `count` = '{$plus}' LIMIT 1");
if($MainDB['type'] == 'send2all'){
foreach($AllUserID as $id){
LampStack('sendmessage',[
'chat_id'=> $id[0],        
'text'=>$MainDB['text'],
]);
usleep(100000);
}
}
if($MainDB['type'] == 'for2all'){
foreach($AllUserID as $id){
LampStack('ForwardMessage',[
'chat_id'=> $id[0],
'from_chat_id'=>$MainDB['fromid'],
'message_id'=>$MainDB['msgid']
]);
usleep(100000);
}
}
if($plus >= $MySQLi->query("SELECT `id` FROM `user`")->num_rows){
LampStack('sendmessage',[
'chat_id'=> $MainDB['fromid'],
'text'=> 'ارسال / فروارد همگانی با موفقیت پایان یافت.',
 ]);
$MySQLi->query("DELETE FROM `cronjob` WHERE `type` = 'send2all' OR `type` = 'for2all'");
}

ToDie($MySQLi);