<?
session_start();
include_once($_SERVER['DOCUMENT_ROOT']."/agentScoreCard/includes/agentScoreCard.class.inc.php");
$agentScoreObj3 = new agentScoreCard();


$sessID = $_REQUEST[sessID];
$check = addslashes($_REQUEST[check]);
$set = addslashes($_REQUEST[set]);
$inspect = addslashes($_REQUEST[inspect]);
$empComDate = $_REQUEST[empComDate];
$coachFollowdate = $_REQUEST[coachFollowdate];
$mainBehCoached = $_REQUEST[mainBehCoached];

/*$query ="UPDATE 
			RNet.dbo.[prmEmployeeCoachingSessions]
		SET 
			setValue = '$set',
			checkValue = '$check',
			inspectValue = '$inspect',
			employeeCommitmentDate = '$empComDate',
			coachFollowUpDate = '$coachFollowdate',
			mainBehaviourCoachID = '$mainBehCoached'
		WHERE 
			coachSessionID = '$sessID' ";*/
$query = "EXEC Rnet.dbo.[process_spUpdateEmployeeCoachingSession] '$set', '$check', '$inspect', '$empComDate', '$coachFollowdate', '$mainBehCoached', '$sessID' ";				
	$result = $agentScoreObj3->ExecuteQuery($query);


echo 'gotoScoreCard';	
?>
<?php $agentScoreObj3->closeConn();?>