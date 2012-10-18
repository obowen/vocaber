<?php
  $headercontent="<SCRIPT type=\"text/javascript\" src=\"scripts/progressbar.js\" charset=\"utf-8\"></SCRIPT>"; 
  $headercontent.="<SCRIPT type=\"text/javascript\" src=\"scripts/listmanager.js\" charset=\"utf-8\"></SCRIPT>";
  $headercontent.="<link rel=\"stylesheet\" href=\"styles/learn.css\" type=\"text/css\" />";
  $bodyloadcontent="onload=\"initLearn();\"";
  //Buffer larger content areas like the main page content
  ob_start();
  
  include 'config.php';
  
  session_start(); // start up the PHP session

  mysql_connect($hostname,$sqlUser,$sqlPass) or die( "Unable to select database");
  @mysql_select_db($database) or die( "Unable to select database");
    
  // get name of the set the user wants to learn
  if (isset($_GET['listid']))
    $learnlistID = $_GET['listid']; 

// check if the user has logged in
if (!isset($_SESSION['userid']))
{
  // get default list if nothing specified
  if (!isset($learnlistID))
    $learnlistID=$DEFAULT_LIST_ID;
    
  // demo mode
  $_SESSION['listid']=$learnlistID;
    
  // no database interaction here, just set the position if its not already set 
  if (!isset($_SESSION['position']))
    $_SESSION['position']=0;
    
  // non logged in users cannot edit lists 
  $fixable=0;
  $position=0;
}
else
{
  // session exists, get user id
  $userID = $_SESSION['userid'];
  
  if (!isset($learnlistID))
  {
    // get current list from the data base
    $query = "SELECT * FROM users where ID='$userID'";
    $result = run_mysql_query($query);
    $curPos = mysql_result($result,0,"currpos");
    $query = "SELECT * FROM positions where ID='$curPos'";
    $result = run_mysql_query($query);
    if(mysql_affected_rows()==0)
    {
      // in case this position doesn't exist
      $learnlistID=$DEFAULT_LIST_ID;
      $_SESSION['listid']=$learnlistID;
    }
    else
    {
      $learnlistID = mysql_result($result,0,"list");
      $position  = mysql_result($result,0,"position");
    }
  }
  else
  {
    // Update DB so this is the current list
    $query = "SELECT * FROM positions where list='$learnlistID' AND userid ='$userID'";
    $result = run_mysql_query($query);
    if (mysql_numrows($result)==0)
    {
      die_with_master ("<div>You haven't added list <b>".$learnlistID."</b> yet.");
    }
    $newPosID = mysql_result($result,0,"ID");
    $position = mysql_result($result,0,"position");
    
    $query = "UPDATE users SET currpos='$newPosID' WHERE ID='$userID'";
    run_mysql_query($query);
  }
}  
// check editable permission and get creator
$query    = "SELECT * FROM lists WHERE ID='$learnlistID'";
$result   = mysql_query($query) or die(my_sql_error());
if (mysql_numrows($result)==0)
{
  die_with_master("<div>Unable to retrieve list.");
}
$listName = mysql_result($result,0,"name");
$creator  = mysql_result($result,0,"creator");
$fixable  = mysql_result($result,0,"fixable");
  
// set session variables for this list and position
$_SESSION['listid']=$learnlistID;
$_SESSION['position']=$position;

mysql_close();
?>

<script>
<?php
  include "getwords.php";
?>
</script>
      
         <!-- Right side column -->
         <div id="rightsidecol_small" >
           <!-- Word rate stats -->
            <div id="wordStats">
              <span class="boxHeading">Learning Stats</span>
              <table width="220" cellpadding="2px">
              <tr>
                <td >Checkpoint word rate:</td>
                <td align="right"><span id="checkRate">0 wpm</span></td>
              </tr>
                <td>Best word rate:</td>
                <td align="right"><span id="bestRate">0 wpm</span></td>
              <tr>
                <td>New words learned:</td>
                <td align="right"><span id="newWordsLearnt">0</span></td>
              </tr>
              <tr>
              </tr>
              </table>
            </div> <!-- end word stats -->           
             
            <!-- worst words --> 
    <!--
            <div id="worstWords">
              <span class="boxHeading">Worst Words</span>
              <table width="270" cellpadding="2px">
              <tr>
                <td>1. aller</td>
                <td align="right"><a href="#">view</a></td>
              <tr>
                <td>2. demangement</td>
                <td align="right" ><a href="#">view</a></td>
              </tr>
              <tr>
              </tr>
              </table>
            </div>
    -->        
          
         </div> 
        
         
         <!-- Main column -->
         <div id="maincol_left_big">
            <br>
            <div id="learnBox">
              <table style="font-size:13px;" align="center" width="100%">
              
                <tr>
                <td align="left" width="30%"> 
                  <table cellpadding="1">
                    <tr><td id="listNameCell"> <?php print "<b>".$listName."</b>"; ?></td></tr>
                    <tr><td>Progress: <span id="progress" style="font-weight:bold;"></span></td></tr>
                  </table>
                <td align="center"  width="400px">
                  <table  align="center" width="340px">
                    <tr>
                    <td width="30px">&nbsp</td>
                    <td width="280px" align="center">
                    <!-- checkpoint progress bar -->
                      <div id="progressBar">
                          <div class="progressBarSegLeft"><div class="progressBarSeg2" id = "progressSeg0"></div></div>
                          <div class="progressBarSeg"><div class="progressBarSeg2" id = "progressSeg1"></div></div>
                          <div class="progressBarSeg"><div class="progressBarSeg2" id = "progressSeg2"></div></div>
                          <div class="progressBarSeg"><div class="progressBarSeg2" id = "progressSeg3"></div></div>
                          <div class="progressBarSeg"><div class="progressBarSeg2" id = "progressSeg4"></div></div>
                          <div class="progressBarSeg"><div class="progressBarSeg2" id = "progressSeg5"></div></div>
                          <div class="progressBarSeg"><div class="progressBarSeg2" id = "progressSeg6"></div></div>
                          <div class="progressBarSeg"><div class="progressBarSeg2" id = "progressSeg7"></div></div>
                          <div class="progressBarSeg"><div class="progressBarSeg2" id = "progressSeg8"></div></div>
                          <div class="progressBarSeg"><div class="progressBarSeg2" id = "progressSeg9"></div></div>
                          <div class="progressBarSeg"><div class="progressBarSeg2" id = "progressSeg10"></div></div>
                          <div class="progressBarSeg"><div class="progressBarSeg2" id = "progressSeg11"></div></div>
                          <div class="progressBarSeg"><div class="progressBarSeg2" id = "progressSeg12"></div></div>
                          <div class="progressBarSeg"><div class="progressBarSeg2" id = "progressSeg13"></div></div>
                          <div class="progressBarSeg"><div class="progressBarSeg2" id = "progressSeg14"></div></div>
                          <div class="progressBarSeg"><div class="progressBarSeg2" id = "progressSeg15"></div></div>
                          <div class="progressBarSeg"><div class="progressBarSeg2" id = "progressSeg16"></div></div>
                          <div class="progressBarSeg"><div class="progressBarSeg2" id = "progressSeg17"></div></div>
                          <div class="progressBarSeg"><div class="progressBarSeg2" id = "progressSeg18"></div></div>
                          <div class="progressBarSegRight"><div class="progressBarSeg2" id = "progressSeg19"></div></div>
                          
                        <!-- tooltip-->
                        <div id="tooltipCheckpoint">
                          Vocaber chooses a sub-set of words from various points in your list.  Once all the words in this subset are
                          answered correctly, a "checkpoint" is reached, and your position in the list is advanced.  
                        </div>
                       
                      </div> 
                      <div id="checkProgressBarText" onMouseOver="document.getElementById('tooltipCheckpoint').style.visibility='visible';" onMouseOut="document.getElementById('tooltipCheckpoint').style.visibility='hidden'">
                        Checkpoint progress
                      </div>
                    </td>
                    <td width="30px" align="left">
                        <div id="checkProgress">0%</div>
                    </td>
                    </tr>
                  </table>
                </td>
                <td align="right" width="30%">
                  <table cellpadding="0" "style="font-size:12px;">
                    <form name="optionsForm">
                      <tr onMouseOver="document.getElementById('tooltipRepeat').style.visibility='visible';" onMouseOut="document.getElementById('tooltipRepeat').style.visibility='hidden'">
                      <td align="right">
                        More repetition</td> <td><input type="checkbox" name = "moreRepeat" value="yes" onclick="toggleRepetition();">
                      </td></tr>
                      <tr onMouseOver="document.getElementById('tooltipInverse').style.visibility='visible';" onMouseOut="document.getElementById('tooltipInverse').style.visibility='hidden'">
                      <td align="right">
                        Inverse testing</td> <td><input type="checkbox" name = "inverseTest" value="yes" onclick="toggleInverseTest();"onMouseOver="document.getElementById('tooltipInverse').style.visibility='visible';" onMouseOut="document.getElementById('tooltipInverse').style.visibility='hidden'">
                      </td></tr>
                      <tr onMouseOver="document.getElementById('tooltipIgnore').style.visibility='visible';" onMouseOut="document.getElementById('tooltipIgnore').style.visibility='hidden'">
                      <td align="right">
                        Ignore parenthesis</td> <td><input type="checkbox" name = "ignoreParenth" value="yes" onclick="toggleIgnoreParenth();"onMouseOver="document.getElementById('tooltipIgnore').style.visibility='visible';" onMouseOut="document.getElementById('tooltipIgnore').style.visibility='hidden'">
                      </td></tr>
                    </form>
                  </table>
                </td>
                 </tr>
                 
              </table>

                
                <br>
              <div id="wordsDiv">
                <!-- Word Box -->
                <table padding="5" style="font-size:14px" align="center">
                <tr>
                  <td colspan="2" align="center">
                    <div id="meaningDiv">
                      <span id="meaning">Ready?</span>
                      
                    </div>
                  </td>
                  
                </tr>
                <tr>
                  <td width="40px">
                  </td>
                  <td align="center">
                    <input type="text" size="50" id="word" value="" onkeypress="handleKeyPress(event);">
                    <button id="nextWordButton" onclick="nextWord();">next word</button> 
                  </td>
                  <!--<td width="150" align ="left"></td>-->
                </tr>
                </table>
                <br>
                <!-- Feedback -->
                <table align="center">
                  <tr>
                    <td align="center" colspan="3">
                      <div id ="feedback">
                      &nbsp  
                      </div>
                    </td>
                  </tr>
                  <tr>
                  <td width="30px">
                  </td>
                  <td>
                    <br>
                    <table id="feedbackTable" cellspacing="0">
                      <tr>
                        <td class="feedTableHeadingTop" width="70px" id="feedbackRow1">Word</td>
                        <td class="feedTableTopCell" width="450px"><span id="disp_word">&nbsp</span></td>
                      </tr>
                      <tr>
                        <td class="feedTableHeading" id="feedbackRow2">Meaning</td>
                        <td class="feedTableCell"><span id="disp_meaning">&nbsp</span></td>
                      </tr>
                      <tr>
                        <td class="feedTableHeadingBottom" id="feedbackRow3">Context</td>
                        <td class="feedTableBottomCell"><span id="disp_context">&nbsp</span></td>
                      </tr>
                    </table>
                  
                  <!--
                    <table cellpadding="5" style="font-size:13px" cellspacing="1">
                    
                    <tr id="feedbackRow" bgcolor="#CC9999" >
                      
                      <td width="250" align="center"><span id="disp_meaning">&nbsp</span></td> 
                      <td width="250" align="center"><span id="disp_word">&nbsp</span></td>
                    </tr>
                    <tr id="feedbackRow2" bgcolor="#CC9999" >
                      
                      <td width="500" align="center" colspan="2"><span id="disp_context">&nbsp</span></td>
                    </tr>
                    </table>
                    -->
                  </td>
                  <td>
                    <table>
                      <?
                         // check if this list is editable
                         if ($fixable==1 or isset($userID) and $creator==$userID)
                         {
                            print "<tr><td><span id=\"editLink\"><a href=\"#\" onClick=\"editWord();return false;\">edit</a></span></td></tr>\n";
                         }
                      ?>
                    </table>
                  </td>
                  </tr>
                 
                </table>
              </div> <!-- end wordsDiv -->
                <br>
                
                <div id="statusbar">&nbsp 
                </div>
                <?   
                  // display demo message if not logged in    
                  if (!isset($_SESSION['userid']))
                  {
                    print"<br>";
                    print"<div id=\"demoMessage\">";
                    print "You are not logged in.<br>".
                          "<a href=\"index.php\">Login</a> or <a href=\"index.php\">create an account</a> and vocaber will remember your progress.";
                    print"</div>";
                    print"<br>";
                  }
                ?> 
                
                <!-- tool tips -->
                
                <div id="tooltipRepeat">
                  Check this box if you list contains words
                  that are especially difficult to remember.  It will result in more
                  repetition for the words you get wrong.
                </div>
                <div id="tooltipInverse">
                  Check this box if you would like to guess the word definition instead of the word itself.
                </div>
                <div id="tooltipIgnore">
                  Check this box if you would like vocaber to ignore anything in parenthesis (..) when checking answers.
                </div>
                
              </div> <!-- learn box -->
                
                <!-- edit box -->
                <div id="editBox">
                <br>
                <table align="center" >
                <tr>
                  <td align="center">Word</td>
                  <td align="center">Meaning</td>
                  <td align="center">Context</td>
                </tr>
                <tr>
                  <td><input type="text" size="22" id="editbox_word"></td>
                  <td><input type="text" size="22" id="editbox_meaning"></td>
                  <td><input type="text" size="44" id="editbox_context"></td>
                </tr>
                <tr>
                <td colspan="3">
                  <table align="right">
                  <tr>
                  <td><input type="button" value="OK"     onClick="saveWord(true);"></td>
                  <td><input type="button" value="Cancel" onClick="saveWord(false);"></td>
                  </tr>
                  </table>
                </td>
                </tr>
    
                </table>
                </div> <!-- edit box -->
               <br>
               <br>
              <div id = "showlist"></div>
          </div> <!-- end of main column -->

<?php
  //Assign all Page Specific variables
  $pagemaincontent = ob_get_contents();
  
  ob_end_clean();
  $bodyWrapBig=1;
  $navActive[3]=1;  		    
  //Apply the template
  include("master.php");
?>






