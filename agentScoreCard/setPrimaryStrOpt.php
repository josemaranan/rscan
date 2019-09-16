<?php
include_once($_SERVER['DOCUMENT_ROOT']."/agentScoreCard/includes/agentScoreCard.class.inc.php");
$agentScoreObj3 = new agentScoreCard();
	
	
	$type = $_REQUEST[type];
	$kpiID = $_REQUEST[kpiID];
	$behaviorID = $_REQUEST[behaviorID];
	$sessionID = $_REQUEST[sessionID];
	$callID = $_REQUEST[callID];
	$methodID = $_REQUEST[methodID];
	
	
	$query ="EXEC Rnet.dbo.[process_spUpdatePrimaryStrOpt] '$sessionID', '$type', '$kpiID', '$callID', '$behaviorID', '$methodID' ";	
	
	/*$query ="
	
			UPDATE 
				RNet.dbo.[prmEmployeeCoachingSessionDetails]
			SET
				isPrimary = 'N'
			WHERE 
				coachSessionID = '$sessionID' 
				AND
				coachSessionCallID = '$callID'
				AND
				type = '$type' 
				";
	
	
		$query .= "	UPDATE 
				RNet.dbo.[prmEmployeeCoachingSessionDetails]
			SET
				isPrimary = 'Y'
			WHERE 
				coachSessionID = '$sessionID' 
				AND
				type = '$type'
				AND
				KPIID = '$kpiID'
				AND
				behaviorID = '$behaviorID'
				AND
				coachSessionCallID = '$callID'
				";
	
	if($type == 'Opportunities')
	{
		$query .= " AND methodID = '$methodID' ";
	}*/
	
	$result = $agentScoreObj3->ExecuteQuery($query);

	echo $query;
	//echo 'str'.'|'.$callID;
	
?>
<?php $agentScoreObj3->closeConn();?>