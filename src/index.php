<?php
  //Buffer larger content areas like the main page content
  ob_start();
  session_start();
?>
                  
              <!-- echo $pagemaincontent; -->
       <div id="rightsidecol" >
       <div id="loginBoxes">
          <div id="loginVertBar">
          </div>
       
          <!-- Login Box -->
       <div id="loginBox">
       <form method="POST" action="login.php">
    	    <table align="center">
    	    <tr><td colspan="2" align="center"><b>Sign-in</b></td>
          </tr>
          <tr>
            <td> Username: </td><td><input type="text" name="username" size="16" /></td>
          </tr>
          <tr>
            
            <td>Password: </td><td><input type="password" name="password" size="16" /></td>
          </tr>
          <tr>
            <td></td>
            <td align="right">  
            <input type="submit" value="Sign-in" />
            </td>
          </tr>
          </table> 
        </form>
    	 </div><!-- account box -->
    	 <br>
       <br>
       <div id="createAccountBox">
       <form method="POST" action="createaccount.php">  
    	  <table align="center">
    	    <tr><td colspan="2" align="center"><b>Create an Account</b></td>
          </tr>
          <tr>
            <td> Enter a username: </td><td><input type="text" name="username" size="16" autocomplete="off"/></td>
          </tr>
          <tr>
            <td>Choose a password: </td><td><input type="password" name="password" size="16" autocomplete="off"/></td>
          </tr>
          <tr>
            <td>Retype password: </td><td><input type="password" name="repassword" size="16" autocomplete="off"/></td>
          </tr>
          <tr>
            <td></td>
            <td align="right">  
            <input type="submit" value="Create Account" style="width:120px;"/>
            </td>
          </tr>
          <tr>
          <td colspan="2" style="font-size:smaller;" align="center">
          (no email address necessary)
          </td>
          </tr>
          </table>
          </form> 
          
    	 </div> <!-- account box -->
    	 </div> <!-- login boxes -->
          
      </div> <!-- right sidecol -->
        
      <div id = "maincol_left">
      <div class="mainHeading">
	       Learn and Remember Vocabulary
      </div>
      <div id="features">
      <table>
        <tr>
          <td valign="top">
            <div id="repetition-img"> </div>
          </td>
          <td>
          <b>Spaced Repetition</b><br>
          Retests you on words at the right intervals to move them from short term memory 
              into long term memory.
              <br><br>
          
          </td>
        </tr>
        <tr>
          <td valign="top">
            <div id="lists-img"> </div>
          </td>
          <td>
          <b>Create and Share</b><br>
            Learn from existing word lists, or easily create and share your own 
            <br><br>
          
          </td>
        </tr>
        <tr>
          <td valign="top">
            <div id="piggy-img"> </div>
          </td>
          <td>
          <b>Completely Free</b><br>
            No account fees. No spam. Sign-up in seconds. 
            <br><br>
          </td>
        </tr>
      </table>
      </div><!-- end features -->
      </div><!-- end main column side -->
       <div id="otherContent">
      <div id="learnNow">
        <div class="subHeading">
          Learn Now
        </div>
          <table>
          <tr>
            <td width="110px"> <a href="learnwords.php?listid=2">SAT Hit-list</a> </td>
            <td> Most common SAT words </td>
          </tr>
          <tr>
            <td> <a href="learnwords.php?listid=3">French Food</a></td>
            <td> French food vocabulary <br> </td>
          </tr>
          <tr>
            <td> <a href="learnwords.php?listid=1">World Capitals</a></td>
            <td> 100 Capital cities<br> </td>
          </tr>
          <tr>
            <td> <a href="learnwords.php?listid=23">Spanish Numbers</a></td>
            <td> Basic Spanish numbers<br> </td>
          </tr>
        </table>
      </div> <!-- end learn now -->
      
      <div id="findLists">
        <div class="subHeading">
          Find Lists
        </div>
        <a href="browse_lists.php">browse all lists</a>  
      </div>
      <div class="clear">
     </div>
    </div> <!-- other content -->   

<?php
  //Assign all Page Specific variables
  $pagemaincontent = ob_get_contents();
  ob_end_clean();
  
  $navActive[0]=1;
  $displayLoginLink=0;
  //Apply the template
  include("master.php");
?>