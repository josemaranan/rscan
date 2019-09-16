<?php
include_once($_SERVER['DOCUMENT_ROOT']."/Include/logADPPayrollChangesClass.inc.php");
$employeeeMaintenanceObj = new ADPPayroll();

$ssnConf = $_REQUEST['ssnConf'];

$returnFlag = 'NOTEXISTS';

unset($sqlSSNCheck);
unset($rstSSNCheck);
unset($rowSSNCheck);
$sqlSSNCheck = " SELECT COUNT(*) as ssnCount FROM ctlEmployees WITH  (NOLOCK) WHERE common.dbo.fn_rnetV3_Decrypt(secureSSN,'".DBMS_PASSWORD."') = '$ssnConf' ";
//echo $sqlSSNCheck;exit;
$rstSSNCheck = $employeeeMaintenanceObj->ExecuteQuery($sqlSSNCheck);
while($rowSSNCheck = mssql_fetch_assoc($rstSSNCheck))
{
	$ssnCount = $rowSSNCheck['ssnCount'];
}

if($ssnCount>0)
{
	$returnFlag = 'EXISTS';
}
echo $returnFlag;
?>