<?php
include_once($_SERVER['DOCUMENT_ROOT']."/Include/logADPPayrollChangesClass.inc.php");
//$employeeeMaintenanceObj = new ClassQuery();

include_once($_SERVER['DOCUMENT_ROOT']."/adp/includes/adpClassFile.inc.php");
$employeeeMaintenanceObj = new ADPEmployeeClass();

/*echo '<pre>';
print_r($_REQUEST);
echo '</pre>';
exit;*/
$employeeID = $_REQUEST['hdnEmployeeID'];
$hireDate = date('m/d/Y',strtotime($_REQUEST['hdnHireDate']));
$careerRecCount = $_REQUEST['hdnHistoryCount'];
$empFstName = $_REQUEST['hdnEmpFirstName'];
$empLstName = $_REQUEST['hdnEmpLastName'];
$modifiedBy = $employeeeMaintenanceObj->UserDetails->User;
$modifiedDate = date('m/d/Y H:i:s');

unset($sqlQry);
unset($rstQry);
unset($rowQry);
unset($evolvUserName);
$sqlQry = " SELECT userName FROM ctlEmployeeApplications WITH (NOLOCK) WHERE employeeID = $employeeID AND applicationName = 'Evolv' ";
$rstQry = $employeeeMaintenanceObj->ExecuteQuery($sqlQry);
while($rowQry = mssql_fetch_assoc($rstQry))
{
	$evolvUserName = $rowQry['userName'];
}
unset($sqlQry);
unset($rowQry);
mssql_free_result($rstQry);

if($careerRecCount>1)
{
	if(!empty($_REQUEST['hdnTermDate']))
	{
		$prevTermDate = date('m/d/Y',strtotime($_REQUEST['hdnTermDate']));
	}
}
else
{
	$filterPart = " ";
}
unset($sqlTransQry);
unset($rstTransQry);

if($careerRecCount>1)
{
	$sqlTransQry = " BEGIN TRANSACTION employeeDetailsRemoval";
	
	//RNet.dbo.prmEmployeeCareerHistory 
	$sqlTransQry .= "
	DELETE FROM RNet.dbo.prmEmployeeCareerHistory WHERE employeeID = ".$employeeID." AND hireDate = '".$hireDate."'
	IF(@@ERROR <> 0) BEGIN PRINT 'FAILED TO DELETE FROM prmEmployeeCareerHistory' ROLLBACK TRANSACTION END  ";
	
	//RNet.dbo.prmEmployeeTimeClockEntries
	$sqlTransQry .= "
	DELETE FROM RNet.dbo.prmEmployeeTimeClockEntries WHERE employeeID = ".$employeeID." AND startTime >= '".$prevTermDate."'
	IF(@@ERROR <> 0) BEGIN PRINT 'FAILED TO DELETE FROM prmEmployeeTimeClockEntries' ROLLBACK TRANSACTION END ";
	
	//RNet.dbo.prmEmployeePayrollLocations
	$sqlTransQry .= "
	DELETE FROM RNet.dbo.prmEmployeePayrollLocations WHERE employeeID = ".$employeeID." AND effectiveDate >= '".$prevTermDate."'
	IF(@@ERROR <> 0) BEGIN PRINT 'FAILED TO DELETE FROM prmEmployeePayrollLocations' ROLLBACK TRANSACTION END ";
	
	//RNet.dbo.prmEmployeePositionLocations 
	$sqlTransQry .= "
	DELETE FROM RNet.dbo.prmEmployeePositionLocations WHERE employeeID = ".$employeeID." AND effectiveDate >= '".$prevTermDate."'
	IF(@@ERROR <> 0) BEGIN PRINT 'FAILED TO DELETE FROM prmEmployeePositionLocations' ROLLBACK TRANSACTION END ";
	
	//RNet.dbo.prmEmployeePositionClients 
	$sqlTransQry .= "
	DELETE FROM RNet.dbo.prmEmployeePositionClients WHERE employeeID = ".$employeeID." AND effectiveDate >= '".$prevTermDate."'
	IF(@@ERROR <> 0) BEGIN PRINT 'FAILED TO DELETE FROM prmEmployeePositionClients' ROLLBACK TRANSACTION END ";
	
	//results.dbo.ctlEmployeeStatuses 
	$sqlTransQry .= "
	DELETE FROM results.dbo.ctlEmployeeStatuses WHERE employeeID = ".$employeeID." AND effectiveDate >= '".$hireDate."'
	IF(@@ERROR <> 0) BEGIN PRINT 'FAILED TO DELETE FROM ctlEmployeeStatuses' ROLLBACK TRANSACTION END ";
	
	//results.dbo.ctlEmployeePayrollRates 
	$sqlTransQry .= "
	DELETE FROM results.dbo.ctlEmployeePayrollRates WHERE employeeID = ".$employeeID." AND startDate >= '".$hireDate."'
	IF(@@ERROR <> 0) BEGIN PRINT 'FAILED TO DELETE FROM ctlEmployeePayrollRates' ROLLBACK TRANSACTION END ";
	
	/* 
	//results.dbo.ctlEmployeeAddresses 
	$sqlTransQry .= "
	DELETE FROM results.dbo.ctlEmployeeAddresses WHERE employeeID = ".$employeeID." AND effectiveDate >= '".$prevTermDate."'
	IF(@@ERROR <> 0) BEGIN PRINT 'FAILED TO DELETE FROM ctlEmployeeAddresses' ROLLBACK TRANSACTION END ";
	
	//results.dbo.ctlEmployeeEmergencyContactInformation 
	$sqlTransQry .= "
	DELETE FROM results.dbo.ctlEmployeeEmergencyContactInformation WHERE employeeID = ".$employeeID." AND effectiveDate >= '".$prevTermDate."'
	IF(@@ERROR <> 0) BEGIN PRINT 'FAILED TO DELETE FROM ctlEmployeeEmergencyContactInformation' ROLLBACK TRANSACTION END ";
	
	//results.dbo.ctlEmployeeEmailAddresses 
	$sqlTransQry .= "
	DELETE FROM results.dbo.ctlEmployeeEmailAddresses WHERE employeeID = ".$employeeID." AND effectiveDate >= '".$prevTermDate."'
	IF(@@ERROR <> 0) BEGIN PRINT 'FAILED TO DELETE FROM ctlEmployeeEmailAddresses' ROLLBACK TRANSACTION END ";
	
	
	//results.dbo.ctlEmployeePhones 
	$sqlTransQry .= "
	DELETE FROM results.dbo.ctlEmployeePhones WHERE employeeID = ".$employeeID." AND effectiveDate >= '".$prevTermDate."'
	IF(@@ERROR <> 0) BEGIN PRINT 'FAILED TO DELETE FROM ctlEmployeePhones' ROLLBACK TRANSACTION END ";*/
	
	//results.dbo.ctlEmployeeTrainingByDays 
	$sqlTransQry .= "
	DELETE FROM results.dbo.ctlEmployeeTrainingByDays WHERE employeeID = ".$employeeID." AND trainingDate > '".$prevTermDate."'
	IF(@@ERROR <> 0) BEGIN PRINT 'FAILED TO DELETE FROM ctlEmployeeTrainingByDays' ROLLBACK TRANSACTION END ";
	
	//results.dbo.ctlEmployeeSupervisors 
	$sqlTransQry .= "
	DELETE FROM results.dbo.ctlEmployeeSupervisors WHERE employeeID = ".$employeeID." AND effectiveDate >= '".$hireDate."'
	IF(@@ERROR <> 0) BEGIN PRINT 'FAILED TO DELETE FROM ctlEmployeeSupervisors' ROLLBACK TRANSACTION END ";
	
	//results.dbo.ctlEmployeeApplications 
	$sqlTransQry .= "
	DELETE FROM results.dbo.ctlEmployeeApplications WHERE employeeID = ".$employeeID." AND applicationName = 'AD'
	IF(@@ERROR <> 0) BEGIN PRINT 'FAILED TO DELETE FROM ctlEmployeeApplications' ROLLBACK TRANSACTION END ";
	
	//results.dbo.ctlEmployeePositions 
	$sqlTransQry .= "
	DELETE FROM results.dbo.ctlEmployeePositions WHERE employeeID = ".$employeeID." AND effectiveDate >= '".$hireDate."'
	IF(@@ERROR <> 0) BEGIN PRINT 'FAILED TO DELETE FROM ctlEmployeePositions' ROLLBACK TRANSACTION END ";
	
	//rnet.dbo.prmEmployeeNotifications 
	$sqlTransQry .= "
	DELETE FROM Rnet.dbo.prmEmployeeNotifications  WHERE employeeID = ".$employeeID." AND DateCreated >= '".$prevTermDate."'
	IF(@@ERROR <> 0) BEGIN PRINT 'FAILED TO DELETE FROM prmEmployeeNotifications' ROLLBACK TRANSACTION END ";
	
	//results.dbo.ctlEmployeeTrainings  
	$sqlTransQry .= "
	DELETE FROM results.dbo.ctlEmployeeTrainings WHERE employeeID = ".$employeeID." AND assignedDate > '".$prevTermDate."'
	IF(@@ERROR <> 0) BEGIN PRINT 'FAILED TO DELETE FROM ctlEmployeeTrainings' ROLLBACK TRANSACTION END ";
	
	//RNet.dbo.logADPNewHires 
	$sqlTransQry .= "
	DELETE FROM RNet.dbo.logADPNewHires WHERE employeeID = ".$employeeID."  AND effectiveDate > '".$hireDate."' 
	IF(@@ERROR <> 0) BEGIN PRINT 'FAILED TO DELETE FROM logADPNewHires' ROLLBACK TRANSACTION END ";
	
	//RNet.dbo.logADPPayrollChanges 
	$sqlTransQry .= "
	DELETE FROM RNet.dbo.logADPPayrollChanges  WHERE employeeID = ".$employeeID." AND effectiveDate > '".$prevTermDate."' 
	IF(@@ERROR <> 0) BEGIN PRINT 'FAILED TO DELETE FROM logADPPayrollChanges' ROLLBACK TRANSACTION END   ";
	
	//rnet.dbo.prmEmployeeDetails   
	$sqlTransQry .= "
	DELETE FROM RNet.dbo.prmEmployeeDetails  WHERE employeeID = ".$employeeID." AND effectiveDate > '".$prevTermDate."' 
	IF(@@ERROR <> 0) BEGIN PRINT 'FAILED TO DELETE FROM prmEmployeeDetails' ROLLBACK TRANSACTION END ";
	
	$sqlTransQry .= "
	INSERT INTO RNet.dbo.prmEmployeeNoCallNoShows_Removed (employeeID, firstName, lastName, evolvID, modifiedBy, modifiedDate) VALUES (".$employeeID.", '".$empFstName."', '".$empLstName."',";
	
	if(!empty($evolvUserName))
	{
		$sqlTransQry .=  " '".$evolvUserName."', " ;
	}
	else
	{
		$sqlTransQry .= " NULL, ";
	}
	
	$sqlTransQry .= "																															   ".$modifiedBy.", '".$modifiedDate."')
	IF(@@ERROR <> 0) BEGIN PRINT 'FAILED TO INSERT INTO prmEmployeeNoCallNoShows_Removed' ROLLBACK TRANSACTION END ";
}
else
{
	$sqlTransQry = " BEGIN TRANSACTION employeeDetailsRemoval";

	//RNet.dbo.prmEmployeeCareerHistory 
	$sqlTransQry .= "
	DELETE FROM RNet.dbo.prmEmployeeCareerHistory WHERE employeeID = ".$employeeID." 
	IF(@@ERROR <> 0) BEGIN PRINT 'FAILED TO DELETE FROM prmEmployeeCareerHistory' ROLLBACK TRANSACTION END  ";
	
	//RNet.dbo.prmEmployeeTimeClockEntries
	$sqlTransQry .= "
	DELETE FROM RNet.dbo.prmEmployeeTimeClockEntries WHERE employeeID = ".$employeeID." 
	IF(@@ERROR <> 0) BEGIN PRINT 'FAILED TO DELETE FROM prmEmployeeTimeClockEntries' ROLLBACK TRANSACTION END ";
	
	//RNet.dbo.prmEmployeePayrollLocations
	$sqlTransQry .= "
	DELETE FROM RNet.dbo.prmEmployeePayrollLocations WHERE employeeID = ".$employeeID." 
	IF(@@ERROR <> 0) BEGIN PRINT 'FAILED TO DELETE FROM prmEmployeePayrollLocations' ROLLBACK TRANSACTION END ";
	
	//RNet.dbo.prmEmployeePositionLocations 
	$sqlTransQry .= "
	DELETE FROM RNet.dbo.prmEmployeePositionLocations WHERE employeeID = ".$employeeID." 
	IF(@@ERROR <> 0) BEGIN PRINT 'FAILED TO DELETE FROM prmEmployeePositionLocations' ROLLBACK TRANSACTION END ";
	
	//RNet.dbo.prmEmployeePositionClients 
	$sqlTransQry .= "
	DELETE FROM RNet.dbo.prmEmployeePositionClients WHERE employeeID = ".$employeeID." 
	IF(@@ERROR <> 0) BEGIN PRINT 'FAILED TO DELETE FROM prmEmployeePositionClients' ROLLBACK TRANSACTION END ";
	
	//results.dbo.ctlEmployeeStatuses 
	$sqlTransQry .= "
	DELETE FROM results.dbo.ctlEmployeeStatuses WHERE employeeID = ".$employeeID." 
	IF(@@ERROR <> 0) BEGIN PRINT 'FAILED TO DELETE FROM ctlEmployeeStatuses' ROLLBACK TRANSACTION END ";
	
	//results.dbo.ctlEmployeePayrollRates 
	$sqlTransQry .= "
	DELETE FROM results.dbo.ctlEmployeePayrollRates WHERE employeeID = ".$employeeID." 
	IF(@@ERROR <> 0) BEGIN PRINT 'FAILED TO DELETE FROM ctlEmployeePayrollRates' ROLLBACK TRANSACTION END ";
	
	//results.dbo.ctlEmployeeAddresses 
	$sqlTransQry .= "
	DELETE FROM results.dbo.ctlEmployeeAddresses WHERE employeeID = ".$employeeID." 
	IF(@@ERROR <> 0) BEGIN PRINT 'FAILED TO DELETE FROM ctlEmployeeAddresses' ROLLBACK TRANSACTION END ";
	
	//results.dbo.ctlEmployeeEmergencyContactInformation 
	$sqlTransQry .= "
	DELETE FROM results.dbo.ctlEmployeeEmergencyContactInformation WHERE employeeID = ".$employeeID." 
	IF(@@ERROR <> 0) BEGIN PRINT 'FAILED TO DELETE FROM ctlEmployeeEmergencyContactInformation' ROLLBACK TRANSACTION END ";
	
	//results.dbo.ctlEmployeeEmailAddresses 
	$sqlTransQry .= "
	DELETE FROM results.dbo.ctlEmployeeEmailAddresses WHERE employeeID = ".$employeeID." 
	IF(@@ERROR <> 0) BEGIN PRINT 'FAILED TO DELETE FROM ctlEmployeeEmailAddresses' ROLLBACK TRANSACTION END ";
	
	//results.dbo.ctlEmployeePhones 
	$sqlTransQry .= "
	DELETE FROM results.dbo.ctlEmployeePhones WHERE employeeID = ".$employeeID." 
	IF(@@ERROR <> 0) BEGIN PRINT 'FAILED TO DELETE FROM ctlEmployeePhones' ROLLBACK TRANSACTION END ";
	
	//results.dbo.ctlEmployeeTrainingByDays 
	$sqlTransQry .= "
	DELETE FROM results.dbo.ctlEmployeeTrainingByDays WHERE employeeID = ".$employeeID." 
	IF(@@ERROR <> 0) BEGIN PRINT 'FAILED TO DELETE FROM ctlEmployeeTrainingByDays' ROLLBACK TRANSACTION END ";
	
	//results.dbo.ctlEmployeeSupervisors 
	$sqlTransQry .= "
	DELETE FROM results.dbo.ctlEmployeeSupervisors WHERE employeeID = ".$employeeID."  
	IF(@@ERROR <> 0) BEGIN PRINT 'FAILED TO DELETE FROM ctlEmployeeSupervisors' ROLLBACK TRANSACTION END ";
	
	//results.dbo.ctlEmployeeApplications 
	$sqlTransQry .= "
	DELETE FROM results.dbo.ctlEmployeeApplications WHERE employeeID = ".$employeeID." 
	IF(@@ERROR <> 0) BEGIN PRINT 'FAILED TO DELETE FROM ctlEmployeeApplications' ROLLBACK TRANSACTION END ";
	
	//results.dbo.ctlEmployeePositions 
	$sqlTransQry .= "
	DELETE FROM results.dbo.ctlEmployeePositions WHERE employeeID = ".$employeeID." 
	IF(@@ERROR <> 0) BEGIN PRINT 'FAILED TO DELETE FROM ctlEmployeePositions' ROLLBACK TRANSACTION END ";
	
	//rnet.dbo.prmEmployeeNotifications 
	$sqlTransQry .= "
	DELETE FROM Rnet.dbo.prmEmployeeNotifications  WHERE employeeID = ".$employeeID." 
	IF(@@ERROR <> 0) BEGIN PRINT 'FAILED TO DELETE FROM prmEmployeeNotifications' ROLLBACK TRANSACTION END ";
	
	//results.dbo.ctlEmployeeTrainings  
	$sqlTransQry .= "
	DELETE FROM results.dbo.ctlEmployeeTrainings WHERE employeeID = ".$employeeID." 
	IF(@@ERROR <> 0) BEGIN PRINT 'FAILED TO DELETE FROM ctlEmployeeTrainings' ROLLBACK TRANSACTION END ";
	
	//ctlEmployees Foriegn Key referenced tables 
	$sqlTransQry .= "
	DELETE FROM results.dbo.ctlEmployeeLocations WHERE employeeID = ".$employeeID."  
	IF(@@ERROR <> 0) BEGIN PRINT 'FAILED TO DELETE FROM ctlEmployeeLocations' ROLLBACK TRANSACTION END 
	
	DELETE FROM results.dbo.ctlEmployeePayrollDepartments WHERE employeeID = ".$employeeID."  
	IF(@@ERROR <> 0) BEGIN PRINT 'FAILED TO DELETE FROM ctlEmployeePayrollDepartments' ROLLBACK TRANSACTION END 
	
	DELETE FROM results.dbo.ctlEmployeePayrollNonTaxables WHERE employeeID = ".$employeeID."  
	IF(@@ERROR <> 0) BEGIN PRINT 'FAILED TO DELETE FROM ctlEmployeePayrollNonTaxables' ROLLBACK TRANSACTION END
	
	DELETE FROM results.dbo.ctlEmployeePayrollTaxExemptions WHERE employeeID = ".$employeeID."  
	IF(@@ERROR <> 0) BEGIN PRINT 'FAILED TO DELETE FROM ctlEmployeePayrollTaxExemptions' ROLLBACK TRANSACTION END
	
	DELETE FROM results.dbo.ctlEmployeeRoles WHERE employeeID = ".$employeeID."  
	IF(@@ERROR <> 0) BEGIN PRINT 'FAILED TO DELETE FROM ctlEmployeeRoles' ROLLBACK TRANSACTION END
	
	DELETE FROM results.dbo.ctlEmployeeShifts WHERE employeeID = ".$employeeID."  
	IF(@@ERROR <> 0) BEGIN PRINT 'FAILED TO DELETE FROM ctlEmployeeShifts' ROLLBACK TRANSACTION END
	
	DELETE FROM results.dbo.ctlEmployeeSkills WHERE employeeID = ".$employeeID."  
	IF(@@ERROR <> 0) BEGIN PRINT 'FAILED TO DELETE FROM ctlEmployeeSkills' ROLLBACK TRANSACTION END
	
	DELETE FROM results.dbo.ctlEmployeeSupervisors WHERE supervisorID = ".$employeeID."  
	IF(@@ERROR <> 0) BEGIN PRINT 'FAILED TO DELETE FROM ctlEmployeeSupervisors' ROLLBACK TRANSACTION END ";
	
	
	//RNet.dbo.logADPNewHires 
	$sqlTransQry .= "
	DELETE FROM RNet.dbo.logADPNewHires WHERE employeeID = ".$employeeID."  
	IF(@@ERROR <> 0) BEGIN PRINT 'FAILED TO DELETE FROM logADPNewHires' ROLLBACK TRANSACTION END ";
	
	//RNet.dbo.logADPPayrollChanges 
	$sqlTransQry .= "
	DELETE FROM RNet.dbo.logADPPayrollChanges  WHERE employeeID = ".$employeeID."  
	IF(@@ERROR <> 0) BEGIN PRINT 'FAILED TO DELETE FROM logADPPayrollChanges' ROLLBACK TRANSACTION END ";
	
	//rnet.dbo.prmEmployeeDetails   
	$sqlTransQry .= "
	DELETE FROM RNet.dbo.prmEmployeeDetails  WHERE employeeID = ".$employeeID." 
	IF(@@ERROR <> 0) BEGIN PRINT 'FAILED TO DELETE FROM prmEmployeeDetails' ROLLBACK TRANSACTION END ";
	
	//ctlEmployees   
	$sqlTransQry .= "
	DELETE FROM results.dbo.ctlEmployees  WHERE employeeID = ".$employeeID." 
	IF(@@ERROR <> 0) BEGIN PRINT 'FAILED TO DELETE FROM ctlEmployees' ROLLBACK TRANSACTION END ";
	
	$sqlTransQry .= "
	INSERT INTO RNet.dbo.prmEmployeeNoCallNoShows_Removed (employeeID, firstName, lastName, evolvID, modifiedBy, modifiedDate) VALUES (".$employeeID.", '".$empFstName."', '".$empLstName."',";
	
	if(!empty($evolvUserName))
	{
		$sqlTransQry .=  " '".$evolvUserName."', " ;
	}
	else
	{
		$sqlTransQry .= " NULL, ";
	}
	
	$sqlTransQry .= "																															   ".$modifiedBy.", '".$modifiedDate."')
	IF(@@ERROR <> 0) BEGIN PRINT 'FAILED TO INSERT INTO prmEmployeeNoCallNoShows_Removed' ROLLBACK TRANSACTION END ";
}
$sqlTransQry .= " COMMIT TRANSACTION employeeDetailsRemoval ";
//echo $sqlTransQry;exit;
$rstTransQry = $employeeeMaintenanceObj->ExecuteQuery($sqlTransQry);
if($rstTransQry)
{
	//$requiredArray = $employeeeMaintenanceObj->updateLogFiles($_REQUEST, $employeeID, $effectiveDate);
	mssql_free_result($rstTransQry);
	header("Location: index.php?adpMode=hr&adpTask=empManagement&error=removeEmployee&activeLink=20");
	exit;
}
?>