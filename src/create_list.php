<?php
  $headercontent="<SCRIPT type=\"text/javascript\" src=\"scripts/create_list.js\"></SCRIPT>";
  //Buffer larger content areas like the main page content
  ob_start();

  include 'config.php';

  session_start(); // start up the PHP session
?>

          <div id="maincol_only">
  
          <?php

          if (!isset($_SESSION['userid']))
          {
            die ("You must be logged in to create lists. &nbsp <a href=\"index.html\">login</a><br>");
          }
          
          // connect to DB
          mysql_connect($hostname,$sqlUser,$sqlPass);
          @mysql_select_db($database) or die( "Unable to select database");
          
          
          ?>  
                           
           <div class="subHeading">
        	   Create Your Own Word List
           </div>
           
           <?php
            include("create_instr.html");
           ?> 
           
           <form accept-charset="utf-8" name="createForm" method="post" onsubmit="return checkList();" action="submit_list.php">
              <table class="text">
                <tr><td>List name:</td><td><input type="text" name="listname" id="listname" size="15"/>
                    <span class="smallLightText">Common French Verbs</span>
                </tr>
                <tr><td>Tags: </td><td><input type="text" name="listTags" id="listTags" size="15"/>
                    <span class="smallLightText">eg. french verbs</span></td>
                </tr>
                <tr><td valign="top">Description: </td><td>
                     
                    <input size="50" type="text" name="listdesc" id="listdesc" size="15"/>
                     
                    <span class="smallLightText">eg. 300 most common french verbs</span></td>
                </tr>
              </table> <!-- end lists table -->
                <br>
                <table class="text"> 
                <tr><td >Type or paste your word list here:</td></tr>
                <tr><td>
                  <textarea name="data" rows="20" cols="80" value=""></textarea>
                </td></tr>
                </table> <!-- end text area table -->
                <br>
                
                <input type="checkbox" name="shared" value="yes" checked onClick="toggleSharing();"> Allow others to use my list.<br>
                <input type="checkbox" name="fixable" value="yes" checked> Allow others to correct mistakes in my list.<br>
                <input type="checkbox" name="editable" value="yes" checked> Allow others to directly edit or add to my list.<br>
                   <div class="lightText">
                    &nbsp &nbsp (Save a local copy of your list just in case)<br>
                   </div>
                <br>
              
              <a href="my_lists.php">Cancel</a>&nbsp &nbsp
              <input type="submit" value="create list"/>
              <input type="hidden" name="isnew" value="yes">
              </form>
           
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
  include("master.php");
?>
      	

