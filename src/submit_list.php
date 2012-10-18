<?php
  //Buffer larger content areas like the main page content
  ob_start();

  include 'config.php';

  session_start(); // start up the PHP session

  // make sure there is an actual user
  if (!isset($_SESSION['userid']))
  {
    die("You must be logged in to edit lists"); 
  }
?>

        
            <div id = "maincol_only">
<?php   
                // get user id
                $userID=$_SESSION['userid'];
                
                // get parameters
                $data     = $_POST['data'];
                $new      = $_POST['isnew'];
                $shared   = (isset($_POST['shared']) && $_POST['shared']=='yes');
                $fixable  = (isset($_POST['fixable']) && $_POST['fixable']=='yes');
                $editable = (isset($_POST['editable']) && $_POST['editable']=='yes');
                
                // connect to DB
                mysql_connect($hostname,$sqlUser,$sqlPass);
                @mysql_select_db($database) or die( "Unable to select database");
                
                if ($new=="yes")
                {
                  // TODO allow these to be changed from edit page, at which point these should be moved above
                  $listname = $_POST['listname'];
                  $tags     = $_POST['listTags'];
                  $desc     = $_POST['listdesc'];
                
                  if ($listname=="")
                    die("No list name provided");
                
                  // create a new entry in the lists table
                  
                  $query = "INSERT INTO lists VALUES ('', '$listname', '$desc', '$userID', '$shared', '$fixable', '$editable')";
                  if (!mysql_query($query))
                  {
                      $err=mysql_error(); print $err; 
                      exit(); 
                  }
                  // get the ID of that position
                  $listID = mysql_insert_id();
                  
                  // add to the users lists
                  $query = "INSERT INTO positions VALUES ('','$userID', '$listID', '0')";
                  mysql_query($query) or die(mysql_error());
                  
                  // Populate TAGS table, preg_split explodes with regular expression
                  // matches commas and spaces
                  $tag_list = preg_split("/[\s,]+/", $tags);
                  $i = 0;
                  while ($i != sizeof($tag_list))
                  {
                    $query = "INSERT INTO tags VALUES ('', '$listID', '$tag_list[$i]')";
                    run_mysql_query($query);
                    $i++;
                  }
                     
                } 
                else
                { 
                  // list isn't new, but may need to update its information
                  $listID = $_POST['listid'];
                  // TODO: allow user to change list description and tags
                  
                  $query = "UPDATE lists SET shared='$shared', fixable='$fixable', editable='$editable' WHERE ID='$listID'";
                  run_mysql_query($query);
                }
                // remove current words in this list
                $query = "DELETE FROM terms WHERE list='$listID'";
                run_mysql_query($query);
                
                // add data to the terms table
                $dataLines = explode("\n",$data); // XXX what abouut dos unix issues
                $i = 0;
                $numLines= sizeof($dataLines);
                
                while ($i != $numLines) 
                {
                  
                  // only store non empty lines
                  if (strlen(chop($dataLines[$i]))> 0)
                  {
                    //print $dataLines[$i];
                    $word="";
                    $context="";
                    $meaning="";
                    
                    $parsed = explode(";", $dataLines[$i]);
                    // remove spaces from left and right sides
                    if (isset($parsed[0])) 
                      $word=mysql_real_escape_string(ltrim(rtrim($parsed[0])));
                    if (isset($parsed[1])) 
                      $meaning=mysql_real_escape_string(ltrim(rtrim($parsed[1])));
                    if (isset($parsed[2])) 
                      $context=mysql_real_escape_string(ltrim(rtrim($parsed[2])));
                  
                    $query = "INSERT INTO terms VALUES ('','$word','$meaning','$context','$listID')";
                    if (!mysql_query($query))
                    {
                       $err=mysql_error(); print $err; 
                       exit();  
                    }
                  }
                  //print $word." ".$meaning." ".$context."<br>";
                  $i++;	
                }
                

                
                mysql_close();
                if($new=="yes")
                  print "<span class=\"status_msg\">Word list created successfully!</span><br>";
                else
                  print "<span class=\"status_msg\">Word list changes submitted.</span><br>";
                print "<br><a href=\"learnwords.php?listid=".$listID."\">learn list</a><br>";
                ?>
                  <a href="my_lists.php">back to my vocab</a>  
           </div> <!-- main col -->
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