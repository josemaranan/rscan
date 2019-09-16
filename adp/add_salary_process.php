<?php 
//include_once($_SERVER['DOCUMENT_ROOT']."/Include/logADPPayrollChangesClass.inc.php");
include_once($_SERVER['DOCUMENT_ROOT'] . '/RNetIncludes/lib/RDSData/DbConfig.php');
include_once($_SERVER['DOCUMENT_ROOT']."/RNetIncludes/class1/logADPPayrollChangesClass.inc.php");
$employeeeMaintenanceObj = new ADPPayroll();

/*echo '<pre>';
print_r($_REQUEST);
echo '</pre>';
exit;*/

if($_REQUEST)
{
	$employeeID = $_POST['employeeID'];
	$modifiedBy = $_SESSION['empID'];
	$modifiedDate = date("Y-m-d", strtotime('now'));
	
	$startDate = date('m/d/Y',strtotime($_REQUEST['startDate']));
	$Paytype = $_REQUEST['Paytype'];
	$Amount = $_REQUEST['Amount'];
	$Amount2 = 0;
	
	$payChangeR = $_REQUEST['ddlPayChangeReason'];
	$compEntryDate = $_REQUEST['txtCompEntryDate'];
	
	
	$fullPartTime = $_REQUEST['ddlTimingTypes'];
	$payrollLocation = $_REQUEST["hdnPayrollLocation"];
	
	$changeReason = $_REQUEST['txaReason'];
	//echo 'ccccccccccccc'.$payChangeR;
	
	
	if($payrollLocation == '384'  || $payrollLocation == '75' || $payrollLocation == '72')
	{
		$contractMonthSalary = $_REQUEST['txtContractMonthSalary'];
	}
	else
	{
		$contractMonthSalary = 'NULL';
	}
	
	
	$chkQuery = " SELECT * FROM ctlEmployeePayrollRates WITH (NOLOCK) WHERE employeeID = '$employeeID' AND startDate = '$startDate' ";
	//echo $chkQuery;exit;
	$rst = $employeeeMaintenanceObj->execute($chkQuery);
	$num =$employeeeMaintenanceObj->getNumRows($rst);
	mssql_free_result($rst);
	if($num == 0)
	{	
		$query = "	INSERT INTO [ctlEmployeePayrollRates]
						([employeeID]
						,[startDate]
						,[payType]
						,[amount]
						,[amount2]
						,[payChangeReason]
						,[compEntryDate]
						,[changeReason]
						,[contractedMonthlySalary]
						,[modifiedBy]
						,[modifiedDate])
					VALUES
						('$employeeID'
						,'$startDate'
						,'$Paytype'
						,'$Amount'
						,'$Amount2' ";
			if(!empty($payChangeR))
			{
					$query .=  " ,'$payChangeR' ";
			}
			else
			{
					$query .= " , NULL" ;
					
			}
			
			if(!empty($compEntryDate))
			{
					$query .=  " ,'$compEntryDate' ";
			}
			else
			{
					$query .= " , NULL" ;
					
			}
			
			if(!empty($changeReason))
			{
				$query .=  " ,'$changeReason' ";
			}
			else
			{
				$query .= " , NULL" ;
					
			}
				
				$query .= "	,$contractMonthSalary
							,'$modifiedBy'
							,'$modifiedDate')";
		
		
						
		$rst = $employeeeMaintenanceObj->execute($query);
		if($rst)
		{
			$requiredArray = $employeeeMaintenanceObj->updateLogFiles($_REQUEST, $employeeID, $startDate);
			
			if(empty($changeReason))
			{
				$changeReasonV = 'N';
				
			}
			else 
			{
				$adpcQuery = "EXEC Rnet.dbo.[rnet_spCheckForHistoricalChnagetoADP] '".$employeeID."','".$startDate."','Pay Rate'";
				$result = $employeeeMaintenanceObj->execute($adpcQuery);
				$finalResponse = mssql_result($result,0,0);
				$changeReasonV = $finalResponse;
			}
			//header("Location: employeeConditionalInfo.php?employeeID=".$employeeID."&type=salaryInfo&error=AddSalarySuccess");
			header("Location: index.php?hdnEmployeeID=".$employeeID."&changeReason=".$changeReasonV."&adpMode=hr&adpTask=empSalary&error=AddSalarySuccess&effectiveDate=".$startDate);

		exit;
		}
	}
	else
	{
		header("Location: index.php?hdnEmployeeID=".$employeeID."&adpMode=hr&adpTask=empSalary&error=AddSalaryInfoError");
		//header("Location: employeeConditionalInfo.php?employeeID=".$employeeID."&type=salaryInfo&error=AddSalaryInfoError");
		exit;
	}
} 

?>
