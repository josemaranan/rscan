<?php
session_start();
unset($p);
if(isset($_REQUEST['p']))
{
	$p  = $_REQUEST['p'];   
}
$emloyeeAuthReveal = $_SESSION['emloyeeAuthReveal'];


if($p=='next')
{
	$secureSSNValue = str_pad(substr($emloyeeAuthReveal,5,4),9,'*',STR_PAD_LEFT);	
	$returnValue = 'yes||'.$secureSSNValue;
}
else
{
	$secureSSNValue = $emloyeeAuthReveal;
	$returnValue = 'no||'.$secureSSNValue;
}
echo $returnValue;
exit();
?>