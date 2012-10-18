var DEV_VERSION=0;

var just_started;
var one_guessed;
var starting_pos = 0;

var checkWordRate = 0;
var bestWordRate = 0;
var checkStartTime;
var mini_size = 0;
var newWordsLearnt = 0;

function initLearn()
{

  score = 0;
  
  document.getElementById("word").value="";
  
  document.getElementById("nextWordButton").innerHTML="start";
  document.getElementById("nextWordButton").accessKey="s";
  
  document.getElementById("meaning").innerHTML="Ready?";
  document.getElementById("progress").innerHTML=pos+" / "+numWords;
  
  just_started=1;
  one_guessed = false;
  initMiniList();
  
  starting_pos = pos;
  bestWordRate = 0;
  checkWordRate = 0;
  newWordsLearnt=0;
  document.getElementById("newWordsLearnt").innerHTML=newWordsLearnt;
  document.getElementById("checkRate").innerHTML="0 wpm";
  document.getElementById("bestRate").innerHTML="0 wpm";

  // clear options checkboxes
  document.optionsForm.moreRepeat.checked = false;
  document.optionsForm.inverseTest.checked = false;
  
  // handle case when word list is empty
  if (numWords==0)
  {
    document.getElementById("meaning").innerHTML="<span style=\"color:#CCCCCC;\">Word list is empty</span>";
    document.getElementById("nextWordButton").disabled=true;
    document.getElementById("word").disabled=true;
  }
}
// This function sets the amount of repetition that occurs
// if a word is answered incorrectly.
var moreRepetition = false;
function toggleRepetition()
{
  moreRepetition = !moreRepetition;
  if (moreRepetition)
  {
    PROP_INC = 1;
    PENALTY  = 3;
  }
  else
  {
    PROP_INC = 2;
    PENALTY = 2;
  }
}

var inverse_test = false;
var ignore_parenth = false;
// this function changes tesing mode, either user must find word, or meaning
function toggleInverseTest()
{
  inverse_test = !inverse_test;
 
}
function toggleIgnoreParenth()
{
  ignore_parenth = !ignore_parenth;
}
function handleKeyPress(event)
{
  var key=event.keyCode || event.which;
  if (key==13 && document.getElementById("nextWordButton").disabled==false)
  {
    nextWord();
  }
}

function setFeedback(str)
{
  document.getElementById("feedback").innerHTML=str;
}

var GROUP_SIZE = 5; // number of new words taken each checkpoint
var REVIST_FACT = 3; // factor used when determining how often words are revisited
var PROP_INC    = 2; // number of spaces the incorrect word is propogated forward (multiplied by state)
var PENALTY     = 2; // if you get it wrong, must get it right this many times

// multi dimensional array storing word info for mini_words list
var MAX_MINI_WORDS =200;
var WORD           =0;
var MEANING        =1;
var CONTEXT        =2;
var BIG_IDX        =3;
var STATE          =4; // store info about incorrectly / correctly answered
var MINI_LIST_NUM  =5;
var mini_list = new Array(MAX_MINI_WORDS);

// initialize mini list
function initMiniList()
{
  for (i=0; i < MAX_MINI_WORDS; i++)
  {
    mini_list[i] = new Array(MINI_LIST_NUM);
  }
}


// this function gets a current list of words from the big list
// all relative to current position
function buildMiniList()
{
  var bigPtr = pos;
  var miniPtr = 0;
  var jump = 1;
  var i;

  while (bigPtr >= 0)
  {
    for (i = 0; i < GROUP_SIZE; i++)
    {
      if (bigPtr + i < numWords)
      {
        mini_list[miniPtr][WORD] = words[bigPtr+i];
        mini_list[miniPtr][MEANING] = meanings[bigPtr+i];
        mini_list[miniPtr][CONTEXT] = contexts[bigPtr+i];
        mini_list[miniPtr][BIG_IDX] = bigPtr+i;
        mini_list[miniPtr][STATE]   = 1;
        miniPtr++;
      }
    } 
    // move bigPtr back by revist_factor
    bigPtr = bigPtr - jump*GROUP_SIZE;
    jump = jump * REVIST_FACT;
  }
  // store size of mini array
  mini_size = miniPtr;
  
  
  if (!just_started)
  {
    if (pos >= numWords)
    {
      document.getElementById("statusbar").innerHTML="End of list reached. You can add more words to this list from <a href=\"my_lists.php\">here</a>." +
                                                     "<br>Or you can <a href=\"#\"onclick=\"restartList();\">restart</a> this list.";
      // XXX add the option to just keep practicing the set, pos moves forward, but no new words get added
      //, this way recent words still come up more often. - might end up with no words in minilist though.
      // alternatively just enter a kind of test mode.                                                     
    }
    else
    {
      document.getElementById('statusbar').innerHTML="Checkpoint reached!<br>Word rate "+checkWordRate + " wpm";
      
    } 
    // update best word rate
    if (Number(checkWordRate) > Number(bestWordRate))
    {
      bestWordRate = checkWordRate;
      document.getElementById("bestRate").innerHTML=bestWordRate+" wpm";
    }
    newWordsLearnt = pos - starting_pos;
    // display new words learnt, update for next time this function is called
    document.getElementById("newWordsLearnt").innerHTML=newWordsLearnt;
  }
  // shuffle words
  var rand_idx;
  var temp = Array(MINI_LIST_NUM);
  for (i=mini_size-1; i >= 0; i--)
  {
    rand_idx = parseInt(Math.random() * (i+1));
    // store position i
    for (j = 0; j < MINI_LIST_NUM; j++)
    {
      temp[j] = mini_list[i][j];
      mini_list[i][j] = mini_list[rand_idx][j];
      mini_list[rand_idx][j] = temp[j];
    }
  }
//  for(var j, x, i = v.length; i; j = parseInt(Math.random() * i), x = v[--i], v[i] = v[j], v[j] = x)
  
  // initialize checkpoint timer
  checkStartTime = new Date();
  // for debugging
  /*
  var wordstring = "";
  for (i = 0; i < mini_size; i++)
    wordstring += mini_list[i][WORD] + "(" + mini_list[i][MEANING] + ")  ";
  document.getElementById('showlist').innerHTML=wordstring;
  */
 
}


var miniPos = 0;
var barL1 = 0;
var barL2 = 0;
var numCorrectL1 = 0;
var numCorrectL2 = 0;
var last_pos = 0;
var prop_amount = 0;

// moves an element at a given index forward in mini list array by some amount
// This function also updates the global variable last_post used for editing words
function propogate_mini_list(idx, amount)
{
  var i, j, k;
  var temp = Array(MINI_LIST_NUM);
  
  // check if this word was answered incorrectly, if so handle it differently
  var incorrect = mini_list[idx][STATE]==PENALTY;
  
  var rand_idx;
  // propogate forward by amount, or as far as it will go:
  // save propagating element
  for (j=0;j < MINI_LIST_NUM; j++)
  {
    temp[j] = mini_list[idx][j];
  }
  // shift contents up
  for (i=idx; i < Math.min(mini_size-1,idx+amount);i++)
  {
    for (j=0;j < MINI_LIST_NUM; j++)
    {
      mini_list[i][j] = mini_list[i+1][j];
    }
  }
  // final insert
  for (j=0;j < MINI_LIST_NUM; j++)
  {
    mini_list[i][j] = temp[j];
  }
  last_pos = i;
  // handle case when item to be propogated was already very close to the end
  // this requires moving pointer back and randomly switching some words so
  // there is still an interval between being tested on the word
  if (mini_size-idx <= PROP_INC) 
  {
    miniPos = Math.max(0,(mini_size-1) - PROP_INC);
    last_pos = Math.max(0,mini_size-1);
    // make sure k doesn't end up being negative
    for (k=Math.max(0,mini_size-1-PROP_INC); k < idx; k++)
    {
      rand_idx = parseInt(Math.random() * (mini_size-1-PROP_INC));
      for (j=0;j<MINI_LIST_NUM;j++)
      {
        temp[j] = mini_list[k][j];
        mini_list[k][j] = mini_list[rand_idx][j];
        mini_list[rand_idx][j] = temp[j];
      }
    }
  }
  
  // now make sure there is going to be enough room for the element to propogate forward
  // the next time it is guessed.  This is to ensure that the miniPos is only decremented
  // once when the word is incorrectly guessed.  Otherwise it ends up moving back even when words are 
  // answered correctly and this looks wiered on checkpoint progress bar.  Also, only do this if the 
  // word was actually answerd incorrectly.
  var space_needed = PROP_INC;
  for (k=1;k<PENALTY;k++)
  {
    space_needed = space_needed + PROP_INC*k; 
  }
    
  if (mini_size-idx <= space_needed && incorrect)
  {
    
    // make sure not negative
    miniPos = Math.max(0, (mini_size-1) - space_needed);
    
    if (mini_size-idx <= PROP_INC)
    {
      // to determine shift size, we must consider how much more this
      // is going to try and move forward if all subsequent guesses are correct.
      shift_size = 0;
      for (k=1; k < PENALTY; k++)
        shift_size = shift_size + k*PROP_INC;
    }
    else
      shift_size = idx - miniPos;
    // make sure shift size isn't bigger than mini-list
    shift_size = Math.min(shift_size, mini_size);
    
    last_pos = last_pos - shift_size;
    
    var temp2 = Array(shift_size);
      for (k=0; k < shift_size; k++)
    temp2[k]=Array(MINI_LIST_NUM);
    
    
    // save first element in temp
    for (k=0; k < shift_size; k++)
    {
      for (j=0;j < MINI_LIST_NUM; j++)
      {
        temp2[k][j] = mini_list[miniPos+k][j];
      }
    } 
    for (k = miniPos; k < mini_size-shift_size;k++)
    { 
      for (j=0; j < MINI_LIST_NUM;j++)
      {
        mini_list[k][j] = mini_list[k+shift_size][j];
      }
    }
    // put temp data back in array
    for (k=0; k < shift_size; k++)
    {
      for (j=0;j < MINI_LIST_NUM; j++)
      {
        mini_list[mini_size-shift_size+k][j]=temp2[k][j];
      }
    } 
     
    // now perform random switching of duplicated data at the end of the array
    for (k = mini_size-shift_size; k < mini_size; k++)
    {
      rand_idx = parseInt(Math.random() * (miniPos));
      for (j=0;j<MINI_LIST_NUM;j++)
      {
        temp[j] = mini_list[k][j];
        mini_list[k][j] = mini_list[rand_idx][j];
        mini_list[rand_idx][j] = temp[j];
      }
    }
    
  }
  // for debug
/*
  var wordstring = "";
  for (i = 0; i < mini_size; i++)
    wordstring += mini_list[i][WORD]+" ";
  document.getElementById('showlist').innerHTML=wordstring;
*/
}

function nextWord()
{
  var percent100 = false; // used when set is done so progress bar not reset
  // handle initial case
  if (pos==-1)
  {
    pos++;
    // get first list
    buildMiniList();
    document.getElementById("meaning").innerHTML=mini_list[pos][MEANING];
    document.getElementById("nextWordButton").innerHTML="check";
    just_started=0; 
  }
  else
  {
    if (just_started==1)
    {
      document.getElementById("nextWordButton").innerHTML="check";
      buildMiniList();
      just_started = 0;
    }
    else
    {
      document.getElementById("statusbar").innerHTML=" ";
      one_guessed = true;
      last_pos = miniPos;
      // display previous word
      document.getElementById("disp_word").innerHTML    = mini_list[miniPos][WORD];
      document.getElementById("disp_meaning").innerHTML = mini_list[miniPos][MEANING];
      document.getElementById("disp_context").innerHTML = mini_list[miniPos][CONTEXT];
    
      var compareTo ="";
      if (inverse_test)
      {
        compareTo = mini_list[miniPos][MEANING];
      }
      else
      {
        compareTo = mini_list[miniPos][WORD];
      }
      if(ignore_parenth)
      {
        // use regular expression to remove anything inside parenthesis,
        // including a white space just before parenthesis start 
        compareTo = compareTo.replace(/(\s*\([^\)]*\))*/g, "");
      }
      
      // check word
      if (document.getElementById("word").value == compareTo)
      {
        // word was correct
        document.getElementById("feedback").innerHTML="checking...";
        // this adds a small delay so there is some feedback
        var CORRECT_COLOR="#93C32C";
        //var CORRECT_COLOR="#AACC55";
        window.setTimeout('setFeedback("Correct!")',100);
        document.getElementById("feedbackRow1").style.backgroundColor = CORRECT_COLOR;
        document.getElementById("feedbackRow2").style.backgroundColor = CORRECT_COLOR;
        document.getElementById("feedbackRow3").style.backgroundColor = CORRECT_COLOR;
        
        // check state of word, this indicates how many times it has to be gotten
        // right before it is removed from the mini list
        if (mini_list[miniPos][STATE] == 1 ||
            mini_list[miniPos][STATE] == 0)
        {
          // done with this word
          miniPos++;
          mini_list[miniPos][STATE] == 0;
          // Check if we're done with the miniPos
          if (miniPos >= mini_size)
          {
            if (pos + GROUP_SIZE > numWords)
            {
              pos = numWords;
            }
            else
            {
              pos = pos + GROUP_SIZE;
            }
            buildMiniList();
            miniPos = 0;
            // save position in database,
            savePos();
            
            // set progress bar to 100%
            setProgressBar(1,20);
            percent100=true;
            
            
          } 
        }
        else
        {
          // drecrement state
          mini_list[miniPos][STATE]--;
          prop_amount = (PENALTY+1-mini_list[miniPos][STATE])*PROP_INC;
          // propogate word forward in list
          propogate_mini_list(miniPos, prop_amount);
        }
      }
      else
      {
        // incorrect case
        document.getElementById("feedback").innerHTML="checking...";
        // this adds a small delay so there is some feedback
        window.setTimeout('setFeedback("Incorrect")',100);
        //var WRONG_COLOR="#DDDDDD";
        var WRONG_COLOR="#F14E4E";
        //var WRONG_COLOR="#DD8888";
        document.getElementById("feedbackRow1").style.backgroundColor=WRONG_COLOR;
        document.getElementById("feedbackRow2").style.backgroundColor=WRONG_COLOR;
        document.getElementById("feedbackRow3").style.backgroundColor=WRONG_COLOR;
        
        lastCorrect = false;
        // set state to penalty
        mini_list[miniPos][STATE]=PENALTY+1;
        // propogate forward 
        //prop_amount = (PENALTY+1-mini_list[miniPos][STATE])*PROP_INC;
        // propogate word forward in list
        //propogate_mini_list(miniPos, prop_amount);
      }
       
    }
    
    // set next word and indicate how many times it needs to be answered
    var i, flags = "";
    for (i = 0; i < mini_list[miniPos][STATE]-1; i++)
      flags += "*";
    if (mini_list[miniPos][STATE] > 1)
      flags += " ";
    if (!inverse_test)
    {
      document.getElementById("meaning").innerHTML=flags+mini_list[miniPos][MEANING];
    }
    else
    {
      document.getElementById("meaning").innerHTML=flags+mini_list[miniPos][WORD];
    }
    // update the score
    document.getElementById("progress").innerHTML=pos+" / "+numWords;
    
    // update the checkpoint progress
    var checkProg = (miniPos+0.0001) / (mini_size+0.0001);
    // update progress bar
    if (percent100==1)
    {
      percent100=0;
      // progress bar already set to 100%
    }
    else
    {
      setProgressBar(checkProg, 20);
    }
    // update percentage amount
    checkProg = checkProg * 100.0;
    checkProg = checkProg.toFixed(0);
    document.getElementById("checkProgress").innerHTML=checkProg+"%";
    
    
    // set checkpoint rate
    var tempDate = new Date();
    var timeDiff = (tempDate.getTime() - checkStartTime.getTime())/60000;
    if (timeDiff > 0)
    {
      checkWordRate = miniPos / timeDiff;
      checkWordRate = checkWordRate.toFixed(1);
      document.getElementById("checkRate").innerHTML=checkWordRate+" wpm";
    }
    
    // clear the box
    document.getElementById("word").value = "";
    
    // ??? for debugging
    /*
    document.getElementById("miniPos").innerHTML=miniPos;
    document.getElementById("barL1").innerHTML=barL1;
    document.getElementById("barL2").innerHTML=barL2;
    document.getElementById("numCorrect").innerHTML=numCorrectL1;
    */
  }
  
}
function restartList()
{
  pos=0;
  start_pos=0;
  document.getElementById("statusbar").innerHTML="";
  document.getElementById("progress").innerHTML=pos+" / "+numWords;
  savePos();
  initLearn();
}
function editWord()
{
  // only edit if there is a word to edit
  if (one_guessed)
  {
    // fill in word and meaning fields from current word
    document.getElementById("editbox_word").value=mini_list[last_pos][WORD];
    document.getElementById("editbox_meaning").value=mini_list[last_pos][MEANING];
    document.getElementById("editbox_context").value=mini_list[last_pos][CONTEXT];
  
    document.getElementById("editBox").style.visibility="visible";
  } 
}
function saveWord(save)
{
  if (save)
  {
    saveEdit();
  }
  document.getElementById("editBox").style.visibility="hidden";
}

// ajax stuff
//============
function savePos()
{

// declare ajax object
var xmlHttp;
// get it - this depends on the browser so it trys different things
try
  {
  // Firefox, Opera 8.0+, Safari
  xmlHttp=new XMLHttpRequest();
  }
catch (e)
  {
  // Internet Explorer
  try
    {
    xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
    }
  catch (e)
    {
    try
      {
      xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
      }
    catch (e)
      {
      alert("Your browser does not support AJAX!");
      return false;
      }
    }
  }
  xmlHttp.onreadystatechange=function()
    {
    if(xmlHttp.readyState==4)
      {
        // here one cen provide feedback
        //document.myForm.time.value=xmlHttp.responseText;
        
      }
    }
  // to send the position as a variable it must be appended to the URL
  // then it can be received by the php script just like form variables
  if (DEV_VERSION)
    var url = "../dev/savepos.php";
  else
    var url = "../savepos.php";
  url = url + "?pos=" + pos;
  /*
  if (!restarted)
    url = url + "&restarted=0";
  else
    url = url + "&restarted=1";
  */
  xmlHttp.open("GET",url,true);
  xmlHttp.send(null);
}

// ajax stuff
//============
function saveEdit()
{

// declare ajax object
var xmlHttp;
// get it - this depends on the browser so it trys different things
try
  {
  // Firefox, Opera 8.0+, Safari
  xmlHttp=new XMLHttpRequest();
  }
catch (e)
  {
  // Internet Explorer
  try
    {
    xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
    }
  catch (e)
    {
    try
      {
      xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
      }
    catch (e)
      {
      alert("Your browser does not support AJAX!");
      return false;
      }
    }
  }
  xmlHttp.onreadystatechange=function()
    {
    if(xmlHttp.readyState==4)
      {
        
      }
    }
   
  // get the edited fields
  var editbox_word = document.getElementById("editbox_word").value;
  var editbox_meaning = document.getElementById("editbox_meaning").value;
  var editbox_context = document.getElementById("editbox_context").value;
  
  // update mini list, use miniPos, or miniPos-1 if last word was actually correct
  
  var miniIndex = last_pos;
  
  var bigIndex = mini_list[miniIndex][BIG_IDX];
  var editID = wordIDs[bigIndex];
  
  // save the modification to the word list in memory
  mini_list[miniIndex][WORD]    = editbox_word;
  mini_list[miniIndex][MEANING] = editbox_meaning;
  mini_list[miniIndex][CONTEXT] = editbox_context;
  
  // update big list - need to get index in big list
  words[bigIndex]    = editbox_word;
  meanings[bigIndex] = editbox_meaning;
  contexts[bigIndex] = editbox_context;

  // update data base
  if (DEV_VERSION) // Not sure why thisn't working without the dev path
    var url = "../dev/editword.php";
  else
    var url = "../editword.php";
    
  url = url + "?id=" + editID;
  url = url + "&word=" + editbox_word;
  url = url + "&meaning=" + editbox_meaning;
  url = url + "&context=" + editbox_context;
  
  xmlHttp.open("GET",url,true);
  xmlHttp.send(null);
  
  // update the feedback box
  document.getElementById("disp_word").innerHTML    = editbox_word;
  document.getElementById("disp_meaning").innerHTML = editbox_meaning;
  document.getElementById("disp_context").innerHTML = editbox_context;
}
