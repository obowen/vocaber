<?php
  //Buffer larger content areas like the main page content
  ob_start();
?>
        <div id = "maincol_only">

<?php

include 'config.php';

$table="users";

$username=$_POST['username'];
$password=$_POST['password'];


// connect to DB
mysql_connect($hostname,$sqlUser,$sqlPass);
@mysql_select_db($database) or die( "Unable to select database");

session_start(); // start up the PHP session

?>

        
<?php
$loginGood=0;   
$query = "SELECT * FROM users WHERE username='$username'";
$result = run_mysql_query($query);
if(mysql_numrows($result)!=0)
{
  $dbHash=mysql_result($result,0,"password");
  $passwordHash=generateHash($password,$dbHash);
  if($dbHash == $passwordHash)
  {
    $loginGood=1;
    // now get the current position ID of the user
    $posID=mysql_result($result,0,"currpos");
    $userID=mysql_result($result,0,"ID");
    
    // use this to retrieve the position information
    $query = "SELECT * FROM positions WHERE ID='$posID'";
    $result = mysql_query($query);
    if(!$result) 
    {
      $err=mysql_error(); print $err; 
      exit(); 
    }
    if(mysql_affected_rows()==0)
    {
      // position entry doesn't appear to exist
      $position = 0;
      $listID = DEFAULT_LIST_ID;
    }
    else
    {  
      $position = mysql_result($result,0,"position");
      $listID = mysql_result($result,0,"list");
    }
    // store all the relevant information in the current session
    $_SESSION['username']=$username;
    $_SESSION['password']=$password;
    $_SESSION['listid']=$listID;
    $_SESSION['position']=$position;
    $_SESSION['userid']=$userID;
    
    // redirect to my_lists page
    Header("Location: my_lists.php"); 
    
    // in case that doesn't work?
    print"<div class=\"heading\">";
  	print"Login Successful!";
    print"</div>";
    print"<div class=\"text\">";
    print"<a href=\"my_lists.php\">Continue</a> to your word lists.";
    print"</div>";
  }
  
}
if (!$loginGood)
{
    print"<div class=\"text\">";
    print "Invalid username or password. <a href=\"index.php\">Back to login page.</a>";
    print"</div>";
    //unset($_SESSION['userid']);
    // clear current session
    $_SESSION = array();
}

mysql_close();

?>
	 <br>
	 <br>
	 <br>
	 <br>
	 <br>
	 
 </div> <!-- end of content -->
 
<?php
  //Assign all Page Specific variables
  $pagemaincontent = ob_get_contents();
  ob_end_clean();
  //Apply the template
  include("master.php");
?>


