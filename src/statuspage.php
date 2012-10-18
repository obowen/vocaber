<?php
  //Buffer larger content areas like the main page content
  ob_start();
  session_start();
  
?>
                  
      <div id = "maincol_only">
        <?php
          // get status or error message
          if (isset($_GET['msg']))
          {
            $msg = $_GET['msg'];
            switch($msg)
            {
              case "PAGE_REACH": print "Page reached incorrectly.<br>";
                                 break;
              default: print("Status message not found<br>");
            }
          }
          else
            print "No page status to report<br>";
        ?>
        <br>
        <br>
        <br>
      </div>
<?php
  //Assign all Page Specific variables
  $pagemaincontent = ob_get_contents();
  ob_end_clean();
  
  //Apply the template
  include("master.php");
?>