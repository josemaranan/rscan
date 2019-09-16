<?php
include_once($_SERVER['DOCUMENT_ROOT']."/Include/logADPPayrollChangesClass.inc.php");
$employeeeMaintenanceObj = new ADPPayroll();

$hdnLocation = $_REQUEST['hdnLocation'];
$modifiedBy = $employeeeMaintenanceObj->UserDetails->User;

if(!empty($_REQUEST['chkemp']))
{
	unset($updateQuery);
	$curDate = date('m/d/Y');
	
	foreach($_REQUEST['chkemp'] as $key=>$empVal)
	{
		unset($empHireDate);
		unset($employeeID);
		unset($empValArray);
		unset($sqlQuery);
		unset($sqlQueryResultsSet);
		
		$empValArray = explode('##',$empVal);
		$empHireDate = $empValArray[1];
		$employeeID = $empValArray[0];
		
		
		
		$updateQuery .= " UPDATE Rnet.dbo.PrmEmployeeCareerHistory SET day1PresentDate = '".$curDate."' 
							WHERE employeeID = '".$employeeID."' AND hireDate = '".$empHireDate."' ";
							
	//	$sqlQuery = " EXEC RNet.dbo.[process_spSynchronizeEmployeeHireDate]  '".$employeeID."' , '".$empHireDate."' ,  '".$modifiedBy."' ";
		
		//$sqlQueryResultsSet = $employeeeMaintenanceObj->ExecuteQuery($sqlQuery);
		
	}
		$resultsSet = $employeeeMaintenanceObj->ExecuteQuery($updateQuery);
	

	if($resultsSet)
	{
		header('Location:index.php?activeLink=&error=day1Confirm&hdnLocation='.$hdnLocation);	
	}
}
?>