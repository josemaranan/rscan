<?php
//ini_set('display_errors','1');
include_once($_SERVER['DOCUMENT_ROOT']."/agentScoreCard/includes/agentScoreCard.class.inc.php");
$agentScoreObj = new agentScoreCard();

include_once($_SERVER['DOCUMENT_ROOT']."/Include/HTMLContent.class.inc.php");
$htmlObject = new HTMLClass();

include_once($_SERVER["DOCUMENT_ROOT"]."/Include/tempTableStructure.inc.php");




////////GETTING EXISTING ACTIVE SESSIOSN DETAILS///////////

$isExistingSession = 'N';


$coachingClient = $_SESSION['agentScoreClient'];
$coachingLob = $_SESSION['agentScoreCardLob_id'];



	$qryExisSess = " EXEC Rnet.dbo.[report_spEmployeeActiveCoachingSessionDetails] ".$agentScoreObj->UserDetails->User.",'$coachingClient','$coachingLob'";






	$result = $agentScoreObj->ExecuteQuery($qryExisSess);
	unset($_SESSION['eSessCoach']);
	unset($eStartTime);
	unset($sCoachSessionID);

	$checkRows = $agentScoreObj->bindingIntoArray($result);
	foreach($checkRows as $row)
    {
       $eCoachLocation =$row['coachLocation'];
	   $eCoachPosition =$row['coachPosition'];
	   $eCoach =$row['coach'];
	   $eStartTime=$row['startTime'];
	   $sCoachSessionID = $row['coachSessionID'];
	   $_SESSION['eSessCoach'] = $eCoach;
   	   $coachName = $row['coachName'];

	   $eTimeSpent =$row['timeSpent'];

	   $eTimeSpentSP = explode(':',$eTimeSpent);

	   $eTimeSpentH = $eTimeSpentSP[0];
	   $eTimeSpentM = $eTimeSpentSP[1];
	   $eTimeSpentS = $eTimeSpentSP[2];
	   $isExistingSession = 'Y';

	}


	$isStrengthsExisted = 'N';
	$isOptsExisted = 'N';
	$isCallIDExisted = 'N';
	$sessionCallID = '';


	if($isExistingSession == 'Y')
	{


		///GETTING EXISTING SESSION CALL ID

	$qryExisSessionCallID = " EXEC RNet.dbo.[report_spGetEmployeeExistingCoachingSessionCallID] '$sCoachSessionID' 	";

	$resultCallID = $agentScoreObj->ExecuteQuery($qryExisSessionCallID);
	$num_rowsCallID = $agentScoreObj->getNumRows($resultCallID);


		$checkRows = $agentScoreObj->bindingIntoArray($resultCallID);
		foreach($checkRows as $row)
    	{
			$sessionCallID = $row['coachSessionCallID'];
		}


	if($sessionCallID >= 1)
	{

		$isCallIDExisted = 'Y';
	}



	$agentScoreObj->freeResult($resultCallID);


	if($isCallIDExisted == 'Y')
	{

		$qryExisStr = " EXEC Rnet.dbo.[report_spGetEmployeeCoachingSessionStrengths] '$sCoachSessionID','$sessionCallID' ";


		$result1 = $agentScoreObj->ExecuteQuery($qryExisStr);
		$num_rows1 = $agentScoreObj->getNumRows($result1);


		if($num_rows1 >= 1)
		{
			$isStrengthsExisted = 'Y';
		}


	$qryExisOpp = " EXEC Rnet.dbo.[report_spGetEmployeeCoachingSessionOpportunities]  '$sCoachSessionID', '$sessionCallID' ";



		$result2 = $agentScoreObj->ExecuteQuery($qryExisOpp);
		$num_rows2 = $agentScoreObj->getNumRows($result2);


		if($num_rows2 >= 1)
		{
			$isOptsExisted = 'Y';
		}


	$qryExisSessionCallID2 = "EXEC Rnet.dbo.[report_spGetEmployeeCoachingSessionEvaluationMethods] '$sCoachSessionID ', '$sessionCallID' ";

	$resultCallID2 = $agentScoreObj->ExecuteQuery($qryExisSessionCallID2);
	$num_rowsCallID2 = $agentScoreObj->getNumRows($resultCallID2);


		$checkRows = $agentScoreObj->bindingIntoArray($resultCallID2);
		foreach($checkRows as $row)
		{
			$UCID = $row['UCID'];
			$QAAlert = $row['QAAlert'];
			$evaluationMethodID = $row['evaluationMethodID'];
		}




			///CHECKING PREVIOUSLY CLOSED CALLS

		$qryExisSessionCallID2 = " Rnet.dbo.[report_spGetEmployeeCoachingSessionPreviouslyClosedCalls] '$sCoachSessionID', '$sessionCallID' ";

		$resultCallID2 = $agentScoreObj->ExecuteQuery($qryExisSessionCallID2);
		$num_rowsCallID2 = $agentScoreObj->getNumRows($resultCallID2);


		$agentScoreObj->freeResult($resultCallID2);

		if($num_rowsCallID2 >= 1)
		{
			$isViewPrevCalls = 'Y';
		}
	}


	$qryExisStrPause = " EXEC Rnet.dbo.report_spGetEmployeeCoachingSessionPauseDetails '$sCoachSessionID' ";


	$result3 = $agentScoreObj->ExecuteQuery($qryExisStrPause);
	$num_rows3 = $agentScoreObj->getNumRows($result3);


	if($num_rows3 >= 1)
	{
		$isPauseExisted = 'Y';
	}


	$agentScoreObj->freeResult($result3);



	$qryExisStrResume = " EXEC Rnet.dbo.[report_spGetEmployeeCoachingSessionResumeDetails] '$sCoachSessionID' ";


	$result4 = $agentScoreObj->ExecuteQuery($qryExisStrResume);
	$num_rows4 = $agentScoreObj->getNumRows($result4);


	if($num_rows4 >= 1)
	{
		$isResumeExisted = 'Y';
	}


	$agentScoreObj->freeResult($result4);








}


/*
	echo $eTimeSpentH.'<br>';
	echo $eTimeSpentM.'<br>';
	echo $eTimeSpentS.'<br>';
	echo $eTimeSpent.'<br>';
	exit();
*/

if(empty($eTimeSpentH))
{
	$eTimeSpentH = 0;
}

if(empty($eTimeSpentM))
{
	$eTimeSpentM = 0;
}

if(empty($eTimeSpentS))
{
	$eTimeSpentS = 0;
}








////////GETTING EXISTING ACTIVE SESSIOSN DETAILS///////////









/* Step 2 */
// Load html header content.
$htmlObject->htmlMetaTagsTitle('Coaching');



$cssJsArray = array('CSS'=>array('readiNetAll.css','agentScore.css','modalwindowzindex.css','dhtmlgoodies_calendar.css?random=20051112'), 'JS'=>array('agentScoreCard/jquery.min.js', 'agentScoreCard/coaching_validations.js', 'dymicwidthHeightv2.js','Validate_IntraDay_Reports.js','jquery.js', 'dhtmlgoodies_calendar_score_new.js'));

$htmlObject->loadCSSJsFiles($cssJsArray);

/* Step 3 */
// Load body tag and left menu.
// Don't pass any thing if dont want left menu.

//$htmlObject->loadBodyTag('leftMenu','','style="bgcolor:red"');

$htmlObject->loadBodyTag('leftMenu');
$seconds = 5;

/* Step - 4 Load header part */
// Send object of DB class.
//$pageHyperlinks = array('Main Menu'=>'Clients/Results/index.php', 'Back'=>'agentScoreCard/viewMyScoreCard.php');


if($_SESSION['isScoreCardApplicable'] == 'Y')
{
	$pageHyperlinks = array('Main Menu'=>'Clients/Results/index.php', 'Back'=>'agentScoreCard/viewMyScoreCard.php','image'=>array('image'=>'agentScoreCard/includes/images/gotoAgentScorecard_bevel.png','style'=>'float:right; margin-right:50px; margin-top:-22px; padding:3px; height:25px; cursor:hand', 'onClick'=>'return goToAgentScorecard();'));
}
else
{
	$pageHyperlinks = array('Main Menu'=>'Clients/Results/index.php', 'Back'=>'agentScoreCard/viewMyScoreCard.php');
}


$htmlObject->htmlHeadPart($agentScoreObj->UserDetails->User, $pageHyperlinks);

/* Variable declariations */
$loggedInemployeeID = $agentScoreObj->UserDetails->User;
$requestedDate = date('m/d/Y');
$todayDate = date('m/d/Y');
$yesterDayDate = date ('m/d/Y', strtotime('-1 day', strtotime($todayDate)));

$topLevelHeading = 'Coaching';

$employeeID = '42909';
$currentDate = '06/29/2013';



$coachingClient = $_SESSION['agentScoreClient'];
$scoreCardEntityID = $_SESSION['scoreCardEntityID'];


//$coachingClient = 'Wellcare';

/*
$KPIs = $agentScoreObj->getKPIs($coachingClient);
$behaviors = $agentScoreObj->getBehaviors($coachingClient,'Strengths');
$behaviors2 = $agentScoreObj->getBehaviors($coachingClient,'Opportunities');
$methods = $agentScoreObj->getMethods($coachingClient);
*/



$KPIs = $agentScoreObj->getKPIsByEntity($scoreCardEntityID);
$behaviors = $agentScoreObj->getBehaviorsByEntity($scoreCardEntityID,'Strengths');
$behaviors2 = $agentScoreObj->getBehaviorsByEntity($scoreCardEntityID,'Opportunities');
$methods = $agentScoreObj->getMethodsByEntity($scoreCardEntityID);




$agentCoachForms = $agentScoreObj->getCoachForms();
$evaluationMethod  = $agentScoreObj->getEvaluationMethods();

$mainBehaviorCoach  = $agentScoreObj->getMainBehaviorCoachs();









?>
<form name="form_name" id="form_name" method="post" action="populateCoachTimer.php">
<div id="test" class="outer">
<br />
</div>
<div id="report_content" style="margin-left: 0px;" >
<div class="scrollingdatagrid" id="scrollingdatagrid" visible="true">
<table border="0" cellpadding="4px;">
<tr>
<td width="70%" style="text-align:left" class="HeaderBlack">
<em>CTRW - Check, Set Inspect Form</em>
</td>
<td width="30%" align="center">
<fieldset style="border:2px solid #000; vertical-align:middle;">

<table border="0" cellspacing="2" style="padding-top:6px; padding-bottom:6px;">
<tr>
<td class="ColumnHeaderBlack">
Coach
</td>
<td style="text-align:left;">
<?php echo $coachName;?>
</td>
</tr>
<tr>
<td class="ColumnHeaderBlack">
Coaching Form
</td>
<td style="text-align:left">
<select name="ddlCoachingForm"  id="ddlCoachingForm" disabled="disabled">
    <?php
    foreach($agentCoachForms as $agentCoachFormsArrayK=>$agentCoachFormsArrayV)
    {?>
        <option value="<?php echo $agentCoachFormsArrayV['coachingFormID'];?>">
		<?php echo $agentCoachFormsArrayV['coachingFormName'];?></option>
     <?php
    }?>
    </select>&nbsp;&nbsp;&nbsp;
</td>
</tr>
</table>

</fieldset>
</td>
</tr>

<tr>
<td colspan="2">
<div id="RNetDiv" class="RNetDivWithBackground" align="left">
<font size="+1" style="padding-left:4px"><strong>Call</strong></font>
<table border="0" cellpadding="2px;">
<tr>
<td width="5%">
</td>
<td class="HeaderWhite">
<strong>Event (UCID)#: </strong>
</td>
<td class="HeaderWhite" style="text-align:left">
<input type="text" name="txtUCID" id="txtUCID" value="<? echo $UCID;?>"  class="textBoxClass" <? if($isCallIDExisted == 'Y') {?> disabled="disabled" <? }?> />
</td>
<td class="HeaderWhite">
<strong>QA Alert#: </strong>
</td>
<td  class="HeaderWhite" style="text-align:left">
<input type="text" name="txtQAAlert" id="txtQAAlert" value="<? echo $QAAlert;?>" class="textBoxClass" <? if($isCallIDExisted == 'Y') {?> disabled="disabled" <? }?> />
</td>
<td class="HeaderWhite">
<strong>Evaluation Method: </strong>
</td>
<td  class="HeaderWhite" style="text-align:left">
<select name="ddlEvaluationMethos"  id="ddlEvaluationMethos" <? if($isCallIDExisted == 'Y') {?> disabled="disabled" <? }?>>
<option value="">Please Select</option>
    <?php
    foreach($evaluationMethod as $evaluationMethodArrayK=>$evaluationMethodArrayV)
    {?>
        <option value="<?php echo $evaluationMethodArrayV['evaluationMethodID'];?>"

        		<?php
		if ($evaluationMethodArrayV['evaluationMethodID'] == $evaluationMethodID)
		{
			print "selected";
		}?>
        >
		<?php echo $evaluationMethodArrayV['evaluationMethod'];?></option>
     <?php
    }?>
    </select>
</td>
</tr>
</table>

<table border="0">
<tr>
<td width="3%">
</td>
<td>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<div id="RNetDiv" class="RNetDivWithWhiteBackground" align="left" style="padding-top:6px; padding-bottom:6px;">
<font size="-1" style="padding-left:6px; padding-top:6px; padding-bottom:6px;"> <strong> Strengths </strong></font>
<table cellpadding="2px" style="padding-left:10px">
<tr>
<td width="1%">

</td>
<td>
<div class="AgentScoreColumnHeader"><strong>KPI</strong></div>
</td>
<td style="text-align:left;">
<select name="ddlStrengthKPIs"  id="ddlStrengthKPIs">
    <option value="">Please Select</option>
    <?php
    foreach($KPIs as $KPIArrayK=>$KPIArrayV)
    {?>
        <option value="<?php echo $KPIArrayV['KPI_ID'];?>">
		<?php echo $KPIArrayV['description'];?></option>
     <?php
    }?>
    </select>
</td>
<td >
<div class="AgentScoreColumnHeader"><strong>Behavior</strong></div>
</td>
<td style="text-align:left;">

    <select name="ddlStrengthBehavior"  id="ddlStrengthBehavior">
    <option value="">Please Select</option>
    <?php
    foreach($behaviors as $bhArrayK=>$bhArrayV)
    {?>
        <option value="<?php echo $bhArrayV['behaviorID'];?>">
		<?php echo $bhArrayV['description'];?></option>
     <?php
    }?>
    </select>
</td>
<td>
<a href="#"><img src="includes/images/addStrOpp.png"  id="btnaddStr" name="btnaddStr" onclick="return addStrenghts();" style="border:0; height:30px; width:30px" title="Click here to save or add another strength" /></a>
</td>
</tr>
<tr>
<td width="1%">
</td>
<td colspan="5" style="text-align:left;" id="strDetails">
</td>
</tr>



</table>
</div>
</td>
<td width="3%">
</td>
</tr>
</table>

<table border="0">
<tr>
<td width="3%">
</td>

<td>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<div id="RNetDiv" class="RNetDivWithWhiteBackground" align="left" style="padding-top:6px">
<font size="-1" style="padding-left:6px; padding-top:6px;"> <strong> Opportunities </strong></font>


<table cellpadding="2px" style="padding-left:10px">
<tr>
<td width="1%">

</td>
<td>
<div class="AgentScoreColumnHeader"><strong>KPI</strong></div>
</td>
<td style="text-align:left;">
<select name="ddlOppKPIs"  id="ddlOppKPIs">
    <option value="">Please Select</option>
    <?php
    foreach($KPIs as $KPIArrayK=>$KPIArrayV)
    {?>
        <option value="<?php echo $KPIArrayV['KPI_ID'];?>">
		<?php echo $KPIArrayV['description'];?></option>
     <?php
    }?>
    </select>
</td>
<td >
<div class="AgentScoreColumnHeader"><strong>Behavior</strong></div>
</td>
<td style="text-align:left;">

    <select name="ddlOppBehavior"  id="ddlOppBehavior">
    <option value="">Please Select</option>
    <?php
    foreach($behaviors2 as $bhArrayK=>$bhArrayV)
    {?>
        <option value="<?php echo $bhArrayV['behaviorID'];?>">
		<?php echo $bhArrayV['description'];?></option>
     <?php
    }?>
    </select>
</td>
<td>
<div class="AgentScoreColumnHeader"><strong>Coaching Tool</strong></div>
</td>
<td style="text-align:left;">

    <select name="ddlOppMethods"  id="ddlOppMethods" onchange="return enableActionPlan(this.value);">
    <option value="">Please Select</option>
    <?php
    foreach($methods as $methodArrayK=>$methodArrayV)
    {?>
        <option value="<?php echo $methodArrayV['methodID'].'|'.$methodArrayV['isActionPlan'];?>">
		<?php echo $methodArrayV['description'];?></option>
     <?php
    }?>
    </select>
</td>



<td>
<a href="#"><img src="includes/images/addStrOpp.png"  id="btnaddOpp" name="btnaddOpp"  onclick="return addOpportunities();" style="border:0; height:30px; width:30px" title="Click here to save or add another opportunity"/></a>
</td>
</tr>

<tr>
<td width="1%">
</td>
<td style="text-align:left; width:13%;">
<div class="AgentScoreColumnHeader" style="text-align:right"><input type="button" id="btnActionPlan" name="btnActionPlan" value="Action Plan" style="background-color:#7AC143; text-decoration:blink;" disabled="disabled" onclick="return populateDetails();"/></div>
</td>
<td colspan="6" style="text-align:left;">
<textarea id="txtActionPlan" name="txtActionPlan" cols="50" rows="3"  style="overflow:auto;  border:1px solid #000;"></textarea>
</td>

</tr>
<tr>
<td width="1%">
</td>
<td colspan="8" style="text-align:center;" id="oppDetails">

</td>
</tr>


</table>


</div>
</td>
<td width="3%">
</td>
</tr>
<tr>
<td colspan="2">
<div id="hdnTimer"></div>
<? if($isViewPrevCalls == 'Y')
{
?>
<input type="button" id="btnViewOthCalls" name="btnViewOthCalls" value="View all calls associated with this coaching session" style="background-color:#7AC143; text-decoration:blink;" onclick="return populatePrevCallDetails();"/>

<?
}
else
{
?>
<input type="button" id="btnViewOthCalls" name="btnViewOthCalls" value="View all calls associated with this coaching session" style="background-color:#7AC143; text-decoration:blink;" disabled="disabled" onclick="return populatePrevCallDetails();"/>
<?
}
?>

<input type="button" name="btnAddAnother" id="btnAddAnother" value="Add Another Call" style="background-color:#7AC143" onclick="return addAnotherCall();" />
</td>
<td width="3%">
</td>
</table>
</div>
</td>
</tr>
<tr>
<td>

<!-- SUUCESS PLAN SECTION -->
<div id="RNetDiv" class="RNetDivWithWhiteBackground" align="left">
<table border="0" cellpadding="6px;">
<tr>
<td style="text-align:left">
<font size="+1" style="padding-left:4px"><strong>Success Plan</strong></font>

</td>
<td>
<font style="font-family:Arial, Helvetica, sans-serif; color:#666"> <em><strong>Who does what by when.</strong></em></font>
</td>

<td style="text-align:right">
<font style="font-family:Arial, Helvetica, sans-serif; color:#666"> <em><strong>Main behavior coached</strong></em></font>
</td>

<td style="text-align:left">
<select name="ddlMainBehCoached"  id="ddlMainBehCoached">
    <option value="">Please Select</option>
    <?php
    foreach($mainBehaviorCoach as $mainBehaviorCoachArrayK=>$mainBehaviorCoachArrayV)
    {?>
        <option value="<?php echo $mainBehaviorCoachArrayV['mainBehaviorCoachID'];?>">
		<?php echo $mainBehaviorCoachArrayV['mainBehaviorCoach'];?></option>
     <?php
    }?>
    </select>
</td>
</tr>

<tr>
<td colspan="4">
<table border="0" cellpadding="3px;">
<td width="3%"></td>
<td width="30%" style="text-align:left">
<strong>(C)</strong>heck for understanding
<textarea name="txtCheck" id="txtCheck" rows="4" cols="4" style="width:99%; overflow:auto; border:1px solid #039; background-color: lightyellow; font-family:Arial, Helvetica, sans-serif; color:#666" onfocus="validateCheck1('txhCheck','Has Associate shown understanding and ability? Explain:(Team Leader Detail)');" onblur="validateCheck2('txhCheck','Has Associate shown understanding and ability? Explain:(Team Leader Detail)');">
Has Associate shown understanding and ability? Explain:(Team Leader Detail)</textarea>
</td>
<td width="30%" style="text-align:left">
<strong>(S)</strong>et Expectations
<textarea name="txtSet" id="txtSet" rows="4" cols="4" style="width:99%; overflow:auto;  border:1px solid #039; background-color: lightyellow; font-family:Arial, Helvetica, sans-serif; color:#666" onfocus="validateExp1('txtSet','My Team Leader has set clear expectations for me. My expectations are: (Associate Detail)');" onblur="validateExp2('txtSet','My Team Leader has set clear expectations for me. My expectations are: (Associate Detail)');">
My Team Leader has set clear expectations for me. My expectations are: (Associate Detail)</textarea>
</td>
<td width="30%" style="text-align:left">

<strong>(I)</strong>nspect
<textarea name="txtInspect" id="txtInspect" rows="4" cols="4" style="width:99%; overflow:auto; border:1px solid #039; background-color: lightyellow; font-family:Arial, Helvetica, sans-serif; color:#666" onfocus="validateInsp1('txtInspect','My Team Leader has outlined how they will inspect my progress.');" onblur="validateInsp2('txtInspect','My Team Leader has outlined how they will inspect my progress.');">
My Team Leader has outlined how they will inspect my progress.</textarea>
</td>
<td width="3%">
</td>
</table>
</td>
</tr>
<tr>
<td>Employee Commitment Date </td>
<td style="text-align:left;">

<input type="text" name="txtEmpCommitDate" id="txtEmpCommitDate" style="width:75px;" value="<?php echo $requestedDate;?>"   />&nbsp;&nbsp;&nbsp;<img id="imgstartDate" alt="Choose Date" onclick=        "javascript:displayCalendar(document.getElementById('txtEmpCommitDate'),'mm/dd/yyyy',document.getElementById('imgstartDate'));" src="https://<?php echo $_SERVER['HTTP_HOST']?>/Include/images/calendar.gif" />


</td>
<td>Coach Follow up Date</td>
<td style="text-align:left;">

<input type="text" name="txtCoachFollowUpDate" id="txtCoachFollowUpDate" style="width:75px;" value="<?php echo $requestedDate;?>"   />&nbsp;&nbsp;&nbsp;<img id="imgstartDate2" alt="Choose Date" onclick=        "javascript:displayCalendar(document.getElementById('txtCoachFollowUpDate'),'mm/dd/yyyy',document.getElementById('imgstartDate2'));" src="https://<?php echo $_SERVER['HTTP_HOST']?>/Include/images/calendar.gif" />


</td>
</tr>

</table>
</div>
</td>
<td style="text-align:center">
<table border="0" align="center">
<tr>
<td style="text-align:center">
Current Coaching Duration: <font size="+1"><strong><span id="timeSpentVl"></span></strong></font><br />
</td>
</tr>
<tr>
<td style="text-align:center">
<a href="#">
<img src="includes/images/resetCoachingTimer_bevel.png" id="btnReset" name="btnReset" onclick="return resetCoachTimer();" style="border:0;" /></a>
</td>
</tr>
<tr>
<?
$dis3 = "none";
if($isPauseExisted == 'Y')
{
	$dis3 = "block";
}

$dis4 = "none";
if($isResumeExisted == 'Y')
{
	$dis4 = "block";
}

?>
<td style="text-align:center">


<a href="#">
<img src="includes/images/pauseCoaching.png" id="btnPause" name="btnPause" onclick="return pauseCoachTimer();" style="border:0; display:<?=$dis3;?>;" title="You can only pause once per coaching session" /></a>


<a href="#">
<img src="includes/images/resumeCoaching.png" id="btnResume" name="btnResume" onclick="return resumeCoachTimer();" style="border:0; display:<?=$dis4;?>;" /></a>


</td>
</tr>
<tr>
<td style="text-align:center">
<?

$dis5 = "none";
if($isResumeExisted != 'Y')
{
	$dis5 = "block";
}
?>

<a href="#">
<img src="includes/images/redbutton.png" id="btnStopCoach" name="btnStopCoach" onclick="return stopTimer();" style="border:0; display:<?=$dis5;?>;" />
</a>


</td>

</tr>

</table>
</td>

</tr>
</table>

<?php
	$vl = '1';
	if($isExistingSession == 'Y')
	{
		$vl = '2';
	}
?>


<input type="hidden" name="hdnSupportValue" id="hdnSupportValue" value="<?php echo $vl;?>" />


<input type="hidden" name="hdnTimerStart" id="hdnTimerStart" value="<?=$eStartTime;?>" />
<input type="hidden" name="hdnTimerEnd" id="hdnTimerEnd" value="" />
<input type="hidden" name="hdnSessionID" id="hdnSessionID" value="<?=$sCoachSessionID;?>" />

<input type="hidden" name="hdnISSessionCallIDExisted" id="hdnISSessionCallIDExisted" value="<?=$isCallIDExisted;?>" />
<input type="hidden" name="hdnSessionCallID" id="hdnSessionCallID" value="<?=$sessionCallID;?>" />



<input type="hidden" name="hdnISStrenghsExisted" id="hdnISStrenghsExisted" value="<?=$isStrengthsExisted;?>" />
<input type="hidden" name="hdnISOptsExisted" id="hdnISOptsExisted" value="<?=$isOptsExisted;?>" />

<?php
	$todayDate = date('m/d/Y');
	//$yesterDayDate = date ('m/d/Y', strtotime('-1 day', strtotime($todayDate)));
?>
<input type="hidden" name="hdnCalendarMinStartDate" id="hdnCalendarMinStartDate" value="<?php echo $todayDate;?>" />


<!--<div id="hdnTimer"></div>-->
<div id="hdnReset"></div>
<div id="ch" style="display:none;"></div>



</div>
</div>
</form>
<div id="coachDialogMain" class="window">

<a href="#"class="close"  style="float:right; border:0px;"  /> Close <img src="../Include/images/round_red_close_button.jpg"  border="0" width="20px" height="20px" /></a>
<br />
<div id="replace">
</div>
</div>


<div id="viewPrevDet" class="window">

<a href="#"class="close"  style="float:right; border:0px;"  /> Close <img src="../Include/images/round_red_close_button.jpg"  border="0" width="20px" height="20px" /></a>
<br />
<div id="replace2">
</div>
</div>





<!-- Mask to cover the whole screen -->
<div id="mask"></div>

<script type="text/javascript">makeItDynamic();</script>

<script type="text/javascript">
var c=0;
//var t;
var y;
var timer_is_on=0;

var hours = <? echo $eTimeSpentH;?>;
var minutes = <? echo $eTimeSpentM;?>;
var seconds = <? echo $eTimeSpentS;?>;

function timedCount()
{

 if (seconds <= 0)
  {
      seconds++;
  }

  if (seconds == 60)
  {
      seconds = 0;
	  minutes++;
  }



  if (minutes == 60)
  {
      hours++;
      minutes = 0
  }


 if(seconds < 10)
 {
	seconds2 = '0' + seconds;
 }
 else

 {
	 seconds2 = seconds;
 }


 if(minutes < 10)
 {
	minutes2 = '0' + minutes;
 }
 else
 {
	 minutes2 = minutes;
 }


 if(hours < 10)
 {
	hours2 = '0' + hours;
 }
 else
 {
	 hours2 = hours;
 }





  document.getElementById('timeSpentVl').innerHTML = hours2 + ":" + minutes2 + ":" + seconds2;

  seconds=seconds+1;
  t=setTimeout(function(){timedCount()},1000);
  }

function doTimer()
  {
  if (!timer_is_on)
    {
    timer_is_on=1;
    timedCount();
    }
  }

function stopCount()
  {
  clearTimeout(t);
  timer_is_on=0;
  seconds=0;
  minutes=0;
  hours=0;
  }


function pauseTimer()
  {
  clearTimeout(t);
  timer_is_on=0;
  }



</script>
<? if($isExistingSession == 'Y')
{
	echo "<script type='text/javascript'>doTimer();</script>";
	if($isResumeExisted == 'Y')
	{
		echo "<script type='text/javascript'>pauseTimer();</script>";
	}

}?>
<? if($isExistingSession == 'Y'){echo "<script type='text/javascript'>htmlDataExistingStrenghts('populatesStrengths.php', 'sessID=$sCoachSessionID&sessionCallID=$sessionCallID', 'strDetails');</script>";}?>

<script type="text/javascript">makeItDynamic();</script>
