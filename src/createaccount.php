<?php
  //Buffer larger content areas like the main page content
  ob_start();
  session_start();
  include 'config.php';
  
?>
        <div id = "maincol_only">
     
<?php

  $username=$_POST['username'];
  $password=$_POST['password'];

  // connect to DB
  mysql_connect($hostname,$sqlUser,$sqlPass);
  @mysql_select_db($database) or die( "Unable to select database");

  //This makes sure they did not leave any fields blank
  if (!$_POST['username'] | !$_POST['password'] | !$_POST['repassword'] ) 
  {
    print "<div class=\"text\">";
    print("Please complete all required fields to create an account.<br>");
    print("<a href=\"index.php\">try again</a><br>");
    print "</div>";
  }
  else
  {
    // check if username is already in use
    $usercheck = $_POST['username'];
    $check = mysql_query("SELECT username FROM users WHERE username = '$usercheck'")
    or die(mysql_error());
    $numRows = mysql_num_rows($check);
  
    //if the name exists it gives an error
    if ($numRows != 0) 
    {
      print "<div class=\"text\">";
      print("Sorry, the username <i>".$_POST['username']."</i> is already in use.<br>");
      print("<a href=\"index.php\">back</a><br>");
      print "</div>";
    }
    else
    {
      // this makes sure both passwords entered match
      if ($_POST['password'] != $_POST['repassword']) 
      {
        print "<div class=\"text\">";
        print("Your passwords did not match.<br>");
        print("<a href=\"index.php\">try again</a><br>");
        print "</div>";
      }
      else
      {
        // hash password
        $passwordHash = generateHash($password);

        // now insert the new user into the database
        $query = "INSERT INTO users VALUES ('',SYSDATE(), SYSDATE(),'0','$username','$passwordHash','-1')";
        if (!mysql_query($query))
        {
          $err=mysql_error(); print $err; 
          exit(); 
        }
        
        // get the id (primary key) of the previous insert
        $prevUserID = mysql_insert_id();
        
        // this is the ID of the default list that is added to each new account
        $firstList = $DEFAULT_LIST_ID;
        
        // create a new position entry
        $query = "INSERT INTO positions VALUES ('', '$prevUserID', '$firstList', '0')";
        if (!mysql_query($query))
        {
          $err=mysql_error(); print $err; 
          exit(); 
        }
        
        // get the ID of that position
        $prevPosID = mysql_insert_id();
        
        // update the recently created user record with its position id
        
        $query = "UPDATE users SET currpos = '$prevPosID' WHERE ID= '$prevUserID' ";
        if (!mysql_query($query))
        {
          $err=mysql_error(); print $err; 
          exit(); 
        }

        print "<div class=\"heading\">Account Created Successfully!</div><br>";
        print "<div class=\"text\">";
        print"<a href=\"my_lists.php\">Get started</a> by selecting word lists.";
        print "</div>";
        
        // log the user in directly
          
        // store all the relevant information in the current session
        $_SESSION['username']=$username;
        $_SESSION['password']=$password;
        $_SESSION['userid']=$prevUserID;
  
      }
    }
   }
  // close mysql connection
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


