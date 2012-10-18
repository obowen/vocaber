<?php
  
  session_start();
  session_unset();
  session_destroy();

  //Buffer larger content areas like the main page content
  ob_start();
  
?>


          <div id="maincol_only">
              <br>
              You have been logged out. <a href="index.php"><br>
              login</a><br>
              <br>
              <br>
              <br>
              <br>
              <br>
              <br>
              
          </div> <!-- end of main column -->

<?php
  //Assign all Page Specific variables
  $pagemaincontent = ob_get_contents();
  ob_end_clean();
  
  $navBar = "<ul><li><a href=\"index.php\">Home</a></li>".
      	    "    <li><a href=\"my_lists.php\">Lists</a></li>".
      	    "		 <li><a href=\"learnwords.php\">Learn</a></li>".
      	    "    <li><a href=\"tour.php\">Tour</a></li></ul>";
    		    
  //Apply the template
  $displayLoginLink=0;
  include("master.php");
?>
      	





