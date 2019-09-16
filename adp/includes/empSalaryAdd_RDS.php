<?php
//ini_set('display_errors','1');
//include_once($_SERVER['DOCUMENT_ROOT']."/Include/logADPPayrollChangesClass.inc.php");
include_once($_SERVER['DOCUMENT_ROOT']."/RNetIncludes/class1/logADPPayrollChangesClass.inc.php");
//include_once($_SERVER['DOCUMENT_ROOT']."/adp/includes/adpClassFile.inc.php");
include_once($_SERVER['DOCUMENT_ROOT']."/RNetIncludes/class1/adpClassFile.inc.php");
$employeeeMaintenanceObj = new ADPEmployeeClass();
include_once($_SERVER["DOCUMENT_ROOT"]."/Include/tempTableStructure.inc.php");
//include_once($_SERVER['DOCUMENT_ROOT'] . '/RNetIncludes/class/config.inc.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/RNetIncludes/class1/config.inc.php');

$lockallFields = false;
$lockFieldsStyle = '';
$firstRecordFlag = false;

$employeeID = $_REQUEST['employeeID'];
$topLevelHeading = $_REQUEST['topLevelHeading'];
$payrollLocation = $_REQUEST['emppayrollLocation'];
$hireDate = $_REQUEST['empHireDate'];
$termDate = $_REQUEST['empTermDate'];

$employeeeMaintenanceObj->setEmployeeADPInformation($employeeID);
$employADPData = $employeeeMaintenanceObj->getEmployeeADPInformation();

$empLoc = $employADPData[0]['location'];

$todayDate = date('m/d/Y');

if(empty($hireDate) || !empty($termDate) )
{
	$lockFieldsStyle = 'disabled="disabled"';
	$lockallFields = true;
}

unset($sqlGetHireDate);
unset($rstGetHireDate);
unset($empHireDate);
unset($stPayRate);
$displayPayChangeDropDown = true;
$salaryTypeSal = '';
$amountSal = '';
$amount2Sal = '0.00';
$contractedMonSal = '0.00';
	

$pos_query = "	SELECT 
						b. position 
					FROM 
						results.dbo.ctlEmployeePositions a WITH (NOLOCK) 
					JOIN 
						results.dbo.ctlPositions b WITH (NOLOCK) 
					ON 
						a.positionID=b.positionID 
					WHERE 
						employeeID = '$employeeID' 
					AND 
						endDate IS NULL ";
$pos_res = $employeeeMaintenanceObj->execute($pos_query);


$effDatesArray = $employeeeMaintenanceObj->getN_NumberofPayperiods('4' , $hireDate, $payrollLocation);

unset($sqlGetHireDate);
unset($rstGetHireDate);
unset($rowGetHireDate);
unset($chkRecCount);
	
if(!$lockallFields)
{
		$isFirstRecord = $employeeeMaintenanceObj->fnFirstRecoredInPayrollRates($employeeID , $hireDate);
}

if($isFirstRecord=='N') // so this is the first record.
{
	$firstRecordFlag = true;
	$todayDate = date('m/d/Y',strtotime($hireDate));
}


$sqlPayChangeR = "  SELECT 
						* 
					FROM 
						ctlPayChangeReasons WITH (NOLOCK) 
					WHERE 
						isActive = 'Y' ";
if($firstRecordFlag)
{
	$sqlPayChangeR .= " AND reasonID = 9 ";
}
$sqlPayChangeR .=	" ORDER BY 
						description ";


$rstPayChangeR = $employeeeMaintenanceObj->execute($sqlPayChangeR);
$rowsPayChangeRNum = $employeeeMaintenanceObj->getNumRows($rstPayChangeR);
if($rowsPayChangeRNum>=1)
{
	$payChangeRArray = $employeeeMaintenanceObj->bindingInToArray($rstPayChangeR);
}
mssql_free_result($rstPayChangeR);	


if(!empty($hireDate))
{
	$hireDateMdy = date('m/d/Y', strtotime($hireDate));	
}


echo $htmlTagObj->openTag('div','id="topHeading" class="outer"');
echo $topLevelHeading;
echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->openTag('div','id="businessRuleHeading" class="outer"');
echo 'Employment Compensation Information';
echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->openTag('div','class="outer" id="emptyDiv"');
echo $htmlTagObj->closeTag('div');
$employeeeMaintenanceObj->getTopLevelEmployeeInfo(); 

echo $htmlTagObj->openTag('div','class="scrollingdatagrid" id="scrollingdatagrid" visible="true"');
echo $htmlTagObj->openTag('div','id="adpsearchFieldSet"');


// form starts here
$htmlForm->action = 'add_salary_process_RDS.php';
$htmlForm->name = 'frmPosition';
$htmlForm->id = 'searchForm';
$htmlForm->method = 'post';
echo $htmlForm->startForm();

echo $htmlTagObj->openTag('fieldset');
echo $htmlTagObj->openTag('legend');
echo 'Add New Compensation Record';
echo $htmlTagObj->closeTag('legend');

echo $htmlTagObj->openTag('div','id="emptyDiv"');
echo $htmlTagObj->closeTag('div');

// hidden fields
$htmlTextElement->type = 'hidden';
$htmlTextElement->name = 'employeeID';
$htmlTextElement->id = 'employeeID';
$htmlTextElement->value = $employeeID;
echo $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();

// hidden fields
$htmlTextElement->type = 'hidden';
$htmlTextElement->name = 'hdnPayrollLocation';
$htmlTextElement->id = 'hdnPayrollLocation';
$htmlTextElement->value = $payrollLocation;
echo $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();

// hidden fields
$htmlTextElement->type = 'hidden';
$htmlTextElement->name = 'hdnAjaxVar';
$htmlTextElement->id = 'hdnAjaxVar';
$htmlTextElement->value = '1';
echo $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();

echo $htmlTagObj->openTag('div','style="width:70%; float:left; margin:0px; padding:0px;"');

// table starts here
$tableObj->tableId = 'adpsearchTable';
$tableObj->maxCol = 1;
$tableObj->border = 0;
$tableObj->cellSpacing = 2;
$tableObj->width = '100%';
$tableObj->tableStyle = 'border-collapse: collapse';
$tableObj->setTableClass('');
$tableObj->setTableAttr();

if(!$lockallFields && !$firstRecordFlag)
{
	$commonListBox->name = 'startDate';
	$commonListBox->id = 'startDate';
	$commonListBox->AddRow('','Please Choose');
	foreach($effDatesArray as $effDatesArrayK=>$effDatesArrayV)
	{
		$commonListBox->AddRow(date('m/d/Y',strtotime($effDatesArrayV['startDate'])), date('m/d/Y',strtotime($effDatesArrayV['startDate'])));
		
	}
	$commonListBox->selectedItem = '';
	$adpEffectiveDate = $commonListBox->convertArrayToDropDown();
	$commonListBox->resetProperties();
	
	$accessCal = false;
}
else
{		
	$htmlTextElement->type = 'text';
	$htmlTextElement->name = 'startDate';
	$htmlTextElement->id = 'startDate';
	$htmlTextElement->readonly = 'true';
	if($lockFieldsStyle != '')
	{
		$htmlTextElement->disabled = 'true';
	}
	$htmlTextElement->value = $curDate;
	$htmlTextElement->onfocus = "return MoveFocus('Amount'); return false;";
	$adpEffectiveDate =  $htmlTextElement->renderHtml();
	$htmlTextElement->resetProperties();
	
	$accessCal = true;
}
$employeeeMaintenanceObj->gethiddenValues('startDate', '' ,'results', 'ctlEmployeePayrollRates', 'startDate' , 'ctlEmployeePayrollRates#startDate');
$lbladpEffectiveDate	= $htmlTextElement->addLabel($adpEffectiveDate, 'ADP Effective Date:', '#ff0000',true);


$htmlTextElement->type = 'text';
$htmlTextElement->name = 'Amount';
$htmlTextElement->id = 'Amount';
if($lockFieldsStyle != '')
{
	$htmlTextElement->disabled = 'true';
}
$htmlTextElement->value = $amountSal;
$htmlTextElement->size = "10";
$txtAmount =  $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();

$employeeeMaintenanceObj->gethiddenValues('Amount', stripslashes($amountSal) ,'results', 'ctlEmployeePayrollRates', 'Amount' , 'ctlEmployeePayrollRates#Amount');
$lbltxtAmount = $htmlTextElement->addLabel($txtAmount, "<div id='BASEWAGE'>Compensation Rate</div>:", '#ff0000',true);

if(!$lockallFields && !$firstRecordFlag)
{
	$htmlTextElement->type = 'hidden';
	$htmlTextElement->name = 'txtCompEntryDate';
	$htmlTextElement->id = 'txtCompEntryDate';
	$txtAmount =  $htmlTextElement->renderHtml();
	$htmlTextElement->resetProperties();
}

$num_pos= $employeeeMaintenanceObj->getNumRows($pos_res);
if($num_pos==0)
{
	$jobcode =  'No positions';
}
while($pos_row = mssql_fetch_array($pos_res))
{
	$pos_string .= $pos_row[position].', ';
}	
$jobcode = substr($pos_string, 0, -2);
unset($pos_string);

$lbljobcode = $htmlTextElement->addLabel($jobcode, 'Job Code(s):', '#ff0000','');


$commonListBox->name = 'Paytype';
$commonListBox->id = 'Paytype';
$commonListBox->AddRow('2','Hourly (Non-Exempt)');
$commonListBox->AddRow('1','Salary (Exempt)');
$commonListBox->selectedItem = $salaryTypeSal;
$commonListBox->onChange = "return ChangeBaseWageLabel(); return false;";
$ddlPaytype = $commonListBox->convertArrayToDropDown();
$commonListBox->resetProperties();  //  need to disable..if not empty $lockFieldsStyle Paytype

$employeeeMaintenanceObj->gethiddenValues('Paytype', $salaryTypeSal ,'results', 'ctlEmployeePayrollRates', 'payType' , 'ctlEmployeePayrollRates#payType');

$htmlTextElement->type = 'hidden';
$htmlTextElement->name = 'hdnCOMPFREQUENCY';
$htmlTextElement->id = 'hdnCOMPFREQUENCY';
$htmlTextElement->value = ($salaryTypeSal=='') ? '1' : $salaryTypeSal;
$txtAmount =  $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();

$employeeeMaintenanceObj->gethiddenValues('hdnCOMPFREQUENCY', $salaryTypeSal ,'results', 'ctlEmployeePayrollRates', 'comFrequency' , 'ctlEmployeePayrollRates#comFrequency');
$lblddlPaytype = $htmlTextElement->addLabel($ddlPaytype, 'Employee Type:', '#ff0000','');

if($displayPayChangeDropDown)
{
		
	$commonListBox->name = 'ddlPayChangeReason';
	$commonListBox->id = 'ddlPayChangeReason';
	if(!$firstRecordFlag)
	{
		$commonListBox->AddRow('','Please Select');
	}
	foreach($payChangeRArray as $payChangeReasonK=>$payChangeReasonV)
	{
		$commonListBox->AddRow($payChangeReasonV['reasonID'], $payChangeReasonV['description']);
		
	}
	$commonListBox->selectedItem = '';
	$ddlPayChangeReason = $commonListBox->convertArrayToDropDown();
	$commonListBox->resetProperties();
	
	$lblddlPayChangeReason = $htmlTextElement->addLabel($ddlPayChangeReason, 'Pay Change Reason:', '#ff0000','true');
}

if($payrollLocation == "384"  || $payrollLocation == "75" || $payrollLocation == "72" || $payrollLocation == "802")
{	
		
	$htmlTextElement->type = 'text';
	$htmlTextElement->name = 'txtContractMonthSalary';
	$htmlTextElement->id = 'txtContractMonthSalary';
	if($lockFieldsStyle != '')
	{
		$htmlTextElement->disabled = 'true';
	}
	$htmlTextElement->value = $contractedMonSal;
	$txtContractMonthSalary =  $htmlTextElement->renderHtml().'<font color="#FF0000">*</font> Required for Manila Payroll';
	$htmlTextElement->resetProperties();
	
	$employeeeMaintenanceObj->gethiddenValues('txtContractMonthSalary', stripslashes($contractedMonSal) ,'results', 'ctlEmployeePayrollRates', 'contractedMonthlySalary' , 'ctlEmployeePayrollRates#contractedMonthlySalary');
	
	
	$lbltxtContractMonthSalary = $htmlTextElement->addLabel($txtContractMonthSalary, 'Contracted Monthly Salary:', '#ff0000','');
}


//button Search 
$htmlButtonElement->type = 'button';
$htmlButtonElement->name = 'AddSalary';
$htmlButtonElement->id = 'AddSalary';
$htmlButtonElement->value = 'Add New Compensation Record';
//$htmlButtonElement->onclick = "return validateSalaryInfo('".$displayPayChangeDropDown."','".$hireDateMdy."'); return false;";
$htmlButtonElement->colspan = '2';
$btnAddSalary = $htmlButtonElement->renderHtml();


$tableObj->searchFields['lbladpEffectiveDate'] = $lbladpEffectiveDate; 
$tableObj->searchFields['lbltxtAmount'] = $lbltxtAmount; 
$tableObj->searchFields['lbljobcode'] = $lbljobcode; 
$tableObj->searchFields['lblddlPaytype'] = $lblddlPaytype;
if($displayPayChangeDropDown)
{		
	$tableObj->searchFields['lblddlPayChangeReason'] = $lblddlPayChangeReason;
}
if($payrollLocation == "384"  || $payrollLocation == "75" || $payrollLocation == "72" || $payrollLocation == "802")
{	
	$tableObj->searchFields['lbltxtContractMonthSalary'] = $lbltxtContractMonthSalary;
}
$tableObj->searchFields['btnAddSalary'] = $btnAddSalary;
echo '<br/>'.$tableObj->searchFormTableComponent();
echo $htmlTagObj->closeTag('div');
 
if($lockallFields)
{
	echo $htmlTagObj->openeTag('div','style="width:28%; float:left; margin:0px; padding:0px; text-align:left;"');
	echo $htmlTagObj->openeTag('table');
	echo $htmlTagObj->openeTag('tr');
    echo $htmlTagObj->openeTag('td');
	echo $htmlTagObj->openeTag('div','style="width:90%; padding:5px; font-size:12px; color:#F00; text-align:left;"');
	echo 'This employee needs a compensation rate that is tied to his or her first day of employment.  Please enter a hire date before entering information on this screen';	
    echo $htmlTagObj->closeTag('div');
    echo $htmlTagObj->closeTag('td');
    echo $htmlTagObj->closeTag('tr');
    echo $htmlTagObj->closeTag('table'); 
	echo $htmlTagObj->closeTag('div');
} 
echo $htmlTagObj->closeTag('fieldset');
echo $htmlForm->endForm();
echo $htmlTagObj->closeTag('div');
echo $htmlTagObj->closeTag('div');
?>

<script language="javascript" type="text/javascript">
	var displayPayChangeDropDown = '<?php echo $displayPayChangeDropDown; ?>';
	var hireDateMdy = '<?php echo $hireDateMdy; ?>';
	var lockFieldsStyle = '<?php echo $lockFieldsStyle; ?>';
	
	var accessCal = '<?php echo $accessCal; ?>';
	
	
	

	$(function()
	{
		
	   $("#AddSalary").click(function()
	   {
			isSubmit = validateSalaryInfo(displayPayChangeDropDown,hireDateMdy);
			if(isSubmit != false)
			{
				$("form[name='frmPosition']").submit();
			}
	   });
	   
	   if(lockFieldsStyle  != '')
	   {
		   $("#Paytype, #AddSalary").attr('disabled','disabled');
	   }
	   // .if not empty $lockFieldsStyle Paytype  lockFieldsStyle
	});
	
	if(accessCal)
	{		
		var hostUrl = '<?php echo "https://".$_SERVER["HTTP_HOST"]; ?>';
		$( "#startDate" ).datepicker({
			  showOn: "button",
			  buttonImage: hostUrl+"/Include/images/calendar.gif",
			  buttonText:'Calendar',
			  buttonImageOnly: true,
			  showWeek:true,
			  changeMonth:true,
			  changeYear:true,
			  showButtonPanel:true,
			  closeText: "Close"
		});  
	}
</script>