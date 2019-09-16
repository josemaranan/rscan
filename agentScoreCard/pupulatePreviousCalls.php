<?php

include_once($_SERVER['DOCUMENT_ROOT']."/agentScoreCard/includes/agentScoreCard.class.inc.php");
$agentScoreObj = new agentScoreCard();



$coachingSessionID = $_REQUEST[sessID];
//$coachingSessionID = 648;

$sessionCallID = $_REQUEST[sessionCallID];

if($sessionCallID == '')
{
	$sessionCallID	= 0;
}



///GETTNG COACH DETAILS



/*$coachQry = "
			SELECT
				a.employeeID,
				b.firstName,
				b.lastName,
				c.firstName as coachFirstName,
				c.lastName as coachLastName,
				a.startTime,
				a.setValue,
				a.checkValue,
				a.inspectValue,
				a.employeeCommitmentDate,
				a.coachFollowUpDate,
				d.mainBehaviorCoach,
				CAST(round(DATEDIFF(s,a.startTime,a.endTime)/60.000,2) as decimal(10,2)) - CAST(round(DATEDIFF(s,isnull(a.pause,0),isnull(a.resume,0))/60.000,2) as decimal(10,2)) as duration
			FROM
				rnet..prmEmployeeCoachingSessions a WITH (NOLOCK) 
			JOIN
				Results.dbo.ctlEmployees b WITH (NOLOCK)
			ON
				a.employeeID = b.employeeID
			JOIN
				Results.dbo.ctlEmployees c WITH (NOLOCK)
			ON
				a.coach = c.employeeID
			LEFT JOIN
				RNet.dbo.ctlEmployeeCoachingMainBehaviors d WITh (NOLOCK)
			ON
				a.mainBehaviourCoachID = d.mainBehaviorCoachID
			WHERE 
				coachSessionID = $coachingSessionID 
			";*/
	$coachQry = "EXEC Rnet.dbo.[rnet_spGetPopulateCoaches] '$coachingSessionID' ";	

	$result = $agentScoreObj->ExecuteQuery($coachQry);

	$isExistingSession = 'N';

	while($row=mssql_fetch_assoc($result)) 
    {
       $employeeID =$row['employeeID'];
	   $empName =$row['firstName'].' '.$row['lastName'];
	   $coachName =$row['coachFirstName'].' '.$row['coachLastName'];
	   $startTime =$row['startTime'];
	   $duration=$row['duration'];

		$setValue=$row['setValue'];
		$checkValue=$row['checkValue'];
		$inspectValue=$row['inspectValue'];
		
		if($row['employeeCommitmentDate'] != '')
		{
			$employeeCommitmentDate=date('md/Y',strtotime($row['employeeCommitmentDate']));
		}
		
		if($row['coachFollowUpDate'] != '')
		{
				$coachFollowUpDate=date('m/d/Y',strtotime($row['coachFollowUpDate']));
		}
		
		
		$mainBehaviourCoachID=$row['mainBehaviorCoach'];
	}





		$isExistingSession = 'Y';
	   
	$coachSessions = " EXEC RNet.dbo.[report_viewCoachingPreviousCallDetails] $coachingSessionID, $sessionCallID ";
	//echo $coachSessions; exit();
	
	$resultSess= $agentScoreObj->ExecuteQuery($coachSessions);
	$num_rowsSess = mssql_num_rows($resultSess);


	if($num_rowsSess>=1)
	{
		$sessArray = $agentScoreObj->bindingInToArray($resultSess);
	}
	mssql_free_result($resultSess);
	
	//print_r($sessArray);
	//exit();



////







//echo $allowHRPositionsCount;exit;


?>
<table id="searchTable">
<tr>
    <th style="text-align:left"><strong>Previous Call Details</strong></th>
</tr>
</table>
<div id="searchFieldSet">

<?php
if($num_rowsSess>=1)
{
?>




<?php
$ucID = '';
$type = '';
foreach($sessArray as $strArrayK=>$strArrayV)
{
$existUcID = $strArrayV[ucid];
$existingType = $strArrayV[type];


if($ucID != '' && $ucID != $existUcID)
{
	?>

</table>
</fieldset>

<?php
	
}




if($ucID != $existUcID)
{
?>
<fieldset style="width:98%;">

<legend> Call (UCID <? echo $strArrayV[ucid];?>)</legend>
<table id="searchTable" cellspacing="2">

<?	
}

if($type != $existingType)
{
?>
<tr>
<td colspan="2">
<strong><? echo $strArrayV[type];?></strong>
</td>
</tr>
<?	
}
?>
<tr>
    <th width="15%">KPI:</th>
    <td><?php echo $strArrayV[KPI];?></td>
 </tr>
 <tr>
   	<th width="15%">
    <? if($strArrayV[isPrimary] == 'Y')
    {
	echo 'Primary ';
	}
	?>
    Behavior:</th>
    
	<td><?php echo $strArrayV[Behaviour];?><br /></td>
</tr>
<?php 
if($strArrayV[type] == 'Opportunities')
{
?>

<tr>
    <th width="15%">Coaching Method:</th>
    <td><?php echo $strArrayV[method];?></td>
 </tr>
 <tr>
   	<th width="15%">Action Plan:</th>
	<td><?php echo $strArrayV[actionPlan];?><br /></td>
</tr>

<?php	
}
?>
<tr>
<td>
<br />
</td>
</tr>
<?php
$ucID = $existUcID;
$type = $existingType;

}

}
?>
<br>
</div>
<?php $agentScoreObj->closeConn();?>