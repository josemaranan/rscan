<?
session_start();
include($_SERVER['DOCUMENT_ROOT']."/Include/authenticate.inc.php");
include_once($_SERVER["DOCUMENT_ROOT"]."/Include/Global.inc.php");

if($_POST)
{
	$location = trim($_POST['hdnLocation']);
	
	$db=mssql_connect(MSSQL_HOST, MSSQL_USERNAME, MSSQL_PASSWORD);
	mssql_select_db(MSSQL_DB);
	
	//checking location is existed ot not in ctlLocationPayrollReporting table.
$query3 = "SELECT COUNT(*) FROM ctlLocationPayrollReporting (NOLOCK) WHERE location = ".$location ;

$rst3=mssql_query($query3, $db);



		
	
		if($row=mssql_fetch_array($rst3)) 
		{	
			$num=$row[0];
		}
		

		
		
		//@mssql_free_result($rst3);
		
		
		
		if($num == 0)
		{
			 $qryInsert = "INSERT INTO ctlLocationPayrollReporting (location) values ($location) ";
			 mssql_query(str_replace("\'","''",$qryInsert), $db);
			 
		}

	
	
	$effDate = trim($_POST['ddlStartDates']);
	if(!empty($effDate))
	{
		$update_date = " rnetPayrollEffectiveDate = '$effDate' , " ;
	}
	else
	{
		$update_date = '';
	}
	
	$usingPayroll=$_POST["usingPayroll"];
	$est_exception = $_POST['est_exception'];
	
	if(!empty($est_exception))
	{
			$est_exception_value = 'Y';
	}
	else
	{
			$est_exception_value = 'N';
	}
	
	$useTimeClock = $_POST["useTimeClock"];
	$useNewHireBonus = $_POST["useNewHireBonus"];
	$invoiceType = $_POST["invoiceType"];

	
			$query = "UPDATE ctlLocations
					  SET
						".$update_date."
						rnetPayroll = '$usingPayroll' ,
						useEST = '$est_exception_value',
						useTimeClock = '$useTimeClock',
						InvoiceTypeID = '$invoiceType',
						usesNewHireBonus = '$useNewHireBonus'
					  WHERE
					  	 location = $location";
	mssql_query(str_replace("\'","''",$query), $db);
	
	
	
	//UPDATING ctlLocationPayrollReporting TABLE
	
	$location = $_POST["hdnLocation"];
	$breakGenerationEffectiveDate = $_POST["ddlbreakGenerationEffectiveDate"];
	$payDataFileEffectiveDate = $_POST["ddlpayDataFileEffectiveDate"];
	$summaryReportProcessingEffectiveDate = $_POST["ddlsummaryReportProcessingEffectiveDate"];
	$summaryReportReportingEffectiveDate = $_POST["ddlsummaryReportReportingEffectiveDate"];
	$requiresSchedule = $_POST["rdorequiresSchedule"];
	$overtimeBasisRule = $_POST["ddlovertimeBasisRule"];
	
	$overtimeBasisThreshold = $_POST["txtovertimeBasisThreshold"] * 3600;
	
	if(empty($overtimeBasisThreshold))
	{
		$overtimeBasisThreshold  = 0;
	}

	
	$timeclockCap = $_POST["txttimeclockCap"] * 3600;
	
	if(empty($timeclockCap))
	{
		$timeclockCap = 0;
	}
	
	
	$shiftDifferentialStart = $_POST["txtshiftDifferentialStart"];
	$shiftDifferentialEnd = $_POST["txtshiftDifferentialEnd"];
	
	$usesOasis = $_POST["rdousesOasis"];
	$locked = $_POST["rdolocked"];
	$chdCmsReconciliationEffectiveDate = $_POST["ddlchdCmsReconciliationEffectiveDate"];
	$excludeProductionForTimeclock = $_POST["rdoexcludeProductionForTimeclock"];
	$reportProductionTimeOnly = $_POST["rdoreportProductionTimeOnly"];
	
	
	$employeeAverageHourlyCost = $_POST["txtemployeeAverageHourlyCost"];
	
	if(empty($employeeAverageHourlyCost))
	{
		$employeeAverageHourlyCost = 'NULL';
	}

	
	
				$query2 = "UPDATE ctlLocationPayrollReporting
					  SET ";
					  
					  if(!empty($breakGenerationEffectiveDate))
					  {
						$query2 .= " breakGenerationEffectiveDate = '$breakGenerationEffectiveDate' , " ;
					  }

  					  if(!empty($payDataFileEffectiveDate))
					  {
						$query2 .= " payDataFileEffectiveDate = '$payDataFileEffectiveDate' , " ;
					  }
					  
					  if(!empty($summaryReportProcessingEffectiveDate))
					  {
						$query2 .= " summaryReportProcessingEffectiveDate = '$summaryReportProcessingEffectiveDate' , " ;
					  }

					  if(!empty($summaryReportReportingEffectiveDate))
					  {
						$query2 .= " summaryReportReportingEffectiveDate = '$summaryReportReportingEffectiveDate' , " ;
					  }
					  
					  if(!empty($chdCmsReconciliationEffectiveDate))
					  {
						$query2 .= " chdCmsReconciliationEffectiveDate = '$chdCmsReconciliationEffectiveDate' , " ;
					  }
					  
					  if(!empty($shiftDifferentialStart))
					  {
						$shiftDifferentialStart1 = '1900-01-01 '.$shiftDifferentialStart.':00.000';
						$query2 .= " shiftDifferentialStart = '$shiftDifferentialStart1' , " ;
					  }
					  
					  if(!empty($shiftDifferentialEnd))
					  {
						$shiftDifferentialEnd1 = '1900-01-01 '.$shiftDifferentialEnd.':00.000';
						$query2 .= " shiftDifferentialEnd = '$shiftDifferentialEnd1' , " ;
					  }

			  $query2 .= " 
						 requiresSchedule = '$requiresSchedule', 
						overtimeBasisRule = '$overtimeBasisRule', 
						overtimeBasisThreshold = $overtimeBasisThreshold,
						timeclockCap = $timeclockCap,
						usesOasis = '$usesOasis', 
						locked = '$locked',
						excludeProductionForTimeclock = '$excludeProductionForTimeclock',
						reportProductionTimeOnly = '$reportProductionTimeOnly',
						employeeAverageHourlyCost = $employeeAverageHourlyCost
						 WHERE
					  	 location = $location";
						 
						// echo $query2;
						
						 
	mssql_query(str_replace("\'","''",$query2), $db);

	
	
	
	///
	
	
	
	
	header("Location: SitePayrollChecklist.php?res=recordEdited");
	
	
	
	
	
}
?>
