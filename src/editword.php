<?php
session_start(); // start up the PHP session

include 'config.php';

// make sure there is an actual user
if (!isset($_SESSION['userid']))
{
  die();
}

$userID  = $_SESSION['userid'];

$wordID      = $_GET['id'];
$editWord    = $_GET['word'];
$editMeaning = $_GET['meaning'];
$editContext = $_GET['context'];

// connect to DB
mysql_connect($hostname,$sqlUser,$sqlPass);
@mysql_select_db($database) or die( "Unable to select database");

$query = "UPDATE terms SET word='$editWord',  meaning='$editMeaning', context='$editContext' WHERE ID=$wordID";
if (!mysql_query($query))
  {
      $err=mysql_error(); print $err; 
      exit(); 
  }


mysql_close();

?>
