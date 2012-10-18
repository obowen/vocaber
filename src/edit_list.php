<?php
  
  //Buffer larger content areas like the main page content
  ob_start();

  include 'config.php';

  session_start(); // start up the PHP session
?>
    <div id="maincol_only">
  
        <?php
          
          if (!isset($_SESSION['userid']))
          {
            die_with_master ("You must be logged in to edit lists. &nbsp <a href=\"index.php\">login</a><br>");
          }
          if (!isset($_GET['listid']))
          {
              die_with_master("Page reached unexpectedly.");
          }      
          $listID = $_GET['listid'];
          // get user id
          $userID=$_SESSION['userid'];
          
          // connect to DB
          mysql_connect($hostname,$sqlUser,$sqlPass);
          @mysql_select_db($database) or die( "Unable to select database");
          
          // retreive list information
          $query="SELECT * FROM lists WHERE ID='$listID'";
          $result=mysql_query($query);
          if(!$result) 
          {
            $err=mysql_error(); print $err;
            exit();
          }
          if(mysql_numrows($result)==0)
          {
            die_with_master("Unable to retrieve list");
          }
          
          $creatorID=mysql_result($result,0,"creator");
          $shared=mysql_result($result,0,"shared");
          $fixable=mysql_result($result,0,"fixable");
          $editable=mysql_result($result,0,"editable");
          $listName=mysql_result($result,0,"name");
          // check user has the right to edit this list
          if ($creatorID != $userID && !$editable)
          {
            die_with_master ("You do not have permission to modify this list");
          }
          
        ?>
          
  <div class="subHeading">
    Edit Word List: <?php print("<i>".$listName."</i>");?>
   </div>  
  
          <?php
            // include instructions which are shared between pages
            include("create_instr.html");
          ?> 
  
   <form accept-charset="utf-8" name="createForm" method="post" action="submit_list.php">
      <input type="hidden" name="listid" value="<?php print $listID;?>">
      <input type="hidden" name="isnew" value="no">
      <table class="text"> 
      <tr>
        <td>
          Type or paste your changes here
        </td>
      </tr>
      <tr><td>
<textarea name="data" rows="20" cols="80" value=""><?php
            // get list content
            $query="SELECT * FROM terms WHERE list='$listID' ORDER BY ID";
            $result=run_mysql_query($query);
            $num=mysql_numrows($result);
            $i=0;
            while ($i < $num) 
            { 
              $word=stripslashes(mysql_result($result,$i,"word"));
              $meaning=stripslashes(mysql_result($result,$i,"meaning"));
              $context=stripslashes(mysql_result($result,$i,"context"));
              
              $edit_text = $word."; ".$meaning.";";
              if (strlen($context) > 0)
              {
                $edit_text=$edit_text." ".$context.";";
              }
              echo $edit_text."\n";
              $i++;
            }
?></textarea>
      </td></tr>
      </table>
      <br>
<?php

    
    if ($creatorID==$userID)
    {
      print"<input type=\"checkbox\" name=\"shared\" value=\"yes\"";if ($shared) print "checked"; print">Allow others to use my list.<br>\n";
          
      print"  <input type=\"checkbox\" name=\"fixable\" value=\"yes\"";if ($fixable) print "checked"; print">Allow others to correct mistakes in my list.<br>\n";
      print"  <input type=\"checkbox\" name=\"editable\" value=\"yes\"";if ($editable) print "checked"; print">Allow others to directly edit or add to my list.<br>\n";
      // print local copy warning only for creator
      print"     <div class=\"lightText\">\n";
      print"      &nbsp &nbsp (Save a local copy of your list just in case)<br>\n";
      print"     </div>\n";
    }
    else
    { 
      print"<input type=\"hidden\" name=\"shared\" value=\"yes\"";
      print"  <input type=\"hidden\" name=\"fixable\" value=\"yes\"";
      print"  <input type=\"hidden\" name=\"editable\" value=\"yes\"";
      // print local copy warning only for creator
    }
    print"  <br>";
   
    mysql_close();
?>      
      
      
    <a href="my_lists.php">Cancel</a>&nbsp &nbsp
    <input type="submit" value="Submit Changes"/>
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

