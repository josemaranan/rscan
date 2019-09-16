<?php
//ini_set('display_errors','1');
//$employeeeMaintenanceObj->setUSLocations();

//$employData = $employeeeMaintenanceObj->getEmployeeInformation();
//$employADPData = $employeeeMaintenanceObj->getEmployeeADPInformation();

//include_once($_SERVER['DOCUMENT_ROOT']."/Include/logADPPayrollChangesClass.inc.php");
include_once($_SERVER['DOCUMENT_ROOT'] . '/RNetIncludes/class1/config.inc.php');
include_once($_SERVER['DOCUMENT_ROOT']."/RNetIncludes/class1/logADPPayrollChangesClass.inc.php");
//$employeeeMaintenanceObj = new ClassQuery();

//include_once($_SERVER['DOCUMENT_ROOT']."/adp/includes/adpClassFile.inc.php");
include_once($_SERVER['DOCUMENT_ROOT']."/RNetIncludes/class1/adpClassFile.inc.php");
$employeeeMaintenanceObj = new ADPEmployeeClass();

include_once($_SERVER["DOCUMENT_ROOT"]."/Include/tempTableStructure.inc.php");
//include_once($_SERVER['DOCUMENT_ROOT'] . '/RNetIncludes/class/config.inc.php');


$lockallFields = false;
$lockFieldsStyle = '';
$firstRecordFlag = false;


$employeeID = $_REQUEST['employeeID'];
$topLevelHeading = $_REQUEST['topLevelHeading'];

$employeeeMaintenanceObj->setEmployeeADPInformation($employeeID);
$employADPData = $employeeeMaintenanceObj->getEmployeeADPInformation();


$empLoc = $employADPData[0]['location'];

//echo 'PayrollLocation'.$payrollLocation;


$rowsSalaryInfoNum = $_REQUEST['rowsSalaryInfoNum'];
$lastRecord = $_REQUEST['lastRecord'];
$salaryInfoStDate = $_REQUEST['salaryInfoStDate'];
$payrollLocation = $_REQUEST['emppayrollLocation'];

$hireDate = $_REQUEST['empHireDate'];
$termDate = $_REQUEST['empTermDate'];

if(empty($hireDate) || !empty($termDate) )
{
	$lockFieldsStyle = 'disabled="disabled"';
	$lockallFields = true;
}

if(!empty($_REQUEST['salaryInfoStDate'])) // Edit
{
		if(!empty($_REQUEST['lastRecord']))
		{
				if($_REQUEST['lastRecord']=='No') $displayPayChangeDropDown = true;
		}
		else
		{
				if($_REQUEST['lastRecord']=='Yes') $displayPayChangeDropDown = false; 
		}
}


// As per latet revision drop down always dispalyed

$displayPayChangeDropDown = true;

$salaryTypeSal = '';
$amountSal = '0.00';
$amount2Sal = '0.00';
$contractedMonSal = '0.00';

if(!$lockallFields)
{
		$isFirstRecord = $employeeeMaintenanceObj->fnFirstRecoredInPayrollRates($employeeID , $hireDate);
}// if

if($isFirstRecord=='Y') // so this is the first record.
{
	$firstRecordFlag = true;
	$todayDate = date('m/d/Y',strtotime($hireDate));
}


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
	
////Pay Change Reasons
//$sqlPayChangeR = " SELECT * FROM ctlPayChangeReasons WITH (NOLOCK) ORDER BY description ";

$sqlPayChangeR = "  SELECT 
						* 
					FROM 
						ctlPayChangeReasons WITH (NOLOCK) ";
if($firstRecordFlag)
{
	$sqlPayChangeR .= " WHERE reasonID = 9 ";
}
$sqlPayChangeR .=	" ORDER BY 
						description ";


//echo $sqlPayChangeR;
$rstPayChangeR = $employeeeMaintenanceObj->execute($sqlPayChangeR);
$rowsPayChangeRNum = $employeeeMaintenanceObj->getNumRows($rstPayChangeR);
if($rowsPayChangeRNum>=1)
{
	$payChangeRArray = $employeeeMaintenanceObj->bindingInToArray($rstPayChangeR);
}
mssql_free_result($rstPayChangeR);	
////END of Pay Chnage Reasons	


$sqlEffUpdateQry = "SELECT 
								 CONVERT(VARCHAR(10),[startDate],101) [startDate]
								,[payType]
								,[Amount]
								,[Amount2]
								,[contractedMonthlySalary]
								,CONVERT(VARCHAR(10),[compEntryDate],101) [compEntryDate]
								,[payChangeReason]
								,[changeReason]
							FROM 
								results.dbo.ctlEmployeePayrollRates WITH (NOLOCK) 
							WHERE 
								[employeeID] = '".$employeeID."'
							AND
								[startDate] = '".$salaryInfoStDate."' ";
		//echo $sqlEffUpdateQry;exit;
		$rstEffUpdateQry = $employeeeMaintenanceObj->execute($sqlEffUpdateQry);
	while($rowEffUpdateQry = mssql_fetch_assoc($rstEffUpdateQry))
	{
		$effStartDateSal = $rowEffUpdateQry['startDate'];
		$salaryTypeSal = $rowEffUpdateQry['payType'];
		$amountSal = $rowEffUpdateQry['Amount'];
		$amount2Sal = $rowEffUpdateQry['Amount2'];
		$contractedMonSal = $rowEffUpdateQry['contractedMonthlySalary'];
		//$timingType = $rowEffUpdateQry['fullPartTime'];
		$compEntryDate = $rowEffUpdateQry['compEntryDate'];
		$payChangeReason = $rowEffUpdateQry['payChangeReason'];
		$reason = $rowEffUpdateQry['changeReason'];
		
	}
	mssql_free_result($rstEffUpdateQry);

$effDatesArray = $employeeeMaintenanceObj->getN_NumberofPayperiods('4', $hireDate, $payrollLocation);

if(!empty($hireDate))
{
	$hireDateMdy = date('m/d/Y', strtotime($hireDate));	
}

$sqlQuery = " SELECT
								CONVERT(VARCHAR(10),MIN(startDate),101) as minDate
							FROM
								results.dbo.ctlLocationPaydateschedules (nolock)
							WHERE
								location = ".$payrollLocation."
							AND
								isFinalized IS NULL ";

$resultsSet = $employeeeMaintenanceObj->execute($sqlQuery);
$rowsLocNum = $employeeeMaintenanceObj->getNumRows($resultsSet);
if($rowsLocNum>=1)
{
	while($usLocaRows = mssql_fetch_assoc($resultsSet))
	{
		$resultDate = $usLocaRows['minDate'];
	}
	$payrollMinDate = $resultDate;
}


//######### Main Body starts here
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
$htmlForm->action = 'edit_salary_process.php';
$htmlForm->name = 'frmPosition';
$htmlForm->id = 'searchForm';
$htmlForm->method = 'post';
echo $htmlForm->startForm();

echo $htmlTagObj->openTag('fieldset');
echo $htmlTagObj->openTag('legend');
echo 'Update Compensation Record';
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

$htmlTextElement->type = 'hidden';
$htmlTextElement->name = 'hdnPayrollMinimumDate';
$htmlTextElement->id = 'hdnPayrollMinimumDate';
$htmlTextElement->value = date('m/d/Y', strtotime($payrollMinDate));
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
	$commonListBox->selectedItem = $salaryInfoStDate;
	$commonListBox->onChange = "modifyEffectiveDate(this.value)";
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
	$htmlTextElement->value = $effStartDateSal;
	$htmlTextElement->onfocus = "return MoveFocus('Amount'); return false;";
	$htmlTextElement->onchange = "modifyEffectiveDate(this.value)";
	$adpEffectiveDate =  $htmlTextElement->renderHtml();
	$htmlTextElement->resetProperties();
	
	$accessCal = true;
}

$adpEffectiveDate .= $htmlTagObj->openTag('div','id="EFFDATEDIV" style="display:none;"');
$adpEffectiveDate .= $htmlTagObj->imgTag('../Include/images/progress.gif', 'border="0"');
$adpEffectiveDate .= $htmlTagObj->closeTag('div');

if(!empty($_REQUEST['salaryInfoStDate'])) 
{
	$prevStDate = date('m/d/Y',strtotime($effStartDateSal));
} 
else 
{
	$prevStDate = '';
}
$employeeeMaintenanceObj->gethiddenValues('startDate', $prevStDate ,'results', 'ctlEmployeePayrollRates', 'startDate' , 'ctlEmployeePayrollRates#startDate');
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
$hdnCOMPFREQUENCY =  $htmlTextElement->renderHtml();
echo $hdnCOMPFREQUENCY;
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
	$commonListBox->selectedItem = $payChangeReason;
	$ddlPayChangeReason = $commonListBox->convertArrayToDropDown();
	$commonListBox->resetProperties();
	
	$lblddlPayChangeReason = $htmlTextElement->addLabel($ddlPayChangeReason, 'Pay Change Reason:', '#ff0000','true');
}

if($payrollLocation == "384"  || $payrollLocation == "75" || $payrollLocation == "72" || $payrollLocation == "802")
{	
		
	$htmlTextElement->type = 'text';
	$htmlTextElement->name = 'txtContractMonthSalary';
	$htmlTextElement->id = 'txtContractMonthSalary';
	/*if($lockFieldsStyle != '')
	{
		$htmlTextElement->disabled = 'true';
	}*/
	$htmlTextElement->value = $contractedMonSal;
	$txtContractMonthSalary =  $htmlTextElement->renderHtml().'<font color="#FF0000">*</font> Required for Manila Payroll';
	$htmlTextElement->resetProperties();
	
	$employeeeMaintenanceObj->gethiddenValues('txtContractMonthSalary', stripslashes($contractedMonSal) ,'results', 'ctlEmployeePayrollRates', 'contractedMonthlySalary' , 'ctlEmployeePayrollRates#contractedMonthlySalary');
	
	
	$lbltxtContractMonthSalary = $htmlTextElement->addLabel($txtContractMonthSalary, 'Contracted Monthly Salary:', '#ff0000','');
}

$htmlTextElement->type = 'hidden';
$htmlTextElement->name = 'originalStartDate';
$htmlTextElement->id = 'originalStartDate';
$htmlTextElement->value = $salaryInfoStDate;
echo $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();

$txaReason = $htmlTagObj->textAreatag('rows="4" cols="50" id="txaReason" name="txaReason" ', $reason);

$changeReason = $htmlTagObj->openTag('th', "class='divChangeReason' ");
$changeReason .= 'Reason ';
$changeReason .= $htmlTagObj->openTag('SPAN', 'class=required style="COLOR: #ff0000"');
$changeReason .= '*';
$changeReason .= $htmlTagObj->closeTag('SPAN');
$changeReason .= $htmlTagObj->closeTag('th');
$changeReason .= $htmlTagObj->openTag('td',"class='divChangeReason' ");
$changeReason .= $txaReason;
$changeReason .=$htmlTagObj->closeTag('td');

//button Search 
$htmlButtonElement->type = 'button';
$htmlButtonElement->name = 'UpdateSalary';
$htmlButtonElement->id = 'UpdateSalary';
$htmlButtonElement->value = 'Update Compensation Record';
//$htmlButtonElement->onclick = "return validateSalaryInfo('".$displayPayChangeDropDown."','".$hireDateMdy."'); return false;";
$htmlButtonElement->colspan = '2';
$btnAddSalary = $htmlButtonElement->renderHtml();


$tableObj->searchFields['lbladpEffectiveDate'] = $lbladpEffectiveDate; 
$tableObj->searchFields['reason'] = $changeReason;
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
			var selectedEffectiveDate = $('#startDate').val();
			modifyEffectiveDate(selectedEffectiveDate);
		   $("#UpdateSalary").click(function()
		   {
				isSubmit = validateSalaryInfo(displayPayChangeDropDown,hireDateMdy);
				if(isSubmit != false)
				{
					$("form[name='frmPosition']").submit();
				}
		   });
		   
		   if(lockFieldsStyle  != '')
		   {
			   $("#Paytype, #UpdateSalary").attr('disabled','disabled');
		   }
		   // .if not empty $lockFieldsStyle Paytype  lockFieldsStyle
	});

	function modifyEffectiveDate(selectedValue)
	{
		if(selectedValue != '')
		{	
			var prevEffectiveDate = $('#hdnPayrollMinimumDate').val();
			selectedDate = new Date(selectedValue);
			prevEffectiveDate = new Date(prevEffectiveDate);
	
			if(selectedDate < prevEffectiveDate)
			{
				$('.divChangeReason').show();
			}
			else
			{
				$('#txaReason').val('');
				$('.divChangeReason').hide();		
			}
		}
		else
		{
			$('#txaReason').val('');
			$('.divChangeReason').hide();
		}
	}
</script>