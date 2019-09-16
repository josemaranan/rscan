<?php
//include_once($_SERVER["DOCUMENT_ROOT"].'/Include/logADPPayrollChangesClass.inc.php');
include_once($_SERVER['DOCUMENT_ROOT']."/RNetIncludes/class1/logADPPayrollChangesClass.inc.php");
$employeeeMaintenanceObj = new ADPPayroll();
$employeeID = $_REQUEST['hdnEmployeeID'];
$effectiveDate = date('m/d/Y');
//$requiredArray = $employeeeMaintenanceObj->updateLogFiles($_REQUEST, $employeeID, $effectiveDate);

$adpClient = $_REQUEST['ddlADPClientCode'];

unset($sqlQuery);
unset($resultsSet);
unset($numRows);
unset($adpCode);
$adpFlag = false;

$sqlQuery = " SELECT adpClientCode FROM Rnet.dbo.logADPNewHires (nolock) where employeeID = '$employeeID' AND adpClientCode IS NOT NULL ";
$resultsSet = $employeeeMaintenanceObj->execute($sqlQuery);
$numRows = $employeeeMaintenanceObj->getNumRows($resultsSet);

if($numRows>0)
{
	$adpFlag = true;
}

if(!$adpFlag)
{
	
	unset($sqlQuery);
	unset($resultsSet);
	
	$sqlQuery = " UPDATE 
						Rnet.dbo.logADPNewHires
					SET
						adpClientCode = '".$adpClient."' 
					WHERE employeeID = '$employeeID' ";
	$resultsSet = $employeeeMaintenanceObj->execute($sqlQuery);

}

$updateEmps = " UPDATE 
					ctlEmployees  
				SET 
					adpClientCode = '".$adpClient."',
					modifiedBy = '".$modifiedBy."',
					modifiedDate = '".$modifiedDate."'
				WHERE 
					employeeID= '".$employeeID."' ";
//echo $updateEmps;exit;
$rstUpdateEmps = $employeeeMaintenanceObj->execute($updateEmps);
if($rstUpdateEmps)
{
	$requiredArray = $employeeeMaintenanceObj->updateLogFiles($_REQUEST, $employeeID, $effectiveDate);
	mssql_free_result($rstUpdateEmps);
	header("Location: index_test.php?hdnEmployeeID=".$employeeID."&adpMode=hr&adpTask=empClient&error=EditClientSuccess&activeLink=20");
	exit;
}

/*if($rstUpdateEmps)
{
	//header("Location: employeeAuthentication.php?employeeID=$employeeID");	
	header("Location: index.php?hdnEmployeeID=".$employeeID."&adpMode=hr&adpTask=empClient");
	exit;
}
*/

?>