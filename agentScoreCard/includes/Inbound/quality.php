<?php
include_once($_SERVER['DOCUMENT_ROOT']."/agentScoreCard/includes/agentScoreCard.class.inc.php");
$agentScoreObj = new agentScoreCard();
include_once($_SERVER['DOCUMENT_ROOT']."/agentScoreCard/includes/postVariables.php");


if($period=='WTD' || $period=='MTD')
{
	$trStryleArray = array('0'=>'<tr style="height:20px; background-color:#D0D8E8;" class="hidden">', '1'=>'<tr style="height:20px; background-color:#E9EDF4;" class="hidden">', '3'=>'<tr style="height:20px; background-color:#999999;" class="main" >', '4'=>'<tr style="height:20px; background-color:#E9EDF4;">');
}
else
{
	$trStryleArray = array('0'=>'<tr style="height:20px; background-color:#D0D8E8;">', '1'=>'<tr style="height:20px; background-color:#E9EDF4;">', '3'=>'<tr style="height:20px; background-color:#999999;" class="main" >', '4'=>'<tr style="height:20px; background-color:#E9EDF4;">');
}
/*echo 'Start Date'.$startDate.'<br>';
echo 'End Date'.$endDate.'<br>';
echo 'measure'.$measure.'<br>';
echo 'period'.$period.'<br>';
echo 'employeID'.$employeID.'<br>';*/

if($measure=='CSAT')
{
	$iconf = 'pie_chart_aht.png';	
}
else
{
	$iconf = 'pie_chart_calls_handled.png';	
}
?>


<div id = "dialogTitle" style="background:#7AC143; color:#FFF; font-size:11px; font-weight:bold; padding:5px;" class="outer"><?php echo $title;?></div>

<?php include_once($_SERVER['DOCUMENT_ROOT']."/agentScoreCard/includes/dateAndClose.php"); ?>


<table width="98%" border="1" cellpadding="0" bgcolor="#FFFFFF" cellspacing="0" class="report table-autosort table-stripeclass:alternate"> 

<thead>
<tr>
    <th class="ColumnHeader1 locked">LOB Name</th>
    <th class="ColumnHeader1">Quality Goal</th>
    <th class="ColumnHeader1">Date</th>
    <?php 
		if($period=='WTD' || $period=='MTD')
		{
	?>
    	<th class="ColumnHeader1">&nbsp;</th>
    
    <?php } ?>
    <th class="ColumnHeader1">Number of <br />Evaluations</th>
    <th class="ColumnHeader1">Quality</th>
    
    <th class="ColumnHeader1">Process and <br />Accuracy</th>
    <th class="ColumnHeader1">Caller <br />Experience</th>
    <th class="ColumnHeader1">FCR</th>
    <th class="ColumnHeader1">Compliance</th>
    
    
</tr>
</thead>
<tbody>
      
    <?php 
		if(!empty($requiredData))
		{
			$g = 0;
			
			foreach($requiredData as $requiredDataVal)
			{ 
			
				/*if($requiredDataVal['Date']!='Subtotals')
				{*/
				
					if($requiredDataVal['Date']!='Subtotals' || empty($requiredDataVal['Date']))
					{
						$firstColmn =  $requiredDataVal['LOB'];
						if($requiredDataVal['LOB'] != "Totals"){
						if($g!=0 && $g%2==0)
						{
							$g=0;	
						}
					
						//echo $agentScoreObj->trStryleArray[$g];
						echo $trStryleArray[$g];
						}else{
						echo $trStryleArray['4'];
						}
						$g++;
						echo '<td style="text-align:left; padding-left:20px;" class="locked">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$firstColmn.'</td>';	
					}
					else
					{
						$firstColmn =  $requiredDataVal['LOB'].' - Subtotals';
						echo $trStryleArray['3'];
						unset($tableId);
						$tableId = str_replace(' ', '',$requiredDataVal['LOB']);
                        echo '<td style="text-align:left; padding-left:20px; background-image:url(includes/images/plus.gif); background-repeat:no-repeat; background-position:left;" class="locked" id="'.$tableId.'" onclick="return toggleThis(this.id); return false;" title="expand">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$firstColmn.'</td>';
							
					}
					
					
				?>
                            <td style="text-align:center;">
							<?php 
									if(!empty($requiredDataVal['Quality Goal']))
									{
										echo $agentScoreObj->numberFormat($requiredDataVal['Quality Goal']);
										
									}
									else
									{
										echo '&nbsp;';	
									}
									?>
                            </td>
                            <td style="text-align:center;">
							<?php 
									if(!empty($requiredDataVal['Date']))
									{
										echo $requiredDataVal['Date'];
									}
									else
									{
										echo '&nbsp;';	
									}
									?>
                            </td>
                            <?php 
							if($period=='WTD' || $period=='MTD')
							{?>
                                <td style="text-align:center;">
                                <?php
                                if($requiredDataVal['LOB']=='Totals')
                                { ?>
                                	&nbsp;
                                        <img src="includes/images/bar_graph.png" border="0" style="width:20px; height:20px; cursor:hand;" title="Click here to view graph" onclick="return populateDetails('<?php echo $type;?>', '<?php echo $measure;?>' , '<?php echo $period;?>' , '<?php echo $employeID;?>', '<?php echo $startDate;?>' , '<?php echo $endDate;?>' , 'dataDouble', '' ); return false;" />
                                <?php }
                                else if ($requiredDataVal['Date']=='Subtotals')
                                { ?>
                                
                                <img src="includes/images/bar_graph.png" border="0" style="width:20px; height:20px; cursor:hand;" title="Click here to view graph" onclick="return populateDetails('<?php echo $type;?>', '<?php echo $measure;?>' , '<?php echo $period;?>' , '<?php echo $employeID;?>', '<?php echo $startDate;?>' , '<?php echo $endDate;?>' , 'data', '<?php echo $requiredDataVal['LOB'];?>' ); return false;" />
                                
                               
                                    
                                <?php } 
								
									else
									{
										echo '&nbsp;';	
									}
								?>
                                </td>
                            <?php } ?>
                            <td style="text-align:center;">
							<?php 
								if(!empty($requiredDataVal['Number of Evaluations']))
								{
									echo $requiredDataVal['Number of Evaluations'];
								}
								else
								{
									echo '&nbsp;';	
								}
								
							?>
                            
                            </td>
                            <td style="text-align:center;">
							<?php 
								if(!empty($requiredDataVal['Quality']))
								{
									echo $agentScoreObj->numberFormat($requiredDataVal['Quality']);
								}
								else
								{
									echo '&nbsp;'; 	
								}
							?>
                            </td>
                            
                             
                             
                            <td style="text-align:center;"><?php 
							if(!empty($requiredDataVal['Process and Accuracy']))
							{
								echo $agentScoreObj->numberFormat($requiredDataVal['Process and Accuracy']);
							}
							else
							{
								echo '&nbsp;'; 
							}
								?></td>
                            <td style="text-align:center;"><?php 
							if(!empty($requiredDataVal['Caller Experience']))
							{
							echo $agentScoreObj->numberFormat($requiredDataVal['Caller Experience']);
							}
							else
							{
								echo '&nbsp;';
							}
							?></td>
                            <td style="text-align:center;"><?php 
							
							if(!empty($requiredDataVal['First-Call Resolution']))
							{
								echo $agentScoreObj->numberFormat($requiredDataVal['First-Call Resolution']);
							}
							else
							{
								echo '&nbsp;';	
							}
								?></td>
                            <td style="text-align:center;"><?php 
							if(!empty($requiredDataVal['Compliance']))
							{
								echo $agentScoreObj->numberFormat($requiredDataVal['Compliance']);
							}
							else
							{
								echo '&nbsp;';	
							}	
								?></td>
							
						</tr>
						
						
				<?php 
				
				}
			//} // if not subtotals
		}
		else
		{ ?>
			<tr><td colspan="7" style="text-align:center;">No data found</td></tr>
		<?php }
	?>
    
</tbody>


</table>

</div>