<?php
session_start();

$decodeType = $_REQUEST['decodeType'];
$statusType = $_REQUEST['statusType'];
$emloyeeSSSNReveal = $_SESSION['emloyeeSSSNReveal'];
$emloyeeTaxIDReveal = $_SESSION['emloyeeTaxIDReveal'];

if($decodeType == 1)
{
	if($statusType == 1)
	{
		$returnValue = '1||no||'.$emloyeeSSSNReveal;
	}
	else
	{
		//$secureSSSNValue = str_pad(substr($emloyeeSSSNReveal,6,4),10,'*',STR_PAD_LEFT);	
		$secureSSSNValue = str_repeat('*', strlen($emloyeeSSSNReveal) - 4) . substr($emloyeeSSSNReveal, -4);
		$returnValue = '1||yes||'.$secureSSSNValue;
	}
}
elseif($decodeType == 2)
{
	if($statusType == 1)
	{
		$returnValue = '2||no||'.$emloyeeTaxIDReveal;
	}
	else
	{
		//$secureTaxIDReveal = str_pad(substr($emloyeeTaxIDReveal,5,4),9,'*',STR_PAD_LEFT);	
		$secureTaxIDReveal = str_repeat('*', strlen($emloyeeTaxIDReveal) - 4) . substr($emloyeeTaxIDReveal, -4);
		$returnValue = '2||yes||'.$secureTaxIDReveal;
	}
}
else
{
	$returnValue = false;
}
echo $returnValue;
exit();
?>