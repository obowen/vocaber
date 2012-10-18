<html>
<head>
  <title><?php echo $pagetitle; ?></title>
  <meta http-equiv="content-type" content="text/html; charset=windows-1250">
  <link href="styles/global.css" rel="stylesheet" type="text/css" />
</head>


<body>
   <div id="head-wrap">
    <div id="topCornerLinks">
      <a href="#">login</a>&nbsp|&nbsp<a href="#">create account</a>
    </div>
	
     <div id="header">
	    <div id="logo">
		    <a href="index.html"><img src="images/logo.gif" alt="Vocaber"/></a>
	    </div> <!-- end #logo -->
	    <div id="menu">
        <ul>
	       <li class="active"><a href="index.html">Home</a></li>
      	 <li><a href="lists.php">Lists</a></li>
     		 <li><a href="learn.php">Learn</a></li>
    		 <li><a href="help.html">Help</a></li>
	      </ul>
    	</div>
	  </div>
    </div> <!-- end head wrap -->
	<div id="header-bar">
	</div>
	    
	<div id="wrap">
        <div id="body-wrap">
        
            <div id="mainContent">
            
            
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
            <td>  
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
            <td> Enter a username: </td><td><input type="text" name="username" size="16" /></td>
          </tr>
          <tr>
            <td>Choose a password: </td><td><input type="password" name="password" size="16" /></td>
          </tr>
          <tr>
            <td>Retype password: </td><td><input type="password" name="repassword" size="16" /></td>
          </tr>
          <tr>
            <td></td>
            <td>  
            <input type="submit" value="Create Account" />
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
          <b>Spaced Repition</b></br>
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
          <b>Create and Share</b></br>
            Learn from existing word lists, or easily create and share your own 
            <br><br>
          
          </td>
        </tr>
        <tr>
          <td valign="top">
            <div id="piggy-img"> </div>
          </td>
          <td>
          <b>Completely Free</b></br>
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
            <td width="100px"> <a href="#">SAT Hit-list</a> </td>
            <td> 100 most common SAT words </td>
          </tr>
          <tr>
            <td> <a href="#">French Food</a></td>
            <td> French food vocabulary <br> </td>
          </tr>
          <tr>
            <td> <a href="#">World Capitals</a></td>
            <td> Capital cities of all countries<br> </td>
          </tr>
        </table>
      </div> <!-- end learn now -->
      
      <div id="findLists">
        <div class="subHeading">
          Find Lists
        </div>
        <a href="#">browse all lists</a>  
      </div>
      <div class="clear">
     </div>
    </div> <!-- other content -->       
              
            </div>
            <div id="footerNav">
	            <a href="Default.aspx">Home</a>&nbsp;&nbsp;&nbsp;&nbsp; |&nbsp;&nbsp;&nbsp; 
              <a href="schedule.aspx">Schedule</a>&nbsp;&nbsp;&nbsp; |&nbsp;&nbsp;&nbsp;&nbsp; 
              <a href="about.aspx">About</a>&nbsp;&nbsp;&nbsp; |&nbsp;&nbsp;&nbsp;&nbsp; 
              <a href="contact.aspx">Contact</a>
            </div>
            
		    </div> <!-- end body wrap -->
    </div> <!-- end wrap -->
            
    <div id="footerText">
        vocaber.com 2008
    </div>
  </body>
</html>
