<?php
//ini_set('display_errors','1');
include_once($_SERVER['DOCUMENT_ROOT']."/agentScoreCard/includes/agentScoreCard.class.inc.php");
$agentScoreObj = new agentScoreCard();

include_once($_SERVER['DOCUMENT_ROOT']."/Include/HTMLContent.class.inc.php");
$htmlObject = new HTMLClass();

include_once($_SERVER["DOCUMENT_ROOT"]."/Include/tempTableStructure.inc.php");




////////GETTING EXISTING ACTIVE SESSIOSN DETAILS///////////

$isExistingSession = 'N';

	
	$qryExisSess = "

			DECLARE @endTime AS DATETIME
			SET @endTime = getDate()
			SELECT 
				coachSessionID,
				coachLocation,
				coachPosition,
				coach,
				startTime,
				CONVERT(varchar(6), DATEDIFF(second, startTime, @endTime)/3600)
				+ ':'
				+ RIGHT('0' + CONVERT(varchar(2), (DATEDIFF(second, startTime, @endTime) % 3600) / 60), 2)
				+ ':'
				+ RIGHT('0' + CONVERT(varchar(2), DATEDIFF(second, startTime, @endTime) % 60), 2) AS timeSpent
			FROM 
				RNet.dbo.prmEmployeeCoachingSessions a WITH (NOLOCK)  
			WHERE 
				employeeID = ".$agentScoreObj->UserDetails->User." 
				AND
				endTime IS NULL 
			ORDER BY 
				coachSessionID desc

	";
	

	
	$result = $agentScoreObj->ExecuteQuery($qryExisSess);
	unset($_SESSION[eSessCoach]);
	unset($eStartTime);
	unset($sCoachSessionID);

	while($row=mssql_fetch_assoc($result)) 
    {
       $eCoachLocation =$row['coachLocation'];
	   $eCoachPosition =$row['coachPosition'];
	   $eCoach =$row['coach'];
	   $eStartTime=$row['startTime'];
	   $sCoachSessionID = $row['coachSessionID'];
	   $_SESSION[eSessCoach] = $eCoach;
	   $eTimeSpent =$row['timeSpent'];
	   
	   $eTimeSpentSP = split(':',$eTimeSpent);
	   
	   $eTimeSpentH = $eTimeSpentSP[0];
	   $eTimeSpentM = $eTimeSpentSP[1];
	   $eTimeSpentS = $eTimeSpentSP[2];
	   
	   $isExistingSession = 'Y';
	   
	}


	$isStrengthsExisted = 'N';
	$isOptsExisted = 'N';
	
	
	if($isExistingSession == 'Y')
	{
	$qryExisStr = "
	
			SELECT 
				*
			FROM 
				RNet.dbo.prmEmployeeCoachingSessionDetails a WITH (NOLOCK)  
			WHERE 
				coachSessionID = $sCoachSessionID  
				AND
				type = 'Strength'
	";
	

	
	$result1 = $agentScoreObj->ExecuteQuery($qryExisStr);
	$num_rows1 = mssql_num_rows($result1);


	if($num_rows1 >= 1)
	{
		$isStrengthsExisted = 'Y';
	}


$qryExisOpp = "
	
			SELECT 
				*
			FROM 
				RNet.dbo.prmEmployeeCoachingSessionDetails a WITH (NOLOCK)  
			WHERE 
				coachSessionID = $sCoachSessionID  
				AND
				type = 'Opportunities'
	";
	

	
	$result2 = $agentScoreObj->ExecuteQuery($qryExisOpp);
	$num_rows2 = mssql_num_rows($result2);


	if($num_rows2 >= 1)
	{
		$isOptsExisted = 'Y';
	}
	
	
	
	$qryExisStrPause = "
	
			SELECT 
				*
			FROM 
				RNet.dbo.prmEmployeeCoachingSessions a WITH (NOLOCK)  
			WHERE 
				coachSessionID = $sCoachSessionID  
				AND
				pause IS NULL
				AND
				resume IS NULL
	";
	

	$result3 = $agentScoreObj->ExecuteQuery($qryExisStrPause);
	$num_rows3 = mssql_num_rows($result3);


	if($num_rows3 >= 1)
	{
		$isPauseExisted = 'Y';
	}
	
	
	@mssql_free_result($result3);
	
	
	
	$qryExisStrResume = "
	
			SELECT 
				*
			FROM 
				RNet.dbo.prmEmployeeCoachingSessions a WITH (NOLOCK)  
			WHERE 
				coachSessionID = $sCoachSessionID  
				AND
				pause IS NOT NULL
				AND
				resume IS NULL
	";
	

	$result4 = $agentScoreObj->ExecuteQuery($qryExisStrResume);
	$num_rows4 = mssql_num_rows($result4);


	if($num_rows4 >= 1)
	{
		$isResumeExisted = 'Y';
	}
	
	
	@mssql_free_result($result4);
	
	
	

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


$cssJsArray = array('CSS'=>array('readiNetAll.css','agentScore.css','modalwindowzindex.css'), 'JS'=>array('agentScoreCard/jquery.min.js', 'agentScoreCard/coaching_new.js', 'innerDynamicWH.js'));
$htmlObject->loadCSSJsFiles($cssJsArray);

/* Step 3 */
// Load body tag and left menu.
// Don't pass any thing if dont want left menu.

//$htmlObject->loadBodyTag('leftMenu','','style="bgcolor:red"');

$htmlObject->loadBodyTag('leftMenu','',array('style'=>'background-color:#000'));
$seconds = 5;

/* Step - 4 Load header part */
// Send object of DB class.
//$pageHyperlinks = array('Main Menu'=>'Clients/Results/index.php', 'Back'=>'agentScoreCard/viewMyScoreCard.php');
$pageHyperlinks = array('Main Menu'=>'Clients/Results/index.php', 'Back'=>'agentScoreCard/viewMyScoreCard.php','image'=>array('image'=>'agentScoreCard/includes/images/gotoAgentScorecard_bevel.png','style'=>'float:right; margin-right:50px; margin-top:-22px; padding:3px; height:25px; cursor:hand', 'onClick'=>'JavaScript:window.location.href=\'index.php\''));

$htmlObject->htmlHeadPart($agentScoreObj->UserDetails->User, $pageHyperlinks);

/* Variable declariations */
$loggedInemployeeID = $agentScoreObj->UserDetails->User;
$requestedDate = date('m/d/Y');
$todayDate = date('m/d/Y');
$yesterDayDate = date ('m/d/Y', strtotime('-1 day', strtotime($todayDate)));

$topLevelHeading = 'Coaching';


$employeeID = '42909';
$currentDate = '06/29/2013';



$coachingClient = $_SESSION[agentScoreClient];

$locs = $agentScoreObj->getLocations($agentScoreObj->UserDetails->Locations);
$pos = $agentScoreObj->getPositions();
$KPIs = $agentScoreObj->getKPIs($coachingClient);

$behaviors = $agentScoreObj->getBehaviors($coachingClient,'Strengths');
$behaviors2 = $agentScoreObj->getBehaviors($coachingClient,'Opportunities');


$methods = $agentScoreObj->getMethods();




?>
<div class="outer" id="report_content">
<form name="form_name">
<table border="0" width="100%">
<tr>
<td height="10px" style="font-size:18px; text-align:left;">
<font color="#1F497D">Coaching</font>
</td>
</tr>
</table>
</div> <!-- report content -->

<div style="text-align:center; padding-left:80px;">
<div class="outer" id="emptyDiv"></div>
<div class="outer" id="emptyDiv"></div>
<table border="0" width="60%" bgcolor="#FFFFFF" style="text-align:left;">
<tr>
<td>
<div id="INNERSCROLL" style="text-align:left;">
<table border="0" id="searchTable" style="width:98%;">

<tr>
	<th width="30%;"><strong>Coach Location</strong></th>
    <td>

	 <select name="ddlLocations"  id="ddlLocations" onchange="return populateCoaches();">
    <option value="">Please Select</option>
    <?php
    foreach($locs as $locArrayK=>$locArrayV)
    {?>
        <option value="<?php echo $locArrayV['location'];?>" 
		<?php if($locArrayV['location'] == $eCoachLocation){ print " selected";}?>>
		<?php echo $locArrayV['description'];?></option>
     <?php 
    }?>
    </select>

	</td>
</tr>

<tr><th width="30%;"><strong>Coach Position</strong></th>
    <td>

	 <select name="ddlPositions"  id="ddlPositions" onchange="return populateCoaches();">
    <option value="">Please Select</option>
    <?php
    foreach($pos as $posArrayK=>$posArrayV)
    {?>
        <option value="<?php echo $posArrayV['positionID'];?>" 
		<?php if($posArrayV['positionID'] == $eCoachPosition){ print " selected";}?>>
		<?php echo $posArrayV['description'];?></option>
     <?php 
    }?>
    </select>
    
    <?php
	$vl = '1';
	if($isExistingSession == 'Y')
	{
		$vl = '2';	
	}
	?>
	
	
	<input type="hidden" name="hdnSupportValue" id="hdnSupportValue" value="<?php echo $vl;?>" />
	</td>

</tr>
<tr><th width="30%;"><strong>Coach</strong></th>
<td id="ch">

<select name="ddlCoaches"  id="ddlCoaches" onchange="return coachValidations();">
    <option value="">Please Select</option>
    </select>
    

    
</td>
</tr>
<?
$dis = "none";
$dis2 = "block";
$disStop = "none";
if($isExistingSession == 'Y')
{
	$dis = "block";
	$dis2 = "none";
	$disStop = "block";
}


if($isResumeExisted == 'Y')
{
	$disStop = "none";
}



?>
<tr  id="timer" style="display:<?=$dis;?>;">
<td style="text-align:right;">


 
<a href="#"><img src="includes/images/startCoachingTimer_bevel.png"  id="btnStartCoach" name="btnStartCoach" onclick="return startTimer();" style="border:0; display:<?=$dis2;?>" /></a>

<a href="#">
<img src="includes/images/stopCoachingTimer_bevel.png" id="btnStopCoach" name="btnStopCoach" onclick="return stopTimer();" style="border:0; display:<?=$disStop;?>" /></a>

<!--
<input type="button" id="btnStopCoach" name="btnStopCoach" value="Stop Coaching Timer" style="background-color:#F00; border: #385D8A 1px solid; padding:0px; font-family:Verdana, Geneva, sans-serif; color:#FFF; text-align:center; display:<?=$dis;?>;" onclick="return stopTimer();"  />
-->




</td>
<td style="text-align:left;">
<!--
<input type="button" id="btnReset" name="btnReset" value="Reset Coaching Timer" onclick="return resetCoachTimer();"/>
-->
<table border="0" cellpadding="0" cellspacing="0">
<tr>
<td style="text-align:left">
<a href="#">
<img src="includes/images/resetCoachingTimer_bevel.png" id="btnReset" name="btnReset" onclick="return resetCoachTimer();" style="border:0;" /></a>
</td>
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

<td style="text-align:left">
<a href="#">
<img src="includes/images/pauseCoaching.png" id="btnPause" name="btnPause" onclick="return pauseCoachTimer();" style="border:0; display:<?=$dis3;?>;" title="You can only pause once per coaching session" /></a>


<a href="#">
<img src="includes/images/resumeCoaching.png" id="btnResume" name="btnResume" onclick="return resumeCoachTimer();" style="border:0; display:<?=$dis4;?>;" /></a>
</td>
</tr>
</table>



</td>
</tr>


<tr  id="timeSpent" style="display:block;">
<th width="30%;"><strong>Time Spent</strong></th>
<td id="timeSpentVl">
</td>
</tr>
<tr>
<!--<td colspan="2" style="color:#F00; font-weight:bold;" id="completed">
</td> -->

<td style="color:#F00; font-weight:bold;" id="completed" >
</td>
<td style="text-align:right; padding-right:10px;">
<a href="#">
<img src="../Include/images/notificationFiles/pdfIcon.png" title="Click to down load PDF" id="pdf" name="pdf" style="border:0; height:35px; display:none;" onclick="return exportCoachingSessionPDF();" /></a>
</td>

</tr>
<tr>
<td colspan="2"  id="strenghts" style="display:<?=$dis;?>;">
<fieldset style="border:3px solid #000;">
<legend><strong>Strengths</strong></legend>
<table border="0" width="100%">
<tr>
<th width="20%">KPI <font color="#FF0000">*</font></th>
<td>
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
<th width="20%">Event ID</th>
<td><input type="text" name="txtStrengthEventID" id="txtStrengthEventID" /></td>
</tr>
<tr>
<th width="20%">Behavior <font color="#FF0000">*</font></th>
<td>

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
<td></td><td></td>
</tr>
<tr>
<td colspan="4" style="text-align:center;">
<input type="button" id="btnStrenghs" name="btnStrenghs" value="Save" onclick="return addStrenghts();" />
</td>
</tr>


<tr>
<td colspan="4" style="text-align:center;" id="strDetails">

</td>
</tr>



</table>
</fieldset>
</td>
</tr>

<tr id="opportunities" style="display:<?=$dis;?>;">
<td colspan="2">
<br />
<fieldset style="border:3px solid #000;">
<legend><strong>Opportunities</strong></legend>

<table border="0" width="100%">
<tr>
<th width="20%">KPI <font color="#FF0000">*</font></th>
<td>

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
<th width="20%">Event ID</th>
<td><input type="text" name="txtoppEventID" id="txtoppEventID" /></td>
</tr>
<tr>
<th width="20%">Behavior <font color="#FF0000">*</font></th>
<td>
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
<th width="20%">Method <font color="#FF0000">*</font></th>
<td>

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
</tr>
<tr>
<th width="20%"><input type="button" id="btnActionPlan" name="btnActionPlan" value="Action Plan" style="background-color:#69F; text-decoration:blink;" disabled="disabled" onclick="return populateDetails();"/></th>
<td colspan="3">

<textarea id="txtActionPlan" name="txtActionPlan" cols="50" rows="5"></textarea>
</td>
</tr>

<tr>
<td colspan="4" style="text-align:center;">
<input type="button" id="btnOpportunities" name="btnOpportunities" value="Save" onclick="return addOpportunities();" />
</td>
</tr>

<tr>
<td colspan="4" style="text-align:center;" id="oppDetails">

</td>
</tr>

</table>
</fieldset>
</td>
</tr>

</table>

</div>
</td>
</tr>
</table>

<input type="hidden" name="hdnTimerStart" id="hdnTimerStart" value="<?=$eStartTime;?>" />
<input type="hidden" name="hdnTimerEnd" id="hdnTimerEnd" value="" />
<input type="hidden" name="hdnSessionID" id="hdnSessionID" value="<?=$sCoachSessionID;?>" />

<input type="hidden" name="hdnISStrenghsExisted" id="hdnISStrenghsExisted" value="<?=$isStrengthsExisted;?>" />
<input type="hidden" name="hdnISOptsExisted" id="hdnISOptsExisted" value="<?=$isOptsExisted;?>" />





<div id="hdnTimer"></div>
<div id="hdnReset"></div>

</div>
</form>
<div id="coachDialogMain" class="window">

<a href="#"class="close"  style="float:right; border:0px;"  /> Close <img src="../Include/images/round_red_close_button.jpg"  border="0" width="20px" height="20px" /></a>
<br />
<div id="replace">
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
<?php if($isExistingSession == 'Y'){echo "<script type='text/javascript'>htmlDataExisting1('populateCoaches.php', 'coachLocation=$eCoachLocation&coachPosition=$eCoachPosition&sessID=$sCoachSessionID', 'ch');</script>";}
$agentScoreObj->closeConn();
?>
