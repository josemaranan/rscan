<?php
//ini_set('display_errors','1');
include_once($_SERVER['DOCUMENT_ROOT']."/agentScoreCard/includes/agentScoreCard.class.inc.php");
$agentScoreObj = new agentScoreCard();
$width = '900';
$height = '500';
$title = '';
include_once($_SERVER['DOCUMENT_ROOT']."/agentScoreCard/includes/postVariables.php");
$weektoDate = array('WTD'=>'Week-to-Date', 'MTD'=>'Month-to-Date');
//echo '<pre>';
//print_r($agentScoreObj->indicatorArray);
/*echo '<pre>';
print_r($_REQUEST);
echo '</pre>';*/
//exit();

unset($startDate);
unset($employeeId);
unset($sectionDetailID);

$startDate = $_REQUEST['startDate'];
$employeID = $_REQUEST['employeID'];
$sectionDetailID = $_REQUEST['sectionDetailID'];

unset($sqlQuery);
unset($resultsSet);
unset($numRows);
unset($stackData);
unset($getSp);

$sqlQuery = " SELECT sectionLevelSP FROM Rnet.dbo.prmAgentScoreCardSections WITH (NOLOCK) 
			  WHERE scoreCardSectionID = ".$sectionDetailID." ";
//echo $sqlQuery;
$resultsSet = $agentScoreObj->ExecuteQuery($sqlQuery);
$numRows = mssql_num_rows($resultsSet);
if($numRows>0)
{
	$requiredData = $agentScoreObj->bindingInToArray($resultsSet);	
}

$getSp = $requiredData[0]['sectionLevelSP'];
unset($sqlQuery);
unset($resultsSet);
unset($numRows);

$sqlQuery = " EXEC ".$getSp."  '".$employeID."' , '".$startDate."' ";
//echo $sqlQuery;

$resultsSet = $agentScoreObj->ExecuteQuery($sqlQuery, '', 'LIVE');
$numRows = mssql_num_rows($resultsSet);
if($numRows>0)
{
	$stackData = $agentScoreObj->bindingInToArray($resultsSet);	
}

/*echo '<pre>';
print_r($stackData);
echo '</pre>';*/

?>
<div>

<div id="dialogTitle" style="background-color:#7AC143; height:30px;">
    <div style="float:left; padding-left:10px; padding-top:5px; font-size:11px; font-weight:bold;">
    	<?php
			if($sectionDetailID==17)
			{
				echo 'SXM Outbound Sales';
			}
			else if($sectionDetailID==19)
			{
				echo 'SPRINT PrePaid Agent scorecard - Rollup KPIs - LOB wise';
			}
			else if($sectionDetailID==20)
			{
				echo 'SPRINT PrePaid Agent scorecard - KPIs for Dealer';
			}
			
			else
			{
				echo 'HRB Section One';	
			}
		?>
        
    </div>
        <div style="margin:0px; padding:0px; float:right;" id="imageIcon">
            <img src="../../Include/images/roundCloseSmall_green_20.jpg" border="0" onClick="return closeMask(); return false;" style="cursor:hand; position:fixed; padding-top:5px;" />
        </div>
</div>

<div style="margin:0px; padding:0px; margin-top:7px; width:auto;" id="midConent">
<?php
if($sectionDetailID==14)
{ ?>
	<table border="1" cellpadding="0" bgcolor="#FFFFFF" cellspacing="0" style="width:100%;" class="report table-autosort table-stripeclass:alternate"> 


<thead>
<tr>
    <th class="ColumnHeader1 locked">Date</th>
    <th class="ColumnHeader1" colspan="2">AHT</th>
    <th class="ColumnHeader1" colspan="2">Transfer %</th>
    <th class="ColumnHeader1">Calls Handled</th>
    <th class="ColumnHeader1" colspan="2">Quality</th>

    
</tr>
</thead>
<tbody>
      
      <?php 
		if(!empty($stackData))
		{
			$g = 0;
			$nHeader = '';
			
			foreach($stackData as $stackData)
			{ 
			
				/*if($requiredDataVal['Date']!='Subtotals')
				{*/
				$header = $stackData['Header'];
				if($nHeader!=$header)
				{
					echo $agentScoreObj->trStryleArray[3];
					echo '<td style="text-align:left;" colspan="9">'.$header.'</td>';
					echo '</tr>';
					$nHeader =  $stackData['Header'];
				}
				if($g!=0 && $g%2==0)
				{
					$g=0;	
				}
						
				echo $agentScoreObj->trStryleArray[$g];	
				if(array_key_exists($stackData['Period'], $weektoDate))
				{
					$firstColmn = $weektoDate[$stackData['Period']];
				}
				else
				{
					$firstColmn = $stackData['Period'];
				}
				
				
					
				?>
							
							<td style="text-align:left;" class="locked"><?php  echo ($firstColmn!=''?$firstColmn:'&nbsp;'); ?></td>
                            <td style="text-align:center;"><?php echo ($stackData['AHT']!=''?number_format($stackData['AHT'],2,'.',''):'&nbsp;');?></td>
                            <td style="text-align:center;"><img src="<?php echo $agentScoreObj->indicatorArray[$g][$stackData['AHTIndicator']];?>" width="20" height="20" /></td>
                            <td style="text-align:center;"><?php echo ($stackData['Transfers']!=''?$stackData['Transfers']:'&nbsp;');?></td>
                            <td style="text-align:center;"><img src="<?php echo $agentScoreObj->indicatorArray[$g][$stackData['TransfersIndicator']];?>" width="20" height="20" /></td>
                            <td style="text-align:center;"><?php echo ($stackData['CallsHandled']!=''?$stackData['CallsHandled']:'&nbsp;');?></td>
                            <td style="text-align:center;"><?php echo ($stackData['Quality']!=''?$stackData['Quality']:'&nbsp;');?></td>
                            <td style="text-align:center;"><img src="<?php echo $agentScoreObj->indicatorArray[$g][$stackData['QualityIndicator']];?>" width="20" height="20" /></td>
                            
                            
							
						</tr>
						
						
				<?php 
			$g++;	
				}
			//} // if not subtotals
		}
		else
		{ ?>
			<tr><td colspan="6" style="text-align:center;">No data found</td></tr>
		<?php }
	?>
      
    
    
</tbody>


</table>
<?php } ?>


<?php
if($sectionDetailID==15)
{ ?>
	<table border="1" cellpadding="0" bgcolor="#FFFFFF" cellspacing="0" style="width:100%;" class="report table-autosort table-stripeclass:alternate"> 


<thead>
<tr>
    <th class="ColumnHeader1 locked">Date</th>
    <th class="ColumnHeader1" colspan="2">Case Creation</th>
    <th class="ColumnHeader1" colspan="2">Case Escalation</th>
    <th class="ColumnHeader1" colspan="2">FCR</th>

    
</tr>
</thead>
<tbody>
      
      <?php 
		if(!empty($stackData))
		{
			$g = 0;
			$nHeader = '';
			
			foreach($stackData as $stackData)
			{ 
			
				/*if($requiredDataVal['Date']!='Subtotals')
				{*/
				$header = $stackData['Header'];
				if($nHeader!=$header)
				{
					echo $agentScoreObj->trStryleArray[3];
					echo '<td style="text-align:left;" colspan="8">'.$header.'</td>';
					echo '</tr>';
					$nHeader =  $stackData['Header'];
				}
				if($g!=0 && $g%2==0)
				{
					$g=0;	
				}
						
				echo $agentScoreObj->trStryleArray[$g];	
				
				if(array_key_exists($stackData['Period'], $weektoDate))
				{
					$firstColmn = $weektoDate[$stackData['Period']];
				}
				else
				{
					$firstColmn = $stackData['Period'];
				}
				
				
					
				?>
							
							<td style="text-align:left;" class="locked"><?php  echo ($firstColmn!=''?$firstColmn:'&nbsp;'); ?></td>
                            <td style="text-align:center;"><?php echo ($stackData['CaseCreation']!=''?$stackData['CaseCreation']:'&nbsp;');?></td>
                            <td style="text-align:center;"><img src="<?php echo $agentScoreObj->indicatorArray[$g][$stackData['CasesCreatedIndicator']];?>" width="20" height="20" /></td>
                            <td style="text-align:center;"><?php echo ($stackData['CaseEscalation']!=''?$stackData['CaseEscalation']:'&nbsp;');?></td>
                            <td style="text-align:center;"><img src="<?php echo $agentScoreObj->indicatorArray[$g][$stackData['EscalatedCasesIndicator']];?>" width="20" height="20" /></td>
                        
                            <td style="text-align:center;"><?php echo ($stackData['FCR']!=''?$stackData['FCR']:'&nbsp;');?></td>
                            <td style="text-align:center;"><img src="<?php echo $agentScoreObj->indicatorArray[$g][$stackData['IRIndicator']];?>" width="20" height="20" /></td>
                            
                            
							
						</tr>
						
						
				<?php 
			$g++;	
				}
			//} // if not subtotals
		}
		else
		{ ?>
			<tr><td colspan="6" style="text-align:center;">No data found</td></tr>
		<?php }
	?>
      
    
    
</tbody>


</table>
<?php } ?>




<?php
if($sectionDetailID==17)
{ ?>
	<table border="1" cellpadding="0" bgcolor="#FFFFFF" cellspacing="0" style="width:100%;" class="report table-autosort table-stripeclass:alternate"> 


<thead>
<tr>
    <th class="ColumnHeader1 locked">Campaign</th>
    <th class="ColumnHeader1" colspan="2">Sales</th>
    <th class="ColumnHeader1" colspan="2">Sales/Hour</th>
    <!--<th class="ColumnHeader1" colspan="2">Sales/Hour <br />Stack Ranking</th>-->
    <th class="ColumnHeader1">SPH goal</th>
    <th class="ColumnHeader1" colspan="2">Conversion %</th>
    <!--<th class="ColumnHeader1" colspan="2">Conversion % <br />Stack Ranking</th>-->
    <th class="ColumnHeader1">Conversion goal</th>
    <th class="ColumnHeader1" colspan="2">Credit Card Take Rate</th>
    <th class="ColumnHeader1" colspan="2">Stack Rank</th>
    <!--<th class="ColumnHeader1" colspan="2">Credit Card Take Rat <br />Stack Ranking</th>-->
    
</tr>
</thead>
<tbody>
      
      <?php 
		if(!empty($stackData))
		{
			$g = 0;
			$nHeader = '';
			
			foreach($stackData as $stackData)
			{ 
				if($g!=0 && $g%2==0)
				{
					$g=0;	
				}
				
				$firstColmn = $stackData['Campaign'];
				
				echo $agentScoreObj->trStryleArray[$g];
				?>
							
							<td style="text-align:left;" class="locked"><?php  echo ($firstColmn!=''?$firstColmn:'&nbsp;'); ?></td>
                            <td style="text-align:center;"><?php echo $stackData['Sales'];?></td>
                            <td style="text-align:center;"><img src="<?php echo $agentScoreObj->indicatorArray[$g][$stackData['SalesIndicator']];?>" width="20" height="20" /></td>
                            <td style="text-align:center;"><?php echo $stackData['SalesPerHour'];?></td>
                            <td style="text-align:center;"><img src="<?php echo $agentScoreObj->indicatorArray[$g][$stackData['SalesPerHourIndicator']];?>" width="20" height="20" /></td>
                            
                           <!-- <td style="text-align:center;"><?php //echo $stackData['SalesPerHourStackRank'];?></td>
                            <td style="text-align:center;"><img src="<?php //echo $agentScoreObj->indicatorArray[$g][$stackData['SalesPerHourStackRankIndicator']];?>" width="20" height="20" /></td>-->
                             <td style="text-align:center;"><?php echo $stackData['SalesPerHourGoal'];?></td>
                            

                            <td style="text-align:center;"><?php echo $stackData['Conversion'];?></td>
                            <td style="text-align:center;"><img src="<?php echo $agentScoreObj->indicatorArray[$g][$stackData['ConversionIndicator']];?>" width="20" height="20" /></td>
                            
                            <!--<td style="text-align:center;"><?php //echo $stackData['ConversionStackRank'];?></td>
                            
                            <td style="text-align:center;"><img src="<?php //echo $agentScoreObj->indicatorArray[$g][$stackData['ConversionStackRankIndicator']];?>" width="20" height="20" /></td>-->
                            <td style="text-align:center;"><?php echo $stackData['ConversionGoal'];?></td>
                            
                            <td style="text-align:center;"><?php echo $stackData['CreditCardTakeRate'];?></td>
                            <td style="text-align:center;"><img src="<?php echo $agentScoreObj->indicatorArray[$g][$stackData['CCTRIndicator']];?>" width="20" height="20" /></td>
                            
                            <!--<td style="text-align:center;"><?php //echo $stackData['CreditCardTakeRateStackRank'];?></td>
                            <td style="text-align:center;"><img src="<?php //echo $agentScoreObj->indicatorArray[$g][$stackData['CCTRStackRankIndicator']];?>" width="20" height="20" /></td>-->
							
                            <td style="text-align:center;"><?php echo $stackData['StackRank'];?></td>
                            <td style="text-align:center;"><img src="<?php echo $agentScoreObj->indicatorArray[$g][$stackData['StackRankIndicator']];?>" width="20" height="20" /></td>
                            
						</tr>
						
						
				<?php 
			$g++;	
				}
			//} // if not subtotals
		}
		else
		{ ?>
			<tr><td colspan="6" style="text-align:center;">No data found</td></tr>
		<?php }
	?>
      
    
    
</tbody>


</table>
<?php } ?>


<?php
if($sectionDetailID==19)
{ ?>
	<table border="1" cellpadding="0" bgcolor="#FFFFFF" cellspacing="0" style="width:100%;" class="report table-autosort table-stripeclass:alternate"> 


<thead>
<tr>
	<!--<th class="ColumnHeader1 locked">LOB</th>-->
	<th class="ColumnHeader1 locked">Date</th>
    <th class="ColumnHeader1" colspan="2">AHT</th>
    <th class="ColumnHeader1" colspan="2">Transfers</th>
    <th class="ColumnHeader1" colspan="2">CSAT Top 2 Box</th>
    <th class="ColumnHeader1" colspan="2">CSAT Bottom Box</th>
    <th class="ColumnHeader1" colspan="2">Adjustments</th>
    <th class="ColumnHeader1" colspan="2">NCP 48</th>
 <!--   <th class="ColumnHeader1" colspan="2">Attendance</th>-->
    <th class="ColumnHeader1" colspan="1">Pay for <br />Performance Score</th>
    
    
</tr>
</thead>
<tbody>
      
      <?php 
		if(!empty($stackData))
		{
			$g = 0;
			$nHeader = '';
			$lobName = '';
			foreach($stackData as $stackData)
			{ 
				$lobName2 = $stackData['FA'];
				
				if($lobName!=$lobName2)
				{
					
					echo '<tr><th class="ColumnHeader1" colspan ="14" style="text-align:left; padding-left:5px;">'.$lobName2.'</th></tr>';
					$lobName = $lobName2;
				}
				
				if($g!=0 && $g%2==0)
				{
					$g=0;	
				}
				
				$firstColmn = $stackData['Period'];
				if($firstColmn=='WTD')
				{	
					$firstColmn = 'Week-to-Date';
				}
				
				if($firstColmn=='MTD')
				{	
					$firstColmn = 'Month-to-Date';
				}
				
				echo $agentScoreObj->trStryleArray[$g];
				?>
							
							
                           <!-- <td style="text-align:left;" class="locked"><?php  //echo $stackData['FA']; ?></td>-->
                            <td style="text-align:left;" class="locked"><?php  echo ($firstColmn!=''?$firstColmn:'&nbsp;'); ?></td>
                            <td style="text-align:center;"><?php echo $stackData['AHT'];?></td>
                            <td style="text-align:center;"><img src="<?php echo $agentScoreObj->indicatorArray[$g][$stackData['AHTIndicator']];?>" width="20" height="20" /></td>
                            <td style="text-align:center;"><?php echo $stackData['TransferPct'];?></td>
                            <td style="text-align:center;"><img src="<?php echo $agentScoreObj->indicatorArray[$g][$stackData['TransferIndicator']];?>" width="20" height="20" /></td>
                            
                            <td style="text-align:center;"><?php echo $stackData['TBPct'];?></td>
                            <td style="text-align:center;"><img src="<?php echo $agentScoreObj->indicatorArray[$g][$stackData['TBIndicator']];?>" width="20" height="20" /></td>
                            
                             <td style="text-align:center;"><?php echo $stackData['BBPct'];?></td>
                            <td style="text-align:center;"><img src="<?php echo $agentScoreObj->indicatorArray[$g][$stackData['BBIndicator']];?>" width="20" height="20" /></td>
                            
                            <td style="text-align:center;"><?php echo $stackData['AdjustmentPct'];?></td>
                            <td style="text-align:center;"><img src="<?php echo $agentScoreObj->indicatorArray[$g][$stackData['AjdIndicator']];?>" width="20" height="20" /></td>
                            
                             <td style="text-align:center;"><?php echo $stackData['NCP48Pct'];?></td>
                            <td style="text-align:center;"><img src="<?php echo $agentScoreObj->indicatorArray[$g][$stackData['NCP48Indicator']];?>" width="20" height="20" /></td>
                            <td style="text-align:center;"><?php echo $stackData['PDP'];?></td>
                            
                            
                           
                            
						</tr>
						
						
				<?php 
			if($lobName!=$lobName2)
			{
				$g++;	
			}
				}
			//} // if not subtotals
		}
		else
		{ ?>
			<tr><td colspan="14" style="text-align:center;">No data found</td></tr>
		<?php }
	?>
      
    
    
</tbody>


</table>
<?php } ?>


<?php
if($sectionDetailID==20)
{ ?>
	<table border="1" cellpadding="0" bgcolor="#FFFFFF" cellspacing="0" style="width:100%;" class="report table-autosort table-stripeclass:alternate"> 


<thead>
<tr>
    <th class="ColumnHeader1 locked">Period</th>
    <th class="ColumnHeader1" colspan="2">AHT</th>
    <th class="ColumnHeader1" colspan="2">Transfers</th>
    <th class="ColumnHeader1" colspan="2">Quality</th>
    <th class="ColumnHeader1" colspan="2">Adjustments</th>
 <!--   <th class="ColumnHeader1" colspan="2">Attendance</th>-->
    <th class="ColumnHeader1" colspan="1">Pay for <br />Performance Score</th>
    
    
</tr>
</thead>
<tbody>
      
      <?php 
		if(!empty($stackData))
		{
			$g = 0;
			$nHeader = '';
			
			foreach($stackData as $stackData)
			{ 
				if($g!=0 && $g%2==0)
				{
					$g=0;	
				}
				
				$firstColmn = $stackData['Period'];
				if($firstColmn=='WTD')
				{	
					$firstColmn = 'Week-to-Date';
				}
				
				if($firstColmn=='MTD')
				{	
					$firstColmn = 'Month-to-Date';
				}
				echo $agentScoreObj->trStryleArray[$g];
				?>
							
							<td style="text-align:left;" class="locked"><?php  echo ($firstColmn!=''?$firstColmn:'&nbsp;'); ?></td>
                            <td style="text-align:center;"><?php echo $stackData['AHT'];?></td>
                            <td style="text-align:center;"><img src="<?php echo $agentScoreObj->indicatorArray[$g][$stackData['AHTIndicator']];?>" width="20" height="20" /></td>
                            <td style="text-align:center;"><?php echo $stackData['TransferPct'];?></td>
                            <td style="text-align:center;"><img src="<?php echo $agentScoreObj->indicatorArray[$g][$stackData['TransferIndicator']];?>" width="20" height="20" /></td>
                            
                            <td style="text-align:center;"><?php echo $stackData['QualityPct'];?></td>
                            <td style="text-align:center;"><img src="<?php echo $agentScoreObj->indicatorArray[$g][$stackData['QualityIndicator']];?>" width="20" height="20" /></td>
                            
                            <td style="text-align:center;"><?php echo $stackData['AdjustmentPct'];?></td>
                            <td style="text-align:center;"><img src="<?php echo $agentScoreObj->indicatorArray[$g][$stackData['AjdIndicator']];?>" width="20" height="20" /></td>
                            
                           
                           
                            <td style="text-align:center;"><?php echo $stackData['PDP'];?></td>
                            
                            
                           
                            
						</tr>
						
						
				<?php 
			$g++;	
				}
			//} // if not subtotals
		}
		else
		{ ?>
			<tr><td colspan="6" style="text-align:center;">No data found</td></tr>
		<?php }
	?>
      
    
    
</tbody>


</table>
<?php } ?>



</div>
</div>
<?php $agentScoreObj->closeConn();  ?>