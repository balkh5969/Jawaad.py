<?php
function LampStack($method,$datas=[]){
global $apiKey;
$url = 'https://api.telegram.org/bot'.$apiKey.'/'.$method;
$ch = curl_init();
curl_setopt($ch,CURLOPT_URL,$url);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
curl_setopt($ch,CURLOPT_POSTFIELDS,$datas);
$res = curl_exec($ch);
if(curl_error($ch)){
var_dump(curl_error($ch));
}else{
return json_decode($res);
}
}
//              ---             //
function ToDie($MySQLi){
$MySQLi->close();
die;
}
//              ---             //
function  getUserProfilePhotos($uid) {
global $apiKey;
return json_decode(file_get_contents('https://api.telegram.org/bot'.$apiKey.'/getUserProfilePhotos?user_id='.$uid))->result;
}
//              ---             //
function RandomString($len=10){
$characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
$randstring = null;
for($i = 0; $i < $len; $i++) {
$randstring .= $characters[
rand(0, strlen($characters))
];
}
return $randstring;
}
//              ---             //
function joinCheck($uid){
global $LockChannelsUserID;
global $apiKey;
$chArr = [];
foreach($LockChannelsUserID as $value){
$chArr[] = json_decode(file_get_contents('https://api.telegram.org/bot'.$apiKey.'/getChatMember?chat_id='.$value.'&user_id='.$uid))->result->status;
}
if(in_array('left', $chArr)) return false;
return true;
}
//              ---             //
function getTime($unix=0){
if($unix === 0) $unix = time();
require_once './jdf.php';
return jdate('l', $unix).' '.jdate('d', $unix).' '.jdate('F', $unix).' '.jdate('Y', $unix).' ساعت '.jdate('H:i:s', $unix);
}
//              ---             //
function dbBackup($host, $user, $pass, $dbname, $path) {
$link = mysqli_connect($host,$user,$pass, $dbname);
if (mysqli_connect_errno()){
echo "Failed to connect to MySQL: " . mysqli_connect_error();
exit;
}
mysqli_query($link, "SET NAMES 'utf8'");
$tables = array();
$result = mysqli_query($link, 'SHOW TABLES');
while($row = mysqli_fetch_row($result)) {
$tables[] = $row[0];
}
$return = '';
foreach($tables as $table) {
$result = mysqli_query($link, 'SELECT * FROM '.$table);
$num_fields = mysqli_num_fields($result);
$num_rows = mysqli_num_rows($result);
$return.= 'DROP TABLE IF EXISTS '.$table.';';
$row2 = mysqli_fetch_row(mysqli_query($link, 'SHOW CREATE TABLE '.$table));
$return.= "\n\n".$row2[1].";\n\n";
$counter = 1;
for ($i = 0; $i < $num_fields; $i++) {
while($row = mysqli_fetch_row($result)) {   
if($counter == 1){
$return.= 'INSERT INTO '.$table.' VALUES(';
}else{
$return.= '(';
}
for($j=0; $j<$num_fields; $j++){
$row[$j] = addslashes($row[$j]);
$row[$j] = str_replace("\n","\\n",$row[$j]);
if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; 
}else{
$return.= '""';
}
if ($j<($num_fields-1)) { 
$return.= ',';
}
}
if($num_rows == $counter){
$return.= ");\n";
}else{
$return.= "),\n";
}
++$counter;
}
}
$return.="\n\n\n";
}
$fileName = $path . '.sql';
$handle = fopen($fileName,'w+');
fwrite($handle,$return);
if(fclose($handle)){
return true;
exit; 
}
}






