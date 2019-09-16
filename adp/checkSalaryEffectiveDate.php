<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/RNetIncludes/lib/RDSData/DbConfig.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/RNetIncludes/class1/logADPPayrollChangesClass.inc.php');
//include_once($_SERVER['DOCUMENT_ROOT']."/Include/logADPPayrollChangesClass.inc.php");
$employeeeMaintenanceObj = new ADPPayroll();

$selDate = $_REQUEST['selDate'];
$selLoc = $_REQUEST['selLoc'];

$returnFlag = 'false';

unset($sqlEffDateCheck);
unset($rstEffDateCheck);
unset($rowEffDateCheck);
$sqlEffDateCheck = " 
IF OBJECT_ID('tempdb.dbo.#tempCurPayPeriod') IS NOT NULL DROP TABLE #tempCurPayPeriod
CREATE TABLE #tempCurPayPeriod
(
	startDate DATETIME NULL,
	endDate DATETIME NULL
)
INSERT INTO #tempCurPayPeriod
SELECT startDate,endDate FROM ctlLocationPayDateSchedules WITH (NOLOCK) WHERE location = '$selLoc' AND GETDATE() BETWEEN startDate AND endDate

SELECT COUNT(*) AS dateCount FROM #tempCurPayPeriod WITH (NOLOCK) WHERE ('".$selDate."' BETWEEN startDate AND endDate) OR '".$selDate."'>GETDATE() ";
//echo $sqlEffDateCheck;exit;
$rstEffDateCheck = $employeeeMaintenanceObj->execute($sqlEffDateCheck);
while($rowEffDateCheck = mssql_fetch_assoc($rstEffDateCheck))
{
	$dateCount = $rowEffDateCheck['dateCount'];
}

if($dateCount>0)
{
	$returnFlag = 'true';
}
echo $returnFlag;
?>