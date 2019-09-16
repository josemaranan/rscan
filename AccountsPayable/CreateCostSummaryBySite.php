<?
//ini_set('session.cache_limiter', 'private'); 
error_reporting(0);
session_start();
include($_SERVER['DOCUMENT_ROOT']."/Include/authenticate.inc.php");
include_once($_SERVER["DOCUMENT_ROOT"]."/Include/Global.inc.php");


$db=mssql_connect(MSSQL_HOST, MSSQL_USERNAME, MSSQL_PASSWORD);
mssql_select_db(MSSQL_DB);

if($_REQUEST["loc"])
{
	$loc = $_REQUEST["loc"];
	
	//getting location description
	
	$query = " SELECT [location]
					  ,[description]
			FROM  [ctlLocations]  (NOLOCK)                  
			Where location='".$loc."'"; 
	
	$locRst=mssql_query($query, $db);
	if ($row=mssql_fetch_array($locRst)) 
	{	
		  $locationDes = $row[description];
	}
	
}

if($_REQUEST["Year"])
{
	$Year = $_REQUEST["Year"];
}

if($_REQUEST["Month"])
{
	$Month = $_REQUEST["Month"];
}

if($_REQUEST["monthDesc"])
{
	$monthDesc = $_REQUEST["monthDesc"];
}


///Checking for record alraedy existed or not


		$query1 = "SELECT COUNT(*) FROM common..prm_rnet_CostSummaryBySite (NOLOCK)
				   WHERE 
							location = '".$loc."' 
							AND
							currentYear = '".$Year."'
							AND
							currentMonth = '".$Month."'";
		
	
		
						$lo=mssql_query($query1, $db);
						if ($row=mssql_fetch_array($lo)) 
						{	
							  $num = $row[0];
						}
		if($num != 0)
		{
			echo "<script type='text/javascript'>window.location='CostSummaryBySite.php?result=existed&ddlLocations=$loc&ddlYear=$Year&ddlMonth=$Month';</script>";

		
		}





///



$location = $loc;
$currentYear = $Year;
$currentMonth = $Month;


//---  GETTING FORECASTED
		if($_REQUEST["txtForecastPaidHours"])
		{
			$payableHours = $_REQUEST["txtForecastPaidHours"];
		}
		
		if($_REQUEST["txtForecastAgentRegular"])
		{
			$agentRegularPay = $_REQUEST["txtForecastAgentRegular"];
		}
		
		if($_REQUEST["txtForecastAgentOvertime"])
		{
			$agentOTPay = $_REQUEST["txtForecastAgentOvertime"];
		}
		
		if($_REQUEST["txtForecastAgentTraining"])
		{
			$agentTrainingPay = $_REQUEST["txtForecastAgentTraining"];
		}
		
		if($_REQUEST["txtForecastAgentBonus"])
		{
			$agentBonusPay = $_REQUEST["txtForecastAgentBonus"];
		}
		
		if($_REQUEST["txtForecastAgentIncentives"])
		{
			$agentIncentivePay = $_REQUEST["txtForecastAgentIncentives"];
		}
		
		
		if($_REQUEST["txtForecastDirectLaborRegular"])
		{
			$directLaborRegularPay = $_REQUEST["txtForecastDirectLaborRegular"];
		}
		
		if($_REQUEST["txtForecastDirectLaborOvertime"])
		{
			$directLaborOTPay = $_REQUEST["txtForecastDirectLaborOvertime"];
		}
		
		if($_REQUEST["txtForecastDirectLaborTraining"])
		{
			$directLaborTrainingPay = $_REQUEST["txtForecastDirectLaborTraining"];
		}
		
		if($_REQUEST["txtForecastDirectLaborBonus"])
		{
			$directLaborBonusPay = $_REQUEST["txtForecastDirectLaborBonus"];
		}
		
		if($_REQUEST["txtForecastDirectLaborIncentives"])
		{
			$directLaborIncentivePay = $_REQUEST["txtForecastDirectLaborIncentives"];
		}
		
		
		if($_REQUEST["txtForecastAdministrativeLaborRegular"])
		{
			$adminRegularPay = $_REQUEST["txtForecastAdministrativeLaborRegular"];
		}
		
		if($_REQUEST["txtForecastAdministrativeLaborOvertime"])
		{
			$adminOTPay = $_REQUEST["txtForecastAdministrativeLaborOvertime"];
		}
		
		if($_REQUEST["txtForecastAdministrativeLaborTraining"])
		{
			$adminTrainingPay = $_REQUEST["txtForecastAdministrativeLaborTraining"];
		}
		
		if($_REQUEST["txtForecastAdministrativeLaborBonus"])
		{
			$adminBonusPay = $_REQUEST["txtForecastAdministrativeLaborBonus"];
		}
		
		if($_REQUEST["txtForecastAdministrativeLaborIncentives"])
		{
			$adminIncentivePay = $_REQUEST["txtForecastAdministrativeLaborIncentives"];
		}
		
		if($_REQUEST["txtForecastFixedFacilityCost"])
		{
			$fixedFacilityCost = $_REQUEST["txtForecastFixedFacilityCost"];
		}
		
		if($_REQUEST["txtForecastVariableFacilityCost"])
		{
			$variableFacilityCost = $_REQUEST["txtForecastVariableFacilityCost"];
		}
		
		if($_REQUEST["txtForecastPointtoPointCost"])
		{
			$pointToPointCost= $_REQUEST["txtForecastPointtoPointCost"];
		}
		
		if($_REQUEST["txtForecastDirectTelecomCost"])
		{
			$directTelecomCost = $_REQUEST["txtForecastDirectTelecomCost"];
		}
		
		if($_REQUEST["txtForecastFirstXXHoursatYYrate"])
		{
			$ownershipMarginTier1 = $_REQUEST["txtForecastFirstXXHoursatYYrate"];
		}
		
		if($_REQUEST["txtForecastAboveXXHoursatYYrate"])
		{
			$ownershipMarginTier2 = $_REQUEST["txtForecastAboveXXHoursatYYrate"];
		}
///FORE CASTED DATA END

//GETTING ACTUAL DATA

		if($_REQUEST["txtActualPaidHours"])
		{
			$payableHours1 = $_REQUEST["txtActualPaidHours"];
		}
		
		if($_REQUEST["txtActualAgentRegular"])
		{
			$agentRegularPay1 = $_REQUEST["txtActualAgentRegular"];
		}
		
		if($_REQUEST["txtActualAgentOvertime"])
		{
			$agentOTPay1 = $_REQUEST["txtActualAgentOvertime"];
		}
		
		if($_REQUEST["txtActualAgentTraining"])
		{
			$agentTrainingPay1 = $_REQUEST["txtActualAgentTraining"];
		}
		
		if($_REQUEST["txtActualAgentBonus"])
		{
			$agentBonusPay1 = $_REQUEST["txtActualAgentBonus"];
		}
		
		if($_REQUEST["txtActualAgentIncentives"])
		{
			$agentIncentivePay1 = $_REQUEST["txtActualAgentIncentives"];
		}
		
		
		if($_REQUEST["txtActualDirectLaborRegular"])
		{
			$directLaborRegularPay1 = $_REQUEST["txtActualDirectLaborRegular"];
		}
		
		if($_REQUEST["txtActualDirectLaborOvertime"])
		{
			$directLaborOTPay1 = $_REQUEST["txtActualDirectLaborOvertime"];
		}
		
		if($_REQUEST["txtActualDirectLaborTraining"])
		{
			$directLaborTrainingPay1 = $_REQUEST["txtActualDirectLaborTraining"];
		}
		
		if($_REQUEST["txtActualDirectLaborBonus"])
		{
			$directLaborBonusPay1 = $_REQUEST["txtActualDirectLaborBonus"];
		}
		
		if($_REQUEST["txtActualDirectLaborIncentives"])
		{
			$directLaborIncentivePay1 = $_REQUEST["txtActualDirectLaborIncentives"];
		}
		
		
		if($_REQUEST["txtActualAdministrativeLaborRegular"])
		{
			$adminRegularPay1 = $_REQUEST["txtActualAdministrativeLaborRegular"];
		}
		
		if($_REQUEST["txtActualAdministrativeLaborOvertime"])
		{
			$adminOTPay1 = $_REQUEST["txtActualAdministrativeLaborOvertime"];
		}
		
		if($_REQUEST["txtActualAdministrativeLaborTraining"])
		{
			$adminTrainingPay1 = $_REQUEST["txtActualAdministrativeLaborTraining"];
		}
		
		if($_REQUEST["txtActualAdministrativeLaborBonus"])
		{
			$adminBonusPay1 = $_REQUEST["txtActualAdministrativeLaborBonus"];
		}
		
		if($_REQUEST["txtActualAdministrativeLaborIncentives"])
		{
			$adminIncentivePay1 = $_REQUEST["txtActualAdministrativeLaborIncentives"];
		}
		
		if($_REQUEST["txtActualFixedFacilityCost"])
		{
			$fixedFacilityCost1 = $_REQUEST["txtActualFixedFacilityCost"];
		}
		
		if($_REQUEST["txtActualVariableFacilityCost"])
		{
			$variableFacilityCost1 = $_REQUEST["txtActualVariableFacilityCost"];
		}
		
		if($_REQUEST["txtActualPointtoPointCost"])
		{
			$pointToPointCost1 = $_REQUEST["txtActualPointtoPointCost"];
		}
		
		if($_REQUEST["txtActualDirectTelecomCost"])
		{
			$directTelecomCost1 = $_REQUEST["txtActualDirectTelecomCost"];
		}
		
		if($_REQUEST["txtActualFirstXXHoursatYYrate"])
		{
			$ownershipMarginTier11 = $_REQUEST["txtActualFirstXXHoursatYYrate"];
		}
		
		if($_REQUEST["txtActualAboveXXHoursatYYrate"])
		{
			$ownershipMarginTier21 = $_REQUEST["txtActualAboveXXHoursatYYrate"];
		}


//ACTUAL DATA END


$modifiedBy = $_SESSION[empID];
$modifiedDate = date("m/d/Y");



//Inserting values in to common..prm_rnet_CostSummaryBySite 
if(isset($_POST['Save']))
{

//If value is empty, assigning value as NULL 
	if($payableHours == "")$payableHours = 'NULL';
	if($agentRegularPay == "")$agentRegularPay = 'NULL';
	if($agentOTPay == "")$agentOTPay = 'NULL';
	if($agentTrainingPay == "")$agentTrainingPay = 'NULL';
	if($agentBonusPay == "")$agentBonusPay = 'NULL';
	if($agentIncentivePay == "")$agentIncentivePay = 'NULL';
	if($directLaborRegularPay == "")$directLaborRegularPay = 'NULL';
	if($directLaborOTPay == "")$directLaborOTPay = 'NULL';
	if($directLaborTrainingPay == "")$directLaborTrainingPay = 'NULL';
	if($directLaborBonusPay == "")$directLaborBonusPay = 'NULL';
	if($directLaborIncentivePay == "")$directLaborIncentivePay = 'NULL';
	if($adminRegularPay == "")$adminRegularPay = 'NULL';
	if($adminOTPay == "")$adminOTPay = 'NULL';
	if($adminTrainingPay == "")$adminTrainingPay = 'NULL';
	if($adminBonusPay == "")$adminBonusPay = 'NULL';
	if($adminIncentivePay == "")$adminIncentivePay = 'NULL';
	if($fixedFacilityCost == "")$fixedFacilityCost = 'NULL';
	if($variableFacilityCost == "")$variableFacilityCost = 'NULL';
	if($pointToPointCost == "")$pointToPointCost = 'NULL';
	if($directTelecomCost == "")$directTelecomCost = 'NULL';
	if($ownershipMarginTier1 == "")$ownershipMarginTier1 = 'NULL';
	if($ownershipMarginTier2 == "")$ownershipMarginTier2 = 'NULL';


	if($payableHours1 == "")$payableHours1 = 'NULL';
	if($agentRegularPay1 == "")$agentRegularPay1 = 'NULL';
	if($agentOTPay1 == "")$agentOTPay1 = 'NULL';
	if($agentTrainingPay1 == "")$agentTrainingPay1 = 'NULL';
	if($agentBonusPay1 == "")$agentBonusPay1 = 'NULL';
	if($agentIncentivePay1 == "")$agentIncentivePay1 = 'NULL';
	if($directLaborRegularPay1 == "")$directLaborRegularPay1 = 'NULL';
	if($directLaborOTPay1 == "")$directLaborOTPay1 = 'NULL';
	if($directLaborTrainingPay1 == "")$directLaborTrainingPay1 = 'NULL';
	if($directLaborBonusPay1 == "")$directLaborBonusPay1 = 'NULL';
	if($directLaborIncentivePay1 == "")$directLaborIncentivePay1 = 'NULL';
	if($adminRegularPay1 == "")$adminRegularPay1 = 'NULL';
	if($adminOTPay1 == "")$adminOTPay1 = 'NULL';
	if($adminTrainingPay1 == "")$adminTrainingPay1 = 'NULL';
	if($adminBonusPay1 == "")$adminBonusPay1 = 'NULL';
	if($adminIncentivePay1 == "")$adminIncentivePay1 = 'NULL';
	if($fixedFacilityCost1 == "")$fixedFacilityCost1 = 'NULL';
	if($variableFacilityCost1 == "")$variableFacilityCost1 = 'NULL';
	if($pointToPointCost1 == "")$pointToPointCost1 = 'NULL';
	if($directTelecomCost1 == "")$directTelecomCost1 = 'NULL';
	if($ownershipMarginTier11 == "")$ownershipMarginTier11 = 'NULL';
	if($ownershipMarginTier21 == "")$ownershipMarginTier21 = 'NULL';

	
	$queryInsert = " BEGIN TRANSACTION CostSummary 
					INSERT INTO common..prm_rnet_CostSummaryBySite(
					 									location,
														currentYear,
														currentMonth,
														isForecast,
														payableHours,
														agentRegularPay,
														agentOTPay,
														agentTrainingPay,
														agentBonusPay,
														agentIncentivePay,
														directLaborRegularPay,
														directLaborOTPay,
														directLaborTrainingPay,
														directLaborBonusPay,
														directLaborIncentivePay,
														adminRegularPay,
														adminOTPay,
														adminTrainingPay,
														adminBonusPay,
														adminIncentivePay,
														fixedFacilityCost,
														variableFacilityCost,
														pointToPointCost,
														directTelecomCost,
														ownershipMarginTier1,
														ownershipMarginTier2,
														modifiedBy,
														modifiedDate)
										VALUES(
														'$location',
														$currentYear,
														$currentMonth,
														'Y',
														$payableHours,
														$agentRegularPay,
														$agentOTPay,
														$agentTrainingPay,
														$agentBonusPay,
														$agentIncentivePay,
														$directLaborRegularPay,
														$directLaborOTPay,
														$directLaborTrainingPay,
														$directLaborBonusPay,
														$directLaborIncentivePay,
														$adminRegularPay,
														$adminOTPay,
														$adminTrainingPay,
														$adminBonusPay,
														$adminIncentivePay,
														$fixedFacilityCost,
														$variableFacilityCost,
														$pointToPointCost,
														$directTelecomCost,
														$ownershipMarginTier1,
														$ownershipMarginTier2,
														$modifiedBy,
														'$modifiedDate')
		          IF(@@ERROR <> 0) BEGIN
               		PRINT 'Failed to insert'
               		ROLLBACK TRANSACTION
          		END 														
														
					INSERT INTO common..prm_rnet_CostSummaryBySite(
					 									location,
														currentYear,
														currentMonth,
														isForecast,
														payableHours,
														agentRegularPay,
														agentOTPay,
														agentTrainingPay,
														agentBonusPay,
														agentIncentivePay,
														directLaborRegularPay,
														directLaborOTPay,
														directLaborTrainingPay,
														directLaborBonusPay,
														directLaborIncentivePay,
														adminRegularPay,
														adminOTPay,
														adminTrainingPay,
														adminBonusPay,
														adminIncentivePay,
														fixedFacilityCost,
														variableFacilityCost,
														pointToPointCost,
														directTelecomCost,
														ownershipMarginTier1,
														ownershipMarginTier2,
														modifiedBy,
														modifiedDate)
										VALUES(
														'$location',
														$currentYear,
														$currentMonth,
														'N',
														$payableHours1,
														$agentRegularPay1,
														$agentOTPay1,
														$agentTrainingPay1,
														$agentBonusPay1,
														$agentIncentivePay1,
														$directLaborRegularPay1,
														$directLaborOTPay1,
														$directLaborTrainingPay1,
														$directLaborBonusPay1,
														$directLaborIncentivePay1,
														$adminRegularPay1,
														$adminOTPay1,
														$adminTrainingPay1,
														$adminBonusPay1,
														$adminIncentivePay1,
														$fixedFacilityCost1,
														$variableFacilityCost1,
														$pointToPointCost1,
														$directTelecomCost1,
														$ownershipMarginTier11,
														$ownershipMarginTier21,
														$modifiedBy,
														'$modifiedDate')
														
				COMMIT TRANSACTION CostSummary  ";
														
		mssql_query(str_replace("\'","''",$queryInsert), $db);
		
		
	//Crearing the objects if values is NULL
	if($payableHours == 'NULL')$payableHours = "";
	if($agentRegularPay == 'NULL')$agentRegularPay = "";
	if($agentOTPay == 'NULL')$agentOTPay = "";
	if($agentTrainingPay == 'NULL')$agentTrainingPay = "";
	if($agentBonusPay == 'NULL')$agentBonusPay = "";
	if($agentIncentivePay == 'NULL')$agentIncentivePay = "";
	if($directLaborRegularPay == 'NULL')$directLaborRegularPay = "";
	if($directLaborOTPay == 'NULL')$directLaborOTPay = "";
	if($directLaborTrainingPay == 'NULL')$directLaborTrainingPay = "";
	if($directLaborBonusPay == 'NULL')$directLaborBonusPay = "";
	if($directLaborIncentivePay == 'NULL')$directLaborIncentivePay = "";
	if($adminRegularPay == 'NULL')$adminRegularPay = "";
	if($adminOTPay == 'NULL')$adminOTPay = "";
	if($adminTrainingPay == 'NULL')$adminTrainingPay = "";
	if($adminBonusPay == 'NULL')$adminBonusPay = "";
	if($adminIncentivePay == 'NULL')$adminIncentivePay = "";
	if($fixedFacilityCost == 'NULL')$fixedFacilityCost = "";
	if($variableFacilityCost == 'NULL')$variableFacilityCost = "";
	if($pointToPointCost == 'NULL')$pointToPointCost = "";
	if($directTelecomCost == 'NULL')$directTelecomCost = "";
	if($ownershipMarginTier1 == 'NULL')$ownershipMarginTier1 = "";
	if($ownershipMarginTier2 == 'NULL')$ownershipMarginTier2 = "";


	if($payableHours1 == 'NULL')$payableHours1 = "";
	if($agentRegularPay1 == 'NULL')$agentRegularPay1 = "";
	if($agentOTPay1 == 'NULL')$agentOTPay1 = "";
	if($agentTrainingPay1 == 'NULL')$agentTrainingPay1 = "";
	if($agentBonusPay1 == 'NULL')$agentBonusPay1 = "";
	if($agentIncentivePay1 == 'NULL')$agentIncentivePay1 = "";
	if($directLaborRegularPay1 == 'NULL')$directLaborRegularPay1 = "";
	if($directLaborOTPay1 == 'NULL')$directLaborOTPay1 = "";
	if($directLaborTrainingPay1 == 'NULL')$directLaborTrainingPay1 = "";
	if($directLaborBonusPay1 == 'NULL')$directLaborBonusPay1 = "";
	if($directLaborIncentivePay1 == 'NULL')$directLaborIncentivePay1 = "";
	if($adminRegularPay1 == 'NULL')$adminRegularPay1 = "";
	if($adminOTPay1 == 'NULL')$adminOTPay1 = "";
	if($adminTrainingPay1 == 'NULL')$adminTrainingPay1 = "";
	if($adminBonusPay1 == 'NULL')$adminBonusPay1 = "";
	if($adminIncentivePay1 == 'NULL')$adminIncentivePay1 = "";
	if($fixedFacilityCost1 == 'NULL')$fixedFacilityCost1 = "";
	if($variableFacilityCost1 == 'NULL')$variableFacilityCost1 = "";
	if($pointToPointCost1 == 'NULL')$pointToPointCost1 = "";
	if($directTelecomCost1 == 'NULL')$directTelecomCost1 = "";
	if($ownershipMarginTier11 == 'NULL')$ownershipMarginTier11 = "";
	if($ownershipMarginTier21 == 'NULL')$ownershipMarginTier21 = "";

	echo "<script type='text/javascript'>window.location='CostSummaryBySite.php?ddlLocations=$loc&ddlYear=$Year&ddlMonth=$Month';</script>";
	
}


//Calculation Part
if(isset($_POST['Calculate']))
{
	//Calculations
	
	$AgentHoursForCasted =  $agentRegularPay + $agentOTPay + $agentTrainingPay + $agentBonusPay + $agentIncentivePay;
	$AgentHoursActual =  $agentRegularPay1 + $agentOTPay1 + $agentTrainingPay1 + $agentBonusPay1 + $agentIncentivePay1;
		
	
	$DirectLaborForCasted = $directLaborRegularPay  + $directLaborOTPay + $directLaborTrainingPay + $directLaborBonusPay +  $directLaborIncentivePay;
	 $DirectLaborActual = $directLaborRegularPay1  + $directLaborOTPay1 + $directLaborTrainingPay1 + $directLaborBonusPay1 + $directLaborIncentivePay1;
	
	
	$AdministrativeLaborForCasted = $adminRegularPay + $adminOTPay1 + $adminTrainingPay + $adminBonusPay + $adminIncentivePay ;
	$AdministrativeLaborActual = $adminRegularPay1 + $adminOTPay1 + $adminTrainingPay1 + $adminBonusPay1 + $adminIncentivePay1;
	
	$TotalBonusForCasted = $agentBonusPay + $directLaborBonusPay + $adminBonusPay;
	$TotalBonusActual = $agentBonusPay1 + $directLaborBonusPay1 + $adminBonusPay1;
	
	$TotalIncentiveForCasted = $agentIncentivePay + $directLaborIncentivePay + $adminIncentivePay;
	$TotalIncentiveActual = $agentIncentivePay1 + $directLaborIncentivePay1 + $adminIncentivePay1; 
	
	$TotalPayrollForCasted = $AgentHoursForCasted + $DirectLaborForCasted  + $AdministrativeLaborForCasted;
	$TotalPayrollActual = $AgentHoursActual + $DirectLaborActual  + $AdministrativeLaborActual;
	
	$TotalCostPerHourForCasted = $TotalPayrollForCasted/$payableHours;
	$TotalCostPerHourActual = $TotalPayrollActual/$payableHours1 ;
	
	
	$AgentLaborCostPerHourForCasted =  $AgentHoursForCasted/$payableHours;
	$AgentLaborCostPerHourActual = $AgentHoursActual/$payableHours1;
	
	$DirectLaborCostPerHourForCasted = $DirectLaborForCasted/$payableHours;
	$DirectLaborCostPerHourActual = $DirectLaborActual/$payableHours1;
	
	$AdministrativeCostPerHourForCasted = $AdministrativeLaborForCasted/$payableHours;
	$AdministrativeCostPerHourActual = $AdministrativeLaborActual/$payableHours1;
	
	$OvertimeCostPerHourForCasted = $agentRegularPay/$payableHours;
	$OvertimeCostPerHourActual = $agentRegularPay1/$payableHours1;
	
	$TrainingCostPerHourForCasted = $agentTrainingPay/$payableHours;
	$TrainingCostPerHourActual = $agentTrainingPay1/$payableHours1;
	
	$BonusCostPerHourForCasted =  $TotalBonusForCasted/$payableHours;
	$BonusCostPerHourActual = $TotalBonusActual/$payableHours1;
	
	
	$IncentiveCostPerHourForCasted = $TotalIncentiveForCasted/$payableHours;
	$IncentiveCostPerHourActual = $TotalIncentiveActual/$payableHours1;
	
	$TotalFacilityCostForCasted = $fixedFacilityCost/$variableFacilityCost1;
	$TotalFacilityCostActual =  $fixedFacilityCost1/$variableFacilityCost1;
	
	$FacilityCostPerHourForCasted =  $TotalFacilityCostForCasted/$payableHours;
	$FacilityCostPerHourActual =  $TotalFacilityCostActual/$payableHours1;
	
	$PointtoPointCostPerHourForCasted =  $pointToPointCost/$payableHours;
	$PointtoPointCostPerHourActual =  $pointToPointCost1/$payableHours1;
	
	$DirectTelecomCostPerHourForCasted =  $directTelecomCost/$payableHours;
	$DirectTelecomCostPerHourActual = $directTelecomCost1/$payableHours1;
	
	$OwnershipMarginForCasted =  $ownershipMarginTier1 + $ownershipMarginTier2;
	$OwnershipMarginActual =  $ownershipMarginTier11 + $ownershipMarginTier21;
	
	$OwnershipMarginPerHourForCasted =  $OwnershipMarginForCasted/$payableHours;
	$OwnershipMarginPerHourActual =  $OwnershipMarginActual/$payableHours1;
	
	$TotalSiteCostsForCasted =  $TotalPayrollForCasted + $TotalFacilityCostForCasted + $pointToPointCost + $directTelecomCost + $OwnershipMarginForCasted;
	$TotalSiteCostsActual =  $TotalPayrollActual + $TotalFacilityCostActual +$pointToPointCost1 + $directTelecomCost1 + $OwnershipMarginActual;
	
	$TotalSiteCostsPerHourForCasted = $TotalSiteCostsForCasted/$payableHours;
	$TotalSiteCostsPerHourActual = $TotalSiteCostsActual/$payableHours1;

}

function numberFormat($number) 
{
   if($number != "")
   {
   		$print_number = " " .  number_format ($number, 2, ".", ",") ;
   		return $print_number;
	}
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Cost Summary By Site</title>

<link href="../Include/CSS/main.css" rel="stylesheet" type="text/css" />
<link href="../Include/CSS/dhtmlgoodies_calendar.css?random=20051112" media="screen" rel="stylesheet" type="text/css" />
<script src="ajax.js" type="text/javascript"></script>
<script language="javascript" src='../Include/javascript/dhtmlgoodies_calendar.js?random=20060118' type="text/javascript"></script>

<script language="JavaScript" type="text/JavaScript">
	function Validate()
	{ 

		if(!/^([-+]?\d{0,9}(\,\d{9})*|(\d+))(\.\d{1,2})?$/.test(document.form_data.txtForecastPaidHours.value))
		{
			 alert("Please enter decimal suffixed with 1 or 2 digits.");
			 document.form_data.txtForecastPaidHours.focus();
			 return false;
		 }
		 else if(!/^([-+]?\d{0,9}(\,\d{9})*|(\d+))(\.\d{1,2})?$/.test(document.form_data.txtActualPaidHours.value))
		{
			 alert("Please enter decimal suffixed with 1 or 2 digits.");
			 document.form_data.txtActualPaidHours.focus();
			 return false;
		 }
		 
		else if(!/^([-+]?\d{0,9}(\,\d{9})*|(\d+))(\.\d{1,2})?$/.test(document.form_data.txtForecastAgentRegular.value))
		{
			 alert("Please enter decimal suffixed with 1 or 2 digits.");
			 document.form_data.txtForecastAgentRegular.focus();
			 return false;
		 }
		else if(!/^([-+]?\d{0,9}(\,\d{9})*|(\d+))(\.\d{1,2})?$/.test(document.form_data.txtActualAgentRegular.value))
		{
			 alert("Please enter decimal suffixed with 1 or 2 digits.");
			 document.form_data.txtActualAgentRegular.focus();
			 return false;
		 }
		else if(!/^([-+]?\d{0,9}(\,\d{9})*|(\d+))(\.\d{1,2})?$/.test(document.form_data.txtForecastAgentOvertime.value))
		{
			 alert("Please enter decimal suffixed with 1 or 2 digits.");
			 document.form_data.txtForecastAgentOvertime.focus();
			 return false;
		 }
		else if(!/^([-+]?\d{0,9}(\,\d{9})*|(\d+))(\.\d{1,2})?$/.test(document.form_data.txtActualAgentOvertime.value))
		{
			 alert("Please enter decimal suffixed with 1 or 2 digits.");
			 document.form_data.txtActualAgentOvertime.focus();
			 return false;
		 }
		else if(!/^([-+]?\d{0,9}(\,\d{9})*|(\d+))(\.\d{1,2})?$/.test(document.form_data.txtForecastAgentTraining.value))
		{
			 alert("Please enter decimal suffixed with 1 or 2 digits.");
			 document.form_data.txtForecastAgentTraining.focus();
			 return false;
		 }
		else if(!/^([-+]?\d{0,9}(\,\d{9})*|(\d+))(\.\d{1,2})?$/.test(document.form_data.txtActualAgentTraining.value))
		{
			 alert("Please enter decimal suffixed with 1 or 2 digits.");
			 document.form_data.txtActualAgentTraining.focus();
			 return false;
		 }
		else if(!/^([-+]?\d{0,9}(\,\d{9})*|(\d+))(\.\d{1,2})?$/.test(document.form_data.txtForecastAgentBonus.value))
		{
			 alert("Please enter decimal suffixed with 1 or 2 digits.");
			 document.form_data.txtForecastAgentBonus.focus();
			 return false;
		 }
		else if(!/^([-+]?\d{0,9}(\,\d{9})*|(\d+))(\.\d{1,2})?$/.test(document.form_data.txtActualAgentBonus.value))
		{
			 alert("Please enter decimal suffixed with 1 or 2 digits.");
			 document.form_data.txtActualAgentBonus.focus();
			 return false;
		 }
		else if(!/^([-+]?\d{0,9}(\,\d{9})*|(\d+))(\.\d{1,2})?$/.test(document.form_data.txtForecastAgentIncentives.value))
		{
			 alert("Please enter decimal suffixed with 1 or 2 digits.");
			 document.form_data.txtForecastAgentIncentives.focus();
			 return false;
		 }
		else if(!/^([-+]?\d{0,9}(\,\d{9})*|(\d+))(\.\d{1,2})?$/.test(document.form_data.txtActualAgentIncentives.value))
		{
			 alert("Please enter decimal suffixed with 1 or 2 digits.");
			 document.form_data.txtActualAgentIncentives.focus();
			 return false;
		 }
		else if(!/^([-+]?\d{0,9}(\,\d{9})*|(\d+))(\.\d{1,2})?$/.test(document.form_data.txtForecastDirectLaborRegular.value))
		{
			 alert("Please enter decimal suffixed with 1 or 2 digits.");
			 document.form_data.txtForecastDirectLaborRegular.focus();
			 return false;
		 }
		else if(!/^([-+]?\d{0,9}(\,\d{9})*|(\d+))(\.\d{1,2})?$/.test(document.form_data.txtActualDirectLaborRegular.value))
		{
			 alert("Please enter decimal suffixed with 1 or 2 digits.");
			 document.form_data.txtActualDirectLaborRegular.focus();
			 return false;
		 }
		else if(!/^([-+]?\d{0,9}(\,\d{9})*|(\d+))(\.\d{1,2})?$/.test(document.form_data.txtForecastDirectLaborOvertime.value))
		{
			 alert("Please enter decimal suffixed with 1 or 2 digits.");
			 document.form_data.txtForecastDirectLaborOvertime.focus();
			 return false;
		 }
		else if(!/^([-+]?\d{0,9}(\,\d{9})*|(\d+))(\.\d{1,2})?$/.test(document.form_data.txtActualDirectLaborOvertime.value))
		{
			 alert("Please enter decimal suffixed with 1 or 2 digits.");
			 document.form_data.txtActualDirectLaborOvertime.focus();
			 return false;
		 }
		else if(!/^([-+]?\d{0,9}(\,\d{9})*|(\d+))(\.\d{1,2})?$/.test(document.form_data.txtForecastDirectLaborTraining.value))
		{
			 alert("Please enter decimal suffixed with 1 or 2 digits.");
			 document.form_data.txtForecastDirectLaborTraining.focus();
			 return false;
		 }
		else if(!/^([-+]?\d{0,9}(\,\d{9})*|(\d+))(\.\d{1,2})?$/.test(document.form_data.txtActualDirectLaborTraining.value))
		{
			 alert("Please enter decimal suffixed with 1 or 2 digits.");
			 document.form_data.txtActualDirectLaborTraining.focus();
			 return false;
		 }
		else if(!/^([-+]?\d{0,9}(\,\d{9})*|(\d+))(\.\d{1,2})?$/.test(document.form_data.txtForecastDirectLaborBonus.value))
		{
			 alert("Please enter decimal suffixed with 1 or 2 digits.");
			 document.form_data.txtForecastDirectLaborBonus.focus();
			 return false;
		 }
		else if(!/^([-+]?\d{0,9}(\,\d{9})*|(\d+))(\.\d{1,2})?$/.test(document.form_data.txtActualDirectLaborBonus.value))
		{
			 alert("Please enter decimal suffixed with 1 or 2 digits.");
			 document.form_data.txtActualDirectLaborBonus.focus();
			 return false;
		 }
		else if(!/^([-+]?\d{0,9}(\,\d{9})*|(\d+))(\.\d{1,2})?$/.test(document.form_data.txtForecastDirectLaborIncentives.value))
		{
			 alert("Please enter decimal suffixed with 1 or 2 digits.");
			 document.form_data.txtForecastDirectLaborIncentives.focus();
			 return false;
		 }
		else if(!/^([-+]?\d{0,9}(\,\d{9})*|(\d+))(\.\d{1,2})?$/.test(document.form_data.txtActualDirectLaborIncentives.value))
		{
			 alert("Please enter decimal suffixed with 1 or 2 digits.");
			 document.form_data.txtActualDirectLaborIncentives.focus();
			 return false;
		 }
		else if(!/^([-+]?\d{0,9}(\,\d{9})*|(\d+))(\.\d{1,2})?$/.test(document.form_data.txtForecastAdministrativeLaborRegular.value))
		{
			 alert("Please enter decimal suffixed with 1 or 2 digits.");
			 document.form_data.txtForecastAdministrativeLaborRegular.focus();
			 return false;
		 }
		else if(!/^([-+]?\d{0,9}(\,\d{9})*|(\d+))(\.\d{1,2})?$/.test(document.form_data.txtActualAdministrativeLaborRegular.value))
		{
			 alert("Please enter decimal suffixed with 1 or 2 digits.");
			 document.form_data.txtActualAdministrativeLaborRegular.focus();
			 return false;
		 }
		else if(!/^([-+]?\d{0,9}(\,\d{9})*|(\d+))(\.\d{1,2})?$/.test(document.form_data.txtForecastAdministrativeLaborOvertime.value))
		{
			 alert("Please enter decimal suffixed with 1 or 2 digits.");
			 document.form_data.txtForecastAdministrativeLaborOvertime.focus();
			 return false;
		 }
		else if(!/^([-+]?\d{0,9}(\,\d{9})*|(\d+))(\.\d{1,2})?$/.test(document.form_data.txtActualAdministrativeLaborOvertime.value))
		{
			 alert("Please enter decimal suffixed with 1 or 2 digits.");
			 document.form_data.txtActualAdministrativeLaborOvertime.focus();
			 return false;
		 }
		else if(!/^([-+]?\d{0,9}(\,\d{9})*|(\d+))(\.\d{1,2})?$/.test(document.form_data.txtForecastAdministrativeLaborTraining.value))
		{
			 alert("Please enter decimal suffixed with 1 or 2 digits.");
			 document.form_data.txtForecastAdministrativeLaborTraining.focus();
			 return false;
		 }
		else if(!/^([-+]?\d{0,9}(\,\d{9})*|(\d+))(\.\d{1,2})?$/.test(document.form_data.txtActualAdministrativeLaborTraining.value))
		{
			 alert("Please enter decimal suffixed with 1 or 2 digits.");
			 document.form_data.txtActualAdministrativeLaborTraining.focus();
			 return false;
		 }
		 else if(!/^([-+]?\d{0,9}(\,\d{9})*|(\d+))(\.\d{1,2})?$/.test(document.form_data.txtForecastAdministrativeLaborBonus.value))
		{
			 alert("Please enter decimal suffixed with 1 or 2 digits.");
			 document.form_data.txtForecastAdministrativeLaborBonus.focus();
			 return false;
		 }
		 else if(!/^([-+]?\d{0,9}(\,\d{9})*|(\d+))(\.\d{1,2})?$/.test(document.form_data.txtActualAdministrativeLaborBonus.value))
		{
			 alert("Please enter decimal suffixed with 1 or 2 digits.");
			 document.form_data.txtActualAdministrativeLaborBonus.focus();
			 return false;
		 }
		else if(!/^([-+]?\d{0,9}(\,\d{9})*|(\d+))(\.\d{1,2})?$/.test(document.form_data.txtForecastAdministrativeLaborIncentives.value))
		{
			 alert("Please enter decimal suffixed with 1 or 2 digits.");
			 document.form_data.txtForecastAdministrativeLaborIncentives.focus();
			 return false;
		 }
		else if(!/^([-+]?\d{0,9}(\,\d{9})*|(\d+))(\.\d{1,2})?$/.test(document.form_data.txtActualAdministrativeLaborIncentives.value))
		{
			 alert("Please enter decimal suffixed with 1 or 2 digits.");
			 document.form_data.txtActualAdministrativeLaborIncentives.focus();
			 return false;
		 }
 		else if(!/^([-+]?\d{0,9}(\,\d{9})*|(\d+))(\.\d{1,2})?$/.test(document.form_data.txtForecastFixedFacilityCost.value))
		{
			 alert("Please enter decimal suffixed with 1 or 2 digits.");
			 document.form_data.txtForecastFixedFacilityCost.focus();
			 return false;
		 }
		 else if(!/^([-+]?\d{0,9}(\,\d{9})*|(\d+))(\.\d{1,2})?$/.test(document.form_data.txtActualFixedFacilityCost.value))
		{
			 alert("Please enter decimal suffixed with 1 or 2 digits.");
			 document.form_data.txtActualFixedFacilityCost.focus();
			 return false;
		 }
		 else if(!/^([-+]?\d{0,9}(\,\d{9})*|(\d+))(\.\d{1,2})?$/.test(document.form_data.txtForecastVariableFacilityCost.value))
		{
			 alert("Please enter decimal suffixed with 1 or 2 digits.");
			 document.form_data.txtForecastVariableFacilityCost.focus();
			 return false;
		 }
		 else if(!/^([-+]?\d{0,9}(\,\d{9})*|(\d+))(\.\d{1,2})?$/.test(document.form_data.txtActualVariableFacilityCost.value))
		{
			 alert("Please enter decimal suffixed with 1 or 2 digits.");
			 document.form_data.txtActualVariableFacilityCost.focus();
			 return false;
		 }
		 else if(!/^([-+]?\d{0,9}(\,\d{9})*|(\d+))(\.\d{1,2})?$/.test(document.form_data.txtForecastPointtoPointCost.value))
		{
			 alert("Please enter decimal suffixed with 1 or 2 digits.");
			 document.form_data.txtForecastPointtoPointCost.focus();
			 return false;
		 }
		 else if(!/^([-+]?\d{0,9}(\,\d{9})*|(\d+))(\.\d{1,2})?$/.test(document.form_data.txtActualPointtoPointCost.value))
		{
			 alert("Please enter decimal suffixed with 1 or 2 digits.");
			 document.form_data.txtActualPointtoPointCost.focus();
			 return false;
		 }
		 else if(!/^([-+]?\d{0,9}(\,\d{9})*|(\d+))(\.\d{1,2})?$/.test(document.form_data.txtForecastDirectTelecomCost.value))
		{
			 alert("Please enter decimal suffixed with 1 or 2 digits.");
			 document.form_data.txtForecastDirectTelecomCost.focus();
			 return false;
		 }
		 else if(!/^([-+]?\d{0,9}(\,\d{9})*|(\d+))(\.\d{1,2})?$/.test(document.form_data.txtActualDirectTelecomCost.value))
		{
			 alert("Please enter decimal suffixed with 1 or 2 digits.");
			 document.form_data.txtActualDirectTelecomCost.focus();
			 return false;
		 }
		 else if(!/^([-+]?\d{0,9}(\,\d{9})*|(\d+))(\.\d{1,2})?$/.test(document.form_data.txtForecastFirstXXHoursatYYrate.value))
		{
			 alert("Please enter decimal suffixed with 1 or 2 digits.");
			 document.form_data.txtForecastFirstXXHoursatYYrate.focus();
			 return false;
		 }
		 else if(!/^([-+]?\d{0,9}(\,\d{9})*|(\d+))(\.\d{1,2})?$/.test(document.form_data.txtActualFirstXXHoursatYYrate.value))
		{
			 alert("Please enter decimal suffixed with 1 or 2 digits.");
			 document.form_data.txtActualFirstXXHoursatYYrate.focus();
			 return false;
		 }
		 else if(!/^([-+]?\d{0,9}(\,\d{9})*|(\d+))(\.\d{1,2})?$/.test(document.form_data.txtForecastAboveXXHoursatYYrate.value))
		{
			 alert("Please enter decimal suffixed with 1 or 2 digits.");
			 document.form_data.txtForecastAboveXXHoursatYYrate.focus();
			 return false;
		 }
		 else if(!/^([-+]?\d{0,9}(\,\d{9})*|(\d+))(\.\d{1,2})?$/.test(document.form_data.txtActualAboveXXHoursatYYrate.value))
		{
			 alert("Please enter decimal suffixed with 1 or 2 digits.");
			 document.form_data.txtActualAboveXXHoursatYYrate.focus();
			 return false;
		 }
	

	}
	

	
	
	
</script>
</head>
<body >

<div id="LOGO">
<div id="userinfo">	
		<?php include("../Include/class/DisplayUserinfo.php");?>
    </div>
	<div id="menu1"> 
	<a href="CostSummaryBySite.php?ddlLocations=<?=$loc;?>&ddlYear=<?=$Year;?>&ddlMonth=<?=$Month;?>">Back</a>
	<? include($_SERVER['DOCUMENT_ROOT']."/javascript_menu.php");?>
    </div>
</div>
<div id="content" style="padding-left:10px;">
<form method="POST" action="<? echo $_SERVER['PHP_SELF'];?>" name="form_data">
<input type="hidden" name="loc" id="loc" value="<?=$loc?>" />
<input type="hidden" name="Year" id="Year" value="<?=$Year?>" />
<input type="hidden" name="Month" id="Month" value="<?=$Month?>" />
<input type="hidden" name="monthDesc" id="monthDesc" value="<?=$monthDesc?>" />



  <table width="770" align="center" cellpadding="1" cellspacing="1" bordercolor="#CCCCCC" class="black_small" style="border-collapse:collapse;">
 <tr>
      <td colspan="5" align="left" bgcolor="#CCCCCC" class="ColumnHeader">Cost Summary - <?=$locationDes; ?>,&nbsp;<?=$monthDesc;?> <?=$Year?></td>
    </tr>
       <tr>
         <td height="17" style="text-align: left;">&nbsp;</td>
         <td style="text-align: center;">&nbsp;</td>
         <td style="text-align: center;">&nbsp;</td>
       </tr>
      <tr>
      <td width="154" style="text-align: left;"><strong></strong></td>
      <td width="236" style="text-align: left;"><strong>Forecasted</strong></td>
      <td width="368" style="text-align: left;"><strong>Actual</strong></td>
	  </tr>
	  
       <tr>
      <td width="154" style="text-align: left;"><strong></strong></td>
      <td width="236" style="text-align: left;"><strong></strong></td>
      <td width="368" style="text-align: left;"><strong></strong></td>
	  </tr>

      <tr>
      <td width="154" height="29" style="text-align: left;"><strong>Paid Hours</strong></td>
      <td width="236" style="text-align: left;"> 
	  <input name="txtForecastPaidHours" type="text" id="txtForecastPaidHours"  style="width: 150px" value="<?=$payableHours;?>"/></td> 
      <td width="368" style="text-align: left;">
	  <input name="txtActualPaidHours" type="text" id="txtActualPaidHours"  style="width: 150px" value="<?=$payableHours1;?>"/>	  </td>
	  </tr>
	  
      <tr>
      <td width="154" height="25" style="text-align: left;"><strong>Total Payroll (Dollars)</strong></td>
      <td width="236" style="text-align: left;"><?=numberFormat($TotalPayrollForCasted);?></td>
      <td width="368" style="text-align: left;"><?=numberFormat($TotalPayrollActual);?></td>
	  </tr>
	  
	  
  
      <tr>
      <td width="154" height="25" style="text-align: center;"><strong>Agent Labor</strong></td>
      <td width="236" style="text-align: left;"><?=numberFormat($AgentHoursForCasted);?></td>
      <td width="368" style="text-align: left;"><?=numberFormat($AgentHoursActual);?></td>
	  </tr>
	  
	  

      <tr>
      <td width="154" height="25" style="text-align: center;"><strong>Regular</strong></td>
      <td width="236" style="text-align: left;"><input name="txtForecastAgentRegular" type="text" id="txtForecastAgentRegular"  style="width: 150px" value="<?=$agentRegularPay;?>"/></td>
      <td width="368" style="text-align: left;"><input name="txtActualAgentRegular" type="text" id="txtActualAgentRegular"  style="width: 150px" value="<?=$agentRegularPay1;?>"/></td>
	  </tr>

      <tr>
      <td width="154" height="25" style="text-align: center;"><strong>Overtime</strong></td>
      <td width="236" style="text-align: left;"><input name="txtForecastAgentOvertime" type="text" id="txtForecastAgentOvertime"  style="width: 150px" value="<?=$agentOTPay;?>"/></td>
      <td width="368" style="text-align: left;"><input name="txtActualAgentOvertime" type="text" id="txtActualAgentOvertime"  style="width: 150px" value="<?=$agentOTPay1;?>"/></td>
	  </tr>
      <tr>
      <td width="154" height="25" style="text-align: center;"><strong>Training</strong></td>
      <td width="236" style="text-align: left;"><input name="txtForecastAgentTraining" type="text" id="txtForecastAgentTraining"  style="width: 150px" value="<?=$agentTrainingPay;?>"/></td>
      <td width="368" style="text-align: left;"><input name="txtActualAgentTraining" type="text" id="txtActualAgentTraining"  style="width: 150px" value="<?=$agentTrainingPay1;?>"/></td>
	  </tr>
	  
      <tr>
      <td width="154" height="25" style="text-align: center;"><strong>Bonus</strong></td>
      <td width="236" style="text-align: left;"><input name="txtForecastAgentBonus" type="text" id="txtForecastAgentBonus"  style="width: 150px" value="<?=$agentBonusPay;?>"/></td>
      <td width="368" style="text-align: left;"><input name="txtActualAgentBonus" type="text" id="txtActualAgentBonus"  style="width: 150px" value="<?=$agentBonusPay1;?>"/></td>
	  </tr>
	  
      <tr>
      <td width="154" height="25" style="text-align: center;"><strong>Incentives</strong></td>
      <td width="236" style="text-align: left;"><input name="txtForecastAgentIncentives" type="text" id="txtForecastAgentIncentives"  style="width: 150px" value="<?=$agentIncentivePay;?>"/></td>
      <td width="368" style="text-align: left;"><input name="txtActualAgentIncentives" type="text" id="txtActualAgentIncentives"  style="width: 150px" value="<?=$agentIncentivePay1;?>"/></td>
	  </tr>
	  
	  
	  
	  
	  <tr>
      <td width="154" height="25" style="text-align: center;"><strong>Direct Labor</strong></td>
      <td width="236" style="text-align: left;"><?=numberFormat($DirectLaborForCasted);?></td>
      <td width="368" style="text-align: left;"><?=numberFormat($DirectLaborActual);?></td>
	  </tr>
  
	  <tr>
      <td width="154" height="25" style="text-align: center;"><strong>Regular</strong></td>
      <td width="236" style="text-align: left;"><input name="txtForecastDirectLaborRegular" type="text" id="txtForecastDirectLaborRegular"  style="width: 150px" value="<?=$directLaborRegularPay;?>"/></td>
      <td width="368" style="text-align: left;"><input name="txtActualDirectLaborRegular" type="text" id="txtActualDirectLaborRegular"  style="width: 150px" value="<?=$directLaborRegularPay1;?>"/></td>
	  </tr>

      <tr>
      <td width="154" height="25" style="text-align: center;"><strong>Overtime</strong></td>
      <td width="236" style="text-align: left;"><input name="txtForecastDirectLaborOvertime" type="text" id="txtForecastDirectLaborOvertime"  style="width: 150px" value="<?=$directLaborOTPay;?>"/></td>
      <td width="368" style="text-align: left;"><input name="txtActualDirectLaborOvertime" type="text" id="txtActualDirectLaborOvertime"  style="width: 150px" value="<?=$directLaborOTPay1;?>"/></td>
	  </tr>

      <tr>
      <td width="154" height="25" style="text-align: center;"><strong>Training</strong></td>
      <td width="236" style="text-align: left;"><input name="txtForecastDirectLaborTraining" type="text" id="txtForecastDirectLaborTraining"  style="width: 150px" value="<?=$directLaborTrainingPay;?>"/></td>
      <td width="368" style="text-align: left;"><input name="txtActualDirectLaborTraining" type="text" id="txtActualDirectLaborTraining"  style="width: 150px" value="<?=$directLaborTrainingPay1;?>"/></td>
	  </tr>
	  
      <tr>
      <td width="154" height="25" style="text-align: center;"><strong>Bonus</strong></td>
      <td width="236" style="text-align: left;"><input name="txtForecastDirectLaborBonus" type="text" id="txtForecastDirectLaborBonus"  style="width: 150px" value="<?=$directLaborBonusPay;?>"/></td>
      <td width="368" style="text-align: left;"><input name="txtActualDirectLaborBonus" type="text" id="txtActualDirectLaborBonus"  style="width: 150px" value="<?=$directLaborBonusPay1;?>"/></td>
	  </tr>
	  
      <tr>
      <td width="154" height="25" style="text-align: center;"><strong>Incentives</strong></td>
      <td width="236" style="text-align: left;"><input name="txtForecastDirectLaborIncentives" type="text" id="txtForecastDirectLaborIncentives"  style="width: 150px" value="<?=$directLaborIncentivePay;?>"/></td>
      <td width="368" style="text-align: left;"><input name="txtActualDirectLaborIncentives" type="text" id="txtActualDirectLaborIncentives"  style="width: 150px" value="<?=$directLaborIncentivePay1;?>"/></td>
	  </tr>


	  <tr>
      <td width="154" height="25" style="text-align: center;"><strong>Administrative Labor</strong></td>
      <td width="236" style="text-align: left;"><?=numberFormat($AdministrativeLaborForCasted);?></td>
      <td width="368" style="text-align: left;"><?=numberFormat($AdministrativeLaborActual);?></td>
	  </tr>
	  

	  <tr>
      <td width="154" height="25" style="text-align: center;"><strong>Regular</strong></td>
      <td width="236" style="text-align: left;"><input name="txtForecastAdministrativeLaborRegular" type="text" id="txtForecastAdministrativeLaborRegular"  style="width: 150px" value="<?=$adminRegularPay;?>"/></td>
      <td width="368" style="text-align: left;"><input name="txtActualAdministrativeLaborRegular" type="text" id="txtActualAdministrativeLaborRegular"  style="width: 150px" value="<?=$adminRegularPay1;?>"/></td>
	  </tr>

      <tr>
      <td width="154" height="25" style="text-align: center;"><strong>Overtime</strong></td>
      <td width="236" style="text-align: left;"><input name="txtForecastAdministrativeLaborOvertime" type="text" id="txtForecastAdministrativeLaborOvertime"  style="width: 150px" value="<?=$adminOTPay;?>"/></td>
      <td width="368" style="text-align: left;"><input name="txtActualAdministrativeLaborOvertime" type="text" id="txtActualAdministrativeLaborOvertime"  style="width: 150px" value="<?=$adminOTPay1;?>"/></td>
	  </tr>

      <tr>
      <td width="154" height="25" style="text-align: center;"><strong>Training</strong></td>
      <td width="236" style="text-align: left;"><input name="txtForecastAdministrativeLaborTraining" type="text" id="txtForecastAdministrativeLaborTraining"  style="width: 150px" value="<?=$adminTrainingPay;?>"/></td>
      <td width="368" style="text-align: left;"><input name="txtActualAdministrativeLaborTraining" type="text" id="txtActualAdministrativeLaborTraining"  style="width: 150px" value="<?=$adminTrainingPay1;?>"/></td>
	  </tr>
	  
      <tr>
      <td width="154" height="25" style="text-align: center;"><strong>Bonus</strong></td>
      <td width="236" style="text-align: left;"><input name="txtForecastAdministrativeLaborBonus" type="text" id="txtForecastAdministrativeLaborBonus"  style="width: 150px" value="<?=$adminBonusPay;?>"/></td>
      <td width="368" style="text-align: left;"><input name="txtActualAdministrativeLaborBonus" type="text" id="txtActualAdministrativeLaborBonus"  style="width: 150px" value="<?=$adminBonusPay1;?>"/></td>
	  </tr>
	  
      <tr>
      <td width="154" height="25" style="text-align: center;"><strong>Incentives</strong></td>
      <td width="236" style="text-align: left;"><input name="txtForecastAdministrativeLaborIncentives" type="text" id="txtForecastAdministrativeLaborIncentives"  style="width: 150px" value="<?=$adminIncentivePay;?>"/></td>
      <td width="368" style="text-align: left;"><input name="txtActualAdministrativeLaborIncentives" type="text" id="txtActualAdministrativeLaborIncentives"  style="width: 150px" value="<?=$adminIncentivePay1;?>"/></td>
	  </tr>
	  
	  
       <tr>
      <td width="154" height="25" style="text-align: left;"><strong>Total Bonus (Dollars)</strong></td>
      <td width="236" style="text-align: left;"><?=numberFormat($TotalBonusForCasted);?></td>
      <td width="368" style="text-align: left;"><?=numberFormat($TotalBonusActual);?></td>
	  </tr>

      <tr>
      <td width="154" height="25" style="text-align: left;"><strong>Total Incentive (Dollars)</strong></td>
      <td width="236" style="text-align: left;"><?=numberFormat($TotalIncentiveForCasted);?></td>
      <td width="368" style="text-align: left;"><?=numberFormat($TotalIncentiveActual);?></td>
	  </tr>
	  
       <tr>
      <td width="154" height="25" style="text-align: left;"><strong>Total Cost Per Hour</strong></td>
      <td width="236" style="text-align: left;"><?=numberFormat($TotalCostPerHourForCasted);?></td>
      <td width="368" style="text-align: left;"><?=numberFormat($TotalCostPerHourActual);?></td>
	  </tr>
	  
	  
	         <tr>
      <td width="154" height="25" style="text-align: center;"><strong>Agent Labor Cost Per Hour</strong></td>
      <td width="236" style="text-align: left;"><?=numberFormat($AgentLaborCostPerHourForCasted);?></td>
      <td width="368" style="text-align: left;"><?=numberFormat($AgentLaborCostPerHourActual);?></td>
	  </tr>
	  
	  <tr>
      <td width="154" height="25" style="text-align: center;"><strong>Direct Labor Cost Per Hour</strong></td>
      <td width="236" style="text-align: left;"><?=numberFormat($DirectLaborCostPerHourForCasted);?></td>
      <td width="368" style="text-align: left;"><?=numberFormat($DirectLaborCostPerHourActual);?></td>
	  </tr>
	  
	  <tr>
      <td width="154" height="25" style="text-align: center;"><strong>Administrative Cost Per Hour</strong></td>
      <td width="236" style="text-align: left;"><?=numberFormat($AdministrativeCostPerHourForCasted);?></td>
      <td width="368" style="text-align: left;"><?=numberFormat($AdministrativeCostPerHourActual);?></td>
	  </tr>


       <tr>
      <td width="154" height="25" style="text-align: left;"><strong>Overtime Cost Per Hour</strong></td>
      <td width="236" style="text-align: left;"><?=numberFormat($OvertimeCostPerHourForCasted);?></td>
      <td width="368" style="text-align: left;"><?=numberFormat($OvertimeCostPerHourActual);?></td>
	  </tr>
	  
      <tr>
      <td width="154" height="25" style="text-align: left;"><strong>Training Cost Per Hour</strong></td>
      <td width="236" style="text-align: left;"><?=numberFormat($TrainingCostPerHourForCasted);?></td>
      <td width="368" style="text-align: left;"><?=numberFormat($TrainingCostPerHourActual);?></td>
	  </tr>
	  
      <tr>
      <td width="154" height="25" style="text-align: left;"><strong>Bonus Cost Per Hour</strong></td>
      <td width="236" style="text-align: left;"><?=numberFormat($BonusCostPerHourForCasted);?></td>
      <td width="368" style="text-align: left;"><?=numberFormat($BonusCostPerHourActual);?></td>
	  </tr>
	  
      <tr>
      <td width="154" height="25" style="text-align: left;"><strong>Incentive Cost Per Hour</strong></td>
      <td width="236" style="text-align: left;"><?=numberFormat($IncentiveCostPerHourForCasted);?></td>
      <td width="368" style="text-align: left;"><?=numberFormat($IncentiveCostPerHourActual);?></td>
	  </tr>
	  
      <tr>
      <td width="154" height="25" style="text-align: left;"><strong>Total Facility Cost (Dollars)</strong></td>
      <td width="236" style="text-align: left;"><?=numberFormat($TotalFacilityCostForCasted);?></td>
      <td width="368" style="text-align: left;"><?=numberFormat($TotalFacilityCostActual);?></td>
	  </tr>
	  
	  <tr>
      <td width="154" height="25" style="text-align: center;"><strong>Fixed Facility Cost</strong></td>
      <td width="236" style="text-align: left;"><input name="txtForecastFixedFacilityCost" type="text" id="txtForecastFixedFacilityCost"  style="width: 150px" value="<?=$fixedFacilityCost;?>"/></td>
      <td width="368" style="text-align: left;"><input name="txtActualFixedFacilityCost" type="text" id="txtActualFixedFacilityCost"  style="width: 150px" value="<?=$fixedFacilityCost1;?>"/></td>
	  </tr>
	  
      <tr>
      <td width="154" height="25" style="text-align: center;"><strong>Variable Facility Cost</strong></td>
      <td width="236" style="text-align: left;"><input name="txtForecastVariableFacilityCost" type="text" id="txtForecastVariableFacilityCost"  style="width: 150px" value="<?=$variableFacilityCost;?>"/></td>
      <td width="368" style="text-align: left;"><input name="txtActualVariableFacilityCost" type="text" id="txtActualVariableFacilityCost"  style="width: 150px" value="<?=$variableFacilityCost1;?>"/></td>
	  </tr>
	  
	  <tr>
      <td width="154" height="25" style="text-align: left;"><strong>Facility Cost Per Hour</strong></td>
      <td width="236" style="text-align: left;"><?=numberFormat($FacilityCostPerHourForCasted);?></td>
      <td width="368" style="text-align: left;"><?=numberFormat($FacilityCostPerHourActual);?></td>
	  </tr>
	 
	  <tr>
      <td width="154" height="25" style="text-align: left;"><strong>Point to Point Cost</strong></td>
      <td width="236" style="text-align: left;"><input name="txtForecastPointtoPointCost" type="text" id="txtForecastPointtoPointCost"  style="width: 150px" value="<?=$pointToPointCost;?>"/></td>
      <td width="368" style="text-align: left;"><input name="txtActualPointtoPointCost" type="text" id="txtActualPointtoPointCost"  style="width: 150px" value="<?=$pointToPointCost1;?>"/></td>
	  </tr>

	  <tr>
      <td width="154" height="25" style="text-align: left;"><strong>Point to Point Cost Per Hour</strong></td>
      <td width="236" style="text-align: left;"><?=numberFormat($PointtoPointCostPerHourForCasted);?></td>
      <td width="368" style="text-align: left;"><?=numberFormat($PointtoPointCostPerHourActual);?></td>
	  </tr>
	  
	  <tr>
      <td width="154" height="25" style="text-align: left;"><strong>Direct Telecom Cost</strong></td>
      <td width="236" style="text-align: left;"><input name="txtForecastDirectTelecomCost" type="text" id="txtForecastDirectTelecomCost"  style="width: 150px" value="<?=$directTelecomCost;?>"/></td>
      <td width="368" style="text-align: left;"><input name="txtActualDirectTelecomCost" type="text" id="txtActualDirectTelecomCost"  style="width: 150px" value="<?=$directTelecomCost1;?>"/></td>
	  </tr> 

	  <tr>
      <td width="154" height="25" style="text-align: left;"><strong>Direct Telecom Cost Per Hour</strong></td>
      <td width="236" style="text-align: left;"><?=numberFormat($DirectTelecomCostPerHourForCasted);?></td>
      <td width="368" style="text-align: left;"><?=numberFormat($DirectTelecomCostPerHourActual);?></td>
	  </tr>	  

	  <tr>
      <td width="154" height="25" style="text-align: left;"><strong>Ownership Margin (Dollars)</strong></td>
      <td width="236" style="text-align: left;"><?=numberFormat($OwnershipMarginForCasted);?></td>
      <td width="368" style="text-align: left;"><?=numberFormat($OwnershipMarginActual);?></td>
	  </tr>
	  <?
	  $queryOwn = " SELECT * FROM ctlLocationMarginRates 
	  			 WHERE 
				 location = $loc 
				 AND 
				 effectiveDate = (select max(effectiveDate) from ctlLocationMarginRates where location = $loc
								  AND
								  month(effectiveDate) <= '$Month' 
			                      AND 
								  year(effectiveDate)<= '$Year') ";
	
		$ownRst=mssql_query($queryOwn, $db);
		$isConfigOwnerShip = 0;
		if ($row=mssql_fetch_array($ownRst)) 
		{	
			 $xx = $row[thresholdHours];
			 $firstYY = $row[preThresholdRate];
			 $aboveYY = $row[postThresholdRate];
			 $isConfigOwnerShip = 1;
		}
	  if($isConfigOwnerShip == 0)
	  {?>
	  
	  	<tr>
		<td colspan="3" style="text-align: center;">
		<strong><font color="#FF0000" size="+1"> Please configure ownership margins for this location</font></strong>
		</td>
		</tr>
	  
	  <? }?>
	  
	  
	  
	  
  	  <tr>
      <td width="154" height="25" style="text-align: center;"><strong>First <?=$xx;?> Hours at <?=$firstYY;?> rate</strong></td>
      <td width="236" style="text-align: left;"><input name="txtForecastFirstXXHoursatYYrate" type="text" id="txtForecastFirstXXHoursatYYrate"  style="width: 150px" value="<?=$ownershipMarginTier1;?>"/></td>
      <td width="368" style="text-align: left;"><input name="txtActualFirstXXHoursatYYrate" type="text" id="txtActualFirstXXHoursatYYrate"  style="width: 150px" value="<?=$ownershipMarginTier11;?>"/></td>
	  </tr> 
	  
  	  <tr>
      <td width="154" height="25" style="text-align: center;"><strong>Above <?=$xx;?> Hours at <?=$aboveYY;?> rate</strong></td>
      <td width="236" style="text-align: left;"><input name="txtForecastAboveXXHoursatYYrate" type="text" id="txtForecastAboveXXHoursatYYrate"  style="width: 150px" value="<?=$ownershipMarginTier2;?>"/></td>
      <td width="368" style="text-align: left;"><input name="txtActualAboveXXHoursatYYrate" type="text" id="txtActualAboveXXHoursatYYrate"  style="width: 150px" value="<?=$ownershipMarginTier21;?>"/></td>
	  </tr> 

	  <tr>
      <td width="154" height="25" style="text-align: left;"><strong>Ownership Margin Per Hour</strong></td>
      <td width="236" style="text-align: left;"><?=numberFormat($OwnershipMarginPerHourForCasted);?></td>
      <td width="368" style="text-align: left;"><?=numberFormat($OwnershipMarginPerHourActual);?></td>
	  </tr>

	  <tr>
      <td width="154" height="25" style="text-align: left;"><strong>Total Site Costs</strong></td>
      <td width="236" style="text-align: left;"><?=numberFormat($TotalSiteCostsForCasted);?></td>
      <td width="368" style="text-align: left;"><?=numberFormat($TotalSiteCostsActual);?></td>
	  </tr>

	  <tr>
      <td width="154" height="25" style="text-align: left;"><strong>Total Site Costs  Per Hour</strong></td>
      <td width="236" style="text-align: left;"><?=numberFormat($TotalSiteCostsPerHourForCasted);?></td>
      <td width="368" style="text-align: left;"><?=numberFormat($TotalSiteCostsPerHourActual);?></td>
	  </tr>
	  <tr>
	    <td height="25" style="text-align: left;">&nbsp;</td>
	    <td style="text-align: left;"></td>
	    <td style="text-align: left;"></td>
      </tr>
	  <tr>
	    <td height="25" style="text-align: left;">&nbsp;</td>
	    <td style="text-align: left;">
		
		<input type="submit" value="Calculate" name="Calculate" id="Calculate" onclick="return Validate()" /> &nbsp;&nbsp;&nbsp;
		<input type="submit" value="Save" name="Save" id="Save" onclick="return Validate()"/>
		
		</td>
	    <td style="text-align: left;"></td>
      </tr>
	  <tr>
	    <td height="25" style="text-align: left;">&nbsp;</td>
	    <td style="text-align: left;"></td>
	    <td style="text-align: left;"></td>
      </tr>	  
  </table>
</form>
</div>
</body>
</html>
