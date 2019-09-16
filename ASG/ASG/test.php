<?php
$firstName = 'Vengalrao2';
$lastName = 'Sivvannagari';
$phoneNumber = '9885868007';
$callbackDate = '06/26/2014';
$callbackTime = '10:30:00';
$timezone = 'EST';

$val = array("firstName" => trim($firstName), "lastName" => trim($lastName), "phoneNumber" => trim($phoneNumber), "callbackDate" => trim($callbackDate), "callbackTime" => trim($callbackTime),"timezone" => trim($timezone));

$option=array('trace'=>1); 

$client = new soapClient("http://atl-alt-web/ASGOBLeadCreationWebService/ASGOBLeadCreationWS.asmx?WSDL",$option); 



$results = $client->CreateContact($val); 
$expl = $results->CreateContactResult;
?>