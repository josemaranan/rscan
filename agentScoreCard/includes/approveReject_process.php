<?php
include_once($_SERVER['DOCUMENT_ROOT']."/agentScoreCard/includes/agentScoreCard.class.inc.php");
$agentScoreObj = new agentScoreCard();

$client = $_SESSION[agentScoreClient];
$lob_id = $_SESSION[agentScoreCardLob_id];

if(strtoupper($client) == 'HELIO')
{
	$client2 = 'Sprint';
}
else
{
	$client2 = $client;
}

/*

echo '<pre>';
print_r($_REQUEST);
echo '</pre>';*/
unset($hdnEmployeeID);
unset($hdnRequestedDate);
unset($hdnIsSuccess);
unset($ddlReason);
unset($isRecordExist);

if(isset($_REQUEST['hdnEmployeeID']))
{
	$hdnEmployeeID = $_REQUEST['hdnEmployeeID'];
}

if(isset($_REQUEST['hdnRequestedDate']))
{
	$hdnRequestedDate = $_REQUEST['hdnRequestedDate'];
}

if(!empty($hdnEmployeeID) && !empty($hdnRequestedDate) )
{
	
	$returnMonth = date('m',strtotime($hdnRequestedDate));
	$returnYear = date('Y',strtotime($hdnRequestedDate));
	
	if(isset($_REQUEST['hdnIsSuccess']))
	{
		$hdnIsSuccess = $_REQUEST['hdnIsSuccess'];
	}
	
	if(isset($_REQUEST['texReason']))
	{
		$ddlReason = addslashes($_REQUEST['texReason']);
	}
	
	$isRecordExist = $agentScoreObj->isRecordsExists($hdnEmployeeID , $hdnRequestedDate, $client, $lob_id );
	
	
	if($isRecordExist!='exist')
	{
		$returnResSet = $agentScoreObj->insertRecord($hdnEmployeeID , $hdnRequestedDate, $hdnIsSuccess, $ddlReason, $client, $lob_id);
	}
	else
	{
		$returnResSet = $agentScoreObj->updateRecord($hdnEmployeeID , $hdnRequestedDate, $hdnIsSuccess, $ddlReason, $client, $lob_id);
	}
	
	
	if($returnResSet)
	{
		header('Location:../viewMyScoreCard.php?move_month='.$returnMonth.'&year_flag='.$returnYear.'');	
		exit;	
	}
	
}
else
{
	header('Location:../viewMyScoreCard.php?move_month='.$returnMonth.'&year_flag='.$returnYear.'&error=updateError');	
	exit;
}
$agentScoreObj>closeConn();
?>