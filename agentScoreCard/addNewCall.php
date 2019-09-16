<?php
include_once($_SERVER['DOCUMENT_ROOT']."/agentScoreCard/includes/agentScoreCard.class.inc.php");
$agentScoreObj5 = new agentScoreCard();


$ISSessionCallIDExisted = $_REQUEST[ISSessionCallIDExisted];
$sessionCallID = $_REQUEST[sessionCallID];
$ucid = $_REQUEST[ucid];
$qaAlert = $_REQUEST[qaAlert];
$evaluationMethod = $_REQUEST[evaluationMethod];
$sessID = $_REQUEST[sessID];



	$startTimer = date("m/d/y h:i:s");
	$cdate = date("m/d/y");


	$query1 ="EXEC Rnet.dbo.[rnet_spGetCoachSessionCallID] '$sessID',
					 '$evaluationMethod',
					 '$ucid',
					 '$qaAlert'";
	

		$result = $agentScoreObj5->ExecuteQuery($query1);
		$v=mssql_fetch_array($result);
		$sessionCallID=$v[0];
	 
 		echo $sessionCallID;
 		$agentScoreObj5->closeConn();
?>