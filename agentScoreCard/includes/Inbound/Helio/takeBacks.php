<?php
//ini_set('display_errors','1');
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

if($measure=='AHT')
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


<table width="98%" border="1" cellpadding="0" bgcolor="#FFFFFF" cellspacing="0" class="report table-autosort table-stripeclass:alternate" id="callsHandledTable"> 

<thead>
<tr>
    <th class="ColumnHeader1">LOB</th>
    <?php 
		if($period=='WTD' || $period=='MTD')
		{
	?>
    	<th class="ColumnHeader1">&nbsp;</th>
    
    <?php } ?>
        <th class="ColumnHeader1">Date</th>
        <th class="ColumnHeader1">GA</th>
        
    <!--<th class="ColumnHeader1">Other Time</th>-->

    
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
				
					if($requiredDataVal['initialDisplay']==1 && $requiredDataVal['LOB']=='Totals')
					{
						$firstColmn =  $requiredDataVal['LOB'];
						echo $trStryleArray['4'];	
						echo '<td style="text-align:left; padding-left:20px;" class="locked">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$firstColmn.'</td>';
					}
				
					else if($requiredDataVal['initialDisplay']==1 && $requiredDataVal['LOB']!='Totals')
					{
								
						$firstColmn =  $requiredDataVal['LOB'].' - Subtotals';
						echo $trStryleArray['3'];
						unset($tableId);
						$tableId = str_replace(' ', '',$requiredDataVal['LOB']);
                        echo '<td style="text-align:left; padding-left:20px; background-image:url(includes/images/plus.gif); background-repeat:no-repeat; background-position:left;" class="locked" id="'.$tableId.'" onclick="return toggleThis(this.id); return false;" title="expand">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$firstColmn.'</td>';
					}
					else
					{
						$firstColmn =  $requiredDataVal['LOB'];
						if($g!=0 && $g%2==0)
						{
							$g=0;	
						}
					
						echo $trStryleArray[$g];
						$g++;
						echo '<td style="text-align:left; padding-left:20px;" class="locked">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$firstColmn.'</td>';	
					}
					
					
				?>
											
                            
                            <?php 
							if($period=='WTD' || $period=='MTD')
							{?>
                                <td style="text-align:center;">
                                <?php
                                if($requiredDataVal['LOB']=='Totals')
                                { ?>
                                         <img src="includes/images/pie_chart.png" border="0" style="width:40px; height:35px; cursor:hand;" title="Click here to view chart" onclick="return populateDetails('<?php echo $type;?>', '<?php echo $measure;?>' , '<?php echo $period;?>' , '<?php echo $employeID;?>', '<?php echo $startDate;?>' , '<?php echo $endDate;?>' , 'Helio/dataPie' , '' ); return false;" />
                                <?php }
                                else if ($requiredDataVal['Date']=='Subtotals')
                                { ?>
                                
                                <img src="includes/images/bar_graph.png" border="0" style="width:20px; height:20px; cursor:hand;" title="Click here to view graph" onclick="return populateDetails('<?php echo $type;?>', '<?php echo $measure;?>' , '<?php echo $period;?>' , '<?php echo $employeID;?>', '<?php echo $startDate;?>' , '<?php echo $endDate;?>' , 'Helio/data', '<?php echo $requiredDataVal['LOB'];?>' ); return false;" />
                                
                               
                                    
                                <?php } 
								else
								{
									echo '&nbsp;';	
								}
								?>
                                </td>
                            <?php } ?>
                            
                            <td style="text-align:center;"><?php 
								if(!empty($requiredDataVal['Date']))
								{
									echo $requiredDataVal['Date'];
								}
								else
								{
									echo '&nbsp;';	
								}?></td>
                                
                                
							<td style="text-align:center;"><?php 
								echo $requiredDataVal['GA'];?></td>
							
							
							
							<!--<td style="text-align:center;"><?php //$agentScoreObj->numberFormat($requiredDataVal['Other Time']);?></td>-->
						</tr>
						
						
				<?php 
				
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

</div>
