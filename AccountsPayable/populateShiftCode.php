<?php
include_once($_SERVER["DOCUMENT_ROOT"]."/Include/Global.inc.php");

if(addslashes($_GET[departmentCode]) == '5001')  //agents
{
	$shiftCode = '004';
}
else if(addslashes($_GET[departmentCode]) == '5008')  //supervisor
{
	$shiftCode = '0004';
}
else if(addslashes($_GET[departmentCode]) == '5002')  // HR
{
	$shiftCode = '0104';
}
else if(addslashes($_GET[departmentCode]) == '5006')   //QA
{
	$shiftCode = '0204';
}
else if(addslashes($_GET[departmentCode]) == '50011')  //BK
{
	$shiftCode = '0304';
}
else if(addslashes($_GET[departmentCode]) == '50012')  //Facilities
{
	$shiftCode = '0404';
}
else if(addslashes($_GET[departmentCode]) == '5012')  //WFM
{
	$shiftCode = '0504';
}
else if(addslashes($_GET[departmentCode]) == '5004')  //Misc
{
	$shiftCode = '0604';
}
else if(addslashes($_GET[departmentCode]) == '5009')  //Trainee Billable
{
	$shiftCode = '0704';
}
else if(addslashes($_GET[departmentCode]) == '5010')  //Trainee NonBillable
{
	$shiftCode = '0804';
}
else if(addslashes($_GET[departmentCode]) == '5011')  //Trainer
{
	$shiftCode = '0904';
}
else  // others
{
	$shiftCode = '';
}

print '<input type="text" id="txtShiftCode" name="txtShiftCode" value="'.$shiftCode.'" readonly="readonly" />';

?>
