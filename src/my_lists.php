<?php
  $headercontent="<script type=\"text/javascript\" src=\"scripts/mylists.js\" charset=\"utf-8\"></script>";
  //Buffer larger content areas like the main page content
  ob_start();
  
  session_start(); // start up the PHP session
  
  include 'config.php'; // include DB strings + functions

  if (!isset($_SESSION['userid']))
  {
    die_with_master("<div>You must be logged in to view this page. &nbsp <a href=\"index.php\">login</a>");
  }
  $userID=$_SESSION['userid'];
  
  // connect to DB
  mysql_connect($hostname,$sqlUser,$sqlPass);
  @mysql_select_db($database) or die( "Unable to select database");
  
  
      ?>	
          
          <!-- Progress (right side) -->
          <div id="rightsidecol_small" >
            <div id="listStats">
              <span class="boxHeading">Learning History</span>
              <table width="220" cellpadding="4px">
              <tr>
                <td >Total words learned:</td>
                <td align="right">
                <?php
                  $totalWords = 0;
                  $query = "SELECT * FROM positions WHERE userid='$userID' ORDER BY ID";
                  $result = run_mysql_query($query); 
                  $numlists = mysql_numrows($result);
                  $i=0;
                  while ($i < $numlists) 
                  {
                    $totalWords = $totalWords + mysql_result($result,$i,"position");
                    $i++;
                  }
                  print "$totalWords";
                ?>
                </td>
              </tr>
              <tr>
                
                <?php
                  $query = "SELECT * FROM users WHERE ID='$userID'";
                  $result = run_mysql_query($query);
                  $dateString = mysql_result($result,0,"sincedate");
                  $sinceWords = mysql_result($result,0,"sincewords");
                  
                  $createdDate = date("Y-m-d",strtotime($dateString));
                  $curDate    = date("Y-m-d", time());
                  // split into parts
                  $createdDateParts = explode("-", $createdDate);
                  $curDateParts     = explode("-", $curDate);
                  // convert from M-D-Y to number of days since some constant year
                  $created_day = gregoriantojd($createdDateParts[1], $createdDateParts[2], $createdDateParts[0]);
                  $cur_day     = gregoriantojd($curDateParts[1],     $curDateParts[2],     $curDateParts[0]);
                  
                  $numDays = $cur_day - $created_day + 1;
                  print "<td>Words learned per day:<br><div class=\"lightText\">(since <span id=\"since_date\">";
                  print $dateString."</span> <a href=\"#\" onClick=\"resetSinceDate();return false;\">reset</a>)</div></td>";  
                  print "<td align=\"right\"><span id=\"since_words\">";
                  if ($totalWords - $sinceWords < 0)
                    print "n/a";
                  else
                    print(round(($totalWords - $sinceWords)/$numDays));
                ?>
                </span></td>
              </tr>
              <tr>
                <td>Words learned per day:<br><span class="lightText">(since account created)</span></td>
                <td align="right">
                  <?php
                    $query = "SELECT * FROM users WHERE ID='$userID'";
                    $result = run_mysql_query($query);
                    //$curDate    = date_create("now");
                    $dateString = mysql_result($result,0,"logged");
                    $curPos     = mysql_result($result,0,"currpos");
                    $createdDate = date("Y-m-d",strtotime($dateString));
                    $curDate    = date("Y-m-d", time());
                    // split into parts
                    $createdDateParts = explode("-", $createdDate);
                    $curDateParts     = explode("-", $curDate);
                    // convert from M-D-Y to number of days since some constant year
                    $created_day = gregoriantojd($createdDateParts[1], $createdDateParts[2], $createdDateParts[0]);
                    $cur_day     = gregoriantojd($curDateParts[1],     $curDateParts[2],     $curDateParts[0]);
                    
                    $numDays = $cur_day - $created_day + 1;
                    print(round($totalWords/$numDays));
           
                   // print($createDate);
                  ?>
                </td>
              </tr>
              <tr>
              </tr>
              </table>
            </div> <!-- end word stats -->
            
          </div> <!-- right side col -->
  
          <div id="maincol_left_big">
  
   <div class="lineHeading">
	   My Vocab
   </div>
   
   <div id="currListDiv">
    <div style="font-size:16px;font-weight:bold;">Current List: <i>
      <?php
         $query = "SELECT * FROM positions where ID='$curPos'";
         $result = run_mysql_query($query);
         if (mysql_numrows($result)!=0)
         {
            $position = mysql_result($result,0,"position");
            
            $curListID = mysql_result($result,0,"list");
            
            $query = "SELECT * FROM lists WHERE ID='$curListID'";
            $result = run_mysql_query($query);
            $curListName = mysql_result($result,0,"name");
            $curListDescription = mysql_result($result,0,"description");
            $curListEditable = mysql_result($result,0,"editable");
            $curListCreator  = mysql_result($result,0,"creator");
            // get total list size
            $query = "SELECT * from terms WHERE list='$curListID'"; 
            $reslist = run_mysql_query($query);
            $curListSize = mysql_numrows($reslist);
         }
         if (isset($curListName))
           print $curListName;
      ?>
      </i></div>
    
    <div id="addWordsCorner">
      <?php
        if (isset($curListID) && ($curListEditable || $userID==$curListCreator))
          print "<a href=\"edit_list.php?listid=".$curListID."\">add words</a>";
      ?>
      
    </div>
      <div id="currListText">
      <table>
      <tr>
        <td colspan="2">
          <?php
            if (isset($curListDescription))
              print $curListDescription;
            else
              print "No list currently selected";
          ?>
        </td>
      </tr>
      <tr>
        <td width="100px">Position:</td>
        <td>
        <?php
         if (isset($position))
            print $position."/".$curListSize;
         else
            print "N/A"
        ?>
        </td>
      </tr>
      <!--  TODO (and probably want to remove description above)
      <tr>
        <td>Also reviewing:</td>
        <td>453 <span class="smallLightText">words from other lists</span></td>
      </tr>
      -->
      </table>
      </div>
      <div id="learnNowCorner"> 
        <div id="learnNowButton" class="coolButton">
        <?php
         if (isset($curListID))
            print "<a href=\"learnwords.php?listid=".$curListID."\">Learn Now</a>";
        ?>
        </div> 
      </div>
   </div>
   <br>
   Click on a list below to learn it.<br><br>
   
   <div style="font-weight:bold; font-size:16px">Word Lists</div>
      
      <table cellpadding="1" style="border-top:1px solid #CCCCCC;border-bottom:1px solid #CCCCCC;padding-top:5px; padding-bottom:5px;">
      <!--
      <tr><td colspan = "5" align="center" style="border-bottom:1px solid;border-color:#BBBBBB;"></td></tr>
    -->
<?php
  
  $query = "SELECT * FROM positions WHERE userid='$userID'";
  $respos = run_mysql_query($query);
  $numlists=mysql_numrows($respos);
  if ($numlists==0)
  {
    print "<tr><td><span class=\"lightText\"><i>no lists are currently added to your account.</i></span></td></tr>";
  }
  $i=0;
  while ($i < $numlists) 
  {
    $posID = mysql_result($respos,$i,"ID");
    $listID = mysql_result($respos,$i,"list");
    $pos = mysql_result($respos,$i,"position");
    
    // get total list size
    $query = "SELECT * from terms WHERE list='$listID'"; 
    $reslist = run_mysql_query($query);
    $listSize = mysql_numrows($reslist);
    
    $query="SELECT * FROM lists WHERE ID='$listID'";
    $resedit=run_mysql_query($query);
    if (mysql_numrows($resedit)==0)
    {
      print "<tr><td  style=\"padding-right:30px;\">Error retrieving list with ID=".$listID."</td></tr>";
    }
    else
    {
      $listName=mysql_result($resedit,0,"name");
      $editable=mysql_result($resedit,0,"editable");
     
     /* TODO
      if ($posID != $curPos)
        print"<tr><td><input type=\"checkbox\" onclick=\"clickReviewList($i);\"></td>";
      else 
        print"<tr><td><input type=\"checkbox\" disabled=\"true\"></td>";
      */ 
      print"<tr><td style=\"padding-right:30px;\"><a href =\" learnwords.php?listid=".$listID."\">".$listName." </a> </td>";
      print"<td>position: ".$pos."/".$listSize."</td>";
      
      if (mysql_result($resedit,0,"creator")==$userID || $editable)
      {
        print"<td style=\"padding-left:30px;font-size:12px\"><a href=\"edit_list.php?listid=".$listID."\">edit</a></td>";
      }
      else
      {
        print"<td></td>";
      }
      print"<td style=\"padding-left:8px;font-size:12px\"><a href=\"view_list.php?listid=".$listID."\">view</a></td>";
      print"<td style=\"padding-left:8px;font-size:12px\"><a href=\"remove_list.php?posID=".$posID."\">remove</a></td>";
      print"<tr>";
    }
    $i++;
  }
  
mysql_close();

?>
  </table>


	<br>
   <a href="browse_lists.php";>find existing lists</a> &nbsp &nbsp
   <a href="create_list.php";>create new List</a>
    

  
  </div> <!-- main col -->
  
<?php
  //Assign all Page Specific variables
  $pagemaincontent = ob_get_contents();
  
  ob_end_clean();
      	    
  $navActive[1]=1;
  //Apply the template
  include("master.php");
?>










