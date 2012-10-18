<?php
  ob_start();
  include 'config.php';
  session_start(); 
?>

            <div id = "maincol_only">
              <div class="text">
  
                <?
                
                if (!isset($_SESSION['userid']))
                {
                  die_with_master("You must be logged in to remove lists. <a href=\"index.php\">login</a><br>");
                }
                if (!isset($_GET['posID']))
                {
                  die_with_master("Unable to remove list.");
                }
                $posID = $_GET['posID'];
                // get user id
                $userID=$_SESSION['userid'];
                
                // connect to DB
                mysql_connect($hostname,$sqlUser,$sqlPass);
                @mysql_select_db($database) or die( "Unable to select database");
                
                // remove this user-list pair from the positions table
                $query="DELETE FROM positions WHERE ID = '$posID'";
                $result=mysql_query($query);
                if(!$result) 
                {
                  $err=mysql_error();
                  print $err;
                  exit();
                }
                ?>  
        
                Word list removed.
                <br><br><span class="smallText"><a href="my_lists.php">back to my lists</a></span>  
 
            </div> <!-- end of text -->
          </div> <!-- main col -->

<?php
  //Assign all Page Specific variables
  $pagemaincontent = ob_get_contents();
  ob_end_clean();
  
  $navBar = "<ul><li><a href=\"index.php\">Home</a></li>".
      	    "    <li><a href=\"my_lists.php\">Lists</a></li>".
      	    "		 <li><a href=\"learnwords.php\">Learn</a></li>".
    		    "    <li><a href=\"tour.php\">Tour</a></li></ul>";
  
  //Apply the template
  include("master.php");
?>
