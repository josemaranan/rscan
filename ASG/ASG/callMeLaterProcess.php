<?php
/**
 * @Description: 
 * @Author: Shore Infotech
 * @Date: 05/15/2014
 **/
 
 ini_set('display_errors','0');
 
$pageTitle = "ASG - Home";

//Including the includeClassFiles page for getting the header part and class object instance

include_once($_SERVER['DOCUMENT_ROOT'] . "/ASG/class/config.inc.php");
$dayArray = array('0' => 'Monday' ,'1' => 'Tuesday', '2' => 'Wednesday', '3' => 'Thursday', '4' => 'Friday', '5' => 'Saturday', '6' => 'Sunday');
$timeArray = array('0' => '2pm - 2:30pm (EST)', 
				   '1' => '2:30pm - 3pm (EST)', 
				   '2' => '3pm - 3:30pm (EST)', 
				   '3' => '3:30pm - 4pm (EST)', 
				   '4' => '4pm - 4:30pm (EST)', 
				   '5' => '4:30pm - 5pm (EST)', 
				   '6' => '5pm - 5:30pm (EST)', 
				   '7' => '5:30pm - 6pm (EST)', 
				   '8' => '6pm - 6:30pm (EST)', 
				   '9' => '6:30pm - 7pm (EST)', 
				   '10' => '7pm - 7:30pm (EST)', 
				   '11' => '7:30pm - 8pm (EST)' );

echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
echo $htmlTagObj->openTag('html', 'xmlns="http://www.w3.org/s1999/xhtml"');
echo $htmlTagObj->openTag('head', '');
echo $htmlTagObj->openTag('meta', 'http-equiv="Content-Type" content="text/html; charset=iso-8859-1"');
echo $htmlTagObj->openTag('title', 'http-equiv="X-UA-Compatible" content="IE=EmulateIE7"');
echo 'ASG - HOME';
echo $htmlTagObj->closeTag('title');
echo $htmlTagObj->openTag('link', 'href="https://'.$_SERVER['HTTP_HOST'].'/ASG/css/style.css" type="text/css" rel="stylesheet"');
echo $htmlTagObj->openTag('script', 'language="javascript" src="https://'.$_SERVER['HTTP_HOST'].'/ASG/js/jquery.min.js" type="text/javascript" charset="utf-8"');
echo $htmlTagObj->closeTag('script');
echo $htmlTagObj->openTag('link', 'href="https://'.$_SERVER['HTTP_HOST'].'/ASG/css/jquery-ui-1.10.3.custom.css" type="text/css" rel="stylesheet"');
echo $htmlTagObj->openTag('script', 'language="javascript" src="https://'.$_SERVER['HTTP_HOST'].'/ASG/js/jquery-1.10.2.js" type="text/javascript" charset="utf-8"');
echo $htmlTagObj->closeTag('script');
echo $htmlTagObj->openTag('script', 'language="javascript" src="https://'.$_SERVER['HTTP_HOST'].'/ASG/js/jquery-ui.min.js" type="text/javascript" charset="utf-8"');
echo $htmlTagObj->closeTag('script');

echo $htmlTagObj->closeTag('head');
echo $htmlTagObj->openTag('body', '');

echo $htmlTagObj->openTag('div', 'style="float:right; padding-right:5px; margin-top:3px; margin-bottom:3px; cursor:pointer;" onClick="return closeMask();"');
echo $htmlTagObj->imgTag('https://'.$_SERVER['HTTP_HOST'].'/ASG/images/closeButton.png', 'border="0" width="15px" height="15px"');
echo $htmlTagObj->closeTag('div');

echo '<form name="frmCMN" id="frmCMN" method="post">';

echo $htmlTagObj->openTag('div', 'class="popupWindow"');
echo $htmlTagObj->openTag('div', 'style="margin-top: 70px;"');
echo $htmlTagObj->openTag('span', '');
echo 'Thankyou, Barry. An agent will call '.$dayArray[$_REQUEST['var1']].' between '. $timeArray[$_REQUEST['var2']];
echo $htmlTagObj->closeTag('span');
echo $htmlTagObj->closeTag('div');
echo $htmlTagObj->closeTag('div');
echo '</form>';



echo $htmlTagObj->closeTag('body');
echo $htmlTagObj->closeTag('html');