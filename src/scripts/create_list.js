function checkList()
{
  var listName = document.getElementById("listname").value;
  
  if(listName == "")
  {
    alert("Please enter a list name");
    return false;
  }
  return true;
  
}
function toggleSharing()
{
  if (document.createForm.shared.checked)
  {
    document.createForm.fixable.disabled=false;
    document.createForm.editable.disabled=false;
  }
  else
  {
    document.createForm.fixable.disabled=true;
    document.createForm.editable.disabled=true;
  }
}
/* NO LONGER NEED ANY OF THIS
function handleKeyPress()
{
  // check if the value changed
  list = document.getElementById("listname").value;
  if (list != old_list)
  {
    checkAvailability();
    old_list = list;
  }
}

// ajax stuff
//============
function checkAvailability()
{
  list = document.getElementById("listname").value;
  document.getElementById("available").style.color="#AAAAAA";
  document.getElementById("available").innerHTML="checking availability...";
  list_available=false;
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
        if(xmlHttp.responseText=="list exists")
          {
            document.getElementById("available").style.color="#EE1111";
            document.getElementById("available").innerHTML="list already exists";
            list_available = false;
          }
        else if (xmlHttp.responseText=="list does not exist")
          {
            // make sure the list isn't empty
            if (list == "")
            {
              document.getElementById("available").innerHTML="";
              list_available=false;
              return false;
            }
            if (list.match(" ")!=null)
            {
              document.getElementById("available").style.color="#EE1111";
              document.getElementById("available").innerHTML="list name must not contain spaces";
              list_available=false;
              return false;
            }
            document.getElementById("available").style.color="#44EE44";
            document.getElementById("available").innerHTML="list name available";
            list_available = true;
          }
        else
          {
          
            alert("Database connection error: " + xmlHttp.responseText);
            list_available = false;
          }
      }
    }
  // to send the list as a variable it must be appended to the URL
  // then it can be received by the php script just like form variables
  var url = "listexists.php";
  url = url + "?list=" + list;
  xmlHttp.open("GET",url,true);
  xmlHttp.send(null);
}
*/