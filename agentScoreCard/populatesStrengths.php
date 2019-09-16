<?
include_once($_SERVER['DOCUMENT_ROOT']."/agentScoreCard/includes/agentScoreCard.class.inc.php");

$agentScoreObj6 = new agentScoreCard();

$sessID = $_REQUEST[sessID];
$sessionCallID = $_REQUEST[sessionCallID];

$sql = " EXEC Rnet.dbo.[rnet_spGetKPIScoreCardBehavious] '$sessID', '$sessionCallID' ";		
$result2 = $agentScoreObj6->ExecuteQuery($sql);
$num_rows = mssql_num_rows($result2);
$agentScoreObj6->closeConn();

if($num_rows >= 1)
{
?>
<table border="1" align="center" cellpadding="0" cellspacing="0" class="report" style="border-collapse:collapse;">
<tr class="ColumnHeaderInner">
<td style="text-align:center;">KPI</td><td style="text-align:center;">Behavior</td><td style="text-align:center;">Primary behavior on this call</td><td style="text-align:center;">Remove</td>
</tr>
<?

	$i=0;
	while($row=mssql_fetch_assoc($result2)) 
    {
		$kpi = $row[KPI];
		$behavior = $row[behavior];
		$KPIID = $row[KPIID];
		$behaviorID = $row[behaviorID];
		$type = $row[type];
		$isPrimary =  $row[isPrimary];
		if($i % 2) 
		{ 
			$color =  'bgcolor="#DEE7D1"'; 
		} 
		else 
		{ 
			$color =  'bgcolor="#EFF3EA"'; 
		}
		
		?>
        <tr <?php echo $color;?>>
        <td style="text-align:left;"><? echo $kpi;?></td>
        <td style="text-align:left;"><? echo $behavior;?></td>
        <td style="text-align:center;">
        
        <input type="radio" name="str" <? if($isPrimary == 'Y') {?> checked="checked" <? }?>  onclick="return setPrimary('<?=$type;?>','<?=$KPIID;?>','<?=$behaviorID;?>','','<?=$sessID;?>','<?=$sessionCallID;?>');"/>
        
        </td>
        <td style="text-align:center;"><a href="#"><img src="includes/images/AgenetScoreCross.png"  id="btnDelStr" name="btnDelStr" style="border:0; height:15px; width:15px" onclick="return delStrenghts('<?=$type;?>','<?=$KPIID;?>','<?=$behaviorID;?>','<?=$sessID;?>','<?=$sessionCallID;?>');" /></a></td>
        
        <?
		print '</tr>';
		$i++;
		
	}
	
	
	
?>

</table>
<?php
}
?>