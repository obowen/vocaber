<?php
  ob_start();

  include 'config.php';
  
  session_start(); 
?>

          <div id="maincol_only">
          
<?php

// connect to DB
mysql_connect($hostname,$sqlUser,$sqlPass);
@mysql_select_db($database) or die( "Unable to select database");

// get username if logged in
if (isset($_SESSION['userid']))
{
  $userID=$_SESSION['userid'];
}

?>  
       <div class="subHeading">
    	   Word Lists
       </div>
       Click on the list name to view it  
       <?php
        if (isset($userID))
          print ". Click on the <i>add</i> link to add it to your lists. <br>";
        else
          print " or learn it. You need to <a href=\"index.php\">login</a> to add lists to your account.<br>";
       ?>
       <br>
       
       <div class="listTable">
         <table cellspacing="0">
         <tr align="center" class="browseTableHeading">
            
            <th>Name</th> <th>Description</th> <th>Terms</th> <th style="border-right:none;">Tags</th> <th style="border:none;background:#FFFFFF;"></th>
                  
         </tr>
       
       
    <?php
      
      // get all lists
      $query = "SELECT * FROM lists";
      $reslist = mysql_query($query) or die(mysql_error());
      $numLists =mysql_numrows($reslist);
    
      $i = 0;
      
      while ($i < $numLists) 
      {
        
        // make sure this list is shared
        $shared     = mysql_result($reslist,$i,"shared");
        $creator    = mysql_result($reslist,$i,"creator");
        if ($shared==1 || (isset($userID) && $creator==$userID))
        {
          $listID         = mysql_result($reslist,$i,"ID");
          $listName       = mysql_result($reslist,$i,"name");
          $description    = mysql_result($reslist,$i,"description");

          // get number of words in list
          $query = "SELECT * FROM terms WHERE list='$listID' ORDER BY ID";
          $result = run_mysql_query($query);
          $listSize = mysql_numrows($result);
          
          // get tags for list
          $tags = "";
          $query = "SELECT * FROM tags WHERE list='$listID' ORDER BY ID";
          $result = run_mysql_query($query);
          for ($j = 0; $j < mysql_numrows($result); $j++)
          {
            $tags .= mysql_result($result,$j,"tag")." ";
          }
          
          print "<tr>";
          print "<td><a href=\"view_list.php?listid=".$listID."\">".$listName."</a></td>";     
          print "<td>".$description."</td>";     
          print "<td>".$listSize."</td>";     
          print "<td style=\"text-align:right;border-right:none;\">".$tags."</td>";
          
          // determine if this list has already been added
          if (isset($userID))
          {
            $query = "SELECT * FROM positions WHERE userid = '$userID' AND list='$listID'";
            $result = run_mysql_query($query);
            if (mysql_numrows($result)==0)
            {
              print "<td style=\"border:none;padding-left:30px;\"><a href =\"add_list.php?listid=".$listID."\">Add</a>";
            }
            else
            {
              print "<td style=\"border:none;padding-left:30px;\"><span class=\"subText\"><i>Added</i></span></a>";
            }
          }
        }    
        
        $i++;
      }
      mysql_close();
    ?>	
    	
       
      </table> <!-- lists table -->
      </div><!-- end list table div -->
      <br> 
      <?php
        if (isset($userID))
        {
          print "<a href=\"my_lists.php\";>Back to my lists</a> &nbsp &nbsp".
                "<a href=\"create_list.php\";>Create new List</a>";
        }
       ?>
    	 
      </div> <!-- end of main col --> 

<?php
  //Assign all Page Specific variables
  $pagemaincontent = ob_get_contents();
  ob_end_clean();
  
  $navActive[2] =1;
    
  //Apply the template
  include("master.php");
?>