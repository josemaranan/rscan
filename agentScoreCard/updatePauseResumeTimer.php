<?php
include_once($_SERVER['DOCUMENT_ROOT']."/agentScoreCard/includes/agentScoreCard.class.inc.php");
$agentScoreObj3 = new agentScoreCard();
	
	$sessID = $_REQUEST[sessID];
	$type = $_REQUEST[type];
	
	
	
	$pauseResume = date("m/d/Y H:i:s");
	
	/*if($type == 'pause')
	{
	
	$query ="UPDATE RNet.dbo.[prmEmployeeCoachingSessions]
			SET 
				pause = '$pauseResume'
			WHERE 
				coachSessionID = '$sessID' ";
	}
	else if($type == 'resume')
	{
	
	$query ="UPDATE RNet.dbo.[prmEmployeeCoachingSessions]
			SET 
				resume = '$pauseResume'
			WHERE 
				coachSessionID = '$sessID' ";
	}
	*/
	$query ="EXEC Rnet.dbo.[process_spUpdatePauseResumeTimer] '$sessID', '$type', '$pauseResume' ";
	
	$result = $agentScoreObj3->ExecuteQuery($query);

?>
<?php $agentScoreObj3->closeConn();?>