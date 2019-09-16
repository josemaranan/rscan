<?php
//$employeeeMaintenanceObj->setUSLocations();

//$employData = $employeeeMaintenanceObj->getEmployeeInformation();
//$employADPData = $employeeeMaintenanceObj->getEmployeeADPInformation();

unset($sqlSalaryInfo);
unset($rstSalaryInfo);
unset($rowsSalaryInfoNum);
unset($salaryInfoArray);

$sqlSalaryInfo = "SELECT 
						 a.employeeID
						,a.startDate
						,a.payType
						,a.Amount
						,a.Amount2
						,a.contractedMonthlySalary
						,b.description payChangeReason
						,c.firstName+' '+c.lastName modifiedBy
						,a.modifiedDate
					FROM 
						results.dbo.ctlEmployeePayrollRates a WITH (NOLOCK) 
					LEFT JOIN
						ctlPayChangeReasons b WITH (NOLOCK) 
					ON
						a.payChangeReason = b.reasonID
					LEFT JOIN
						ctlEmployees c WITH (NOLOCK)
					ON
						a.modifiedBy = c.employeeID
					WHERE 
						a.employeeID = '$employeeID'
					ORDER BY 
						a.startDate DESC ";
	//echo $sqlSalaryInfo;
$rstSalaryInfo = $employeeeMaintenanceObj->execute($sqlSalaryInfo);
$rowsSalaryInfoNum = mssql_num_rows($rstSalaryInfo);
if($rowsSalaryInfoNum>=1)
{
	$salaryInfoArray = $employeeeMaintenanceObj->bindingInToArray($rstSalaryInfo);
}
mssql_free_result($rstSalaryInfo);

?>
<div id="topHeading" class="outer"><?php echo $topLevelHeading;?></div>
<div id="businessRuleHeading" class="outer">View Employee Salary</div>
<div class="outer" id="emptyDiv"></div>
<?php $employeeeMaintenanceObj->getTopLevelEmployeeInfo(); ?>
<div class="outer" id="emptyDiv"></div>

<div class="scrollingdatagrid" id="scrollingdatagrid" visible="true">
<div id="topHeading">Historical Salary/Wage Information</div>
<table border="0" class="report"> 
 <thead>
<tr>
    <th>Effective Date </th>
    <th>Employee Type </th>
    <th>Compensation Rate</th>
    <?php if($payrollLocation == '384'  || $payrollLocation == '75' || $payrollLocation == '72' || $payrollLocation == '802') 
    {?>
        <th>Contracted Monthly Salary</th>
    <?php 
    } ?>
    <th>Pay Change Reason</th>
    <th>Modified By</th>
    <th>Modified Date</th>
    
</tr>
</thead>

<?php
	
if($rowsSalaryInfoNum!=0)
{
	
    foreach($salaryInfoArray as $salaryInfoArrayK=>$salaryInfoArrayV)
	{
		
		$salaryInfoStDate = date('m/d/Y',strtotime($salaryInfoArrayV['startDate']));
		$salInfoPayTye = $salaryInfoArrayV['payType'];
		$salInfoBaseWage = $salaryInfoArrayV['Amount'];
		$salInfoSecWage = $salaryInfoArrayV['Amount2'];
		$contractedMonthlySalary = $salaryInfoArrayV['contractedMonthlySalary'];
		$existPayChangeR = $salaryInfoArrayV['payChangeReason'];
		$modBy = $salaryInfoArrayV['modifiedBy'];
		$modDate = date('Y-m-d',strtotime($salaryInfoArrayV['modifiedDate']));?>
		<tr>
            <td style="text-align:center;">
            <?php 	echo $salaryInfoStDate; ?> 
			
            
            </td>
		
            <td style="text-align:left;">
            <?php
            if($salInfoPayTye==1)
            {
                echo 'Salary (Exempt)';
            } 
            else 
            {
                echo 'Hourly ( Non-Exempt)';
            }?>
            </td>
            
            <td style="text-align:center;"><?php if(!empty($salInfoBaseWage)) { echo $salInfoBaseWage;} else { echo '&nbsp;'; }?></td>
            
            <?php
            if($payrollLocation == "384"  || $payrollLocation == "75" || $payrollLocation == "72"  || $payrollLocation == "802") 
            {?>
                <td style="text-align:center;"><?php if(!empty($contractedMonthlySalary)) { echo $contractedMonthlySalary; } else { echo '&nbsp;';}?></td>
            <?php
            }?>
            
            <td style="text-align:left;"><?php if(!empty($existPayChangeR)) { echo $existPayChangeR;} else { echo '&nbsp;'; }?></td>
            
            <td style="text-align:left;"><?php if(!empty($modBy)) { echo $modBy;} else { echo '&nbsp;'; }?></td>
            
            <td style="text-align:center;"><?php if(!empty($modDate)) { echo $modDate;} else { echo '&nbsp;'; }?></td>
		</tr>
		<?php
		
	}
}
else 
{
	echo "<td colspan = 6  style='text-align:left;'>No records in the history</td>";
}
?>
</table>
        
</div>