<?php
  ob_start();
  include 'config.php';
  session_start(); 
?>

            <div id = "maincol_only">
              <div class="text">
  
                <?
                
                if (!isset($_SESSION['userid']) or $_SESSION['userid']!=$ADMIN_ID)
                {
                  print $_SESSION['userid'].$ADMIN_ID;
                  die_with_master("Access denied");
                }
                if (!isset($_GET['listid']))
                {
                  die_with_master("Unable to delete list.");
                }
                $listID = $_GET['listid'];
                // get user id
                $userID=$_SESSION['userid'];
                
                // connect to DB
                mysql_connect($hostname,$sqlUser,$sqlPass);
                @mysql_select_db($database) or die( "Unable to select database");
                
                // remove all positions using this list
                $query="DELETE FROM positions WHERE list='$listID'";
                run_mysql_query($query);
                
                // remove all terms in this list
                $query="DELETE FROM terms WHERE list='$listID'";
                run_mysql_query($query);
                
                // remove all tags associated with this list
                $query="DELETE FROM tags WHERE list='$listID'";
                run_mysql_query($query);
                
                // remove this list pair from the lists table
                $query="DELETE FROM lists WHERE ID='$listID'";
                run_mysql_query($query);
                
                ?>  
        
                Word list deleted.
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
