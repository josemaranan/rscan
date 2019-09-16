<?php

$getLocation = $_REQUEST['location'];
unset($dbClassObj);
unset($tableObj);
unset($htmlTextElement);
unset($htmlButtonElement);
include_once($_SERVER['DOCUMENT_ROOT'] . '/RNetIncludes/class/config.inc.php');
$dbClassObj 		= new ClassQuery();	//.$dbClassObj->UserDetails->Locations;
$tableObj			= new Table();	
$htmlTextElement 	= new HtmlTextElement();
$htmlButtonElement = new HtmlButtonElement('button');

$sql="SELECT 
		a.*,
		b.description as invoiceType
	  FROM  
	  	[ctlLocations] (NOLOCK) a 
	  left outer join ctlInvoiceTypes (NOLOCK) b
	  on
	  	a.invoiceTypeID = b.invoiceTypeID             
	  WHERE 
		State IS NOT NULL AND
		location = ".$getLocation;
$rst = $dbClassObj->ExecuteQuery($sql);
while ($row=mssql_fetch_array($rst)) 
{

	$getLocationDescription =$row['description'];
	$getUsingPayroll =$row['rnetPayroll'];
	
	if(isset($row['rnetPayrollEffectiveDate']))
	{
		//$getEffDate = date('m/d/Y',strtotime($row['rnetPayrollEffectiveDate']));
		$getEffDate = $row['rnetPayrollEffectiveDate'];
	}
	else
	{
		$getEffDate = '';
	}

	$getEST=$row[useEST];
	$getUseTimeClock=$row['useTimeClock'];
	$getUsingNewHireBonus =$row['usesNewHireBonus'];
	$getInvoiceTypeID=$row['invoiceTypeID'];
}
//echo $getLocationDescription.' - '.$getUsingPayroll.' - '.$getEffDate.' - est: '.$getEST.' - '.$getUseTimeClock.' - '.$getUsingNewHireBonus.$getInvoiceTypeID;
mssql_free_result($rst);

$sql2="SELECT 
		*,
		convert(varchar,shiftDifferentialStart,108) as shiftDifferentialStart1,
		convert(varchar,shiftDifferentialEnd,108) as shiftDifferentialEnd1
	  FROM  
		[ctlLocationPayrollReporting] (NOLOCK)             
	  WHERE 
		location = ".$getLocation;

$rst = $dbClassObj->ExecuteQuery($sql2);
  
   while ($row=mssql_fetch_array($rst)) 
   {
		if(isset($row[breakGenerationEffectiveDate]))
		{
			//$getBreakGenerationEffectiveDate = date('m/d/Y',strtotime($row[breakGenerationEffectiveDate]));
			$getBreakGenerationEffectiveDate = $row[breakGenerationEffectiveDate];
		}
		else
		{
			$getBreakGenerationEffectiveDate = '';
		}
		
		
		if(isset($row[payDataFileEffectiveDate]))
		{
			//$getPayDataFileEffectiveDate = date('m/d/Y',strtotime($row[payDataFileEffectiveDate]));
			$getPayDataFileEffectiveDate = $row[payDataFileEffectiveDate];
		}
		else
		{
			$getPayDataFileEffectiveDate = '';
		}
		

		if(isset($row[3]))
		{
			$getSummaryReportProcessingEffectiveDate = $row[3];
		}
		else
		{
			$getSummaryReportProcessingEffectiveDate = '';
		}
		
		
		if(isset($row[4]))
		{
			$getSummaryReportReportingEffectiveDate = $row[4];
		}
		else
		{
			$getSummaryReportReportingEffectiveDate = '';
		}
		
		if(isset($row[13]))
		{
			$getChdCmsReconciliationEffectiveDate = $row[13];
		}
		else
		{
			$getChdCmsReconciliationEffectiveDate = '';
		}

		
		$getRequiresSchedule = $row[5];
		$getOvertimeBasisRule = $row[6];
		$getOvertimeBasisThreshold = $row[7]/3600;
		$getTimeclockCap = $row[8]/3600;
		
		
		if(isset($row[shiftDifferentialStart1]))
		{
			//$shiftDifferentialStart = date('H:i',strtotime($row[shiftDifferentialStart]));
			$getShiftDifferentialStart = substr($row[shiftDifferentialStart1], 0, 5);  
		}
		else
		{
			$getShiftDifferentialStart = '';
		}
		
		if(isset($row[shiftDifferentialEnd1]))
		{
			//$shiftDifferentialEnd = date('H:i',strtotime($row[shiftDifferentialEnd]));
			$getShiftDifferentialEnd = substr($row[shiftDifferentialEnd1], 0, 5);  
			
		}
		else
		{
			$getShiftDifferentialEnd = '';
		}
		
		$getUsesOasis = $row[11];
		$getLocked = $row[12];
		
		$getExcludeProductionForTimeclock = $row[14];
		$getReportProductionTimeOnly = $row[reportProductionTimeOnly];
		$getEmployeeAverageHourlyCost = $row[employeeAverageHourlyCost];
		//echo $getLocked.$getUsesOasis.$getExcludeProductionForTimeclock;
   }
  
  mssql_free_result($rst);
	
// Effective Date & break gen eff date ddls 
$effectiveDateDdl 	= new ListBox('ddlEffectiveDate', '', '');
$effectiveDateDdl->Id = 'ddlEffectiveDate';  
$effectiveDateDdl->SelectedItem = $getEffDate;

$breakEffectiveDateDdl 	= new ListBox('ddlBreakEffectiveDate', '', '');
$breakEffectiveDateDdl->Id = 'ddlBreakEffectiveDate';  
$breakEffectiveDateDdl->SelectedItem = $getBreakGenerationEffectiveDate;

$PayDataEffDateDdl 	= new ListBox('ddlPayDataEffDate', '', '');
$PayDataEffDateDdl->Id = 'ddlPayDataEffDate';  
$PayDataEffDateDdl->SelectedItem = $getPayDataFileEffectiveDate;

$srpEffDateDdl 	= new ListBox('ddlSrpEffDate', '', '');
$srpEffDateDdl->Id = 'ddlSrpEffDate';  
$srpEffDateDdl->SelectedItem = $getSummaryReportProcessingEffectiveDate;

$srrEffDateDdl 	= new ListBox('ddlSrrEffDate', '', '');
$srrEffDateDdl->Id = 'ddlSrrEffDate';  
$srrEffDateDdl->SelectedItem = $getSummaryReportReportingEffectiveDate;

$chdCmsEffDateDdl 	= new ListBox('ddlChdCmsEffDate', '', '');  // chdcmsEffDateDisplay
$chdCmsEffDateDdl->Id = 'ddlChdCmsEffDate';  
$chdCmsEffDateDdl->SelectedItem = $getChdCmsReconciliationEffectiveDate;

$qry = "
	SELECT 
		startDate 
	FROM 
		ctlLocationPaydateschedules (NOLOCK) 
	WHERE 
		location = ".$getLocation." 
	ORDER BY 
		startDate";

$rst = $dbClassObj->ExecuteQuery($qry);
$rows = mssql_num_rows($rst);
if($rows >= 1)
{
	$mainArray = $dbClassObj->bindingInToArray($rst);
}
$effectiveDateDdl->AddRow('', 'Please Choose');
$breakEffectiveDateDdl->AddRow('', 'Please Choose');
$PayDataEffDateDdl->AddRow('', 'Please Choose');
$srpEffDateDdl->AddRow('', 'Please Choose');
$srrEffDateDdl->AddRow('', 'Please Choose');
$chdCmsEffDateDdl->AddRow('', 'Please Choose');

foreach($mainArray as $mainArrayK=>$mainArrayV)
{		 
	 $startDate = date('M. j, Y',strtotime($mainArrayV['startDate']));
	 
	 $effectiveDateDdl->AddRow($mainArrayV['startDate'],$startDate);
	 $breakEffectiveDateDdl->AddRow($mainArrayV['startDate'],$startDate);	 
 	 $PayDataEffDateDdl->AddRow($mainArrayV['startDate'],$startDate);
	 $srpEffDateDdl->AddRow($mainArrayV['startDate'],$startDate);
 	 $srrEffDateDdl->AddRow($mainArrayV['startDate'],$startDate);
  	 $chdCmsEffDateDdl->AddRow($mainArrayV['startDate'],$startDate);
}
mssql_free_result($rst);
$effectiveDateDisplay = $effectiveDateDdl->display();
$breakEffectiveDateDisplay = $breakEffectiveDateDdl->display();
$payDataEffDateDisplay = $PayDataEffDateDdl->display();
$srpEffDateDisplay = $srpEffDateDdl->display();
$srrEffDateDisplay = $srrEffDateDdl->display();
$chdcmsEffDateDisplay = $chdCmsEffDateDdl->display();

// invoice type ddls 
$invoiceTypeDdl 	= new ListBox('ddlInvoiceType', '', '');
$invoiceTypeDdl->Id = 'ddlInvoiceType';  
$invoiceTypeDdl->SelectedItem = $getInvoiceTypeID;
$qry = "
	SELECT * FROM 
		ctlinvoiceTypes with (NOLOCK)";
$rst = $dbClassObj->ExecuteQuery($qry);
$rows = mssql_num_rows($rst);
if($rows >= 1)
{
	$mainArray = $dbClassObj->bindingInToArray($rst);
}
$invoiceTypeDdl->AddRow('', 'Please Choose');
foreach($mainArray as $mainArrayK=>$mainArrayV)
{		 
	 $invoiceTypeDdl->AddRow($mainArrayV['invoiceTypeID'],$mainArrayV['description']);
}
mssql_free_result($rst);
$invoiceTypeDisplay = $invoiceTypeDdl->display();

// Overtime basis rule ddls 
$overTimeBasisRuleDdl 	= new ListBox('ddlOverTimeBasisRule', '', '');
$overTimeBasisRuleDdl->Id = 'ddlOverTimeBasisRule';  
$overTimeBasisRuleDdl->SelectedItem = $getOvertimeBasisRule;
$overTimeBasisRuleDdl->AddRow('', 'Please Choose');
$overTimeBasisRuleDdl->AddRow('Week', 'Week');
$overTimeBasisRuleDdl->AddRow('Shift', 'Shift');
$otBasisRuleDisplay = $overTimeBasisRuleDdl->display();




 //button   submit
$htmlButtonElement->name = 'btnSubmit';
$htmlButtonElement->value = 'Submit';
$htmlButtonElement->Class = 'WSGInputButton';
$htmlButtonElement->id = 'btnSubmit';
$bSubmit = $htmlButtonElement->renderHtml();

 //button   cancel
$htmlButtonElement->name = 'btnCancel';
$htmlButtonElement->value = 'Cancel';
$htmlButtonElement->Class = 'WSGInputButton';
$htmlButtonElement->id = 'btnCancel';
$bCancel = $htmlButtonElement->renderHtml();

//radio button using payroll to
 $htmlTextElement->type = 'radio';
 $htmlTextElement->name = 'rdoUsingPayroll';
 $htmlTextElement->id = 'rdoUsingPayroll';
 $htmlTextElement->txt = 'Yes';
 $htmlTextElement->value = 'Y';
 $htmlTextElement->isDefaultChkd  = ($getUsingPayroll == 'Y') ? '1' : '0';
 $rdoUsingPayroll1 = $htmlTextElement->renderHtml();
 
  //radio button using payroll to
 $htmlTextElement->type = 'radio';
 $htmlTextElement->name = 'rdoUsingPayroll';
 $htmlTextElement->id = 'rdoUsingPayroll';
 $htmlTextElement->txt = 'No';
 $htmlTextElement->value = 'N';
 $htmlTextElement->isDefaultChkd  = ($getUsingPayroll == 'N') ? '1' : '0';
 $rdoUsingPayroll2 = $htmlTextElement->renderHtml();
 $usingPayrollDisplay = $rdoUsingPayroll1.'&nbsp;&nbsp;'.$rdoUsingPayroll2;
 
 //radio button using time clock to
 $htmlTextElement->type = 'radio';
 $htmlTextElement->name = 'rdoUsingTimeClock';
 $htmlTextElement->id = 'rdoUsingTimeClock';
 $htmlTextElement->txt = 'Yes';
 $htmlTextElement->value = 'Y';
 $htmlTextElement->isDefaultChkd  = ($getUseTimeClock == 'Y') ? '1' : '0';
 $rdoUsingTimeClock1 = $htmlTextElement->renderHtml();
 
  //radio button using time clock to
 $htmlTextElement->type = 'radio';
 $htmlTextElement->name = 'rdoUsingTimeClock';
 $htmlTextElement->id = 'rdoUsingTimeClock';
 $htmlTextElement->txt = 'No';
 $htmlTextElement->value = 'N';
  $htmlTextElement->isDefaultChkd  = ($getUseTimeClock == 'N') ? '1' : '0';
 $rdoUsingTimeClock2 = $htmlTextElement->renderHtml();
 $usingTimeClockDisplay = $rdoUsingTimeClock1.'&nbsp;&nbsp;'.$rdoUsingTimeClock2;
 
 //radio button using new hire bonus to
 $htmlTextElement->type = 'radio';
 $htmlTextElement->name = 'rdoUsingNHBonus';
 $htmlTextElement->id = 'rdoUsingNHBonus';
 $htmlTextElement->txt = 'Yes';
 $htmlTextElement->value = 'Y';
 $htmlTextElement->isDefaultChkd  = ($getUsingNewHireBonus == 'Y') ? '1' : '0';
 $rdoUsingNHBonus1 = $htmlTextElement->renderHtml();
 
  //radio button using new hire bonus to
 $htmlTextElement->type = 'radio';
 $htmlTextElement->name = 'rdoUsingNHBonus';
 $htmlTextElement->id = 'rdoUsingNHBonus';
 $htmlTextElement->txt = 'No';
 $htmlTextElement->value = 'N';
  $htmlTextElement->isDefaultChkd  = ($getUsingNewHireBonus == 'N') ? '1' : '0';
 $rdoUsingNHBonus2 = $htmlTextElement->renderHtml();
 $usingNHBDisplay = $rdoUsingNHBonus1.'&nbsp;&nbsp;'.$rdoUsingNHBonus2;
 
 //radio button requires schedule
 $htmlTextElement->type = 'radio';
 $htmlTextElement->name = 'rdoRequireSchedule';
 $htmlTextElement->id = 'rdoRequireSchedule';
 $htmlTextElement->txt = 'Yes';
 $htmlTextElement->value = 'Y';
 $htmlTextElement->isDefaultChkd  = ($getRequiresSchedule == 'Y') ? '1' : '0';
 $rdoRequireSchedule1 = $htmlTextElement->renderHtml();
 
 //radio button requires schedule
 $htmlTextElement->type = 'radio';
 $htmlTextElement->name = 'rdoRequireSchedule';
 $htmlTextElement->id = 'rdoRequireSchedule';
 $htmlTextElement->txt = 'No';
 $htmlTextElement->value = 'N';
  $htmlTextElement->isDefaultChkd  = ($getRequiresSchedule == 'N') ? '1' : '0';
 $rdoRequireSchedule2 = $htmlTextElement->renderHtml();
 $requireSheduleDisplay = $rdoRequireSchedule1.'&nbsp;&nbsp;'.$rdoRequireSchedule2;
 
 //radio button exclude production time
 $htmlTextElement->type = 'radio';
 $htmlTextElement->name = 'rdoExcludeProductionTime';
 $htmlTextElement->id = 'rdoExcludeProductionTime';
 $htmlTextElement->txt = 'Yes';
 $htmlTextElement->value = 'Y';
 $htmlTextElement->isDefaultChkd  = ($getExcludeProductionForTimeclock == 'Y') ? '1' : '0'; 
 $rdoExcludeProductionTime1 = $htmlTextElement->renderHtml();
 
 //radio button exclude production time
 $htmlTextElement->type = 'radio';
 $htmlTextElement->name = 'rdoExcludeProductionTime';
 $htmlTextElement->id = 'rdoExcludeProductionTime';
 $htmlTextElement->txt = 'No';
 $htmlTextElement->value = 'N';
 $htmlTextElement->isDefaultChkd  = ($getExcludeProductionForTimeclock == 'N') ? '1' : '0';
 $rdoExcludeProductionTime2 = $htmlTextElement->renderHtml();
 $productionTimeDisplay = $rdoExcludeProductionTime1.'&nbsp;&nbsp;'.$rdoExcludeProductionTime2;
 
 //radio button report production time only
 $htmlTextElement->type = 'radio';
 $htmlTextElement->name = 'rdoReportProdTimeOnly';
 $htmlTextElement->id = 'rdoReportProdTimeOnly';
 $htmlTextElement->txt = 'Yes';
 $htmlTextElement->value = 'Y';
 $htmlTextElement->isDefaultChkd  = ($getReportProductionTimeOnly == 'Y') ? '1' : '0';
 $rdoReportProdTimeOnly1 = $htmlTextElement->renderHtml();
 
 //radio button report production time only
 $htmlTextElement->type = 'radio';
 $htmlTextElement->name = 'rdoReportProdTimeOnly';
 $htmlTextElement->id = 'rdoReportProdTimeOnly';
 $htmlTextElement->txt = 'No';
 $htmlTextElement->value = 'N';
 $htmlTextElement->isDefaultChkd  = ($getReportProductionTimeOnly == 'N') ? '1' : '0';
 $rdoReportProdTimeOnly2 = $htmlTextElement->renderHtml();
 $reportProductionDisplay = $rdoReportProdTimeOnly1.'&nbsp;&nbsp;'.$rdoReportProdTimeOnly2;
 
 
 //radio button uses oasis payroll 
 $htmlTextElement->type = 'radio';
 $htmlTextElement->name = 'rdoUsesOasisPayroll';
 $htmlTextElement->id = 'rdoUsesOasisPayroll';
 $htmlTextElement->txt = 'Yes';
 $htmlTextElement->value = 'Y';
 $htmlTextElement->isDefaultChkd  = ($getUsesOasis == 'Y') ? '1' : '0'; 
 $rdoUsesOasisPayroll1 = $htmlTextElement->renderHtml();
 
 //radio button uses oasis payroll 
 $htmlTextElement->type = 'radio';
 $htmlTextElement->name = 'rdoUsesOasisPayroll';
 $htmlTextElement->id = 'rdoUsesOasisPayroll';
 $htmlTextElement->txt = 'No';
 $htmlTextElement->value = 'N';
 $htmlTextElement->isDefaultChkd  = ($getUsesOasis == 'N') ? '1' : '0';
 $rdoUsesOasisPayroll2 = $htmlTextElement->renderHtml();
 $oasisPayrollDisplay = $rdoUsesOasisPayroll1.'&nbsp;&nbsp;'.$rdoUsesOasisPayroll2;
 
 //radio button locked
 $htmlTextElement->type = 'radio';
 $htmlTextElement->name = 'rdoLocked';
 $htmlTextElement->id = 'rdoLocked';
 $htmlTextElement->txt = 'Yes';
 $htmlTextElement->value = 'Y';
 $htmlTextElement->isDefaultChkd  = ($getLocked == 'Y') ? '1' : '0'; 
 $rdoLocked1 = $htmlTextElement->renderHtml();
 
 //radio button locked
 $htmlTextElement->type = 'radio';
 $htmlTextElement->name = 'rdoLocked';
 $htmlTextElement->id = 'rdoLocked';
 $htmlTextElement->txt = 'No';
 $htmlTextElement->value = 'N';
 $htmlTextElement->isDefaultChkd  = ($getLocked == 'N') ? '1' : '0';
 $rdoLocked2 = $htmlTextElement->renderHtml();
 $lockedDisplay = $rdoLocked1.'&nbsp;&nbsp;'.$rdoLocked2;
 
 
  //check box id textbox
$htmlTextElement->type = 'checkbox';
$htmlTextElement->name = 'chkESTException';
$htmlTextElement->id = 'chkESTException';
$htmlTextElement->txt = '';
$htmlTextElement->value = 'Y';
$htmlTextElement->isDefaultChkd  = ($getEST == 'Y') ? '1' : '0';
$estExceptionsDisplay = $htmlTextElement->renderHtml();

// defining all text box  = $row[7]/3600;

$htmlTextElement->type = 'text';
$htmlTextElement->name = 'txtOverTimeBasisThresholdHrs';
$htmlTextElement->id = 'txtOverTimeBasisThresholdHrs';
$htmlTextElement->value = $getOvertimeBasisThreshold;
$otBasisThresholdHrsDisplay = $htmlTextElement->renderHtml();

$htmlTextElement->name = 'txtTimeClockCapHrs';
$htmlTextElement->id = 'txtTimeClockCapHrs';
$htmlTextElement->value = $getTimeclockCap;
$tccHrsDisplay = $htmlTextElement->renderHtml();

$htmlTextElement->name = 'txtShiftStart';
$htmlTextElement->id = 'txtShiftStart';
$htmlTextElement->value = $getShiftDifferentialStart;
$sdStartDisplay = $htmlTextElement->renderHtml();

$htmlTextElement->name = 'txtShiftEnd';
$htmlTextElement->id = 'txtShiftEnd';
$htmlTextElement->value = $getShiftDifferentialEnd;
$sdEndDisplay = $htmlTextElement->renderHtml();

$htmlTextElement->name = 'txtAvgHrsCost';
$htmlTextElement->id = 'txtAvgHrsCost';
$htmlTextElement->value = $getEmployeeAverageHourlyCost;
$avgHrsCostDisplay = $htmlTextElement->renderHtml();

$htmlTextElement->type = 'hidden';
$htmlTextElement->name = 'hdnLocationID';
$htmlTextElement->id = 'hdnLocationID';
$htmlTextElement->value = $getLocation;
$hiddenLocation = $htmlTextElement->renderHtml();

$htmlTextElement->type = 'hidden';
$htmlTextElement->name = 'task';
$htmlTextElement->id = 'task';
$htmlTextElement->value = 'editPayRoll';
$task = $htmlTextElement->renderHtml();

   
//  define table labels
$effdate	= $htmlTextElement->addLabel($effectiveDateDisplay, 'Effective Date:', '#ff0000','true');
$breakEffdate	= $htmlTextElement->addLabel($breakEffectiveDateDisplay, 'Break Generation Effective Date:', '#ff0000','');
$usingPayroll	= $htmlTextElement->addLabel($usingPayrollDisplay, 'Using Payroll?:', '#ff0000','true');
$payDataEffDate	= $htmlTextElement->addLabel($payDataEffDateDisplay, 'Pay Data File Effective Date:', '#ff0000',''); //ddl
$usingTimeClock	= $htmlTextElement->addLabel($usingTimeClockDisplay, 'Using Time Clock?:', '#ff0000','true');
$srpEffDate	= $htmlTextElement->addLabel($srpEffDateDisplay, 'Summary Report Processing Effective Date:', '#ff0000','');
$usingNewHire	= $htmlTextElement->addLabel($usingNHBDisplay, 'Using New Hire Bonus?:', '#ff0000','true');
$srrEffDate	= $htmlTextElement->addLabel($srrEffDateDisplay, 'Summary Report Reporting Effective Date:', '#ff0000','');
$invoiceType	= $htmlTextElement->addLabel($invoiceTypeDisplay, 'Invoice Type:', '#ff0000','true');
$requireShedule	= $htmlTextElement->addLabel($requireSheduleDisplay, 'Requires Schedule?:', '#ff0000','');
$estExceptions	= $htmlTextElement->addLabel($estExceptionsDisplay, 'Use EST for exceptions?:', '#ff0000','true');
$otBasisRule	= $htmlTextElement->addLabel($otBasisRuleDisplay, 'Overtime Basis Rule:', '#ff0000','');
$otBasisThresholdHrs	= $htmlTextElement->addLabel($otBasisThresholdHrsDisplay, 'Overtime Basis Threshold (hours):', '#ff0000','');
$tccHrs	= $htmlTextElement->addLabel($tccHrsDisplay, 'Time clock cap (hours):', '#ff0000','');
$sdStart	= $htmlTextElement->addLabel($sdStartDisplay, 'Shift differential start(HH:MM) :', '#ff0000','');
$sdEnd	= $htmlTextElement->addLabel($sdEndDisplay, 'Shift differential end(HH:MM) :', '#ff0000','');
$oasisPayroll	= $htmlTextElement->addLabel($oasisPayrollDisplay, 'Uses Oasis payroll?:', '#ff0000','');
$locked	= $htmlTextElement->addLabel($lockedDisplay, 'Locked?:', '#ff0000','');
$chdcmsEffDate	= $htmlTextElement->addLabel($chdcmsEffDateDisplay, 'CHD/CMS reconciliation effective date:', '#ff0000','');
$productionTime	= $htmlTextElement->addLabel($productionTimeDisplay, 'Exclude production time when time clock hours are present?:', '#ff0000','');
$reportProduction	= $htmlTextElement->addLabel($reportProductionDisplay, 'Report production time only?:', '#ff0000','');
$avgHrsCost	= $htmlTextElement->addLabel($avgHrsCostDisplay, 'Employee average hourly cost?:', '#ff0000','');
$emptyCell	= $htmlTextElement->addLabel('', '', '','');

	
$tableObj->tableId = 'searchTable';
$tableObj->tableClass = 'searchtab';
$tableObj->maxCol = 2;

$tableObj->searchFields['effdate'] = $effdate; 
$tableObj->searchFields['breakEffdate'] = $breakEffdate; 
$tableObj->searchFields['usingPayroll'] = $usingPayroll; 
$tableObj->searchFields['payDataEffDate'] = $payDataEffDate; 
$tableObj->searchFields['usingTimeClock'] = $usingTimeClock; 
$tableObj->searchFields['srpEffDateDisplay'] = $srpEffDate; 
$tableObj->searchFields['usingNewHire'] = $usingNewHire;
$tableObj->searchFields['srrEffDate'] = $srrEffDate; 
$tableObj->searchFields['invoiceType'] = $invoiceType; 
$tableObj->searchFields['requireShedule'] = $requireShedule; 
$tableObj->searchFields['estExceptions'] = $estExceptions; 
$tableObj->searchFields['otBasisRule'] = $otBasisRule; 
$tableObj->searchFields['empty1'] = $emptyCell; 
$tableObj->searchFields['otBasisThresholdHrs'] = $otBasisThresholdHrs; 
$tableObj->searchFields['empty2'] = $emptyCell;
$tableObj->searchFields['tccHrs'] = $tccHrs; 
$tableObj->searchFields['empty3'] = $emptyCell;
$tableObj->searchFields['sdStart'] = $sdStart; 
$tableObj->searchFields['empty4'] = $emptyCell;
$tableObj->searchFields['sdEnd'] = $sdEnd; 
$tableObj->searchFields['empty5'] = $emptyCell;
$tableObj->searchFields['oasisPayroll'] = $oasisPayroll; 
$tableObj->searchFields['empty6'] = $emptyCell;
$tableObj->searchFields['locked'] = $locked; 
$tableObj->searchFields['empty7'] = $emptyCell;
$tableObj->searchFields['chdcmsEffDate'] = $chdcmsEffDate; 
$tableObj->searchFields['empty8'] = $emptyCell;
$tableObj->searchFields['productionTime'] = $productionTime; 
$tableObj->searchFields['empty9'] = $emptyCell;
$tableObj->searchFields['reportProduction'] = $reportProduction; 
$tableObj->searchFields['empty10'] = $emptyCell;
$tableObj->searchFields['avgHrsCost'] = $avgHrsCost; 
echo "<form id='postForm'>";
echo '<div id="processDialog">';
echo '</div>';	
echo  $task.$hiddenLocation;
echo  $tableObj->searchFormTableComponent();
echo "</form>";
echo $bSubmit.'&nbsp;&nbsp;'.$bCancel;

?>
<script type="text/javascript">
	$(document).ready(function()
  	{
	   //alert("<?php echo $_REQUEST['location']; ?>");
	   //$("#chkESTException").attr('checked','checked');
	   //var setUsingPayroll = '<?php echo $getUsingPayroll; ?>';
	   //alert(setUsingPayroll);
	   //$('input[name=rdoUsingPayroll][value='+setUsingPayroll+']').attr('checked', true); 
	   
	   $("#btnCancel").click(function()
	   {
			closeDialog();
	   });
	   $("#btnSubmit").click(function()
	   {
			validateInputs();
	   });
	   $("#txtShiftStart, #txtShiftEnd").change(function()
	   {
		   chkVal = this.value;
		   var regex=/^[0-23]{2}:[0-5][0-9]$/;
		   var isMatch = regex.test(chkVal);
		   if(chkVal != '' && (!isMatch))
		   {
			   alert('Invalid time. Time should have HH:MM format.');
			   $("#"+this.id).val('');
			   this.focus();
			   return false;
		   }
		  
	   });
	   
   	});
	
	function validateInputs()
	{
		
		var regExp = /^([-+]?\d{0,9}(\,\d{9})*|(\d+))(\.\d{1,2})?$/;
		var alertMsg = 'Please enter decimal suffixed with 1 or 2 digits.';
		var otbHrs = $("#txtOverTimeBasisThresholdHrs").val();
		var tccHrs = $("#txtTimeClockCapHrs").val();
		var avgHrs = $("#txtAvgHrsCost").val();
		
		if(!regExp.test(otbHrs))
		{
			 alert(alertMsg+'  (Field: Overtime Basis Threshold (hours) )');
			 return false;
		}
		else if(!regExp.test(tccHrs))
		{
			 alert(alertMsg+'  (Field: Time clock cap (hours) )');
			 return false;
		}
		
		else if(!regExp.test(avgHrs))
		{
			 alert(alertMsg+'  (Field: Employee average hourly cost?:)');
			 return false;
		}
		else
		{
			var pars = $("#postForm").serialize();
			//alert('edit here..'+pars);			
			$("#processDialog").html("<div align='center'><img src='../../../Include/images/progress.gif' /><br/>Please wait, Processing data...</div>");
			$.post(
				   "SitePayrollChecklist_Retrieve.php", 
				   pars, 
				   function(res)
				   {
					    if(res.result == 'true')
						{
							closeDialog();
							alert(res.msg);
							loadTable();
						}
						else
						{
							alert('Error in updating.');
						}
				   },
				   "json"
			);
		}
		
	}
		
	
</script>