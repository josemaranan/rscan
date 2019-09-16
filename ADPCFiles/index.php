<?php

/**
 * @description : ADPC Files
 * @author : Bhanu Prakash
 * @date : 
 * */

include_once($_SERVER['DOCUMENT_ROOT'] . '/RNetIncludes/class1/includeClassFilesWithoutMenu.php');
//$RDSObj = new RDSData(MSSQL_HOST, MSSQL_USERNAME, MSSQL_PASSWORD, MSSQL_DB, MSSQL_TYPE, MSSQL_PORT, '');
echo '<link href="css/index.css" type="text/css" rel="stylesheet" />';
$employeeID = ( isset($_REQUEST['ID']) ) ? $_REQUEST['ID'] : '';

echo $htmlTagObj->openTag('div','class="pageHeaderTitle"');
echo "ADPC File Generation";
echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->openTag('div','class="mainThreeLayer"');

echo $htmlTagObj->openTag('div','class="leftColumnDiv"');
echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->openTag('div','class="middleColumnDiv"');
	echo $htmlTagObj->openTag('div','class="linkWiseDataDiv" id="container"');
	echo $htmlTagObj->closeTag('div');
echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->closeTag('div');                    

echo $htmlTagObj->closeTag('body');
echo $htmlTagObj->closeTag('html');

echo $htmlTagObj->openTag('div', 'id="loaderDialog" style="display:none; text-align: center;"');
echo "<img src='/Include/images/progress.gif' /><br/>Please wait...";
echo $htmlTagObj->closeTag('div');


echo '<script language="javascript" src="js/index.js" type="text/javascript" charset="utf-8"></script>';
?>