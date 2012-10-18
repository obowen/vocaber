<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
  <title>Vocaber | Learn and Remember Lots of Vocabulary</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <link href="styles/global.css" rel="stylesheet" type="text/css" />
  <?php if (isset($headercontent)) echo $headercontent; ?>
</head>

<body <?php if (isset($bodyloadcontent)) echo $bodyloadcontent; ?> >
   <div id="head-wrap">
    <div id="topCornerLinks">
      
      <?php
        if (!isset($displayLoginLink) || $displayLoginLink)
        {
          // check if logged in
          if (isset($_SESSION['userid']))
          {
            print "<a href=\"logout.php\">logout</a>";
            if(isset($_SESSION['username']))
            {
              print" <span id=\"topCornerName\">".$_SESSION['username']."</span>";
            }
          }
          else
          {
            print "<a href=\"index.php\">login</a>&nbsp|&nbsp<a href=\"index.php\">create account</a>";
          }
        }
      ?>
      
      
    </div>
	
     <div id="header">
	    <div id="logo">
		    <a href="index.php"><img src="images/logo.gif" alt="Vocaber"/></a>
	    </div> <!-- end #logo -->
	    <div id="menu">
	    <?php 
        
        // assume no active links if this isn't set 
        if (!isset($navActive))
          $navActive=array(0,0,0,0,0);
          
        // used to set active links
        $active=" class=\"active\"";
          
        $navBar = "<ul><li".(isset($navActive[0])&&$navActive[0] ? $active: "")."><a href=\"index.php\">Home</a></li>";

        if (isset($_SESSION['userid']))
        {
          // only display my vocab link if logged in
          $navBar .="<li".(isset($navActive[1])&&$navActive[1] ? $active: "")."><a href=\"my_lists.php\">My Vocab</a></li>";
        }
        
        $navBar .="<li".(isset($navActive[2])&&$navActive[2] ? $active: "")."><a href=\"browse_lists.php\">Lists</a></li>".
      	          "<li".(isset($navActive[3])&&$navActive[3] ? $active: "")."><a href=\"learnwords.php\">Learn</a></li>".
    		          "<li".(isset($navActive[4])&&$navActive[4] ? $active: "")."><a href=\"tour.php\">Tour</a></li></ul>";
    		echo $navBar;
      ?>
        
    	</div>
	  </div>
    </div> <!-- end head wrap -->
	<div id="header-bar">
	</div>
	    
	<div id="wrap">
        <?php
          if (isset($bodyWrapFixed))
            print "<div id=\"body-wrap-fixed\">";
          else if(isset($bodyWrapBig))
            print "<div id=\"body-wrap-big\">";
          else
            print "<div id=\"body-wrap\">";
         
        ?>
        
    
            <div id="mainContent">
              <?php  if (isset($pagemaincontent)) echo $pagemaincontent; ?>
            </div>
            
            <div id="footerNav">
	            <a href="index.php">home</a>&nbsp;&nbsp;&nbsp;&nbsp; |&nbsp;&nbsp;&nbsp; 
              <a href="browse_lists.php">lists</a>&nbsp;&nbsp;&nbsp; |&nbsp;&nbsp;&nbsp;&nbsp;
              <a href="tour.php">tour</a>&nbsp;&nbsp;&nbsp; |&nbsp;&nbsp;&nbsp;&nbsp;
              <a href="about.php">about</a>&nbsp;&nbsp;&nbsp; |&nbsp;&nbsp;&nbsp;&nbsp; 
              <a href="contact.php">contact</a>
            </div>
            
		    </div> <!-- end body wrap -->
    </div> <!-- end wrap -->
            
    <div id="footerText">
        vocaber.com 2008
    </div>
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-8128831-1");
pageTracker._trackPageview();
} catch(err) {}</script>
  </body>
</html>


