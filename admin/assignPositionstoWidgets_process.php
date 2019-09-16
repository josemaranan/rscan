<?php
ob_start();
/**
 * @description : Configure position to workflow widgets
 * @author : Vasudev
 * @date : 08/14/2014
 * */
 
include_once($_SERVER['DOCUMENT_ROOT'] . '/RNetIncludes/class1/includeClassFiles.php');
$RDSObj = new RDSData(MSSQL_HOST, MSSQL_USERNAME, MSSQL_PASSWORD, MSSQL_DB, MSSQL_TYPE, MSSQL_PORT, '');


$ddlWorkFlows 		= 	'';
$ddlDepartment 		= 	'';
$hdnValues			=	'';
if(isset($_REQUEST['hdnddlWorkFlows']))
{
	$ddlWorkFlows = $_REQUEST['hdnddlWorkFlows'];		
}


if(isset($_REQUEST['hdnddlDepartment']))
{
	$ddlDepartment = $_REQUEST['hdnddlDepartment'];		
}



unset($allPositionString);
unset($allWorkFlowString);

foreach($_REQUEST[hdnVal] as $key=>$value)
{
		unset($valueArray);
		$valueArray = explode('||' , $value);
		if(!empty($valueArray[0]))
		{
			$allPositionString .= trim($valueArray[0]).',';
		}
		
		if(!empty($valueArray[1]))
		{
			$allWorkFlowString .= trim($valueArray[1]).',';
		}
}
$allPositionString = substr($allPositionString,0,-1);
$allWorkFlowString = substr($allWorkFlowString,0,-1);

unset($sqlQuery);
unset($resultsSet);

$sqlQuery  = " DELETE 
					FROM Rnet.dbo.prmWorkflowPositions 
			   WHERE 
			   		positionID IN (".$allPositionString.") 
				AND
					workFlowID IN (".$allWorkFlowString.") ";


					
					
//echo $sqlQuery;

$resultsSet = $RDSObj->execute($sqlQuery);

unset($sqlQuery);
unset($resultsSet);

foreach($_REQUEST[ckhBVox] as $keyEx=>$ValueEx)
{
		unset($vArray);
		if(!empty($ValueEx))
		{
			$vArray = explode('||' , $ValueEx);
			$sqlQuery .= " INSERT INTO Rnet.dbo.prmWorkflowPositions
							VALUES (
										'".trim($vArray[1])."',
										'".trim($vArray[0])."'
									)";
							
		}
}

//echo $sqlQuery;

$resultsSet = $RDSObj->execute($sqlQuery);
if(!empty($ddlDepartment))
{
	header("Location:assignPositionstoWidgets_configure.php?ddlWorkFlows=".$ddlWorkFlows."&ddlDepartment=".urlencode($ddlDepartment));
}
else
{
	header("Location:assignPositionstoWidgets_search.php");	
}
exit();
?>