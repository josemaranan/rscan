<?php
include_once($_SERVER['DOCUMENT_ROOT']."/agentScoreCard/includes/agentScoreCard.class.inc.php");
$agentScoreObj3 = new agentScoreCard();
	
	
	$type = $_REQUEST[type];
	$kpiID = $_REQUEST[kpiID];
	$behaviorID = $_REQUEST[behaviorID];
	$sessionID = $_REQUEST[sessionID];
	$callID = $_REQUEST[callID];
	$methodID = $_REQUEST[methodID];
	
	/*
	$query ="
			DECLARE @coachingSessionDetailID INT 
			DELETE 
				FROM RNet.dbo.[prmEmployeeCoachingSessionDetails]
			WHERE 
				coachSessionID = '$sessionID' 
				AND
				type = '$type'
				AND
				KPIID = '$kpiID'
				AND
				behaviorID = '$behaviorID'
				AND
				methodID = '$methodID'
				AND
				coachSessionCallID = '$callID'
				
				
				IF (SELECT COUNT(*) FROM RNet.dbo.prmEmployeeCoachingSessionDetails (NOLOCK) WHERE type = '$type' AND coachSessionID = $sessionID AND coachSessionCallID = $callID AND isPrimary = 'Y') = 0
			BEGIN
			
				SET @coachingSessionDetailID = (SELECT MIN(coachingSessionDetailID) FROM RNet.dbo.prmEmployeeCoachingSessionDetails (NOLOCK) 
														 WHERE type = '$type' AND coachSessionID = $sessionID AND coachSessionCallID = $callID )
				UPDATE 
					RNet.dbo.prmEmployeeCoachingSessionDetails 
				SET
					isPrimary = 'Y' 
				WHERE 
					coachingSessionDetailID = @coachingSessionDetailID
			END
				
				
				";*/
				
	$query ="EXEC Rnet.dbo.[process_spDeleteEmployeeCoachingOppurtunities] '$sessionID', '$type', '$kpiID', '$callID', '$behaviorID','$methodID'  ";	
	
	$result = $agentScoreObj3->ExecuteQuery($query);

	echo 'opt'.'|'.$callID;
	
	$agentScoreObj3->closeConn();
	
?>