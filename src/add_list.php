<?php
  ob_start();
  include 'config.php';
  session_start(); 
?>

 <div id = "maincol_only">
              <br>
              <?php
                
                if (!isset($_SESSION['userid']))
                  die_with_master("You must be logged in to add lists. <a href=\"index.html\">login</a><br>");
                $userID=$_SESSION['userid'];
                
                if (!isset($_GET['listid']))
                  die_with_master("Page reached unexpectedly.");
                $listID = $_GET['listid'];
                
                // connect to DB
                mysql_connect($hostname,$sqlUser,$sqlPass);
                @mysql_select_db($database) or die( "Unable to select database");
                
                // retreive list information
                $query="SELECT * FROM lists WHERE ID='$listID'";
                $result=run_mysql_query($query);
                if(mysql_numrows($result)==0)
                  die_with_master("Sorry, can't find that list.");
                $creatorID=mysql_result($result,0,"creator");
                $shared=mysql_result($result,0,"shared");
                $listName=mysql_result($result,0,"name");
                
                // check user has the right to add this list
                if (!$shared && $creatorID != $userID)
                  die_with_master ("You do not have permission to add this list");
                
                // make sure the position entry doesn't already exist
                $query = "SELECT * FROM positions WHERE list='$listID' AND userid='$userID'";
                $result = run_mysql_query($query);
                if (mysql_numrows($result))
                {
                  print "You've already added word list <i>$listName</i>.<br>";
                  print "<br><br>";
                }
                else
                {
                  $query = "INSERT INTO positions VALUES ('','$userID', '$listID', '0')";
                  run_mysql_query($query);
                  print "Word list <i>$listName</i> added.<br>";
                  print "<br>";
                  print "<a href=\"learnwords.php?listid=".$listID."\">learn now</a>";
                  print "<br>";
                }
                print "<a href=\"my_lists.php\">back to my vocab</a>"; 
              ?>  
            <br>
            <br>
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
