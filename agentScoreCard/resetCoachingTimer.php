<?php
include_once($_SERVER['DOCUMENT_ROOT']."/agentScoreCard/includes/agentScoreCard.class.inc.php");
$agentScoreObj3 = new agentScoreCard();

$sessID = $_REQUEST[sessID];

if(!empty($sessID))
{
	$startTimer = date("m/d/Y H:i:s");
	$cdate = date("m/d/Y");


/*$query =" DELETE 
				FROM 
			RNet.dbo.[prmEmployeeCoachingSessions]
		WHERE
			coachSessionID = $sessID  
			
			DELETE 
				FROM 
			RNet.dbo.[prmEmployeeCoachingSessionDetails]
		WHERE
			coachSessionID = $sessID 
			

		DELETE 
				FROM 
			RNet.dbo.[prmEmployeeCoachingSessionCalls]
		WHERE
			coachSessionID = $sessID 

			
			";*/
$query = "EXEC Rnet.dbo.[rnet_spResetCoachingTimer] '$sessID'";			
$result = $agentScoreObj3->ExecuteQuery($query);






}


$agentScoreObj3->closeConn();
?>