<?php 
//ini_set('display_errors','1');
//include_once($_SERVER["DOCUMENT_ROOT"].'/Include/logADPPayrollChangesClass.inc.php');
include_once($_SERVER['DOCUMENT_ROOT']."/RNetIncludes/class1/logADPPayrollChangesClass.inc.php");
$employeeeMaintenanceObj = new ADPPayroll();
if($_REQUEST)
{
	$employeeID = $_REQUEST["employeeID"];
	$modifiedBy = $_SESSION[empID];
	$modifiedDate = date("m/d/Y");
		
	
	$pk_startDate = $_REQUEST["startDate"];
	$originalStartDate = $_REQUEST['originalStartDate'];
	$fullPartTime = $_REQUEST['ddlTimingTypes'];
	$effectiveDate = date('m/d/Y',strtotime($pk_startDate));
	
	//echo $employeeID;
	//echo $pk_startDate;
	if($effectiveDate==$originalStartDate)
	{
			$chkQuery = "SELECT * FROM 	Rnet.dbo.[prmEmployeePayrollPartTimeFullTime]
		 WITH (NOLOCK) WHERE employeeID = '$employeeID' AND effectiveDate = '$pk_startDate' AND fullPartTime = '$fullPartTime' ";
	}
	else
	{
			$chkQuery = "SELECT * FROM 	Rnet.dbo.[prmEmployeePayrollPartTimeFullTime]
		 WITH (NOLOCK) WHERE employeeID = '$employeeID' AND effectiveDate = '$pk_startDate' ";
	}
	//echo $chkQuery;exit;
	$rst = $employeeeMaintenanceObj->execute($chkQuery);
	$num =$employeeeMaintenanceObj->getNumRows($rst);
	mssql_free_result($rst);
	if($num == 0)
	{
		if(!empty($employeeID))
		{
			$query = "	UPDATE 
							Rnet.dbo.[prmEmployeePayrollPartTimeFullTime]
					   	SET 
							[effectiveDate] = '$pk_startDate' 
							,[fullPartTime] = '$fullPartTime'
							,[modifiedBy] = '$modifiedBy'
							,[modifiedDate] = '$modifiedDate'
						WHERE 
							[employeeID] = $employeeID
							and effectiveDate= '$originalStartDate'";
			//echo $query;exit;				
			$rstQry = $employeeeMaintenanceObj->execute($query);
			if($rstQry)
			{
				mssql_free_result($rstQry);
				$requiredArray = $employeeeMaintenanceObj->updateLogFiles($_REQUEST, $employeeID, $effectiveDate);
				header("Location: index_test.php?hdnEmployeeID=".$employeeID."&adpMode=hr&adpTask=empFullTime&error=EditPartTimeSuccess&activeLink=20");
				//header("Location: employeeAuthentication.php?employeeID=$employeeID");
				//exit;
				
			}

		}
	
	}
	else
	{
		//header("Location: employeeConditionalInfo.php?employeeID=".$employeeID."&startDate=".$pk_startDate."&type=fullPartTime&error=EditPartTimeError");	
		header("Location: index_test.php?hdnEmployeeID=".$employeeID."&fullTimePartTimeEffectiveDate=".$pk_startDate."&adpMode=hr&adpTask=empFullTimeEdit&error=EditPartTimeError");
		
		
		exit;
	}
	
}
?>