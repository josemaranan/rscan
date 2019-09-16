<?php
include_once($_SERVER['DOCUMENT_ROOT']."/agentScoreCard/includes/agentScoreCard.class.inc.php");
$agentScoreObj3 = new agentScoreCard();
	$sessID = $_REQUEST[sessID];
	$pause = date("m/d/Y H:i:s");
	
	$query ="UPDATE RNet.dbo.[prmEmployeeCoachingSessions]
			SET 
				pause = '$pause'
			WHERE 
				coachSessionID = '$sessID' ";
				
	$result = $agentScoreObj3->ExecuteQuery($query);
	$agentScoreObj3->closeConn();
?>