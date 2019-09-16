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


// Loading Time Listbox
$commonListBox->name 			= 'ddlTime';
$commonListBox->id 				= 'ddlTime';
$commonListBox->customArray 	= $timeArray;
//$commonListBox->selectedItem 	= $businessFunction;
$commonListBox->optionKey 		= 'time';
$commonListBox->style	 		= 'float:left; margin: 7px 5px 0 0;';
$commonListBox->optionVal 		= 'timeVal';
$ddlTime 						= $commonListBox->AddRow('', 'Please choose');
$ddlTime 						= $commonListBox->convertArrayToDropDown();
$commonListBox->resetProperties();

// Loading Days
$commonListBox->name 			= 'ddlDays';
$commonListBox->id 				= 'ddlDays';
$commonListBox->customArray 	= $dayArray;
//$commonListBox->selectedItem 	= $businessFunction;
$commonListBox->optionKey 		= 'day';
$commonListBox->optionVal 		= 'dayVal';
$commonListBox->style	 		= 'float:left; margin: 7px 5px 0 0;';
$ddlDays 						= $commonListBox->AddRow('', 'Please choose');
$ddlDays 						= $commonListBox->convertArrayToDropDown();
$commonListBox->resetProperties();

echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
echo $htmlTagObj->openTag('html', 'xmlns="http://www.w3.org/s1999/xhtml"');
echo $htmlTagObj->openTag('head', '');
echo $htmlTagObj->openTag('meta', 'http-equiv="Content-Type" content="text/html; charset=iso-8859-1"');
echo $htmlTagObj->openTag('title', 'http-equiv="X-UA-Compatible" content="IE=EmulateIE7"');
echo 'ASG - HOME';
echo $htmlTagObj->closeTag('title');
echo $htmlTagObj->openTag('link', 'href="https://'.$_SERVER['HTTP_HOST'].'/ASG/css/styleOld.css" type="text/css" rel="stylesheet"');
echo $htmlTagObj->openTag('link', 'href="https://'.$_SERVER['HTTP_HOST'].'/ASG/css/modalwindowzindex.css" type="text/css" rel="stylesheet"');
echo $htmlTagObj->openTag('script', 'language="javascript" src="https://'.$_SERVER['HTTP_HOST'].'/ASG/js/jquery.min.js" type="text/javascript" charset="utf-8"');
echo $htmlTagObj->closeTag('script');
//echo $htmlTagObj->openTag('link', 'href="https://'.$_SERVER['HTTP_HOST'].'/ASG/css/jquery-ui-1.10.3.custom.css" type="text/css" rel="stylesheet"');
echo $htmlTagObj->openTag('script', 'language="javascript" src="https://'.$_SERVER['HTTP_HOST'].'/ASG/js/scripts.js" type="text/javascript" charset="utf-8"');
echo $htmlTagObj->closeTag('script');
//echo $htmlTagObj->openTag('script', 'language="javascript" src="https://'.$_SERVER['HTTP_HOST'].'/ASG/js/jquery-ui.min.js" type="text/javascript" charset="utf-8"');
//echo $htmlTagObj->closeTag('script');

echo $htmlTagObj->closeTag('head');
echo $htmlTagObj->openTag('body', '');
echo $htmlTagObj->openTag('div', 'class="mainDiv"');
echo $htmlTagObj->openTag('div', 'class="headDiv"');
echo $htmlTagObj->imgTag('https://'.$_SERVER['HTTP_HOST'].'/ASG/images/logo.png', 'align="center"');

echo $htmlTagObj->openTag('div', 'class="emptyDiv"');
echo $htmlTagObj->closeTag('div');
echo $htmlTagObj->imgTag('https://'.$_SERVER['HTTP_HOST'].'/ASG/images/banner.jpg', '');

echo $htmlTagObj->openTag('div', 'class="emptyDiv" style="height:30px"');
echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->openTag('div', 'class="iframeDiv"');
echo '<iframe class="videobox" id="videobox" src="http://asg.idomoo.com/iframe_content.html?id=0425/2WxJatdkktOzdgWe81YTACOrl100003N" frameborder="0" allowfullscreen=""></iframe>';

echo $htmlTagObj->openTag('div', 'class="emptyDiv" style="height:20px"');
echo $htmlTagObj->closeTag('div');

echo 'Agents are available to speak with you between 2 pm and 8 pm EST, Monday through Friday';

echo $htmlTagObj->openTag('div', 'class="emptyDiv" style="height:30px"');
echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->openTag('div', 'class="buttonsDiv"');
echo $htmlTagObj->openTag('div', 'class="blueButton" id="callMeNow" onclick="loadCallMeNow();"');
echo 'Call Me Now';
echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->openTag('div', 'class="blueButton" id="callMeLater" onclick="loadCallMeLater();"');
echo 'Call Me Later';
echo $htmlTagObj->closeTag('div');
echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->openTag('div', 'class="selectTimeDiv" id="selectTimeDiv" style="display:none;');
echo '<form name="frmSelTime" id="frmSelTime" method="post">';
echo $ddlDays;
echo $ddlTime;
echo $htmlTagObj->openTag('div', 'class="submitButton" onclick="populateCallMeLater()" style="margin: 0 0 0 0; padding-top: 7px; "');
echo 'Submit';
echo $htmlTagObj->closeTag('div');
echo '</form>';
echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->closeTag('div');
echo $htmlTagObj->closeTag('div');
echo $htmlTagObj->closeTag('body');
echo $htmlTagObj->closeTag('html');

echo $htmlTagObj->openTag('div', ' id="dialog" class="window" style="margin:0px; padding:0px;"');
echo $htmlTagObj->openTag('div', ' id="replace_main"');
echo $htmlTagObj->closeTag('div');
echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->openTag('div', ' id="mask" style="position:absolute; width:100%;"');
echo $htmlTagObj->closeTag('div');

// dialog box
//echo $htmlTagObj->openTag('div', 'id="showDialog" style="display:none;"');
//echo $htmlTagObj->closeTag('div');
// end dialog box

?>

<script type="text/javascript">
/*$(document).ready(function ()
{
	$("#callMeNow").click(function() 
	{
		alert('entered call me now');
	});
	
	$("#callMeLater").click(function() 
	{
		alert('entered call me laer');
	});
});*/
	
/*var loader = "<img src='../../../Include/images/progress.gif' />";

function openDialog(tit, pageurl)
{
	var windowWidth = $(window).width();
 	var windowHeight = $(window).height();
	
	

	var empID = $("#empID").val();
	$('#showDialog').html('<div align="center"><br/>'+loader+'<br/>Please wait...</div>');
	$('#showDialog').dialog({										   
		height: 'auto',
		width:225,										   
		modal:false,
		position: ['middle',100]
	});//.html('test data.'+task); 
	$('#showDialog').load(pageurl+'.php?empID=' + empID);
	
}

function closePopup()
{
	$('#showDialog').dialog('close');
}
	*/
</script>