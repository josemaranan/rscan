<?php

/**
 * @Description: 
 * @Author: Shore Infotech
 * @Date: 06/13/2014
 **/
 
 ini_set('display_errors','0');
 
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

$UNIQUE_ID = $_REQUEST['UNIQUE_ID'];
$FIRST_NAME = ucfirst($_REQUEST['FIRST_NAME']);
$LAST_NAME = ucfirst($_REQUEST['LAST_NAME']);

$thumbnail = $_REQUEST['THUMBNAIL'];
$landingPageUrl = $_REQUEST['LANDING_PAGE_URL'];
//$companyLogo = $_REQUEST['COMPANY_LOGO'];
//$companyLogo = 'http://www.myplan1.com/ASG/images/ss-discover.png';
$companyLogo = $_REQUEST['AGENCY_NAME'];
$footerCompanyName = 'Starkweather & Shepley Insurance Brokerage, Inc.';

$fName = $FIRST_NAME.' '.$LAST_NAME;
$phone = $_REQUEST['PHONE'];

echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
echo $htmlTagObj->openTag('html', 'xmlns="http://www.w3.org/s1999/xhtml"');
echo $htmlTagObj->openTag('head', '');
echo $htmlTagObj->openTag('meta', 'http-equiv="Content-Type" content="text/html; charset=iso-8859-1"');

echo $htmlTagObj->openTag('title', 'http-equiv="X-UA-Compatible" content="IE=EmulateIE7"');
echo $pageTitle;
echo $htmlTagObj->closeTag('title');
echo $htmlTagObj->openTag('link', 'href="'.$http.'://'.$_SERVER['HTTP_HOST'].'/ASG/css/style.css" type="text/css" rel="stylesheet"');
echo $htmlTagObj->openTag('link', 'href="'.$http.'://'.$_SERVER['HTTP_HOST'].'/ASG/css/jquery-ui.css" type="text/css" rel="stylesheet"');
echo $htmlTagObj->openTag('link', 'href="'.$http.'://'.$_SERVER['HTTP_HOST'].'/jquery-ui-1.10.3.custom" type="text/css" rel="stylesheet"');

echo $htmlTagObj->openTag('link', 'href="'.$http.'://'.$_SERVER['HTTP_HOST'].'/ASG/css/modalwindowzindex.css" type="text/css" rel="stylesheet"');
echo $htmlTagObj->openTag('script', 'language="javascript" src="'.$http.'://'.$_SERVER['HTTP_HOST'].'/ASG/js/jquery.min.js" type="text/javascript" charset="utf-8"');
echo $htmlTagObj->closeTag('script');
echo $htmlTagObj->openTag('script', 'language="javascript" src="'.$http.'://'.$_SERVER['HTTP_HOST'].'/ASG/js/jquery-ui.min.js" type="text/javascript" charset="utf-8"');
echo $htmlTagObj->closeTag('script');

echo $htmlTagObj->openTag('script', 'language="javascript" src="'.$http.'://'.$_SERVER['HTTP_HOST'].'/ASG/js/scripts.js" type="text/javascript" charset="utf-8"');
echo $htmlTagObj->closeTag('script');

echo $htmlTagObj->closeTag('head');
echo $htmlTagObj->openTag('body', '');
echo $htmlTagObj->openTag('div', 'class="mainDiv"');

echo $htmlTagObj->openTag('div', 'class="headDiv"');
echo $htmlTagObj->openTag('div', 'class="bodyDiv"');
echo $htmlTagObj->openTag('div', 'class="bodyDivBlue"');
echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->openTag('div', 'class="logoDiv" style="text-align:center;"');
//echo $htmlTagObj->imgTag($companyLogo, 'align="center"');
echo '<h1>'.$companyLogo.'</h1>';
echo $htmlTagObj->closeTag('div');
echo $htmlTagObj->closeTag('div');
echo $htmlTagObj->closeTag('div');
