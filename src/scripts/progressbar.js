function setProgressBar(percentage, numSegs)
{
  var i;
  // get percent out of numSegs
  var filledSegs = Math.round(percentage * numSegs);
  // var PROGRESS_COLOR = "#BA2F24";
  var PROGRESS_COLOR = "#007AD1";
  for (i=0; i < numSegs; i++)
  {
   if (i < filledSegs)
      document.getElementById('progressSeg'+i).style.backgroundColor=PROGRESS_COLOR;
    else
      document.getElementById('progressSeg'+i).style.backgroundColor="white";  
  }
  
}