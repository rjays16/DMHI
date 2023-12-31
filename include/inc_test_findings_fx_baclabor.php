<?php

/**
*  processFindings() processes the variables' contents based on the indexes 
* and concatenate them into one single string
*  param $tag = array of indexes for selecting the group of variables (reference call)
*  param $offset = determines which part of the array element should be used: 0 = the index;  1= the value
*  & return = concatenated string (reference return)
*/
function processFindings(&$indx,$offset=0)
{
   global $HTTP_POST_VARS ;
   
   $ret_str=''; 
    
   if($offset)
   {
      while(list($x,$v)=each($indx))
	  {	
	    if(isset($HTTP_POST_VARS[$v]) && $HTTP_POST_VARS[$v])
		{
		  if($ret_str=='') $ret_str=$v.'=1';
		    else $ret_str.='&'.$v.'=1';
		}
	   }
	}
	else
	{
      while(list($x,$v)=each($indx))
	  {	
	    if(isset($HTTP_POST_VARS[$x]) && $HTTP_POST_VARS[$x])
		{
		  if($ret_str=='') $ret_str=$x.'=1';
		    else $ret_str.='&'.$x.'=1';
		}
	  }
    }
   
   return $ret_str;
}
