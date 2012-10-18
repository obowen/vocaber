<?php
  //Buffer larger content areas like the main page content
  ob_start();
  session_start();
?>
                  
       
      <div id="maincol_fixed">
              <div class="mainHeading2">
                Learn and Remember LOTS of Vocabulary
              </div>
              <div class="subHeading">An Online Vocabulary Learning Tool</div>
              Vocaber is a simple online tool to help you effectively learn and remember lots of vocabulary, 
              or whatever else you want to learn.<br>
              <br>
              
              <div class="subHeading">Spaced Repetition for Effective Memorization</div>
              Vocaber uses a spaced repetition algorithm to help you retain vocabulary you've already learned.  
              This is done by retesting you on words after progressively larger and larger intervals, allowing 
              words to move from short term memory into long term memory.  
              <b>Too much repetition is inefficient and 
              significantly less effective than spaced repetition.</b>
              
              <div id="img_spaced_repetition">
              </div>  
              <div class="caption">Spaced repetition intervals</div>  
              
              
              <div class="subHeading">Find or Create Word Lists</div>
              You can either find existing lists or create your own.  You can also add to your list as you encounter 
              new words or terms to learn. Vocaber keeps track of your position in this list and ensures that you revisit old 
              words as you progress through it.<br>
              <br>
              
              
              <div class="subHeading">Learn in Checkpoints</div>
              You progress through your word list in terms of checkpoints.  Each checkpoint consists of 5 new 
              words, and some number of old words.  The old words are taken from various places in your list 
              according to the spaced-repetition algorithm.  Once you know all of the words in the checkpoint, 
              your position is updated and you can progress onto the next checkpoint.
              
              <div id="img_learn_screenshot">
              </div>
              <div class="caption">Screenshot from the learning page</div>  
              <br>
               
              <div class="subHeading">Track Your Learning Statistics</div>
              Vocaber keeps track of how many new words you've learned per day, so you can make sure 
              you're keeping up with your learning objectives.<br>
              <br>
              
              <div class="subHeading">Share and Collaborate</div>
              Vocaber allows you to easily share your lists with other people and fix mistakes 
              in each others' lists.  This makes studying for exams a lot more efficient.<br>
              <br>
              
              <div class="subHeading">Start Learning Now</div>
              Test out Vocaber with one of these lists:<br>
              <table>
              <tr>
                <td width="100px"> <a href="learnwords.php?listid=2">SAT Hit-list</a> </td>
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
              </table>
              <br>
              
              <br>
              <br>
        </div><!-- end main col -->

<?php
  //Assign all Page Specific variables
  $pagemaincontent = ob_get_contents();
  ob_end_clean();
  
  $navActive[4]=1;
  $bodyWrapFixed = 1;
  //Apply the template
  include("master.php");
?>