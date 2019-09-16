<?php 
//include_once($_SERVER['DOCUMENT_ROOT']."/Include/logADPPayrollChangesClass.inc.php");
include_once($_SERVER['DOCUMENT_ROOT'] . '/RNetIncludes/lib/RDSData/DbConfig.php');
include_once($_SERVER['DOCUMENT_ROOT']."/RNetIncludes/class1/logADPPayrollChangesClass.inc.php");
$employeeeMaintenanceObj = new ADPPayroll();
/*
echo '<pre>';
print_r($_REQUEST);
echo '</pre>';
exit;*/

if($_REQUEST)
{
	$employeeID = $_REQUEST['employeeID'];
	$modifiedBy = $_SESSION['empID'];
	$modifiedDate = date("m/d/Y");
	
	$Paytype = $_REQUEST['Paytype'];
	$Amount = $_REQUEST['Amount'];
	$Amount2 = 0;
	
	$payChangeR = $_REQUEST['ddlPayChangeReason'];
	$compEntryDate = $_REQUEST['txtCompEntryDate'];
	$changeReason = $_REQUEST['txaReason'];
	
	$pk_startDate = $_REQUEST['startDate'];
	$originalStartDate = $_REQUEST['originalStartDate'];
	$payrollLocation = $_REQUEST['hdnPayrollLocation'];
	if($payrollLocation == '384'  || $payrollLocation == '75')
	{
		$contractMonthSalary = $_REQUEST['txtContractMonthSalary'];
	}
	else
	{
		$contractMonthSalary = 'NULL';
	}
	$effectiveDate = date('m/d/Y',strtotime($pk_startDate));
	

	/*if($effectiveDate!=$originalStartDate)
	{*/
	//$chkQuery = " SELECT * FROM ctlEmployeePayrollRates WITH (NOLOCK) WHERE employeeID = '$employeeID' AND startDate = '$pk_startDate'  ";
	if($effectiveDate!=$originalStartDate)
	{
		$chkQuery = "SELECT * FROM ctlEmployeePayrollRates WITH (NOLOCK) WHERE employeeID = '$employeeID' AND startDate = '$pk_startDate' ";
		$rst = $employeeeMaintenanceObj->execute($chkQuery);
		$num = $employeeeMaintenanceObj->getNumRows($rst);
		mssql_free_result($rst);
		if($num >=1)
		{
			//header("Location: employeeConditionalInfo.php?employeeID=".$employeeID."&startDate=".$effectiveDate."&type=salaryInfo&error=AddSalaryInfoError");
			header("Location: index.php?hdnEmployeeID=".$employeeID."&adpMode=hr&adpTask=empSalary&error=AddSalaryInfoError");
			exit;
			
			
		}
	}
		
	if(!empty($employeeID))
	{
		$query = "	UPDATE 
						[ctlEmployeePayrollRates]
					SET 
						[startDate] = '$pk_startDate' 
						,[payType] = '$Paytype'
						,[amount] = '$Amount'
						,[amount2] = '$Amount2' ";
						
			if(!empty($payChangeR))
			{
				$query .=	" ,[payChangeReason] = '$payChangeR' ";
			}
			else
			{
					$query .= " ,[payChangeReason] = NULL ";
			}
			
			if(!empty($compEntryDate))
			{
				$query .=	" ,[compEntryDate] = '".$compEntryDate."' ";
			}
			else
			{
					$query .= " ,[compEntryDate] = NULL ";
			}
			if(!empty($changeReason))
			{
				$query .=	" ,[changeReason] = '".$changeReason."' ";
			}
			else
			{
				$query .= " ,[changeReason] = NULL ";
			}
			$query .=	 " ,[contractedMonthlySalary] = $contractMonthSalary
						 ,[modifiedBy] = '$modifiedBy'
						 ,[modifiedDate] = '$modifiedDate'
						 
					WHERE 
						[employeeID] = $employeeID
					AND 
						startDate= '$originalStartDate'";
		//echo $query;exit;				
		$rstQry = $employeeeMaintenanceObj->execute($query);
		if($rstQry)
		{
			mssql_free_result($rstQry);
			$requiredArray = $employeeeMaintenanceObj->updateLogFiles($_REQUEST, $employeeID, $effectiveDate);
			//header("Location: employeeAuthentication.php?employeeID=$employeeID");
			//header("Location: employeeConditionalInfo.php?employeeID=".$employeeID."&type=salaryInfo&error=EditSalarySucess");
			
			if(empty($changeReason))
			{
				$changeReasonV = 'N';
			
			}
			else
			{
				$adpcQuery = "EXEC Rnet.dbo.[rnet_spCheckForHistoricalChnagetoADP] '".$employeeID."','".$effectiveDate."','Pay Rate'";
				$result = $employeeeMaintenanceObj->execute($adpcQuery);
				$finalResponse = mssql_result($result,0,0);
				$changeReasonV = $finalResponse;
			}
			
			header("Location: index.php?hdnEmployeeID=".$employeeID."&adpMode=hr&changeReason=".$changeReasonV."&adpTask=empSalary&error=EditSalarySucess&effectiveDate=".$pk_startDate);
			exit;
			
		}
	
	}
}
?>