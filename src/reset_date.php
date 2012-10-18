<?php
session_start(); // start up the PHP session
include 'config.php';

// make sure there is an actual user
if (!isset($_SESSION['userid']))
{
  die("error, session id not set");
}


$userID  = $_SESSION['userid'];

// connect to DB
mysql_connect($hostname,$sqlUser,$sqlPass);
@mysql_select_db($database) or die( "Unable to select database");

// get total number of words, this is what since words will be reset to
$totalWords = 0;
$query = "SELECT * FROM positions WHERE userid='$userID'";
$result = mysql_query($query) or die(mysql_error()); 
$numlists = mysql_numrows($result);
$i=0;
while ($i < $numlists) 
{
  $totalWords = $totalWords + mysql_result($result,$i,"position");
  $i++;
}
  
// now update since date and words
$query = "UPDATE users SET sincedate=SYSDATE(), sincewords='$totalWords' WHERE ID='$userID'";
mysql_query($query) or die(mysql_error());

// get the date and print this
$query = "SELECT * from users WHERE ID='$userID'";
$result = mysql_query($query) or die(mysql_error());

$dateString = mysql_result($result,0,"sincedate");
print ($dateString);


mysql_close();

?>
