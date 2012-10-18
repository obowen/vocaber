<?php
  ob_start();
  include 'config.php';
  session_start(); 
?>

      <div id="maincol_only">
        <?php
          // get listID
          if (!isset($_GET['listid']))
              die_with_master("Page reached unxpectedly.");
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
          
          if (isset($_SESSION['userid']))
          {
            $userID=$_SESSION['userid']; 
          }
          // check user has the right to view this list
          if (!$shared && !(isset($userID) && $creatorID == $userID))
          {
            die_with_master ("You do not have permission to view this list.");
          }
        ?>  
        <div class="subHeading">
          Terms in <?php print("<i>".$listName."</i>");?>
        </div>
        <br>
        
        <?php
          $canBeAdded=0;
          if (isset($userID))
          {
            // determine if this list has already been added
            $query = "SELECT * FROM positions WHERE userid = '$userID' AND list='$listID'";
            $result = run_mysql_query($query);
            if (mysql_numrows($result)==0)
            {
              $canBeAdded=1;
            }
          }
          function print_list_links($listID, $canBeAdded)
          {
            
            if (isset($_SESSION['userid']))
            {
              if ($canBeAdded)
                print "<td style=\"border:none;padding-left:30px;\"><a href =\"add_list.php?listid=".$listID."\">Add to my lists</a> &nbsp &nbsp";
            }
            if (!$canBeAdded)
              print "<a href=\"learnwords.php?listid=".$listID."\">learn list</a>  &nbsp &nbsp";
            if (isset($_SESSION['userid']))
              print "<a href=\"my_lists.php\">my vocab</a> &nbsp &nbsp";
          }
          print_list_links($listID, $canBeAdded);
          
        ?>
        
        
        <a href="browse_lists.php">browse lists</a>
        <br>
        <br>
        
        <!-- words -->
          <div class="listTable">
          <table>
            <tr class="browseTableHeading" align="center">
               <th>Word</th> <th>Meaning</th> <th style="border-right:none;">Context</th>
            </tr>
           
            <?php
              $query="SELECT * FROM terms WHERE list='$listID' ORDER BY ID";
              $result = run_mysql_query($query);
              $num=mysql_numrows($result);
           
              $i=0;
              while ($i < $num) 
              {
                $word=mysql_result($result,$i,"word");
                $meaning=mysql_result($result,$i,"meaning");
                $context=mysql_result($result,$i,"context");
                print "<tr><td>$word</td>". 
                      "<td>$meaning</td> <td  style=\"border-right:none;\">$context</td></tr>";
                $i++;
              }
              mysql_close();  
            ?>
          </table>
          </div> <!-- end listTable div -->
           
          <br><br>
          <?php
            print_list_links($listID, $canBeAdded);
          ?>
          <a href="browse_lists.php">browse lists</a>
          <br>
          <br>
          <br>
      </div>	<!-- end of main col -->

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
