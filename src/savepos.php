<?php
session_start(); // start up the PHP session

include 'config.php';


// make sure there is an actual user
if (!isset($_SESSION['userid']))
{
  die();
}

$pos = $_GET['pos'];

$userID = $_SESSION['userid'];
$curList = $_SESSION['listid'];

// connect to DB
mysql_connect($hostname,$sqlUser,$sqlPass);
@mysql_select_db($database) or die( "Unable to select database");

$query = "UPDATE positions SET position = '$pos' WHERE list='$curList' AND userid ='$userID'";
mysql_query($query) or die (mysql_error());

// if restarted, then subtract words from sincewords
/*
$query = "SELECT * FROM users WHERE ID='$userID'";
$result = mysql_query($query) or die(mysql_error());
*/               

mysql_close();

?>
