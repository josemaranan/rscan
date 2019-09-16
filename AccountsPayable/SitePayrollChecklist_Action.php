<? 
session_start();
include_once($_SERVER["DOCUMENT_ROOT"]."/Include/Global.inc.php");

$db=mssql_connect(MSSQL_HOST, MSSQL_USERNAME, MSSQL_PASSWORD);
mssql_select_db(MSSQL_DB);


if($_REQUEST['loc'])
{
	$location = $_REQUEST['loc'];
}

 	  $sql="SELECT a.*, b.description as invoiceType
			FROM  [ctlLocations] (NOLOCK) a 
			left outer join ctlInvoiceTypes (NOLOCK) b
			on  a.invoiceTypeID = b.invoiceTypeID             
			WHERE 
				State IS NOT NULL AND
				location = ".$location;

  $rst=mssql_query($sql, $db);
  
   while ($row=mssql_fetch_array($rst)) 
   {

		$locationDescription =$row[description];
		$usingPayroll =$row[rnetPayroll];
		
		if(isset($row[rnetPayrollEffectiveDate]))
		{
			$effDate = date('m/d/Y',strtotime($row[rnetPayrollEffectiveDate]));
		}
		else
		{
			$effDate = '';
		}

		$est_exception_value=$row[useEST];
		$useTimeClock=$row[useTimeClock];
		$usingNewHireBonus =$row[usesNewHireBonus];
		$invoiceTypeID=$row[invoiceTypeID];
   }
  
  mssql_free_result($rst);
  
  
  
   	  $sql2="SELECT *,convert(varchar,shiftDifferentialStart,108) as shiftDifferentialStart1 ,convert(varchar,shiftDifferentialEnd,108) as shiftDifferentialEnd1
			FROM  
				[ctlLocationPayrollReporting] (NOLOCK)             
			WHERE 
				location = ".$location;

  $rst=mssql_query($sql2, $db);
  
   while ($row=mssql_fetch_array($rst)) 
   {
		if(isset($row[breakGenerationEffectiveDate]))
		{
			$breakGenerationEffectiveDate = date('m/d/Y',strtotime($row[breakGenerationEffectiveDate]));
		}
		else
		{
			$breakGenerationEffectiveDate = '';
		}
		
		
		if(isset($row[payDataFileEffectiveDate]))
		{
			$payDataFileEffectiveDate = date('m/d/Y',strtotime($row[payDataFileEffectiveDate]));
		}
		else
		{
			$payDataFileEffectiveDate = '';
		}
		

		if(isset($row[3]))
		{
			$summaryReportProcessingEffectiveDate = date('m/d/Y',strtotime($row[3]));
		}
		else
		{
			$summaryReportProcessingEffectiveDate = '';
		}
		
		
		if(isset($row[4]))
		{
			$summaryReportReportingEffectiveDate = date('m/d/Y',strtotime($row[4]));
		}
		else
		{
			$summaryReportReportingEffectiveDate = '';
		}
		
		if(isset($row[13]))
		{
			$chdCmsReconciliationEffectiveDate = date('m/d/Y',strtotime($row[13]));
		}
		else
		{
			$chdCmsReconciliationEffectiveDate = '';
		}

		
		$requiresSchedule = $row[5];
		$overtimeBasisRule = $row[6];
		$overtimeBasisThreshold = $row[7]/3600;
		$timeclockCap = $row[8]/3600;
		
		
		if(isset($row[shiftDifferentialStart1]))
		{
			//$shiftDifferentialStart = date('H:i',strtotime($row[shiftDifferentialStart]));
			$shiftDifferentialStart = substr($row[shiftDifferentialStart1], 0, 5);  
		}
		else
		{
			$shiftDifferentialStart = '';
		}
		
		if(isset($row[shiftDifferentialEnd1]))
		{
			//$shiftDifferentialEnd = date('H:i',strtotime($row[shiftDifferentialEnd]));
			$shiftDifferentialEnd = substr($row[shiftDifferentialEnd1], 0, 5);  
			
		}
		else
		{
			$shiftDifferentialEnd = '';
		}

		

		
		
		$usesOasis = $row[11];
		$locked = $row[12];
		
		$excludeProductionForTimeclock = $row[14];
		$reportProductionTimeOnly = $row[reportProductionTimeOnly];
		$employeeAverageHourlyCost = $row[employeeAverageHourlyCost];
	
   }
  
  mssql_free_result($rst);
  mssql_close();

  
  
?>

<div id = "dialogTitle" style="background:#1266B1; color:#FFF; font-size:11px; font-weight:bold; padding:5px;" class="outer"><?=$locationDescription?> Payroll</div>

<div style="margin:0px; padding:0px; float:right; padding-right:10px;" id="testDiv" class="outer">
<a href="#" onclick="return closeMask(); return false;">Close</a>
</div>

<br clear="all" />


<div>
<form method="POST" action="SitePayrollChecklist_Action_process.php" name="form_data">

<table  border="1" bordercolor="#CCCCCC" class="black_small" style="border-collapse:collapse; vertical-align:top; width:98%; text-align:left;">
	  
		 <tr style="width:40%">
		 
		 <td valign="top">
         <INPUT TYPE="HIDDEN" VALUE="<?php echo $location ?>" NAME="hdnLocation">
		 <table border="1" cellpadding="1" cellspacing="1" bordercolor="#CCCCCC" class="black_small" style="border-collapse:collapse;">
		 <tr>
		 
		 	
        <td style="text-align: right;">Effective Date:<span style="color: #ff0000">*</span></td>
        <td class="blue_large" style="text-align: left;"><select name="ddlStartDates"  id="ddlStartDates" style="width:200px;">
            <option value="">Please Choose</option>
		<?php
		$db=mssql_connect(MSSQL_HOST, MSSQL_USERNAME, MSSQL_PASSWORD);
		mssql_select_db(MSSQL_DB);

			$SQL=" SELECT startDate FROM ctlLocationPaydateschedules (NOLOCK) WHERE location = ".$location." ORDER BY startDate";
			
		$rst=mssql_query($SQL, $db);		
		
		while ($row=mssql_fetch_array($rst)) {
			$startDate = date('M. j, Y',strtotime($row[startDate]));
			print "<option value='$row[startDate]'";
			if (date('m/d/Y',strtotime($row[startDate])) == $effDate) { 
				print " selected";
			}
			print ">$startDate</option>\n";
		}
		mssql_free_result($rst);
	  ?>
          </select></td>
      </tr>
      <tr>
        <td style="text-align: right;">Using Payroll?<span style="color: #ff0000">*</span></td>
        <td style="text-align: left;"><label>
          <input type="radio" name="usingPayroll" id="usingPayroll_1" value="Y" <? if($usingPayroll == 'Y'){ print " checked";}?>/>
          </label>
          Yes
          <input type="radio" name="usingPayroll" id="usingPayroll_2" value="N" <? if($usingPayroll != 'Y'){ print " checked";}?> />
          No</td>
      </tr>
	  <tr>
        <td style="text-align: right;">Using Time Clock?<span style="color: #ff0000">*</span></td>
        <td style="text-align: left;"><label>
          <input type="radio" name="useTimeClock" id="useTimeClock" value="Y" <? if($useTimeClock == 'Y'){ print " checked";}?>/>
          </label>
          Yes
          <input type="radio" name="useTimeClock" id="useTimeClock" value="N" <? if($useTimeClock != 'Y'){ print " checked";}?> />
          No</td>
      </tr>
	  
	  
	  	        <tr>
        <td style="text-align: right;">Using New Hire Bonus?<span style="color: 

#ff0000">*</span></td>
        <td style="text-align: left;"><label>
          <input type="radio" name="useNewHireBonus" id="usingNewHireBonus_1" value="Y" <? 

if($usingNewHireBonus == 'Y'){ print " checked";}?>/>
          </label>
          Yes
          <input type="radio" name="useNewHireBonus" id="usingNewHireBonus_2" value="N" <? 

if($usingNewHireBonus != 'Y'){ print " checked";}?> />
          No</td>
      </tr>

	  
	  
	  <tr>
	    <td style="text-align: right;">Invoice Type</td>
	    <td style="text-align: left;"><span class="blue_large" style="text-align: left;">
	      <select name="invoiceType"  id="invoiceType" style="width:200px;">
            <option value="">Please Choose</option>
            <?php

			$SQL2=" SELECT * FROM ctlinvoiceTypes (NOLOCK)";
			
		$rst2=mssql_query($SQL2, $db);		
		
		while ($row=mssql_fetch_array($rst2)) {
			echo "<option value='$row[invoiceTypeID]'";
			if ($row[invoiceTypeID] == $invoiceTypeID) { 
				echo " selected";
			}
			echo ">$row[description]</option>\n";
		}
		mssql_free_result($rst);
	  ?>
          </select>
	    </span></td>
      </tr>
	  <tr>
        <td style="text-align: right;">Use EST for exceptions?</td>
        <td style="text-align: left;">
		<input type="checkbox" id="est_exception" name="est_exception" value="Y" <? if($est_exception_value=='Y') { echo 'checked="checked" ';  } ?> />        </td>
      </tr>


		
		 </table>
		 </td>
	 
 		 <td style="width:55%">
	<table border="1" width="450px" cellpadding="1" cellspacing="1" bordercolor="#CCCCCC" class="black_small" style="border-collapse:collapse;">

		 <tr>
		 <td style="text-align: right;">
		 Break Generation Effective Date
		 </td>
 		 <td>
		 <select name="ddlbreakGenerationEffectiveDate"  id="ddlbreakGenerationEffectiveDate" style="width:200px;">
            <option value="">Please Choose</option>
		<?php


			$SQL=" SELECT startDate FROM ctlLocationPaydateschedules (NOLOCK) WHERE location = ".$location." ORDER BY startDate";
			
		$rst=mssql_query($SQL, $db);		
		
		while ($row=mssql_fetch_array($rst)) {
			$startDate = date('M. j, Y',strtotime($row[startDate]));
			print "<option value='$row[startDate]'";
			if (date('m/d/Y',strtotime($row[startDate])) == $breakGenerationEffectiveDate) { 
				print " selected";
			}
			print ">$startDate</option>\n";
		}
		mssql_free_result($rst);
	  ?>
          </select>
		 </td>
		 </tr>
		 <tr>
 		 <td style="text-align: right;">
		 Pay Data File Effective Date
		 </td>
 		 <td>
		 <select name="ddlpayDataFileEffectiveDate"  id="ddlpayDataFileEffectiveDate" style="width:200px;">
            <option value="">Please Choose</option>
		<?php


			$SQL=" SELECT startDate FROM ctlLocationPaydateschedules (NOLOCK) WHERE location = ".$location." ORDER BY startDate";
			
		$rst=mssql_query($SQL, $db);		
		
		while ($row=mssql_fetch_array($rst)) {
			$startDate = date('M. j, Y',strtotime($row[startDate]));
			print "<option value='$row[startDate]'";
			if (date('m/d/Y',strtotime($row[startDate])) == $payDataFileEffectiveDate) { 
				print " selected";
			}
			print ">$startDate</option>\n";
		}
		mssql_free_result($rst);
	  ?>
          </select>
		 </td>
		 </tr>
		 <tr>
 		 <td style="text-align: right;">
		 Summary Report Processing Effective Date
		 </td>
 		 <td>
		<select name="ddlsummaryReportProcessingEffectiveDate"  id="ddlsummaryReportProcessingEffectiveDate" style="width:200px;">
            <option value="">Please Choose</option>
		<?php


			$SQL=" SELECT startDate FROM ctlLocationPaydateschedules (NOLOCK) WHERE location = ".$location." ORDER BY startDate";
			
		$rst=mssql_query($SQL, $db);		
		
		while ($row=mssql_fetch_array($rst)) {
			$startDate = date('M. j, Y',strtotime($row[startDate]));
			print "<option value='$row[startDate]'";
			if (date('m/d/Y',strtotime($row[startDate])) == $summaryReportProcessingEffectiveDate) { 
				print " selected";
			}
			print ">$startDate</option>\n";
		}
		mssql_free_result($rst);
	  ?>
          </select>
		 </td>
		 </tr>
		 <tr>
 		 <td style="text-align: right;">
		 Summary Report Reporting Effective Date
		 </td>
 		 <td>
		 <select name="ddlsummaryReportReportingEffectiveDate"  id="ddlsummaryReportReportingEffectiveDate" style="width:200px;">
            <option value="">Please Choose</option>
		<?php

			$SQL=" SELECT startDate FROM ctlLocationPaydateschedules (NOLOCK) WHERE location = ".$location." ORDER BY startDate";
			
		$rst=mssql_query($SQL, $db);		
		
		while ($row=mssql_fetch_array($rst)) {
			$startDate = date('M. j, Y',strtotime($row[startDate]));
			print "<option value='$row[startDate]'";
			if (date('m/d/Y',strtotime($row[startDate])) == $summaryReportReportingEffectiveDate) { 
				print " selected";
			}
			print ">$startDate</option>\n";
		}
		mssql_free_result($rst);
	  ?>
          </select>
		 </td>
		 </tr>
		 <tr>
 		 <td style="text-align: right;">
		 Requires Schedule?		 </td>
 		 <td>
		 <input type="radio" name="rdorequiresSchedule" id="requiresSchedule_1" value="Y" <? 

if($requiresSchedule == 'Y'){ print " checked";}?>/>
          </label>
          Yes
          <input type="radio" name="rdorequiresSchedule" id="requiresSchedule_2" value="N" <? 

if($requiresSchedule != 'Y'){ print " checked";}?> />
          No
		 </td>
		 </tr>
		 <tr>
 		 <td style="text-align: right;">
		 Overtime Basis Rule		 </td>
 		 <td>
		 <select name="ddlovertimeBasisRule"  id="ddlovertimeBasisRule" style="width:200px;">
            <option value="">Please Choose</option>
			<option  value="Week"  <? if($overtimeBasisRule == 'Week') {echo 'selected';} ?>>Week</option>
			<option value="Shift" <? if($overtimeBasisRule == 'Shift') {echo 'selected';} ?>>Shift</option>
		 </select>
		 </td>
		 </tr>
		 <tr>
		 
		 <td style="text-align: right;">
		 Overtime Basis Threshold (hours)
		 </td>
 		 <td>
 <input name="txtovertimeBasisThreshold" type="text" id="txtovertimeBasisThreshold"  style="width: 60px" value="<?=$overtimeBasisThreshold;?>" />
		 </td>
		 </tr>
		 <tr>
 		 <td style="text-align: right;">
		 Time clock cap (hours)
		 </td>
 		 <td>
	 <input name="txttimeclockCap" type="text" id="txttimeclockCap"  style="width: 60px" value="<?=$timeclockCap;?>" />
		 </td>
		 </tr>
		 
	   <tr>
	  <td colspan="2">
	  <img src="../Include/images/progress.gif" id="progress" style="display: none"  />
	  </td>
	  </tr>
		 
		 
		 <tr>
 		 <td style="text-align: right;">
		 Shift differential start (HH:MM)
		 </td>
 		 <td>
	<input name="txtshiftDifferentialStart" type="text" id="txtshiftDifferentialStart"  style="width: 60px" value="<?=$shiftDifferentialStart;?>" maxlength="5"  onchange="return setdatefield(this.value, this.id); return false;"  />
		 </td>
		 </tr>
		 <tr>
 		 <td style="text-align: right;">
		 Shift differential end (HH:MM)
		 </td>
 		 <td>
	<input name="txtshiftDifferentialEnd" type="text" id="txtshiftDifferentialEnd"  style="width: 60px" value="<?=$shiftDifferentialEnd;?>" maxlength="5"  onchange="return setdatefield(this.value, this.id); return false;"  />
		 </td>
		 </tr>
		 <tr>
 		 <td style="text-align: right;">
		 Uses Oasis payroll?
		 </td>
 		 <td>
		 <input type="radio" name="rdousesOasis" id="usesOasis_1" value="Y" <? 

if($usesOasis == 'Y'){ print " checked";}?>/>
          </label>
          Yes
          <input type="radio" name="rdousesOasis" id="usesOasis_2" value="N" <? 

if($usesOasis != 'Y'){ print " checked";}?> />
          No
		 </td>
		 </tr>
		 
		 <tr>
 		 <td style="text-align: right;">
		 Locked?
		 </td>
 		 <td>
		 <input type="radio" name="rdolocked" id="locked_1" value="Y" <? 

if($locked == 'Y'){ print " checked";}?>/>
          </label>
          Yes
          <input type="radio" name="rdolocked" id="locked_2" value="N" <? 

if($locked != 'Y'){ print " checked";}?> />
          No
		 </td>
		 </tr>
		 
		 <tr>
 		 <td style="text-align: right;">
		 CHD/CMS reconciliation effective date
		 </td>
 		 <td>
		 <select name="ddlchdCmsReconciliationEffectiveDate"  id="ddlchdCmsReconciliationEffectiveDate" style="width:200px;">
            <option value="">Please Choose</option>
		<?php

			$SQL=" SELECT startDate FROM ctlLocationPaydateschedules (NOLOCK) WHERE location = ".$location." ORDER BY startDate";
			
		$rst=mssql_query($SQL, $db);		
		
		while ($row=mssql_fetch_array($rst)) {
			$startDate = date('M. j, Y',strtotime($row[startDate]));
			print "<option value='$row[startDate]'";
			if (date('m/d/Y',strtotime($row[startDate])) == $chdCmsReconciliationEffectiveDate) { 
				print " selected";
			}
			print ">$startDate</option>\n";
		}
		mssql_free_result($rst);
	
	  ?>
          </select>
		 </td>
		 </tr>


		 <tr>
 		 <td style="text-align: right;">
		 Exclude production time when time clock hours are present?
		 </td>
 		 <td>
		 <input type="radio" name="rdoexcludeProductionForTimeclock" id="excludeProductionForTimeclock_1" value="Y" <? 

if($excludeProductionForTimeclock == 'Y'){ print " checked";}?>/>
          </label>
          Yes
          <input type="radio" name="rdoexcludeProductionForTimeclock" id="excludeProductionForTimeclock_2" value="N" <? 

if($excludeProductionForTimeclock != 'Y'){ print " checked";}?> />
          No
		 </td>
		 </tr>


		 <tr>
 		 <td style="text-align: right;">
		 Report production time only?
		 </td>
 		 <td>
		 <input type="radio" name="rdoreportProductionTimeOnly" id="reportProductionTimeOnly_1" value="Y" <? 

if($reportProductionTimeOnly == 'Y'){ print " checked";}?>/>
          </label>
          Yes
          <input type="radio" name="rdoreportProductionTimeOnly" id="reportProductionTimeOnly_2" value="N" <? 

if($reportProductionTimeOnly != 'Y'){ print " checked";}?> />
          No
		 </td>
		 </tr>


		 <tr>
 		 <td style="text-align: right;">
		 Employee average hourly cost
		 </td>
 		 <td>
	<input name="txtemployeeAverageHourlyCost" type="text" id="txtemployeeAverageHourlyCost"  style="width: 60px" value="<?=$employeeAverageHourlyCost;?>" />
		 </td>
		 </tr>


		 
		 </table>
		 </td>



      </tr>
   </table>
   </tr>
    

<tr align="center">
<td align="center" colspan="4">
    <input class="WSGInputButton" type="submit" name="submit" value="Submit" onclick="return Validate()"/>
    <input type="button" value="Cancel" id="Cancel" onclick="return closeMask(); return false;"/>
	
</td>
</table>
<? mssql_close(); ?>
 </form>
</div>