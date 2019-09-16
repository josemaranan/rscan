<?php

session_start();

include_once($_SERVER['DOCUMENT_ROOT'] . '/RNetIncludes/class1/common.config.inc.php');
$RDSObj = new RDSData(MSSQL_HOST, MSSQL_USERNAME, MSSQL_PASSWORD, MSSQL_DB, MSSQL_TYPE, MSSQL_PORT, '');

$location = $_POST['location'];
$selected = $_POST['selected'];

$commonListBox->name 			= 'txtADPEffecDate';
$commonListBox->id 				= 'txtADPEffecDate';
//$commonListBox->AddRow('', 'Please choose');
if($location != '')
{
	$resourceId = $RDSObj->execute("EXEC Rnet.dbo.[report_spGetOpenPayPeriods] '".$location."', '10'");
	if($RDSObj->getNumRows($resourceId) > 0)
	{
		$payDates = $RDSObj->bindingInToArray($resourceId);
		foreach($payDates as $idx => $row)
		{
			$kv = date("m/d/Y",strtotime($row['paydate']));
			$commonListBox->AddRow($kv,$kv);
		}
		
	}
}
else
{
	$commonListBox->AddRow(date("m/d/Y"), date("m/d/Y"));
}

$commonListBox->selectedItem 	= $selected;

$txtADPEffecDate 					= $commonListBox->display();
$commonListBox->resetProperties();
echo $txtADPEffecDate;
exit();

?>