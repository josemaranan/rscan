<?php

/*
if(!empty($_REQUEST['firstName']))
{
	echo $testing = $_REQUEST['firstName'] . ' - ' . $_REQUEST['lastName'] . ' - ' . $_REQUEST['phone'] . ' - ' . $_REQUEST['renDate'] . ' - ' . $_REQUEST['ddlTime'] ;
}
else
{
	echo false;	
}
*/
$firstName = $_REQUEST['firstName'];
$lastName = $_REQUEST['lastName'];
$phoneNumber = $_REQUEST['phone'];
$callbackDate = date('m/d/Y',strtotime($_REQUEST['renDate']));
$callbackTime = $_REQUEST['ddlTime'];
$timezone = 'EST';

$val = array("firstName" => trim($firstName), "lastName" => trim($lastName), "phoneNumber" => trim($phoneNumber), "callbackDate" => trim($callbackDate), "callbackTime" => trim($callbackTime),"timezone" => trim($timezone));

$option=array('trace'=>1); 

$client = new soapclient("http://atl-alt-web/ASGOBLeadCreationWebService/ASGOBLeadCreationWS.asmx?WSDL",$option); 


$results = $client->CreateContact($val); 
$expl = $results->CreateContactResult;

echo $expl;
?>