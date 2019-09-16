<?php
$width = '900';
$height = '500';
$title = '';


$startDate = date('m/d/Y');
$endDate = date('m/d/Y');

/*echo '<pre>';
print_r($_REQUEST);
echo '</pre>';*/
//exit;


if(isset($_REQUEST['startDate']))
{
	$startDate = $_REQUEST['startDate'];	
}


if(isset($_REQUEST['endDate']))
{
	$endDate = $_REQUEST['endDate'];	
}


if(isset($_REQUEST['type']))
{
	$type = $_REQUEST['type'];	
}

if(isset($_REQUEST['measure']))
{
	$measure = $_REQUEST['measure'];	
}

if(isset($_REQUEST['period']))
{
	$period = $_REQUEST['period'];	
}

if(isset($_REQUEST['employeID']))
{
	$employeID = $_REQUEST['employeID'];	
}

if(isset($_REQUEST['defaultUrl']))
{
	$defaultUrl = $_REQUEST['defaultUrl'];	
}
if(isset($_REQUEST['dLOB']))
{
	$lob = $_REQUEST['dLOB'];	
}
if(isset($_REQUEST['sectionDetailID']))
{
	$sectionDetailID = $_REQUEST['sectionDetailID'];	
}
if(isset($_REQUEST['reportType']))
{
	$reportType = $_REQUEST['reportType'];	
}
if(isset($_REQUEST['height']))
{
	$height = $_REQUEST['height'];	
}
if(isset($_REQUEST['width']))
{
	$width = $_REQUEST['width'];	
}


if(isset($_REQUEST['isBar']))
{
	$isBar = $_REQUEST['isBar'];	
}


if(isset($_REQUEST['isPie']))
{
	$isPie = $_REQUEST['isPie'];	
}






$count = 0;
unset($sqlQuery);
unset($resultsSet);
unset($numRows);
unset($stackData);
unset($periodParameter);
unset($currentSP);


if($period=='WTD' || $period=='MTD')
{
	$trStryleArray = array('0'=>'<tr style="height:20px; background-color:#D0D8E8;" class="hidden">', '1'=>'<tr style="height:20px; background-color:#E9EDF4;" class="hidden">', '3'=>'<tr style="height:20px; background-color:#999999;" class="main" >', '4'=>'<tr style="height:20px; background-color:#E9EDF4;">');
}
else
{
	$trStryleArray = array('0'=>'<tr style="height:20px; background-color:#D0D8E8;">', '1'=>'<tr style="height:20px; background-color:#E9EDF4;">', '3'=>'<tr style="height:20px; background-color:#999999;" class="main" >', '4'=>'<tr style="height:20px; background-color:#E9EDF4;">');
}
if($reportType!='tscCustom')
{

	$getStoreProcedure = $agentScoreObj->getStoreProcedureDetails($sectionDetailID);
	unset($columnHeading);
	unset($type);
	unset($SP);
	$columnHeading = $getStoreProcedure[0]['columnHeading'];
	$type = $getStoreProcedure[0]['scoreCardSectionName'];
	$SP = $getStoreProcedure[0]['SP'];
	$pointingTo = $getStoreProcedure[0]['pointingTo'];
	/*
	echo '<pre>';
	print_r($getStoreProcedure);
	echo '</pre>';
	*/
	$parameters = $agentScoreObj->getParameterTitle($period, $columnHeading , $type, $lob);
	$title = $parameters[0];	
	$periodParameter = $parameters[1];
	$subTitle = $parameters[2];
	
	/*echo 'EmpId'.$employeID.'<br>';
	echo 'startDate'.$startDate.'<br>';
	echo 'periodParameter'.$periodParameter.'<br>';
	echo 'sp'.$SP.'<br>';
	*/
	$requiredData = $agentScoreObj->getData($employeID, $startDate, $periodParameter, $SP, $_SESSION[agentScoreClient], $pointingTo);
	//echo 'employeeID'.$employeID.'<br>';
	$measure = str_replace(array('<br>','<br />', '<br/>'),'',$columnHeading);
	
	switch($_SESSION[agentScoreClient])
	{
		case 'Wellcare':
			switch($measure)
			{
				case 'AHT':
					$iconf = 'pie_chart_aht.png';	
				break;
				
				case 'Calls Handled':
					$iconf = 'pie_chart_calls_handled.png';
				break;
				default:
					$iconf = 'pie_chart.png';
				break;
			}
		break;
		
		default:
			$iconf = 'pie_chart.png';
		break;
		
	}
}
?>