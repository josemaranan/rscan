<?php
$task = $_REQUEST['task'];
//include_once($_SERVER['DOCUMENT_ROOT'] . '/RNetIncludes/class/config.inc.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/RNetIncludes/class1/common.config.inc.php');

switch ($task)
{
	case 'editPayRoll':
		editPayRoll();
		break;
	case 'loadContent':
		loadContent();
		break;
	default:
		echo "Default.".$task;
}


function loadContent()
{	
	//$dbClassObj 		= new ClassQuery();
	$dbClassObj = new RDSData(MSSQL_HOST, MSSQL_USERNAME, MSSQL_PASSWORD, MSSQL_DB, MSSQL_TYPE, MSSQL_PORT, '');
	$tableObj			= new Table();	
	
     $qry = "SELECT 
	 			a.*,
				b.description as invoiceType
			FROM  
				[ctlLocations] a WITH (NOLOCK) 
			left outer join 
				ctlInvoiceTypes b WITH (NOLOCK) 
			on  
				a.invoiceTypeID = b.invoiceTypeID             
			WHERE 
				State IS NOT NULL AND
				location IN ".$dbClassObj->UserDetails->Locations." AND
				switch ='N' AND 
				active = 'Y' 
				AND productionReportDisplay = 'Y'
				ORDER BY [description]";
	 //Grid report generation   Location   Effective Date     Action   
	 $tableObj->headers[] = 'Action';
     $tableObj->headers[] = 'Location';
	 $tableObj->headers[] = 'Using Payroll?';
	 $tableObj->headers[] = 'Effective Date';
	 $tableObj->headers[] = 'Use EST for exceptions?';
	 $tableObj->headers[] = 'Use New Hire Bonus?';
	 $tableObj->headers[] = 'Invoice Type';
 	 $tableObj->headers[] = 'Use Time Clock';

	 
	 $tableObj->tableId = 'listTable';
	 $tableObj->width = '100%';
	 $tableObj->border = 0;
	 $tableObj->align = 'left';
	 $tableObj->cellPadding = '0';
	 $tableObj->bgColor = '#FFFFFF';
	 $tableObj->cellSpacing = '0';
	 $tableObj->tableClass = 'report table-autosort table-stripeclass:alternate';
	 $tableObj->zebra = 1;
	 //$tableObj->fixedCol = 2;
	 $tableObj->setTableAttr("0", "3", "#FFFFFF", "3", "left", "100%");
	 
	 $rstMainQry = $dbClassObj->execute($qry);
	 $rowsMainQryNum = mssql_num_rows($rstMainQry);
	 if($rowsMainQryNum>=1)
	 {
		$mainArray = $dbClassObj->bindingInToArray($rstMainQry);
	 }
	 mssql_free_result($rstMainQry);
	 
         
        
	 foreach($mainArray as $mainArrayK=>$row)
	 {	
  			$usingPayroll = ($row['rnetPayroll'] == 'Y') ? 'Yes' : 'No';
  			$usesNewHireBonus = ($row['usesNewHireBonus'] == 'Y') ? 'Yes' : 'No';			
			$useEstExp = ($row['useEST']== 'Y') ? 'Yes' : 'No';			
			$effDate = (isset($row['rnetPayrollEffectiveDate'])) ? date('m/d/Y',strtotime($row['rnetPayrollEffectiveDate'])) : '';
			$loc = $row['location'];
			$locDesc = $row['description'];
			$editLink = '<a href="#" onclick="editPayRoll('.$loc.',\''.$locDesc.'\');">EDIT</a>';
			
			$data[$mainArrayK]['editLink'] = $editLink;			
			$data[$mainArrayK]['description'] = $locDesc;
			$data[$mainArrayK]['rnetPayroll'] = $usingPayroll;
			$data[$mainArrayK]['rnetPayrollEffectiveDate'] = $effDate;
			$data[$mainArrayK]['useEST'] = $useEstExp;
			$data[$mainArrayK]['usesNewHireBonus'] = $usesNewHireBonus;
			$data[$mainArrayK]['invoiceType'] = $row['invoiceType'];
			$data[$mainArrayK]['useTimeClock'] = $row['useTimeClock'];
	 }
	
	 echo $tableObj->showTable($data, count($data));
}

function editPayRoll()
{
	$json_array  = array();
	$json_array['result'] = 'flase';
	//$dbClassObj 		= new ClassQuery();
	$dbClassObj = new RDSData(MSSQL_HOST, MSSQL_USERNAME, MSSQL_PASSWORD, MSSQL_DB, MSSQL_TYPE, MSSQL_PORT, '');

	// get all fields
	$hdnLocationID = $_REQUEST['hdnLocationID'];
	
	$ddlEffectiveDate = trim($_REQUEST['ddlEffectiveDate']);	
	if(!empty($ddlEffectiveDate))
	{
		$update_date = " rnetPayrollEffectiveDate = '$ddlEffectiveDate' , " ;
	}
	else
	{
		$update_date = '';
	}
	$ddlBreakEffectiveDate = $_REQUEST['ddlBreakEffectiveDate'];
	$ddlPayDataEffDate = $_REQUEST['ddlPayDataEffDate'];
	$ddlSrpEffDate = $_REQUEST['ddlSrpEffDate'];
	$ddlSrrEffDate = $_REQUEST['ddlSrrEffDate'];
	$ddlChdCmsEffDate = $_REQUEST['ddlChdCmsEffDate']; 
	$ddlInvoiceType = $_REQUEST['ddlInvoiceType']; 
	$ddlOverTimeBasisRule = $_REQUEST['ddlOverTimeBasisRule']; //ddlInvoiceType
	
	$rdoUsingPayroll = $_REQUEST['rdoUsingPayroll'];
	$rdoUsingTimeClock = $_REQUEST['rdoUsingTimeClock'];
	$rdoUsingNHBonus = $_REQUEST['rdoUsingNHBonus'];
	$rdoRequireSchedule = $_REQUEST['rdoRequireSchedule'];
	$rdoExcludeProductionTime = $_REQUEST['rdoExcludeProductionTime'];
	$rdoReportProdTimeOnly = $_REQUEST['rdoReportProdTimeOnly']; 
	$rdoUsesOasisPayroll = $_REQUEST['rdoUsesOasisPayroll']; 
	$rdoLocked = $_REQUEST['rdoLocked'];
	
	$chkESTException = $_REQUEST['chkESTException'];
	if(!empty($chkESTException))
	{
			$est_exception_value = 'Y';
	}
	else
	{
			$est_exception_value = 'N';
	}
	
	$txtOverTimeBasisThresholdHrs = $_REQUEST['txtOverTimeBasisThresholdHrs'] * 3600;
	if(empty($txtOverTimeBasisThresholdHrs))
	{
		$txtOverTimeBasisThresholdHrs  = 0;
	}
	$txtTimeClockCapHrs = $_REQUEST['txtTimeClockCapHrs'] * 3600;	
	if(empty($txtTimeClockCapHrs))
	{
		$txtTimeClockCapHrs = 0;
	}
	$txtShiftStart = $_REQUEST['txtShiftStart'];
	$txtShiftEnd = $_REQUEST['txtShiftEnd'];
	$txtAvgHrsCost = $_REQUEST['txtAvgHrsCost']; 
	if(empty($txtAvgHrsCost))
	{
		$txtAvgHrsCost = 'NULL';
	}
	
	
	$query = "SELECT 
					COUNT(*) 
				 FROM 
				 	ctlLocationPayrollReporting (NOLOCK) 
				 WHERE 
				 	location = ".$hdnLocationID ;
	
	$rstMainQry = $dbClassObj->execute($query);
	$rowsMainQryNum = mssql_fetch_array($rstMainQry);
	if($rowsMainQryNum[0] == 0)
	{
		$query = "INSERT 
					INTO 
				  ctlLocationPayrollReporting 
					(location) 
				  values 
				  	($hdnLocationID) ";	
		$dbClassObj->execute($query);
	}
	
	$query = "UPDATE ctlLocations
					  SET
						".$update_date."
						rnetPayroll = '$rdoUsingPayroll' ,
						useEST = '$est_exception_value',
						useTimeClock = '$rdoUsingTimeClock',
						InvoiceTypeID = '$ddlInvoiceType',
						usesNewHireBonus = '$rdoUsingNHBonus'
					  WHERE
					  	 location = $hdnLocationID";
	$rstMainQry = $dbClassObj->execute($query);	
	mssql_free_result($rstMainQry);
	$query2 = "UPDATE ctlLocationPayrollReporting
					  SET ";
					  
					  if(!empty($ddlBreakEffectiveDate))
					  {
						$query2 .= " breakGenerationEffectiveDate = '$ddlBreakEffectiveDate' , " ;
					  }

  					  if(!empty($ddlPayDataEffDate))
					  {
						$query2 .= " payDataFileEffectiveDate = '$ddlPayDataEffDate' , " ;
					  }
					  
					  if(!empty($ddlSrpEffDate))
					  {
						$query2 .= " summaryReportProcessingEffectiveDate = '$ddlSrpEffDate' , " ;
					  }

					  if(!empty($ddlSrrEffDate))
					  {
						$query2 .= " summaryReportReportingEffectiveDate = '$ddlSrrEffDate' , " ;
					  }
					  
					  if(!empty($ddlChdCmsEffDate))
					  {
						$query2 .= " chdCmsReconciliationEffectiveDate = '$ddlChdCmsEffDate' , " ;
					  }
					  
					  if(!empty($txtShiftStart))
					  {
						$shiftDifferentialStart1 = '1900-01-01 '.$txtShiftStart.':00.000';
						$query2 .= " shiftDifferentialStart = '$shiftDifferentialStart1' , " ;
					  }
					  
					  if(!empty($txtShiftEnd))
					  {
						$shiftDifferentialEnd1 = '1900-01-01 '.$txtShiftEnd.':00.000';
						$query2 .= " shiftDifferentialEnd = '$shiftDifferentialEnd1' , " ;
					  }

			  $query2 .= " 
						 requiresSchedule = '$rdoRequireSchedule', 
						overtimeBasisRule = '$ddlOverTimeBasisRule', 
						overtimeBasisThreshold = $txtOverTimeBasisThresholdHrs,
						timeclockCap = $txtTimeClockCapHrs,
						usesOasis = '$rdoUsesOasisPayroll', 
						locked = '$rdoLocked',
						excludeProductionForTimeclock = '$rdoExcludeProductionTime',
						reportProductionTimeOnly = '$rdoReportProdTimeOnly',
						employeeAverageHourlyCost = $txtAvgHrsCost
						 WHERE
					  	 location = $hdnLocationID";
	$rstMainQry = $dbClassObj->execute($query2);
	mssql_free_result($rstMainQry);
	
	$json_array['result'] = 'true';
	$json_array['msg'] = ' Edited successfully.';
	echo json_encode($json_array);
}
?>