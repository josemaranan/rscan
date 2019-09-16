<?php
//ini_set('display_errors','1');
include_once($_SERVER['DOCUMENT_ROOT']."/agentScoreCard/includes/agentScoreCard.class.inc.php");
$agentScoreObj = new agentScoreCard();

include_once($_SERVER['DOCUMENT_ROOT']."/Include/HTMLContent.class.inc.php");
$htmlObject = new HTMLClass();

include_once($_SERVER["DOCUMENT_ROOT"]."/Include/tempTableStructure.inc.php");

/* Step 2 */
// Load html header content.
$htmlObject->htmlMetaTagsTitle('Agent score card');


$cssJsArray = array('CSS'=>array('agentScoreNew.css','dhtmlgoodies_calendar.css?random=20051112'), 'JS'=>array('dhtmlgoodies_calendar.js?random=20060118' , 'table.js','agentScoreCard/jquery.min.js', 'agentScoreCard/rnetCharts.js' , 'agentScoreCard/coachingNew.js','innerDynamicWH.js'));
$htmlObject->loadCSSJsFiles($cssJsArray);

/* Step 3 */
// Load body tag and left menu.
// Don't pass any thing if dont want left menu.

//$htmlObject->loadBodyTag('leftMenu','','style="bgcolor:red"');

$htmlObject->loadBodyTag('leftMenu','',array('style'=>'background-color:#1F497D'));


/* Step - 4 Load header part */
// Send object of DB class.
$pageHyperlinks = array('Main Menu'=>'Clients/Results/index.php', 'Back'=>'agentScoreCard/viewMyScoreCard.php');
$htmlObject->htmlHeadPart($agentScoreObj->UserDetails->User, $pageHyperlinks);

/* Variable declariations */
$loggedInemployeeID = $agentScoreObj->UserDetails->User;
$requestedDate = date('m/d/Y');
$todayDate = date('m/d/Y');
$yesterDayDate = date ('m/d/Y', strtotime('-1 day', strtotime($todayDate)));

$topLevelHeading = 'Coaching';


$employeeID = '42909';
$currentDate = '06/29/2013';


$locs = $agentScoreObj->getLocations($agentScoreObj->UserDetails->Locations);
$pos = $agentScoreObj->getPositions();
$coaches = $agentScoreObj->getCoaches();
$KPIs = $agentScoreObj->getKPIs();
$behaviors = $agentScoreObj->getBehaviors();
$methods = $agentScoreObj->getMethods();


?>
<div class="outer" id="report_content">

<table border="0" width="100%">
<tr>
<td height="10px" style="font-size:18px; text-align:left;">
<font color="#1F497D">Coaching</font>
</td>
</tr>
</table>

</div> <!-- report content -->

<div align="center">
<br /><br /><br />
<table border="0" width="60%" bgcolor="#FFFFFF">
<tr>
<td>
<div id="INNERSCROLL">
<table border="0" id="searchTable">

<tr>
	<th width="30%;"><strong>Coach Location</strong></th>
    <td>

	 <select name="ddlLocations"  id="ddlLocations">
    <option value="">Please Select</option>
    <?php
    foreach($locs as $locArrayK=>$locArrayV)
    {?>
        <option value="<?php echo $locArrayV['location'];?>">
		<?php echo $locArrayV['description'];?></option>
     <?php 
    }?>
    </select>

	</td>
</tr>

<tr><th width="30%;"><strong>Coach Position</strong></th>
    <td>

	 <select name="ddlPositions"  id="ddlPositions">
    <option value="">Please Select</option>
    <?php
    foreach($pos as $posArrayK=>$posArrayV)
    {?>
        <option value="<?php echo $posArrayV['positionID'];?>">
		<?php echo $posArrayV['description'];?></option>
     <?php 
    }?>
    </select>

	</td>

</tr>
<tr><th width="30%;"><strong>Coach</strong></th>
<td id="ch">

<select name="ddlCoaches"  id="ddlCoaches" onchange="return coachValidations();">
    <option value="">Please Select</option>
    <?php
    foreach($coaches as $coachArrayK=>$coachArrayV)
    {?>
        <option value="<?php echo $coachArrayV['employeeID'];?>">
		<?php echo $coachArrayV['firstName'].' '.$coachArrayV['lastName'];?></option>
     <?php 
    }?>
    </select>
    <input type="hidden" name="hdnSupportValue" id="hdnSupportValue" value="1" />
    
</td>
</tr>
<tr  id="timer" style="display:none;">
<td>

<input type="button" id="btnStartCoach" name="btnStartCoach" value="Start Coaching Timer" style="background-color:#0C3; border: #385D8A 1px solid; padding:0px; font-family:Verdana, Geneva, sans-serif; color:#FFF; text-align:center;" onclick="return startTimer();"/>


<input type="button" id="btnStopCoach" name="btnStopCoach" value="Stop Coaching Timer" style="background-color:#F00; border: #385D8A 1px solid; padding:0px; font-family:Verdana, Geneva, sans-serif; color:#FFF; text-align:center; display:none;" onclick="return stopTimer();"  />

</td>
<td>
<input type="button" id="btnReset" name="btnReset" value="Reset Coaching Timer" onclick="return resetCoachTimer();"/>
</td>
</tr>

<tr  id="timeSpent" style="display:none;">
<th width="30%;"><strong>Time Spent</strong></th>
<td>
00:05:31
</td>
</tr>

<tr>
<td colspan="2"  id="strenghts" style="display:none;">
<br />
<fieldset style="border:3px solid #385D8A;">
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
<input type="button" id="btnStrenghs" name="btnStrenghs" value="Add Strengths" onclick="return addStrenghts();" />
</td>
</tr>

</table>
</fieldset>
</td>
</tr>

<tr id="opportunities" style="display:none;">
<td colspan="2">
<br />
<fieldset style="border:3px solid #385D8A;">
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
    foreach($behaviors as $bhArrayK=>$bhArrayV)
    {?>
        <option value="<?php echo $bhArrayV['behaviorID'];?>">
		<?php echo $bhArrayV['description'];?></option>
     <?php 
    }?>
    </select>
</td>
<th width="20%">Method <font color="#FF0000">*</font></th>
<td>

<select name="ddlOppMethods"  id="ddlOppMethods">
    <option value="">Please Select</option>
    <?php
    foreach($methods as $methodArrayK=>$methodArrayV)
    {?>
        <option value="<?php echo $methodArrayV['methodID'];?>">
		<?php echo $methodArrayV['description'];?></option>
     <?php 
    }?>
    </select>

</td>
</tr>
<tr>
<th width="20%"><input type="button" id="test" value="Action Plan" style="background-color:#69F; text-decoration:blink;" onclick="return populateDetailsNew();"/></th>
<td colspan="3">

<textarea id="txtActionPlan" name="txtActionPlan" cols="50" rows="5"></textarea>
</td>
</tr>

<tr>
<td colspan="4" style="text-align:center;">
<input type="button" id="btnOpportunities" name="btnOpportunities" value="Add Opportunities" onclick="return addOpportunities();" />
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
<input type="hidden" name="hdnTimerStart" id="hdnTimerStart" value="" />
<input type="hidden" name="hdnTimerEnd" id="hdnTimerEnd" value="" />
<input type="hidden" name="hdnSessionID" id="hdnSessionID" value="" />

<div id="hdnTimer"></div>

</div>

<div id="coachDialogMain" class="window">

<a href="#"class="close"  style="float:right; border:0px;"  /> Close <img src="../Include/images/round_red_close_button.jpg"  border="0" width="20px" height="20px" /></a>
<br />
<div id="replace">
</div>
</div>
<!-- Mask to cover the whole screen -->
<div id="mask"></div>

<script type="text/javascript">makeItDynamic();</script>

<script language="javascript" type="text/javascript">
function populateDetailsNew()  
{
	var list = window.open("pupulateActionPlansNew.php","ChooseEmployee", "width=450,height=200,resizable=0,scrollbars=0");
}
</script>

<style type="text/css">
#mask {
  position:absolute;
  left:0;
  top:0;
  z-index:9000;
  background-color:#000;
  display:none;
}
  
.window {
  position:absolute;
  left:0;
  top:0;
  width:40px;
  height:600px;
  display:none;
  z-index:9999;
  padding:20px;
  overflow:auto;
  
}

#dialogMain {
  width:940px; 
  height:400px;
  padding:10px;
  background-color:#ffffff;
}


#coachDialogMain
{
  width:600px; 
  height:200px;
  padding:10px;
  background-color:#ffffff;
}
</style>
