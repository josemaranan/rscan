<?php 
//include_once($_SERVER["DOCUMENT_ROOT"].'/Include/logADPPayrollChangesClass.inc.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/RNetIncludes/lib/RDSData/DbConfig.php');
include_once($_SERVER['DOCUMENT_ROOT']."/RNetIncludes/class1/logADPPayrollChangesClass.inc.php");
$employeeeMaintenanceObj = new ADPPayroll();

/*echo '<pre>';
print_r($_REQUEST);
echo '</pre>';*/
//exit;

if($_REQUEST)
{
	$employeeID = $_POST["employeeID"];
	$modifiedBy = $_SESSION[empID];
	$modifiedDate = date("Y-m-d", strtotime('now'));
	
	$startDate = date('m/d/Y',strtotime($_REQUEST["startDate"]));
	$fullPartTime = $_REQUEST['ddlTimingTypes'];
	
	//$requiredArray = $employeeeMaintenanceObj->updateLogFiles($_REQUEST, $employeeID, $startDate);
	
	
	$chkQuery = "SELECT * FROM 	Rnet.dbo.[prmEmployeePayrollPartTimeFullTime]
 WITH (NOLOCK) WHERE employeeID = '$employeeID' AND effectiveDate = '$startDate'  ";

	$rst = $employeeeMaintenanceObj->execute($chkQuery);
	$num = $employeeeMaintenanceObj->getNumRows($rst);
	mssql_free_result($rst);
	//echo $num; exit;
	if($num == 0)
	{	
		
		$query = "	INSERT INTO Rnet.dbo.[prmEmployeePayrollPartTimeFullTime]
						([employeeID]
						,[effectiveDate]
						,[fullPartTime]	
						,[modifiedBy]
						,[modifiedDate]
						)
					VALUES
						('$employeeID'
						,'$startDate'
						,'$fullPartTime'
						,'$modifiedBy'
						,'$modifiedDate')";
		$rst = $employeeeMaintenanceObj->execute($query);
		if($rst)
		{
			$requiredArray = $employeeeMaintenanceObj->updateLogFiles($_REQUEST, $employeeID, $startDate);
			//header("Location: employeeAuthentication.php?employeeID=$employeeID");
			header("Location: index.php?hdnEmployeeID=".$employeeID."&adpMode=hr&adpTask=empFullTime&error=AddPartTimeSuccess&activeLink=20");	
			exit;
		}
	}
	else
	{
		//header("Location: employeeConditionalInfo.php?employeeID=".$employeeID."&type=fullPartTime&error=AddPartTimeError");	
		header("Location: index.php?hdnEmployeeID=".$employeeID."&adpMode=hr&adpTask=empFullTime&error=AddPartTimeError");	
		exit;
	}
} 

?>
