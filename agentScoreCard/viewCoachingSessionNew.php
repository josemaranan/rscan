<?php

include_once($_SERVER['DOCUMENT_ROOT']."/agentScoreCard/includes/agentScoreCard.class.inc.php");
$agentScoreObj = new agentScoreCard();

include_once($_SERVER['DOCUMENT_ROOT']."/Include/HTMLContent.class.inc.php");
$htmlObject = new HTMLClass();


$coachingSessionID = $_REQUEST[coachSessID];
//$coachingSessionID = 444;


//CHECKING SECURITY FOR AGENT AND COACH

$empID = $_SESSION['empID'];

$chkSec = "
			SELECT 
				coachSessionID 
			FROM 
				rnet..prmEmployeeCoachingSessions a WITH (NOLOCK) 
			WHERE
				coachSessionID = $coachingSessionID 
				AND 
				(employeeID = $empID OR coach = $empID)
			";

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
$coachQry = "
			SELECT
				a.employeeID,
				b.firstName,
				b.lastName,
				c.firstName as coachFirstName,
				c.lastName as coachLastName,
				a.startTime,
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
			WHERE 
				coachSessionID = $coachingSessionID 
			";
			

	$result = $agentScoreObj->ExecuteQuery($coachQry);

	$isExistingSession = 'N';

	while($row=mssql_fetch_assoc($result)) 
    {
       $employeeID =$row['employeeID'];
	   $empName =$row['firstName'].' '.$row['lastName'];
	   $coachName =$row['coachFirstName'].' '.$row['coachLastName'];
	   $startTime =$row['startTime'];
	   $duration=$row['duration'];

		$isExistingSession = 'Y';
	   
	}




///GETTNG STRENFGTHS DETAILS

$coachSTRQry = "
			SELECT 
				k.description as KPI,
				c.eventID,
				b.description as Behaviour
			FROM
				rnet..prmEmployeeCoachingSessionDetails c WITH (NOLOCK)
			JOIN
				RNet.dbo.ctlWellCareScorecardKPIs k WITH (NOLOCK)
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
			";
			

	$resultstr = $agentScoreObj->ExecuteQuery($coachSTRQry);
	$num_rowsSTR = mssql_num_rows($resultstr);


	if($num_rowsSTR>=1)
	{
		$strArray = $agentScoreObj->bindingInToArray($resultstr);
	}
	mssql_free_result($resultstr);





///GETTNG Opportunities  DETAILS

$coachOPQry = "
			SELECT 
				k.description as KPI,
				c.eventID,
				b.description as Behaviour,
				c.actionPlan,
				m.description as method
			FROM
				rnet..prmEmployeeCoachingSessionDetails c WITH (NOLOCK)
			JOIN
				RNet.dbo.ctlWellCareScorecardKPIs k WITH (NOLOCK)
			ON
				c.KPIID = k.KPI_ID
			JOIN
				RNet.dbo.ctlScorecardBehaviors b WITH (NOLOCK)
			ON
				c.behaviorID = b.behaviorID
			JOIN
				RNet.dbo.ctlWellCareScorecardMethods m WITH (NOLOCK)
			ON
				c.methodID = m.methodId
			WHERE
				c.type = 'Opportunities'
				AND
				c.coachSessionID = $coachingSessionID 
			";
			

	$resultOPT = $agentScoreObj->ExecuteQuery($coachOPQry);
	$num_rowsOPT = mssql_num_rows($resultOPT);


	if($num_rowsOPT>=1)
	{
		$optArray = $agentScoreObj->bindingInToArray($resultOPT);
	}
	mssql_free_result($resultOPT);


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
<fieldset>
<legend>Coaching Session for <?php echo $empName;?></legend>

<table id="searchTable">
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
<input type="hidden" name="hdnSessionID" id="hdnSessionID" value="<?=$coachingSessionID;?>" />
</form>
</div>
</div>


<div id="report_content" > 
<div class="scrollingdatagrid" id="scrollingdatagrid" visible="true">
<div id="searchFieldSet">
<?php
if($num_rowsSTR>=1)
{
?>
<fieldset style="width:98%;">
<legend>Strengths Discussed:</legend>

<table id="searchTable" cellspacing="2">

<?php
foreach($strArray as $strArrayK=>$strArrayV)
{
?>
<tr>
    <th width="15%">KPI:</th>
    <td><?php echo $strArrayV[KPI];?></td>
 </tr>
 <tr>
	<th width="15%">Event ID:</th>
	<td><?php 
	
	if(empty($strArrayV[eventID]))
	{
		echo '(none provided)';
	}
	else
	{
		echo $strArrayV[eventID];
	}
	?></td>
 </tr>
 <tr>
   	<th width="15%">Behavior:</th>
	<td><?php echo $strArrayV[Behaviour];?><br /></td>
</tr>
<tr>
<td>
<br />
</td>
</tr>
<?php
}
?>

</table>
</fieldset>
<?php
}
?>
<br>
<?php
if($num_rowsOPT>=1)
{
?>
<fieldset style="width:98%;">
<legend>Opportunities Discussed:</legend>

<table id="searchTable" cellspacing="2">

<?php
foreach($optArray as $optArrayK=>$optArrayV)
{
?>
<tr>
    <th width="15%">KPI:</th>
    <td><?php echo $optArrayV[KPI];?></td>
 </tr>
 <tr>
	<th width="15%">Event ID:</th>
	<td><?php 
	
	if(empty($optArrayV[eventID]))
	{
		echo '(none provided)';
	}
	else
	{
		echo $optArrayV[eventID];
	}
	?></td>
 </tr>

<tr>
   	<th width="15%">Behavior:</th>
	<td><?php echo $optArrayV[Behaviour];?><br /></td>
</tr>


<tr>
   	<th width="15%">Coaching Method:</th>
	<td><?php echo $optArrayV[method];?><br /></td>
</tr>


<tr>
   	<th width="15%">Action Plan:</th>
	<td><?php echo $optArrayV[actionPlan];?><br /></td>
</tr>
<tr>
<td>
<br />
</td>
</tr>
<?php
}
?>

</table>

</fieldset>
<?
}
?>

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