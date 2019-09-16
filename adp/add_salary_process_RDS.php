<?php 
//include_once($_SERVER['DOCUMENT_ROOT']."/Include/logADPPayrollChangesClass.inc.php");
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
				
				$query .= "	,$contractMonthSalary
							,'$modifiedBy'
							,'$modifiedDate')";
		
		
						
		$rst = $employeeeMaintenanceObj->execute($query);
		if($rst)
		{
			$requiredArray = $employeeeMaintenanceObj->updateLogFiles($_REQUEST, $employeeID, $startDate);
			
			//header("Location: employeeConditionalInfo.php?employeeID=".$employeeID."&type=salaryInfo&error=AddSalarySuccess");
			header("Location: index_test.php?hdnEmployeeID=".$employeeID."&adpMode=hr&adpTask=empSalary&error=AddSalarySuccess");

		exit;
		}
	}
	else
	{
		header("Location: index_test.php?hdnEmployeeID=".$employeeID."&adpMode=hr&adpTask=empSalary&error=AddSalaryInfoError");
		//header("Location: employeeConditionalInfo.php?employeeID=".$employeeID."&type=salaryInfo&error=AddSalaryInfoError");
		exit;
	}
} 

?>
