<?php
include ('config.php');
$MySQLi = new mysqli('localhost',$DB['username'],$DB['password'],$DB['dbname']);
$MySQLi->query("SET NAMES 'utf8'");
$MySQLi->set_charset('utf8mb4');
if ($MySQLi->connect_error){
echo 'Connection failed: ' . $MySQLi->connect_error;
$MySQLi->close();
die;
}


//   Make Users Table   
$query = "CREATE TABLE `user` (
`id` BIGINT(255) PRIMARY KEY,
`step` VARCHAR(50),
`sex` VARCHAR(50),
`name` VARCHAR(400),
`age` int DEFAULT NULL,
`coin` int DEFAULT NULL,
`inviter` BIGINT(128) DEFAULT NULL,
`invite_reward` int(1) DEFAULT NULL,
`randomuser` BIGINT(128) DEFAULT NULL,
`bool` int DEFAULT NULL,
`pID` INT(255)
) default charset = utf8mb4";
if($MySQLi->query($query) === false)
echo $MySQLi->error.'<br>';


//   Make Groups Table   
$query = "CREATE TABLE `gps` (
`id` VARCHAR(100) PRIMARY KEY,
`turn` BIGINT(255) DEFAULT NULL,
`date` BIGINT(255) DEFAULT NULL,
`creator` BIGINT(255)
) default charset = utf8mb4";
if($MySQLi->query($query) === false)
echo $MySQLi->error.'<br>';


//   Make UserGroups Table   
$query = "CREATE TABLE `ugps` (
`randcode` VARCHAR(20) PRIMARY KEY,
`id` VARCHAR(100) DEFAULT NULL,
`userid` BIGINT(255) DEFAULT NULL,
`change` BIGINT(255) DEFAULT NULL,
`name` VARCHAR(400) DEFAULT NULL
) default charset = utf8mb4";
if($MySQLi->query($query) === false)
echo $MySQLi->error.'<br>';


//   Make Private Table   
$query = "CREATE TABLE `privates` (
`id` BIGINT(255) PRIMARY KEY,
`you` VARCHAR(100) DEFAULT NULL,
`want` VARCHAR(100) DEFAULT NULL,
`coin` VARCHAR(30) DEFAULT NULL
) default charset = utf8mb4";
if($MySQLi->query($query) === false)
echo $MySQLi->error.'<br>';



//   Make Questions Table   
$query = "CREATE TABLE `questions` (
`id` BIGINT(255) PRIMARY KEY,
`type` VARCHAR(50) DEFAULT NULL,
`question` VARCHAR(8000) DEFAULT NULL
) default charset = utf8mb4";
if($MySQLi->query($query) === false)
echo $MySQLi->error.'<br>';


//   Make Groups Table   
$query = "CREATE TABLE `cronjob` (
`type` VARCHAR(64) PRIMARY KEY,
`text` VARCHAR(8000) DEFAULT NULL,
`count` BIGINT(255) DEFAULT NULL,
`fromid` BIGINT(255) DEFAULT NULL,
`msgid` BIGINT
) default charset = utf8mb4";
if($MySQLi->query($query) === false)
echo $MySQLi->error.'<br>';



$MySQLi->close();
die('DONE');