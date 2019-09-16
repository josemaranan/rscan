<?php
//ini_set('display_errors','1');
include_once($_SERVER['DOCUMENT_ROOT']."/agentScoreCard/includes/agentScoreCard.class.inc.php");
$agentScoreObj = new agentScoreCard();
$width = '900';
$height = '500';
$title = '';
include_once($_SERVER['DOCUMENT_ROOT']."/agentScoreCard/includes/postVariables.php");
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
    	HRB Secton Two
    </div>
        <div style="margin:0px; padding:0px; float:right;" id="imageIcon">
            <img src="../../Include/images/roundCloseSmall_green_20.jpg" border="0" onClick="return closeMask(); return false;" style="cursor:hand; position:fixed; padding-top:5px;" />
        </div>
</div>

<div style="margin:0px; padding:0px; margin-top:7px; width:auto;" id="midConent">

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
						
				$firstColmn = $stackData['Period'];
				echo $agentScoreObj->trStryleArray[$g];	
				
				
					
				?>
							
							<td style="text-align:left;" class="locked"><?php  echo $firstColmn; ?></td>
                            <td style="text-align:center;"><?php echo $stackData['AHT'];?></td>
                            <td style="text-align:center;"><img src="<?php echo $agentScoreObj->indicatorArray[$g][$stackData['AHTIndicator']];?>" width="20" height="20" /></td>
                            <td style="text-align:center;"><?php echo $stackData['Transfers'];?></td>
                            <td style="text-align:center;"><img src="<?php echo $agentScoreObj->indicatorArray[$g][$stackData['TransfersIndicator']];?>" width="20" height="20" /></td>
                            <td style="text-align:center;"><?php echo $stackData['CallsHandled'];?></td>
                            <td style="text-align:center;"><?php echo $stackData['Quality'];?></td>
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

</div>
</div>
<?php $agentScoreObj->closeConn();  ?>