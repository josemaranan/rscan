<?php
include_once($_SERVER['DOCUMENT_ROOT']."/agentScoreCard/includes/agentScoreCard.class.inc.php");
$agentScoreObj = new agentScoreCard();
$width = '900';
$height = '500';
$title = '';
include_once($_SERVER['DOCUMENT_ROOT']."/agentScoreCard/includes/postVariables.php");

?>
<div id = "dialogTitle" style="background:#1266B1; color:#FFF; font-size:11px; font-weight:bold; padding:5px;" class="outer"><?php echo $title;?></div>

<?php include_once($_SERVER['DOCUMENT_ROOT']."/agentScoreCard/includes/dateAndClose.php"); ?>

<table width="98%" border="1" cellpadding="0" bgcolor="#FFFFFF" cellspacing="0" class="report table-autosort table-stripeclass:alternate"> 

<thead>
<tr>
    <th class="ColumnHeader1 locked">Script</th>
    <th class="ColumnHeader1">Date</th>
    <th class="ColumnHeader1">Stack Rank</th>
    <th class="ColumnHeader1">Contacts</th>
    <th class="ColumnHeader1">Contacts <br />per Hour</th>
    <th class="ColumnHeader1">Completes</th>
    <th class="ColumnHeader1">Completes <br />per Hour</th>
    <th class="ColumnHeader1">AHT</th>
    <th class="ColumnHeader1">Sales</th>
    <th class="ColumnHeader1">Refusals</th>
    
    <th class="ColumnHeader1">Conversion</th>
    <th class="ColumnHeader1">Sales per Hour</th>
    <!-- <th class="ColumnHeader1">Quality</th> -->

    
</tr>
</thead>
<tbody>
      
      <?php 
		if(!empty($requiredData))
		{
			$g = 0;
			
			foreach($requiredData as $requiredDataVal)
			{ 
			
				//if($requiredDataVal['Date']!='Subtotals')
				//{
					if($requiredDataVal['Date']!='Subtotals')
					{
						$firstColmn =  $requiredDataVal['Script Name'];
						if($g!=0 && $g%2==0)
						{
							$g=0;	
						}
					
						echo $agentScoreObj->trStryleArray[$g];
						$g++;
					}
					else
					{
						$firstColmn =  $requiredDataVal['Script Name'].' - Subtotals';
						echo $agentScoreObj->trStryleArray['3'];
							
					}
				?>
						
							<td style="text-align:left;" class="locked"><?php echo $firstColmn;?></td>
                            <td style="text-align:center;"><?php echo $requiredDataVal['Date'];?></td>
							<td style="text-align:center;"><?php echo $requiredDataVal['Stack Rank'];?></td>
							<td style="text-align:center;"><?php echo $requiredDataVal['Contacts'];?></td>
							<td style="text-align:center;"><?php $agentScoreObj->numberFormat($requiredDataVal['Contacts per Hour']);?></td>
							<td style="text-align:center;"><?php echo $requiredDataVal['Completes'];?></td>
							<td style="text-align:center;"><?php $agentScoreObj->numberFormat($requiredDataVal['Completes per Hour']);?></td>
							<td style="text-align:center;"><?php $agentScoreObj->numberFormat($requiredDataVal['AHT']);?></td>
                            <td style="text-align:center;"><?php echo $requiredDataVal['Sales'];?></td>
                            <td style="text-align:center;"><?php echo $requiredDataVal['Refusals'];?></td>
                            
                            <td style="text-align:center;"><?php $agentScoreObj->numberFormat($requiredDataVal['Conversion']);?></td>
                            <td style="text-align:center;"><?php $agentScoreObj->numberFormat($requiredDataVal['Sales per Hour']);?></td>
                   <!--<td style="text-align:center;"><?php //$agentScoreObj->numberFormat($requiredDataVal['Quality']);?></td> -->
						</tr>
						
						
				<?php 
				
				}
			//} // if not subtotals
		}
		else
		{ ?>
			<tr><td colspan="14">No data found</td></tr>
		<?php }
	?>
    
</tbody>


</table>

</div>