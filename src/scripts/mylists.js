// ajax to reset date for words per day
//=====================================
function resetSinceDate()
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
        // here one can provide feedback
        document.getElementById("since_date").innerHTML = xmlHttp.responseText;
      }
    }
   
  // run reset date php code
  var url = "../reset_date.php";
  
  xmlHttp.open("GET",url,true);
  xmlHttp.send(null);
  
  // change date settings and reset words learnt
  document.getElementById("since_words").innerHTML = "0";
  //var curDate = new Date();
  //document.getElementById("since_date").innerHTML = curDate.getFullYear()+"-"+(curDate.getMonth()+1)+"-"+curDate.getDate();
}

function clickReviewList(num)
{
  // update review list count
  // TO DO
  // update learnwords link to include new listID(s)
}




