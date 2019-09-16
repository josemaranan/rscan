<?php
include_once($_SERVER['DOCUMENT_ROOT']."/agentScoreCard/includes/agentScoreCard.class.inc.php");
$agentScoreObj5 = new agentScoreCard();

$KPI = $_REQUEST[KPI];
$eventID = addslashes($_REQUEST[eventID]);
$behavior = $_REQUEST[behavior];
$sessID = $_REQUEST[sessID];

$method = $_REQUEST[method];
$actionplan = addslashes($_REQUEST[actionplan]);


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

		$result = $agentScoreObj5->ExecuteQuery($query1);
		$v=mssql_fetch_array($result);
		$sessionCallID=$v[0];
	 
 }


/*
$query =" 
			INSERT INTO  RNet.dbo.[prmEmployeeCoachingSessionDetails]
			(
			 	coachSessionID,
				type,
				KPIID,
				coachSessionCallID,
				behaviorID,
				methodID,
				actionPlan
			 )
			VALUES
			(
			 '$sessID',
			 'Opportunities',
			 '$KPI',
			 '$sessionCallID',
			 '$behavior',
			 '$method',
			 '$actionplan'
			 )
	
	
	
	
			IF (SELECT COUNT(*) FROM RNet.dbo.prmEmployeeCoachingSessionDetails (NOLOCK) WHERE type = 'Opportunities' AND coachSessionID = $sessID AND coachSessionCallID = $sessionCallID) = 1
			BEGIN
				UPDATE 
					RNet.dbo.prmEmployeeCoachingSessionDetails 
				SET
					isPrimary = 'Y' 
				WHERE 
					type = 'Opportunities' 
					AND 
					coachSessionID = $sessID 
					AND 
					coachSessionCallID = $sessionCallID
			END
	
	
	
	
			";*/
$type = 'Opportunities';			
$query ="EXEC Rnet.dbo.[process_spAddEmployeeOppurtunities] '$sessID', '$type', '$KPI', '$sessionCallID', '$behavior', '$method', '$actionplan' ";				
//echo $query; exit();
$result = $agentScoreObj5->ExecuteQuery($query);

		
		echo 'opt'.'|'.$sessionCallID;
		$agentScoreObj5->closeConn();
?>