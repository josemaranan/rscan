<?php
/**
 * @Description: 
 * @Author: Shore Infotech
 * @Date: 05/15/2014
 **/
 
 ini_set('display_errors','0');
 
$pageTitle = "ASG - Home";
if($_SERVER['HTTPS'] == 'on')
{
	$http = 'https';
}
else
{
	$http = 'http';
}

//Including the includeClassFiles page for getting the header part and class object instance
include_once($_SERVER['DOCUMENT_ROOT'] . "/ASG/class/config.inc.php");

//$db = mysql_connect('23.229.143.100', 'RNetMySqlServer', 'results123');
//$rv = mysql_select_db('RNet', $db);

//$db = mysql_connect('localhost', 'RNetMySqlServer', 'results123');
//$rv = mysql_select_db('RNet', $db);

/*
if (!$db) 
{
    die('Could not connect: ' . mysql_error());
}
*/
//echo 'Connected successfully';
//mysql_close($link);

$UNIQUE_ID = $_REQUEST['UNIQUE_ID'];
$FIRST_NAME = ucfirst($_REQUEST['FIRST_NAME']);
$LAST_NAME = ucfirst($_REQUEST['LAST_NAME']);

$thumbnail = $_REQUEST['THUMBNAIL'];
$landingPageUrl = $_REQUEST['LANDING_PAGE_URL'];
//$companyLogo = $_REQUEST['COMPANY_LOGO'];
$companyLogo = 'http://www.myplan1.com/ASG/images/ss-discover.png';
$footerCompanyName = 'Starkweather & Shepley Insurance Brokerage, Inc.';

	

$fName = $FIRST_NAME.' '.$LAST_NAME;
$phone = $_REQUEST['PHONE'];



$vid = $landingPageUrl;

$dayArray = array('0' => 'Monday' ,'1' => 'Tuesday', '2' => 'Wednesday', '3' => 'Thursday', '4' => 'Friday', '5' => 'Saturday', '6' => 'Sunday');
$timeArray = array('0' => '2 pm - 2:30 pm (EST)', 
				   '1' => '2:30 pm - 3 pm (EST)', 
				   '2' => '3 pm - 3:30 pm (EST)', 
				   '3' => '3:30 pm - 4 pm (EST)', 
				   '4' => '4 pm - 4:30 pm (EST)', 
				   '5' => '4:30 pm - 5 pm (EST)', 
				   '6' => '5pm - 5:30 pm (EST)', 
				   '7' => '5:30 pm - 6 pm (EST)', 
				   '8' => '6 pm - 6:30 pm (EST)', 
				   '9' => '6:30 pm - 7 pm (EST)', 
				   '10' => '7 pm - 7:30 pm (EST)', 
				   '11' => '7:30 pm - 8 pm (EST)' );


// Loading Time Listbox
$commonListBox->name 			= 'ddlTime';
$commonListBox->id 				= 'ddlTime';
$commonListBox->customArray 	= $timeArray;
//$commonListBox->selectedItem 	= $timeArray[0];
$commonListBox->optionKey 		= 'time';
$commonListBox->style	 		= 'float:left; margin: 0px 0px 0 0; width:150px;border: #f3f1f2;background-color:#f3f1f2;color: #7e7c7d;';
$commonListBox->optionVal 		= 'frmInput';
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
echo $htmlTagObj->openTag('link', 'href="'.$http.'://'.$_SERVER['HTTP_HOST'].'/ASG/css/style.css" type="text/css" rel="stylesheet"');
echo $htmlTagObj->openTag('link', 'href="'.$http.'://'.$_SERVER['HTTP_HOST'].'/ASG/css/jquery-ui.css" type="text/css" rel="stylesheet"');
echo $htmlTagObj->openTag('link', 'href="'.$http.'://'.$_SERVER['HTTP_HOST'].'/jquery-ui-1.10.3.custom" type="text/css" rel="stylesheet"');

echo $htmlTagObj->openTag('link', 'href="'.$http.'://'.$_SERVER['HTTP_HOST'].'/ASG/css/modalwindowzindex.css" type="text/css" rel="stylesheet"');
echo $htmlTagObj->openTag('script', 'language="javascript" src="'.$http.'://'.$_SERVER['HTTP_HOST'].'/ASG/js/jquery.min.js" type="text/javascript" charset="utf-8"');
echo $htmlTagObj->closeTag('script');
echo $htmlTagObj->openTag('script', 'language="javascript" src="'.$http.'://'.$_SERVER['HTTP_HOST'].'/ASG/js/jquery-ui.min.js" type="text/javascript" charset="utf-8"');
echo $htmlTagObj->closeTag('script');

//echo $htmlTagObj->openTag('link', 'href="'.$http.'://'.$_SERVER['HTTP_HOST'].'/ASG/css/jquery-ui-1.10.3.custom.css" type="text/css" rel="stylesheet"');
echo $htmlTagObj->openTag('script', 'language="javascript" src="'.$http.'://'.$_SERVER['HTTP_HOST'].'/ASG/js/scripts.js" type="text/javascript" charset="utf-8"');
echo $htmlTagObj->closeTag('script');
//echo $htmlTagObj->openTag('script', 'language="javascript" src="'.$http.'://'.$_SERVER['HTTP_HOST'].'/ASG/js/jquery-ui.min.js" type="text/javascript" charset="utf-8"');
//echo $htmlTagObj->closeTag('script');

echo $htmlTagObj->closeTag('head');
echo $htmlTagObj->openTag('body', '');


echo $htmlTagObj->openTag('div', 'class="mainDiv"');
echo $htmlTagObj->openTag('div', 'class="headDiv"');
echo $htmlTagObj->openTag('div', 'class="bodyDiv"');
echo $htmlTagObj->openTag('div', 'class="bodyDivBlue"');
echo $htmlTagObj->closeTag('div');

//echo $htmlTagObj->imgTag(''.$http.'://'.$_SERVER['HTTP_HOST'].'/ASG/images/logo.png', 'align="center"');
echo $htmlTagObj->openTag('div', 'class="logoDiv"');
echo $htmlTagObj->imgTag($companyLogo, 'align="center"');
echo $htmlTagObj->closeTag('div');
echo $htmlTagObj->closeTag('div');
echo $htmlTagObj->closeTag('div');



echo $htmlTagObj->openTag('div', 'class="emptyDiv"');
echo $htmlTagObj->closeTag('div');
//echo $htmlTagObj->imgTag(''.$http.'://'.$_SERVER['HTTP_HOST'].'/ASG/images/banner.jpg', '');

echo $htmlTagObj->openTag('div', 'class="emptyDiv" style="height:30px"');
echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->openTag('div', 'class="iframeDiv" id="iframeDiv"');
echo '<iframe height="300px" width="500px" class="videobox" id="videobox" name="videobox" src="https://asg.idomoo.com/iframe_content.html?id='.$vid.'" frameborder="0" allowfullscreen=""></iframe>';

//echo $htmlTagObj->openTag('div', 'class="emptyDiv" style="height:20px"');
//echo $htmlTagObj->closeTag('div');

//echo 'Agents are available to speak with you between 2 pm and 8 pm EST, Monday through Friday';

echo $htmlTagObj->openTag('div', 'class="renewNow" id="renewNow" style="display:none;"');
echo 'Click the box to begin your insurance renewal process:';
echo $htmlTagObj->openTag('input', 'type="checkbox" class="renewChkBox" id="renewChkBox" name="renewChkBox" onclick="enableTimeDiv();"');
echo $htmlTagObj->closeTag('div');

/*
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
*/
echo $htmlTagObj->closeTag('div');

//echo $htmlTagObj->openTag('div', 'class="emptyDiv" style="height:20px"');
//echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->openTag('div', 'class="selectTimeDiv" id="selectTimeDiv" style="display:none;"');
echo $htmlTagObj->openTag('p', 'class="selectTimeDivText"');
echo "We're setting up plans just for you. Contact us at 1-800-854-4625 to discuss your options. <br /> Or arrange a call back at a more convenient time. Let us know when and where:";
echo $htmlTagObj->closeTag('p');

echo '<form name="frmSelTime" id="frmSelTime" method="post">';
echo '<center>';
echo '<table cellspacing="8" cellpadding="8" border="0" class="frmTable">';
echo '<tr>';
echo '<th>';
echo 'Your Name';
echo '</th>';
echo '<th>';
echo 'Phone Number';
echo '</th>';
echo '<th>';
echo 'Date';
echo '</th>';
echo '<th>';
echo 'Time';
echo '</th>';
echo '</tr>';

echo '<tr>';
echo '<td class="frmTableTD">';
echo '<input type="text" name="fName" id="fName" value="'.$fName.'"  style="width:180px" class="frmInput">';
echo '</td>';
echo '<td class="frmTableTD">';
echo '<input type="text" name="phone" id="phone" value="'.$phone.'"  style="width:180px" class="frmInput">';
echo '</td>';
echo '<td class="frmTableTD">';
//echo $ddlDays;
echo '<input name="renDate" id="renDate" value=""  style="width:100px"  class="frmInput">';
echo '</td>';
echo '<td class="frmTableTD">';
echo $ddlTime;
echo '</td>';
echo '</tr>';

echo '<tr>';
echo '<td colspan="4">';
//echo $htmlTagObj->openTag('div', 'class="submitButton" onclick="populateCallMeLater()" style="margin: 0 0 0 0;"');
echo $htmlTagObj->openTag('div', 'class="submitButton" id="submitButton" name="submitButton" style="margin: 0 0 0 0;"');
echo 'Submit';
echo $htmlTagObj->closeTag('div');
echo '</td>';
echo '</tr>';
echo '</table>';
echo '</center>';
echo '</form>';
echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->openTag('div', 'class="emptyDiv" style="height:30px"');
echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->openTag('div', 'class="push"');
echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->openTag('div', 'class="footer"');
echo $htmlTagObj->openTag('p', 'class=""');
echo 'If you have any questions please <u><a href="contact.php" target="_blank" class="footerLink">contact us</a>.</u><br /> <u><a href="privacy.php" target="_blank" class="footerLink">Privacy Information</a></u> | <u><a href="legalStatement.php" target="_blank" class="footerLink">Legal Statement</a></u> | <u><a href="toc.php" target="_blank" class="footerLink">Terms and Conditions</a></u> <br /> &#169; 2014 '.$footerCompanyName.' All rights reserved.';
echo $htmlTagObj->closeTag('p');
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
$(function() {
    // setTimeout() function will be fired after page is loaded
    // it will wait for 5 sec. and then will fire
    // $("#successMessage").hide() function
    setTimeout(function() {
        $("#renewNow").show('blind', {}, 6000)
    }, 6000);
	
	$( "#renDate" ).datepicker({
      showOn: "button",
      buttonImage: "images/calendar.gif",
	  buttonText:'Calendar',
      buttonImageOnly: true,
	  showButtonPanel: true,
	  closeText: "Close"
    });

	$("#submitButton").click(function()
	{
		if($("#renDate").val() == '')
		{
			alert('Please select Date');
			$("#renDate").focus();
			return false;
			
		}
		
		if($("#ddlTime").val() == '')
		{
			alert('Please select Time');
			$("#ddlTime").focus();
			return false;
			
		}
	});
});

function enableTimeDiv()
{
	if(document.getElementById('renewChkBox').checked == true)
	{
		
		$('#iframeDiv').hide();
		$('#selectTimeDiv').show();
	}
	else
	{
		$('#iframeDiv').show();
		$('#selectTimeDiv').hide();
	}
}

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