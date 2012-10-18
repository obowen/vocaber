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
    	   Users
       </div>
       
       <br>
       
       <div class="listTable">
         <table cellspacing="0">
         <tr align="center" class="browseTableHeading">
            
            <th>Num</th><th>Username</th> <th>Words</th>  <th>list</th>
                  
         </tr>
       
       
    <?php
      
      // get all lists
      $query = "SELECT * FROM users";
      $reslist = mysql_query($query) or die(mysql_error());
      $numUsers =mysql_numrows($reslist);
      $totalWords=0;    
      $i = 0;
      
      while ($i < $numUsers) 
      {
        $name = mysql_result($reslist, $i, "username");
        $userID = mysql_result($reslist, $i, "id");
        $currPos = mysql_result($reslist, $i, "currpos");
        
        $words = 0;
        $query = "SELECT * FROM positions WHERE userid='$userID'";
        $result = run_mysql_query($query); 
        $numlists = mysql_numrows($result);
        $j=0;
        while ($j < $numlists) 
        {
          $words = $words + mysql_result($result,$j,"position");
          $j++;
        }
        $query = "SELECT * FROM positions WHERE ID='$currPos'";
        $result = run_mysql_query($query);
        $curList="";
        if  (mysql_numrows($result)!=0)
        {
          $curListID = mysql_result($result, 0, "list");
          $query = "SELECT * FROM lists WHERE ID='$curListID'";
          $result = run_mysql_query($query);
          if  (mysql_numrows($result)!=0)
          {
            $curList = mysql_result($result, 0, "name");
          }
        } 
        print "<tr><td>$i</td><td>$name</td><td>$words</td><td>$curList</td>";
        $totalWords += $words;
        
        
        
        $i++;
      }
      print "</table>";
      print "<br>";
      print "total words: $totalWords";
      
      mysql_close();
    ?>	
    	
       
      </div><!-- end list table div -->
  </div> <!-- end of main col --> 

<?php
  //Assign all Page Specific variables
  $pagemaincontent = ob_get_contents();
  ob_end_clean();
  
  $navActive[2] =1;
    
  //Apply the template
  include("master.php");
?>