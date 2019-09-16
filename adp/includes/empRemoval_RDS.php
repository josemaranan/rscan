<?php
unset($sqlQry);
unset($rstQry);
unset($rowQry);
unset($historyCount);
$sqlQry = " SELECT 
				COUNT(*) AS recCnt 
			FROM 
				RNet.dbo.prmEmployeeCareerHistory WITH (NOLOCK) 
			WHERE 
				employeeID = $employeeID ";
$rstQry = $employeeeMaintenanceObj->execute($sqlQry);
while($rowQry = mssql_fetch_assoc($rstQry))
{
	$historyCount = $rowQry['recCnt'];
}
//echo $employData[0]['hireDate'];exit;
if($historyCount>1)
{
	unset($sqlQry);
	unset($rstQry);
	unset($rowQry);
	unset($maxTermDate);
	$sqlQry = " SELECT 
					MAX(termDate) AS maxTermDate 
				FROM 
					RNet.dbo.prmEmployeeCareerHistory WITH (NOLOCK) 
				WHERE 
					employeeID = $employeeID ";
	$rstQry = $employeeeMaintenanceObj->execute($sqlQry);
	while($rowQry = mssql_fetch_assoc($rstQry))
	{
		$maxTermDate = $rowQry['maxTermDate'];
	}
}

echo $htmlTagObj->openTag('div','id="topHeading" class="outer"');
echo $topLevelHeading;
echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->openTag('div','id="businessRuleHeading" class="outer"');
echo 'Remove no-show';
echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->openTag('div','class="outer" id="emptyDiv"');
echo $htmlTagObj->closeTag('div');

$employeeeMaintenanceObj->getTopLevelEmployeeInfo();

unset($payrollHours);
$payrollHours =  $employeeeMaintenanceObj->getEmployeePayrollHours($employeeID, $employData[0]['hireDate']);

if(!$payrollHours)
{
	// form starts here
	$htmlForm->action = '';
	$htmlForm->name = 'employeeRemovalinfo';
	$htmlForm->id = 'employeeRemovalinfo';
	$htmlForm->method = 'post';
	echo $htmlForm->startForm();
	
	echo $htmlTagObj->openTag('div','id="blue_button" style="width:auto;" class="outer"');
	echo '<a href="#" onclick="return removeEmployeeDetails(); return false;"/>Remove no-show</a>';
	echo $htmlTagObj->closeTag('div');
	
	// hidden values
	$htmlTextElement->type = 'hidden';
	$htmlTextElement->name = 'hdnEmployeeID';
	$htmlTextElement->id = 'hdnEmployeeID';
	$htmlTextElement->value = $employeeID;
	echo $htmlTextElement->renderHtml();
	$htmlTextElement->resetProperties();
	
	$htmlTextElement->type = 'hidden';
	$htmlTextElement->name = 'hdnEmpLastName';
	$htmlTextElement->id = 'hdnEmpLastName';
	$htmlTextElement->value = $employData[0]['lastName'];
	echo $htmlTextElement->renderHtml();
	$htmlTextElement->resetProperties();
	
	$htmlTextElement->type = 'hidden';
	$htmlTextElement->name = 'hdnEmpFirstName';
	$htmlTextElement->id = 'hdnEmpFirstName';
	$htmlTextElement->value = $employData[0]['firstName'];
	echo $htmlTextElement->renderHtml();
	$htmlTextElement->resetProperties();
	
	$htmlTextElement->type = 'hidden';
	$htmlTextElement->name = 'hdnHireDate';
	$htmlTextElement->id = 'hdnHireDate';
	$htmlTextElement->value = $employData[0]['hireDate'];
	echo $htmlTextElement->renderHtml();
	$htmlTextElement->resetProperties();

	
    if($historyCount>1)
    {
		$htmlTextElement->type = 'hidden';
		$htmlTextElement->name = 'hdnTermDate';
		$htmlTextElement->id = 'hdnTermDate';
		$htmlTextElement->value = date('m/d/Y',strtotime($maxTermDate));
		echo $htmlTextElement->renderHtml();
		$htmlTextElement->resetProperties();
    
    }
	
	$htmlTextElement->type = 'hidden';
	$htmlTextElement->name = 'hdnHistoryCount';
	$htmlTextElement->id = 'hdnHistoryCount';
	$htmlTextElement->value = $historyCount;
	echo $htmlTextElement->renderHtml();
	$htmlTextElement->resetProperties();
	echo $htmlForm->endForm();
    
}
else
{
	echo $htmlTagObj->openTag('div','style="color:#F00;"');
	echo 'This employee cannot be removed because he or she has payroll hours or a bonus amount.';
	echo $htmlTagObj->closeTag('div');
}
?>