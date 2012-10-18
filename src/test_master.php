<?php
  //Buffer larger content areas like the main page content
  ob_start();
?>
<br>
<h1> Page Content </h1>
<p>
This is the page content, it is very nice.
</p>
<?php
  //Assign all Page Specific variables
  $pagemaincontent = ob_get_contents();
  ob_end_clean();
  $pagetitle = "Vocaber";
  //Apply the template
  include("master.php");
?>



