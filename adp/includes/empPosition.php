<?php
//scrollingdatagrid
echo $htmlTagObj->openTag('script', 'language="javascript" src="https://'.$_SERVER['HTTP_HOST'].'/newRnet/js/common.js" type="text/javascript" charset="utf-8"');
echo $htmlTagObj->closeTag('script');


echo $htmlTagObj->openTag('div', 'id="topHeading" class="outer" ');
echo $topLevelHeading;
echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->openTag('div', 'id="businessRuleHeading" class="outer" ');
echo 'Position Update';
echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->openTag('div', 'id="emptyDiv" class="outer" ');
echo $htmlTagObj->closeTag('div');


echo $htmlTagObj->openTag('div', 'id="replace_main" style="display:none;"');
echo $htmlTagObj->closeTag('div');

$_REQUEST['employeeID'] = $employeeID;
$type = 'ADP';
include_once($_SERVER['DOCUMENT_ROOT'] . '/manageEmployee/includes/positionUpdate.php');
echo '<input type="hidden" id="main_menu" name="main_menu" />';

?>