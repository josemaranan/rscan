<?php
include_once($_SERVER['DOCUMENT_ROOT']."/agentScoreCard/includes/agentScoreCard.class.inc.php");
$agentScoreObj4 = new agentScoreCard();

$KPI = $_REQUEST[KPI];
$eventID =addslashes($_REQUEST[eventID]);
$behavior = $_REQUEST[behavior];
$sessID = $_REQUEST[sessID];



$ISSessionCallIDExisted = $_REQUEST[ISSessionCallIDExisted];
$sessionCallID = $_REQUEST[sessionCallID];
$ucid = $_REQUEST[ucid];
$qaAlert = $_REQUEST[qaAlert];
$evaluationMethod = $_REQUEST[evaluationMethod];



	$startTimer = date("m/d/y h:i:s");
	$cdate = date("m/d/y");
	
	
 if($ISSessionCallIDExisted == 'N')
 {
	/*$query1 =" 	 DECLARE @coachSessionCallID INT
				INSERT INTO 
					RNet.dbo.prmEmployeeCoachingSessionCalls
					(
						coachSessionID,
						evaluationMethodID,
						UCID,
						QAAlert
					)
				VALUES
					(
					 '$sessID',
					 '$evaluationMethod',
					 '$ucid',
					 '$qaAlert'
					 )
			SET @coachSessionCallID = @@IDENTITY
			SELECT @coachSessionCallID
			";*/
$query1 ="EXEC Rnet.dbo.[process_spAddEmployeeCoachingSessionCalls] '$sessID', '$evaluationMethod', '$ucid', '$qaAlert' ";
$result = $agentScoreObj4->ExecuteQuery($query1);
		$v=mssql_fetch_array($result);
		$sessionCallID=$v[0];
	 
 }
	
	
	


/*$query =" 
			INSERT INTO  RNet.dbo.[prmEmployeeCoachingSessionDetails]
			(
			 	coachSessionID,
				type,
				KPIID,
				coachSessionCallID,
				behaviorID
			 )
			VALUES
			(
			 '$sessID',
			 'Strength',
			 '$KPI',
			 '$sessionCallID',
			 '$behavior'
			 )
			
			
			IF (SELECT COUNT(*) FROM RNet.dbo.prmEmployeeCoachingSessionDetails (NOLOCK) WHERE type = 'Strength' AND coachSessionID = $sessID AND coachSessionCallID = $sessionCallID) = 1
			BEGIN
				UPDATE 
					RNet.dbo.prmEmployeeCoachingSessionDetails 
				SET
					isPrimary = 'Y' 
				WHERE 
					type = 'Strength' 
					AND 
					coachSessionID = $sessID 
					AND 
					coachSessionCallID = $sessionCallID
			END
			
			
	
			";*/
			
$query ="EXEC Rnet.dbo.[process_spAddEmployeeCoachingSessionDetails] '$sessID', 'Strength', '$KPI', '$sessionCallID', '$behavior' ";			
$result = $agentScoreObj4->ExecuteQuery($query);

		
		echo 'str'.'|'.$sessionCallID;
		$agentScoreObj4->closeConn();
?>