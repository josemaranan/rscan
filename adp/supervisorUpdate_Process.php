<?php
/* Added Newly  AND supApprove IS NOT NULL   */
include_once($_SERVER["DOCUMENT_ROOT"].'/Include/logADPPayrollChangesClass.inc.php');
$employeeeMaintenanceObj = new ADPPayroll();


if($_POST){
// Escape bad characters--condCart
	$employeeID = $_GET["employeeID"];
	$supervisorID=str_replace("'","''",trim($_POST['ddlSupervisors']));
	$effectiveDate=$_POST['effectiveDate'];//.' '.date("H:i:s");
	$modifiedBy = $_SESSION[empID];
	$modifiedDate = date("m/d/Y h:i:s");
	if(isset($_REQUEST['returnPositionID']))
	{
			$positionID = $_REQUEST['returnPositionID']; 
	}
	
	if(isset($_REQUEST['returneffectiveDate']))
	{
			$startDate = $_REQUEST['returneffectiveDate']; 
	}


if(isset($_REQUEST['hdnReturnPath']))
	{
			$returnPath = $_REQUEST['hdnReturnPath'];
	}

$effectiveDateOnly = date('m/d/Y',strtotime($effectiveDate));

$requiredArray = $employeeeMaintenanceObj->updateLogFiles($_REQUEST, $employeeID, $effectiveDateOnly);

		
		$query1 = 		" SELECT count(*) FROM ctlEmployeeSupervisors WITH (NOLOCK) 
								WHERE  employeeID = '".$employeeID."' 
								AND effectiveDate = '".$effectiveDate."' 
								AND supApprove IS NOT NULL ";
		//echo $query1;
			

		//$rst1=mssql_query($query1, $db1);
		$rst1 = $employeeeMaintenanceObj->ExecuteQuery($query1);

		if($row=mssql_fetch_array($rst1)) 
		{	
			$num=$row[0];
		}
		
		if($num == '0')
		{
			if($supervisorID && $effectiveDate)
			{	
				
			$query = "Insert into ctlEmployeeSupervisors (employeeID,SupervisorID,effectiveDate,modifiedBy,modifiedDate)  values (".$employeeID.",'".$supervisorID."' , '".$effectiveDate."','".$modifiedBy."','".$modifiedDate."')";
			
			//$rst=mssql_query($query, $db);
			$rst = $employeeeMaintenanceObj->ExecuteQuery($query);
			
				if($rst)
				{
					header('Location:index.php?hdnEmployeeID='.$employeeID.'&adpMode=hr&adpTask=empPosition&error=DataIUpdate&activeLink=20');
					exit;	
				}
			}
		}
		else
		{
				//header("Location:supervisorUpdate.php?typeNew=existed&employeeID=".$employeeID);
				header('Location:index.php?hdnEmployeeID='.$employeeID.'&typeNew=existed&adpMode=hr&adpTask=empPosition');
				exit;
		}		

		


}
?>
