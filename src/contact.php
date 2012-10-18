<?php
  //Buffer larger content areas like the main page content
  ob_start();
  session_start();
?>
                  
       
      <div id="maincol_fixed">
              <div class="altHeading">
                Contact Vocaber
              </div>
              We welcome any comments, questions or feedback you may have.
              <br>
              <br>
              Until a more functional contact page is developed, please
              email us at vocaber@gmail.com.
              <br>
              <br>
              <br>
              <br>
              <br>
              <br>
              <br>
              <br>
              <br>
              <br>
              <br>
        </div><!-- end main col -->

<?php
  //Assign all Page Specific variables
  $pagemaincontent = ob_get_contents();
  ob_end_clean();
  
  $navBar = "<ul><li><a href=\"index.php\">Home</a></li>".
      	    "    <li><a href=\"my_lists.php\">Lists</a></li>".
      	    "		 <li><a href=\"learnwords.php\">Learn</a></li>".
    		    "    <li><a href=\"tour.php\">Tour</a></li></ul>";
  $bodyWrapFixed = 1;
  //Apply the template
  include("master.php");
?>