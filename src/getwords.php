<?php
  if (!isset($_SESSION['userid']))
  {
    print "var pos = 0;";
  }
  else
  {
    $pos = $_SESSION['position'];
    print "var pos = $pos;";
  }
  // these should be set by leanrwords.php regardless of whethe or not user id is set.
  $listID = $_SESSION['listid'];
  
  mysql_connect($hostname,$sqlUser,$sqlPass) or die( "Unable to select database");
  @mysql_select_db($database) or die( "Unable to select database");
  
$query="SELECT * FROM terms WHERE list='$listID' ORDER BY ID";
$result=mysql_query($query);
if(!$result) 
{
  $err=mysql_error();
  print $err;
  exit();
}
$num=mysql_numrows($result);

mysql_close();

echo "var words = new Array();\n";
echo "var meanings = new Array();\n";
echo "var contexts = new Array();\n";
echo "var wordIDs = new Array();\n";

echo "var numWords = $num;";

$i=0;
while ($i < $num) {

$word=mysql_result($result,$i,"word");
$meaning=mysql_result($result,$i,"meaning");
$context=mysql_result($result,$i,"context");
$wordID=mysql_result($result,$i,"ID");
#echo "<b>$word</b><br> $meaning<br>$context<br><hr><br>";
echo "words[$i]=\"$word\";\n";
echo "meanings[$i]=\"$meaning\";\n";
echo "contexts[$i]=\"$context\";\n";
echo "wordIDs[$i]=\"$wordID\";\n";

$i++;
}
?>