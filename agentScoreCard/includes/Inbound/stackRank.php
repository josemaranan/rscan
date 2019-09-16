<?php
//ini_set('display_errors','1');
include_once($_SERVER['DOCUMENT_ROOT']."/agentScoreCard/includes/agentScoreCard.class.inc.php");
$agentScoreObj = new agentScoreCard();
$width = '900';
$height = '500';
$title = '';
include_once($_SERVER['DOCUMENT_ROOT']."/agentScoreCard/includes/postVariables.php");

unset($sqlQuery);
unset($resultsSet);
unset($numRows);
unset($stackData);

$parameters = $agentScoreObj->getParameterTitle($period, $measure , $type);

$title = $parameters[0];	
$periodParameter = $parameters[1];

$sqlQuery = "  EXEC WellcareCommon.dbo. ".$employeID.", '".$startDate."', '".$periodParameter."' ";
				
//echo $sqlQuery;
				
$resultsSet = $agentScoreObj->ExecuteQuery($sqlQuery, '', 'LIVE');
$numRows = mssql_num_rows($resultsSet);
if($numRows>0)
{
	$requiredData = $agentScoreObj->bindingInToArray($resultsSet);	
}


?>


<div id = "dialogTitle" style="background:#7AC143; color:#FFF; font-size:11px; font-weight:bold; padding:5px;" class="outer"><?php echo $title;?></div>

<?php include_once($_SERVER['DOCUMENT_ROOT']."/agentScoreCard/includes/dateAndClose.php"); ?>


<table width="96%" border="1" cellpadding="0" bgcolor="#FFFFFF" cellspacing="0" class="report table-autosort table-stripeclass:alternate"> 

<thead>
<tr>
    <th class="ColumnHeader1 locked">LOB Name</th
     ><th class="ColumnHeader1">Date</th>
    <th class="ColumnHeader1">My Stack <br />Rank</th>
    <th class="ColumnHeader1">My Team's <br />Stack Rank</th>
    <th class="ColumnHeader1">My Site's <br />Stack Rank</th>
    <th class="ColumnHeader1">Cumulative LOB <br />Stack Rank</th>

    
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
				
					if($requiredDataVal['Date']!='Subtotals')
					{
						$firstColmn =  $requiredDataVal['LOB'];
						if($g!=0 && $g%2==0)
						{
							$g=0;	
						}
					
						echo $agentScoreObj->trStryleArray[$g];
						$g++;
					}
					else
					{
						$firstColmn =  $requiredDataVal['LOB'].' - Subtotals';
						echo $agentScoreObj->trStryleArray['3'];
							
					}
					
					
				?>
						
							<td style="text-align:left;" class="locked"><?php  echo $firstColmn; ?></td>
                            <td style="text-align:center;"><?php echo $requiredDataVal['Date'];?></td>
                            <td style="text-align:center;"><?php echo $requiredDataVal['Date'];?></td>
                            <td style="text-align:center;"><?php echo $requiredDataVal['Date'];?></td>
                            <td style="text-align:center;"><?php echo $requiredDataVal['Date'];?></td>
                            <td style="text-align:center;"><?php echo $requiredDataVal['Date'];?></td>
                            
							
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