<?php
include_once($_SERVER["DOCUMENT_ROOT"].'/Include/logADPPayrollChangesClass.inc.php');
$employeeeMaintenanceObj = new ADPPayroll();

$selPosition = $_REQUEST['selPosition'];
$selectedLoc = $_REQUEST['selectedLoc'];

$returnFlag = 'EXISTS';

unset($sqlCountry);
unset($rstCountry);
unset($rowCountry);
$sqlCountry = " SELECT country FROM ctlLocations WITH (NOLOCK) WHERE location = '$selectedLoc' ";
//echo $sqlCountry;exit;
$rstCountry = $employeeeMaintenanceObj->ExecuteQuery($sqlCountry);
while($rowCountry = mssql_fetch_assoc($rstCountry))
{
	$country = $rowCountry['country'];
}
mssql_free_result($rstCountry);

if($country=='United States of America')
{
	unset($sqlADPJCCheck);
	unset($rstADPJCCheck);
	unset($rowADPJCCheck);
	$sqlADPJCCheck = " SELECT adpJobCode FROM ctlPositions WITH (NOLOCK) WHERE positionID = '$selPosition' ";
	//echo $sqlADPJCCheck;exit;
	$rstADPJCCheck = $employeeeMaintenanceObj->ExecuteQuery($sqlADPJCCheck);
	while($rowADPJCCheck = mssql_fetch_assoc($rstADPJCCheck))
	{
		$adpJobCode = $rowADPJCCheck['adpJobCode'];
	}
	mssql_free_result($rstADPJCCheck);
	if(empty($adpJobCode) || $adpJobCode=='')
	{
		$returnFlag = 'NOTEXISTS';
	}
}
echo $returnFlag;
?>