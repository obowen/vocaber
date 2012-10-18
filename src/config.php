<?php
// config file
//**************

// error reporting
//error_reporting(E_ALL|E_STRICT);
//ini_set("display_errors", TRUE);

// Database connection
$hostname="vocdb.db";
$database="vocabdb";
$sqlUser="obowen";
$sqlPass="chester";

// globals
$DEFAULT_LIST_ID=1;

// Database functions
function run_mysql_query($query)
{
  $result=mysql_query($query);
  if(!$result) 
  {
    $err=mysql_error(); 
    print $err;
    exit();
  }
  return $result;
}
// error handling, this function outputs the master content and the dies so error messages aren't just test
function die_with_master($err_msg)
{
  print $err_msg;
  print "</div><br><br><br>";
  $pagemaincontent = ob_get_contents();
  ob_end_clean();
  include("master.php");
  exit();
}

// password hashing
define('SALT_LENGTH', 5);
function generateHash($plainText, $salt = null)
{
    if ($salt === null)
    {
        $salt = substr(md5(uniqid(rand(), true)), 0, SALT_LENGTH);
    }
    else
    {
        $salt = substr($salt, 0, SALT_LENGTH);
    }

    return $salt . sha1($salt . $plainText);
}

// admin ID (adminob pi****)
$ADMIN_ID=143;
?>
