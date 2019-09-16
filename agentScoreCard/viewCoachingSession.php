<?php

include_once($_SERVER['DOCUMENT_ROOT']."/agentScoreCard/includes/agentScoreCard.class.inc.php");
$agentScoreObj = new agentScoreCard();

include_once($_SERVER['DOCUMENT_ROOT']."/Include/HTMLContent.class.inc.php");
$htmlObject = new HTMLClass();


$coachingSessionID = $_REQUEST[coachSessID];
//$coachingSessionID = 444;


//CHECKING SECURITY FOR AGENT AND COACH

$empID = $_SESSION['empID'];

/*$chkSec = "
			SELECT 
				coachSessionID 
			FROM 
				rnet..prmEmployeeCoachingSessions a WITH (NOLOCK) 
			WHERE
				coachSessionID = $coachingSessionID 
				AND 
				(employeeID = $empID OR coach = $empID)
			";*/
$chkSec = "EXEC Rnet.dbo.[rnet_spCheckEmployeeCoachingAuthorisedUser] '$coachingSessionID', '$empID' ";
$resultChk = $agentScoreObj->ExecuteQuery($chkSec);
$num_rowsChk = mssql_num_rows($resultChk);

$isAuthorizedUser = 'N';
if($num_rowsChk >= 1)
{
	$isAuthorizedUser = 'Y';
}





///GETTNG COACH DETAILS


if($isAuthorizedUser == 'Y')
{	
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
			";
			*/
			
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
			$employeeCommitmentDate=date('m/d/Y',strtotime($row['employeeCommitmentDate']));
		}
		
		if($row['coachFollowUpDate'] != '')
		{
				$coachFollowUpDate=date('m/d/Y',strtotime($row['coachFollowUpDate']));
		}
		
		
		$mainBehaviourCoachID=$row['mainBehaviorCoach'];






		$isExistingSession = 'Y';
	   
	}




///GETTNG STRENFGTHS DETAILS

$coachSTRQry = "EXEC Rnet.dbo.[rnet_spGetEmployeeStrengthsAndOpportunities] 'Strength', '$coachingSessionID' ";
/*$coachSTRQry = "
			SELECT 
				k.description as KPI,
				c.eventID,
				b.description as Behaviour
			FROM
				rnet..prmEmployeeCoachingSessionDetails c WITH (NOLOCK)
			JOIN
				RNet.dbo.ctlScorecardKPIs k WITH (NOLOCK)
			ON
				c.KPIID = k.KPI_ID
			JOIN
				RNet.dbo.ctlScorecardBehaviors b WITH (NOLOCK)
			ON
				c.behaviorID = b.behaviorID
			WHERE
				c.type = 'Strength'
				AND
				c.coachSessionID = $coachingSessionID 
			";*/
			

	$resultstr = $agentScoreObj->ExecuteQuery($coachSTRQry);
	$num_rowsSTR = mssql_num_rows($resultstr);


	if($num_rowsSTR>=1)
	{
		$strArray = $agentScoreObj->bindingInToArray($resultstr);
	}
	mssql_free_result($resultstr);





///GETTNG Opportunities  DETAILS

$coachOPQry = "EXEC Rnet.dbo.[rnet_spGetEmployeeStrengthsAndOpportunities] 'Opportunities', '$coachingSessionID' ";
/*$coachOPQry = "
			SELECT 
				k.description as KPI,
				c.eventID,
				b.description as Behaviour,
				c.actionPlan,
				m.description as method
			FROM
				rnet..prmEmployeeCoachingSessionDetails c WITH (NOLOCK)
			JOIN
				RNet.dbo.ctlScorecardKPIs k WITH (NOLOCK)
			ON
				c.KPIID = k.KPI_ID
			JOIN
				RNet.dbo.ctlScorecardBehaviors b WITH (NOLOCK)
			ON
				c.behaviorID = b.behaviorID
			JOIN
				RNet.dbo.ctlScorecardMethods m WITH (NOLOCK)
			ON
				c.methodID = m.methodId
			WHERE
				c.type = 'Opportunities'
				AND
				c.coachSessionID = $coachingSessionID 
			";
			*/

	$resultOPT = $agentScoreObj->ExecuteQuery($coachOPQry);
	$num_rowsOPT = mssql_num_rows($resultOPT);


	if($num_rowsOPT>=1)
	{
		$optArray = $agentScoreObj->bindingInToArray($resultOPT);
	}
	mssql_free_result($resultOPT);
	
	
	
	$coachSessions = " EXEC RNet.dbo.[report_viewCoachingDetails] $coachingSessionID ";
	
	$resultSess= $agentScoreObj->ExecuteQuery($coachSessions);
	$num_rowsSess = mssql_num_rows($resultSess);


	if($num_rowsSess>=1)
	{
		$sessArray = $agentScoreObj->bindingInToArray($resultSess);
	}
	mssql_free_result($resultSess);
	
	//print_r($sessArray);
	//exit();


}
////





/* Step 2 */
// Load html header content.
$htmlObject->htmlMetaTagsTitle('Coaching Session Details');

$cssJsArray = array('CSS'=>array('readiNetAll.css', 'dhtmlgoodies_calendar.css?random=20051112'), 'JS'=>array('dhtmlgoodies_calendar.js?random=20060118','table.js','jquery.js','dymicwidthHeightv2.js','Users/site_administration/siteManagement/ajax.js'));
$htmlObject->loadCSSJsFiles($cssJsArray);

/* Step 3 */
// Load body tag and left menu.
// Don't pass any thing if dont want left menu.
$htmlObject->loadBodyTag('leftMenu');

/* Step - 4 Load header part */
// Send object of DB class.


if($_REQUEST[from]=='viewMyscoreCard')
{
$pageHyperlinks = array('Main Menu'=>'Clients/Results/index.php','Back'=>'agentScoreCard/viewMyScoreCard.php');
}
else
{
$pageHyperlinks = array('Main Menu'=>'Clients/Results/index.php');
}

$htmlObject->htmlHeadPart($agentScoreObj->UserDetails->User, $pageHyperlinks);



//echo $allowHRPositionsCount;exit;
?>

<div id="searchBody" class="outer">
<div id="searchFieldSet">
<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" name="form_data" id="searchForm">
<?php

if($isAuthorizedUser == 'N')
{
echo '<br><br>';
echo '<font color=\'red\'><strong>You are not an authorized user to view this coach session.</strong></font>';
exit();

}
?>

<table width="100%">
<tr>
<td style="text-align:left; padding-right:6px;">
<a href="#">
<img src="../Include/images/notificationFiles/pdfIcon.png" title="Click to down load PDF" id="pdf" name="pdf" style="border:0; height:35px;" onclick="return exportCoachingSessionPDF();" /></a>
</td>
</tr>
</table>

<input type="hidden" name="hdnSessionID" id="hdnSessionID" value="<?=$coachingSessionID;?>" />
</form>
</div>
</div>


<div id="report_content" > 
<div class="scrollingdatagrid" id="scrollingdatagrid" visible="true">
<div id="searchFieldSet">

<fieldset style="width:98%;">
<legend>Coaching Session for <?php echo $empName;?></legend>

<table id="searchTable" cellspacing="2">
<tr>
    <th>Employee ID:</th>
    <td><?php echo $employeeID;?></td>
	<th>Coach:</th>
	<td><?php echo $coachName;?></td>
</tr>

<tr>
    <th>Date/Time:</th>
    <td><?php echo date('F d, Y h:i A', strtotime($startTime));?></td>
    <th>Duration:</th>
    <td><?php echo $duration;?> minutes</td>
</tr>
</table>

</fieldset>


<fieldset style="width:98%;">
<legend>Success Plan:</legend>

<table id="searchTable" cellspacing="2">
	<tr>
    <th width="15%">Employee Commitment Date:</th>
    <td><?php echo $employeeCommitmentDate;?></td>
 </tr>


<tr>
    <th width="15%">Coach Follow-up Date:</th>
    <td><?php echo $coachFollowUpDate;?></td>
 </tr>


<tr>
    <th width="15%">Main Behavior Coached:</th>
    <td><?php echo $mainBehaviourCoachID;?></td>
 </tr>


 
 <tr>
	<th width="15%">(C)heck</th>
	<td><?php echo $checkValue;?></td>
 </tr>
 <tr>
   	<th width="15%">(S)et</th>
	<td><?php echo $setValue;?></td>
</tr>


 <tr>
   	<th width="15%">(I)nspect</th>
	<td><?php echo $inspectValue;?></td>
</tr>


<tr>
<td>
<br />
</td>
</tr>
</table>
</fieldset>



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
    <th width="15%">Coaching Tool:</th>
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
</div> 
</div>

</body>
</html>
<script type="text/javascript">

function exportCoachingSessionPDF()
{
	sessID = $('#hdnSessionID').val();
	//alert(sessID);
	url = 'exportCoachingSessionPDF.php?coachSessID='+sessID;
	document.forms['form_data'].action=url;
	document.forms['form_data'].submit();
	document.forms['form_data'].target="_self";
	document.forms['form_data'].action='';
	
}

</script>


<script type="text/javascript">makeItDynamic();</script>
<?php $agentScoreObj->closeConn();?>