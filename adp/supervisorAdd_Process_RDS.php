<?php 
//include_once($_SERVER["DOCUMENT_ROOT"].'/Include/logADPPayrollChangesClass.inc.php');
include_once($_SERVER['DOCUMENT_ROOT']."/RNetIncludes/class1/logADPPayrollChangesClass.inc.php");
$employeeeMaintenanceObj = new ADPPayroll();

/*echo '<pre>';
print_r($_REQUEST);
echo '</pre>';*/
//exit;

if($_REQUEST)
{
	$employeeID = $_REQUEST['employeeID'];
	$modifiedBy = $_SESSION['empID'];
	$modifiedDate = date('Y-m-d', strtotime('now'));
	
	$effectiveDate = date('m/d/Y',strtotime($_REQUEST['effectiveDate']));
	$supervisorID=str_replace("'","''",trim($_REQUEST['ddlSupervisors']));
	
	//$requiredArray = $employeeeMaintenanceObj->updateLogFiles($_REQUEST, $employeeID, $startDate);
	
	
	$chkQuery = "SELECT * FROM ctlEmployeeSupervisors WITH (NOLOCK) WHERE  employeeID = '".$employeeID."'  AND effectiveDate = '".$effectiveDate."'   ";//AND supApprove IS NOT NULL
	//echo $chkQuery;exit;
	$rst = $employeeeMaintenanceObj->execute($chkQuery);
	$num = $employeeeMaintenanceObj->getNumRows($rst);
	//echo $num; exit;
	mssql_free_result($rst);
	if($num == 0)
	{	
		$query = "	INSERT INTO results.dbo.[ctlEmployeeSupervisors]
						([employeeID]
						,[SupervisorID]	
						,[effectiveDate]
						,[modifiedBy]
						,[modifiedDate]
						)
					VALUES
						('$employeeID'
						,'$supervisorID'
						,'$effectiveDate'
						,'$modifiedBy'
						,'$modifiedDate')";
		
		$rst = $employeeeMaintenanceObj->execute($query);
		if($rst)
		{
			$requiredArray = $employeeeMaintenanceObj->updateLogFiles($_REQUEST, $employeeID, $effectiveDate);
			//header("Location: employeeAuthentication.php?employeeID=$employeeID");
			header("Location: index_test.php?hdnEmployeeID=".$employeeID."&adpMode=hr&adpTask=empSupervisor&error=AddSupervisorSuccess&activeLink=20");	
			exit;
		}
	}
	else
	{
		//header("Location: employeeConditionalInfo.php?employeeID=".$employeeID."&type=fullPartTime&error=AddPartTimeError");	
		header("Location: index_test.php?hdnEmployeeID=".$employeeID."&adpMode=hr&adpTask=empSupervisor&error=AddSupervisorError");	
		exit;
	}
} 

?>
