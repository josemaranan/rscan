<?php
switch($lob_id)
{
	
		case 'Telesales':
?>
<div class="section">
<div class="sectionContent">
        <table border="0"  bgcolor="#FFFFFF" cellspacing="0" class="report">
        <thead>
          <tr>
            
           
            <th colspan="10" align="center"><strong>Scorecard Paid 4 Performance: Cost per Sale</strong></th>
          </tr>
        </thead>
        <tr>
            <td style="text-align:center"><strong>Scorecard Range</strong></td>
            <td style="text-align:center"><strong>PTG Ranking</strong></td>
            <td style="text-align:center"><strong>PTG Factor</strong></td>
            <td style="text-align:center"><strong>Segment</strong></td>
            <td style="text-align:center"><strong>Seg Bonus</strong></td>
            <td style="text-align:center"><strong>GA</strong></td>
            <td style="text-align:center"><strong>Dev Pro</strong></td>
            <td style="text-align:center"><strong>Accessory</strong></td>
            <td style="text-align:center"><strong>Segment Definition</strong></td>
            <td style="text-align:center"><strong>Qualifier</strong></td>
		</tr>
        <tr>
        	<td style="text-align:center">3.0-3.15</td>
            <td style="text-align:center">Below 90%</td>
            <td style="text-align:center">1</td>
            <td style="text-align:center">C</td>
            <td style="text-align:left">PHP&nbsp;&nbsp;&nbsp;-</td>
            <td style="text-align:center">PHP 100.00</td>
            <td style="text-align:center">PHP 50.00</td>
            <td style="text-align:center">PHP 50.00</td>
            <td style="text-align:center">Met GACR Only</td>
            <td style="text-align:center">3.0 Overall Scorecard</td>
        </tr>

        <tr>
        	<td style="text-align:center">3.16 - 3.49</td>
            <td style="text-align:center">90 - 99.99%</td>
            <td style="text-align:center">2</td>
            <td style="text-align:center">B-</td>
            <td style="text-align:left">PHP&nbsp;&nbsp;&nbsp;-</td>
            <td style="text-align:center">PHP 125.00</td>
            <td style="text-align:center">PHP 50.00</td>
            <td style="text-align:center">PHP 50.00</td>
            <td style="text-align:center">Missed GACR, Met other 2 metrics</td>
            <td style="text-align:center">105%> GA% for Dev& Accy</td>
        </tr>


        <tr>
        	<td style="text-align:center">3.5 - 3.74</td>
            <td style="text-align:center">100 - 104.99%</td>
            <td style="text-align:center">3</td>
            <td style="text-align:center">B</td>
            <td style="text-align:left">PHP&nbsp;&nbsp;&nbsp;-</td>
            <td style="text-align:center">PHP 150.00</td>
            <td style="text-align:center">PHP 50.00</td>
            <td style="text-align:center">PHP 50.00</td>
            <td style="text-align:center">Met GACR +1 Other Metric</td>
            <td style="text-align:center"> 85%+ Scheduled Adherence</td>
        </tr>


        <tr>
        	<td style="text-align:center">3.75 - 3.99</td>
            <td style="text-align:center">105 - 119.99%</td>
            <td style="text-align:center">4</td>
            <td style="text-align:center">A</td>
            <td style="text-align:left">PHP&nbsp;&nbsp;&nbsp;-</td>
            <td style="text-align:center">PHP 175.00</td>
            <td style="text-align:center">PHP 50.00</td>
            <td style="text-align:center">PHP 50.00</td>
            <td style="text-align:center">All 3 Metrics Met </td>
            <td></td>
        </tr>


  		<tr>
        	<td style="text-align:center">4 and above</td>
            <td style="text-align:center">120% and above</td>
            <td style="text-align:center">5</td>
            <td style="text-align:center">A+</td>
            <td style="text-align:left">PHP 5,000</td>
            <td style="text-align:center">PHP 200.00</td>
            <td style="text-align:center">PHP 50.00</td>
            <td style="text-align:center">PHP 50.00</td>
            <td style="text-align:center">All 3 Metrics Met </td>
            <td></td>
        </tr>


        
        
        
        </table>
</div>
<br />

		<div id="sprintlegendContent" style="border:2px solid #7AC143; width:20%; text-align:center;">
    	<strong>Paid for Performance Calculator</strong>
        
        <table border="3" cellpadding="3" cellspacing="3">
        <tr>
        <td>Segment</td><td>A</td></tr>
        <td>Score</td><td>2.0</td></tr>
        <td>P4P Amount</td><td>TBD</td>
        </tr>
        </table>
        
        </div></div>


<br />
<br />

<?php
break;

case 'CLC':
?>
<div class="section">
<div class="sectionContent">
        <table border="0"  bgcolor="#FFFFFF" cellspacing="0" class="report">
        <thead>
          <tr>
            
           
            <th colspan="10" align="center"><strong>Per Sale Commission based on Scorecard</strong></th>
          </tr>
        </thead>
        <tr>
            <td style="text-align:center"><strong>Segment</strong></td>
            <td style="text-align:center"><strong>Segmentation P4P</strong></td>
            <td style="text-align:center"><strong>Segmentation Definition</strong></td>
            <td style="text-align:center"><strong>Qualifier</strong></td>
		</tr>
        <tr>
        	<td style="text-align:center">C</td>
            <td style="text-align:center">PHP 1,000.00</td>
            <td style="text-align:center">0 of 4 Metrics</td>
            <td style="text-align:center">3.0 Overall Scorecard</td>
        </tr>

        <tr>
        	<td style="text-align:center">B-</td>
            <td style="text-align:center">PHP 2,000.00</td>
            <td style="text-align:center">1 of 4 Metrics</td>
            <td style="text-align:center">90%+ CST Usage</td>
        </tr>
        
        <tr>
        	<td style="text-align:center">B</td>
            <td style="text-align:center">PHP 3,000.00</td>
            <td style="text-align:center">2 of 4 Metrics</td>
            <td style="text-align:center">95%+ Schedule Adherence</td>
        </tr>
        
        <tr>
        	<td style="text-align:center">A</td>
            <td style="text-align:center">PHP 4,000.00</td>
            <td style="text-align:center">3 of 4 Metrics</td>
            <td style="text-align:center"></td>
        </tr>

        <tr>
        	<td style="text-align:center">A+</td>
            <td style="text-align:center">PHP 5,000.00</td>
            <td style="text-align:center">4 of 4 Metrics</td>
            <td style="text-align:center"></td>
        </tr>




        
        
        
        </table>
</div>
<br />

</div>


<br />
<br />

<?php
break;

case 'SXM_IB OEM':
//$rejectionReason = $agentScoreObj->getRejectionId($employeeID , $requestedDate, $client, $lob_id);
unset($sqlQuery);
unset($resultsSet);
unset($rosNum);
unset($threeSetData);
$sqlQuery = " EXEC XMCOMMON.dbo.[report_spSXM_Saves_Scorecard_SectionTwo_Test] '".$employeeID."','".$requestedDate."' ";
$resultsSet = $agentScoreObj->ExecuteQuery($sqlQuery, '', 'LIVE');
$rosNum = mssql_num_rows($resultsSet);
if($rosNum>=1)
{
	$threeSetData = $agentScoreObj->bindingInToArray($resultsSet);
}
?>
<div class="section" id="seciton3" style="width:35%; margin-left:4px;"><div class="sectionHeading" id="div3" style="text-align:left; padding-left:20px; background-image:url(includes/images/minus.gif); background-repeat:no-repeat; background-position:left;" class="locked" onclick="return toggleDivs('exPandDiv3', this.id); return false;" title="collapse">&nbsp;</div><div class="sectionContent" id="exPandDiv3"><table border="0"  bgcolor="#FFFFFF" cellspacing="0" class="report" width="99%;">
						<thead>
						<tr><th align="center"><strong>KPI</strong></th><th align="center"><strong>Current <br />Attainment</strong></th><th align="center"><strong>Level</strong></th><th align="center" colspan="1"><strong>Current <br />Bonus*</strong></th><th align="center" colspan="1"><strong>Projected <br />Monthly <br />Bonus*</strong></th></tr></thead><tbody><tr style="height:20px; background-color:#D0D8E8;" class='seciton3'>
						  <td style="text-align:left; padding-left:20px; background-image:url(includes/images/plus.gif); background-repeat:no-repeat; background-position:left;" class="locked" id="140530060832140530060832SXMSaves-SectionTHREE" onclick="return toggleThisUp(this.id, 'seciton3'); return false;" title="expand">AHT</td>
                          <td style="text-align:right;"><?php echo number_format($threeSetData[2]['AHTKPI'], 2,'.','');?></td>
						  <td style="text-align:right;"><?php echo $threeSetData[2]['AHTCA'];?></td>
						  <td rowspan="3" style="text-align:center;"><?php echo $threeSetData[2]['EstimatedBonusMTD'];?></td>
						  <td rowspan="3" style="text-align:center;"><?php echo $threeSetData[2]['ProjectedBonus'];?></td></tr><tr style="height:25px; background-color:#D0D8E8;" class="hidden">
						    <td style="text-align:left;">Save Rate</td>
						    <td style="text-align:right;"><?php echo $threeSetData[2]['SavesRateWOCPercentKPI'];?></td> <td style="text-align:right;"><?php echo $threeSetData[2]['SaveRateConversionCA'];?></td></tr><tr style="height:25px; background-color:#D0D8E8;" class="hidden">
						      <td style="text-align:left;">1 Year Mix</td>
						      <td style="text-align:right;"><?php echo $threeSetData[2]['OneYearMixPercentKPI'];?></td><td style="text-align:right;"><?php echo $threeSetData[2]['OneYearMixCA'];?></td></tr>
                              <tr style="height:25px; background-color:#D0D8E8;" class="hidden">
						      <td style="text-align:left;">Payable Saves</td>
						      <td style="text-align:right;"><?php echo $threeSetData[2]['PayableSaves'];?></td>
                              <td colspan="3">&nbsp;</td></tr>
                              </tbody></table></div></div>   

<div class="section" id="seciton4" style="width:35%; margin-left:4px;"><div class="sectionHeading" id="div4" style="text-align:left; padding-left:20px; background-image:url(includes/images/minus.gif); background-repeat:no-repeat; background-position:left;" class="locked" onclick="return toggleDivs('exPandDiv4', this.id); return false;" title="collapse">SXM Saves - Metrics -  Last Loaded Date</div><div class="sectionContent" id="exPandDiv4"><table border="0"  bgcolor="#FFFFFF" cellspacing="0" class="report" width="99%;">
<thead>
	<tr><th align="center"><strong>AHT Daily</strong></th>
    <th align="center"><strong>AHT Weekly</strong></th>
    <th align="center"><strong>AHT Monthly</strong></th>
    <th align="center"><strong>Schedule Adherence</strong></th>
    <th align="center"><strong>Saves Metrics</strong></th>
    <th align="center"><strong>CSAT</strong></th>
    <th align="center"><strong>Quality</strong></th>
</tr>
</thead>
	<tbody>
    <tr style="height:20px; background-color:#D0D8E8;" class='seciton3'>
	<td style="text-align:center;"><?php echo $threeSetData[2]['AHTDailyLastLoaded'];?></td>
    <td style="text-align:center;"><?php echo $threeSetData[2]['AHTWeeklyLastLoaded'];?></td>
    <td style="text-align:center;"><?php echo $threeSetData[2]['AHTMonthlyLastLoaded'];?></td>
    <td style="text-align:center;"><?php echo $threeSetData[2]['SALastLoaded'];?></td>
    <td style="text-align:center;"><?php echo $threeSetData[2]['SavesMetricsLastLoaded'];?></td>
    <td style="text-align:center;"><?php echo $threeSetData[2]['CSATLastLoaded'];?></td>
    <td style="text-align:center;"><?php echo $threeSetData[2]['QualityLastLoaded'];?></td>
    </tr>
       </tbody>                   

</table>
</div> 
</div>


<?php
break;


}
?>