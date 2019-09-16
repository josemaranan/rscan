<?php
//ini_set('display_errors', '1');
include_once($_SERVER['DOCUMENT_ROOT']."/agentScoreCard/includes/agentScoreCard.class.inc.php");
$agentScoreObj = new agentScoreCard();
include_once($_SERVER['DOCUMENT_ROOT']."/agentScoreCard/includes/postVariables.php");


/*echo 'Start Date'.$startDate.'<br>';
echo 'End Date'.$endDate.'<br>';
echo 'measure'.$measure.'<br>';
echo 'period'.$period.'<br>';
echo 'employeID'.$employeID.'<br>';*/
$drilldownHeaders = $agentScoreObj->getdrillDownHeadersForReport($sectionDetailID , $reportType);
$drilldownMappings = $agentScoreObj->getdrillDownMappingForReport($sectionDetailID , $reportType);
//echo 'employeeID'.$employeeID;

/*echo '<pre>';
print_r($drilldownMappings);
echo '</pre>';
exit;*/

/*echo '<pre>';
print_r($requiredData);
echo '</pre>';
*/

?>
<div>

<div id="dialogTitle" style="background-color:#7AC143; height:30px;">
    <div style="float:left; padding-left:10px; padding-top:5px; font-size:11px; font-weight:bold;">
    	<?php echo str_replace(array('<br>','<br />', '<br/>'),'',$title);?>
    </div>
        <div style="margin:0px; padding:0px; float:right;" id="imageIcon">
            <img src="../../Include/images/roundCloseSmall_green_20.jpg" border="0" onClick="return closeMask(); return false;" style="cursor:hand; position:fixed; padding-top:5px;" />
        </div>
</div>

<div style="margin:0px; padding:0px; margin-top:7px; width:auto;" id="midConent">

<table border="1" cellpadding="0" bgcolor="#FFFFFF" cellspacing="0" style="width:100%;" class="report table-autosort table-stripeclass:alternate"> 

<thead>
	<?php
		$columnHeadings = $agentScoreObj->generateColumnHeadings(
																	   $drilldownHeaders, 
																	   $periodParameter
																	   );
		echo $columnHeadings;
	?>
</thead>


<tbody>
<?php
		$columnContent = $agentScoreObj->generateColumnConent(
																	   $drilldownMappings, 
																	   $periodParameter,
																	   $requiredData,
																	   $trStryleArray,
																	   $period,
																	   $startDate,
																	   $employeID,
																	   $sectionDetailID, 
																	   $height, 
																	   $width,
																	   $reportType,
																	   $iconf,
																	   $isBar,
																	   $isPie
																	   );
		echo $columnContent;
	?>
</tbody>


</table>

</div>
</div>
<?php $agentScoreObj->closeConn();  ?>