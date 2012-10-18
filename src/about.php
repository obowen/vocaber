<?php
  //Buffer larger content areas like the main page content
  ob_start();
  session_start(); // needed for login corner link 
?>
                  
       
      <div id="maincol_fixed">
              <div class="altHeading">
                About Vocaber
              </div>
              Vocaber is an online tool for the rapid acquistion of new vocabulary.<br><br>
                Vocaber grew out of a need for a simple way to learn words without having to worry about 
                forgetting them along the way.  Vocaber acheives this using a spaced repetition algorithm which automatically 
                retests you on words at the appropriate frequency to move them from short-term memory
                into long-term memory.<br> 
              <br>
              Vocaber is a work in progress with new features and improvements being made on a regular basis.
              Please feel free to <a href="contact.php">drop us a line</a> if you have any comments questions or suggestions.
              <br>
              <br>
              <br>
              <br>
              
              <div class="subText">
              Vocaber was developped using javascript, php and mysql, and is hosted at nearlyfreespeech.net.<br>
              Thanks to <a href="http://www.freecsstemplates.org" target="_blank">freecsstemplates.org</a> for style inspiration.
              </div>
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