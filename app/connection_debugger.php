<?php
// Test file to access all items in DB

require_once "DB/DAOs/UsersDAO.php";
require_once "DB/pdoDBManager.php";
require_once "conf/config.inc.php";

$dbmgr = new pdoDBManager;
$connection = $dbmgr->openConnection();


$dao = new UsersDAO($dbmgr);

$result = $dao->getUsers();
foreach ($result as $id => $row){
	foreach ($row as $key=>$value){
		echo ($key . ': ' . $value . '<br>');
	}
	echo ('<br>');
}

$dbmgr->closeConnection();
?>