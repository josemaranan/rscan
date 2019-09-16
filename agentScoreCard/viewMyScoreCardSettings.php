<?php
session_start();

if($_REQUEST[client] == 'H')
{
$_SESSION[client] = 'H&R Block';
$_SESSION[agentScoreClient] = 'H&R Block';
}
else
{
$_SESSION[client] = $_REQUEST[client];
$_SESSION[agentScoreClient] = $_REQUEST[client];
	
	
}



$_SESSION[isScoreCardApplicable] = $_REQUEST[isScoreCardApplicable];
$_SESSION[isCoachingApplicable] = $_REQUEST[isCoachingApplicable];




$_SESSION[agentScoreCardIsLobLevel] = $_REQUEST[isLobLevel];
$_SESSION[agentScoreCardLob_id] = $_REQUEST[lob_id];
$_SESSION['scoreId'] = $_REQUEST['scoreId'];

$_SESSION['isMyEurekaApplicable'] = $_REQUEST[isMyEurekaApplicable];
$_SESSION['myEurekaLink'] = $_REQUEST[myEurekaLink];

$_SESSION['scoreCardEntityID'] = $_REQUEST['scoreCardEntityID'];
//echo $_SESSION[agentScoreCardIsLobLevel];
//echo $_SESSION[agentScoreCardLob_id];



header('Location:viewMyScoreCard.php');	
exit;


?>