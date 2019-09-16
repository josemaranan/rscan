<?php
include_once($_SERVER['DOCUMENT_ROOT']."/agentScoreCard/includes/agentScoreCard.class.inc.php");
$agentScoreObj = new agentScoreCard();
include_once($_SERVER['DOCUMENT_ROOT']."/agentScoreCard/includes/postVariables.php");

/*echo 'Start Date'.$startDate.'<br>';
echo 'End Date'.$endDate.'<br>';
echo 'measure'.$measure.'<br>';
echo 'period'.$period.'<br>';
echo 'employeID'.$employeID.'<br>';
exit;*/

if(empty($requiredData))
{ ?>
	<div style="position:relative; float:right; margin-right:10px;">
    <a href="#" onclick="return closeMask(); return false;">Close</a>
    </div>
	<div style="margin:150px 150px 150px 500px; font-size:20px; font-weight:bold;">No data available</div>
<?php exit(); }
//echo $containerWidth;
$drilldownMappings = $agentScoreObj->getdrillDownMappingForReport($sectionDetailID , $reportType);

unset($drilldownMappingsCalculatedFiled);
unset($drilldownMappingsCount);
unset($requiredDataCount);
$drilldownMappingsCount = count($drilldownMappings); // inner
$requiredDataCount = count($requiredData); // outer 


for($z=0; $z<$drilldownMappingsCount; $z++)
{
	if($drilldownMappings[$z]['isCalculatedField']=='Y')
	{
		$drilldownMappingsCalculatedFiled[] = $drilldownMappings[$z];
	}
	
	$convertDrillDowmMap[$drilldownMappings[$z]['ResultsSetMapping']] = $drilldownMappings[$z];

}
		

for($i=0; $i<$drilldownMappingsCount; $i++)
{
	
	for($k=0; $k<$requiredDataCount; $k++)
	{
		if($requiredData[$k]['LOB']==$lob && $requiredData[$k]['Date']!='Subtotals')
		{
			if($drilldownMappings[$i]['dataType']=='decimal')
			{
				$allData[$drilldownMappings[$i]['ResultsSetMapping']][] = $agentScoreObj->numberFormatReturn($requiredData[$k][$drilldownMappings[$i]['ResultsSetMapping']]);
			}
			else
			{
				$allData[$drilldownMappings[$i]['ResultsSetMapping']][] = $requiredData[$k][$drilldownMappings[$i]['ResultsSetMapping']];
			}
		
		}
		
		
		}

	
}

$countOfAllData = count($allData['Date']);

$elementWidth = 80;

$containerWidth = $countOfAllData*$elementWidth;
if($width>$containerWidth)
{
	$containerWidth = $width;
}

/*
echo '<pre>';
print_r($convertDrillDowmMap);
echo '</pre>';

echo '<pre>';
print_r($allData);
echo '</pre>';*/



foreach($allData as $allDataKey=>$allDataVal)
{
	if($allDataKey!='LOB')
	{
		/*if($allDataKey=='Date')
		{
			$JallData[$allDataKey]=	$agentScoreObj->JsonConvertion($allDataVal , 'string');
		}
		else
		{
			$JallData[$allDataKey]=	$agentScoreObj->JsonConvertion($allDataVal , 'number');	
		}*/
		
		switch($convertDrillDowmMap[$allDataKey]['dataType'])
		{
			case 'int':
			case 'decimal':
				$JallData[$allDataKey]=	$agentScoreObj->JsonConvertion($allDataVal , 'number');	
			break;
			
			case 'string':	
			default:
				$JallData[$allDataKey]=	$agentScoreObj->JsonConvertion($allDataVal , 'string');
			break;
			
		}
	}
	

}




/*echo '<pre>';
print_r($JallData);
echo '</pre>';
//exit;
*/
if($JallData['Date']=='[""]')
{
	$JallData['Date'] = '["Total"]';	
}
	
?>
	
		<script type="text/javascript">
		//var FixedWidth = 950;
		var chart;$(document).ready(function(){chart=new Highcharts.Chart({chart:{renderTo:'container',marginRight:0, marginLeft:5},credits:{enabled:false},title:{text:''},xAxis:{categories: <?php echo $JallData['Date']; ?>},
		yAxis:[
			   	{title:{text:null},
				labels:{formatter:function()
				{return Highcharts.numberFormat(this.value,0)}},
				showFirstLabel:false},
				{gridLineWidth:0,
				opposite:true,title:{text:null},
				labels:{
					formatter:function(){return Highcharts.numberFormat(this.value,0)}},min:0}],legend:{verticalAlign:'top',marginTop:10},tooltip:{formatter:function(){return'<b>'+this.x+'</b><br/>'+this.series.name+': '+this.y+(this.series.name=='FCR' || this.series.name=='FCR Goal'?'%':'')}},plotOptions:{column:{stacking:'normal'}},series:[
					<?php
						unset($dPstring);
						foreach($JallData as $JallDataKey=>$JallDataVal)
						{
							if($JallDataKey!='Date')
							{
								$dPstring .= '{';
								$dPstring .= 'name:"'.$convertDrillDowmMap[$JallDataKey]['ResultsSetMapping'].'",';
								$dPstring .= 'type:"'.$convertDrillDowmMap[$JallDataKey]['graphType'].'",';
								$dPstring .= 'yAxis:'.$convertDrillDowmMap[$JallDataKey]['yAixs'].',';
								$dPstring .= 'data:'.$JallDataVal.'';
								$dPstring .= '},';
							}
						}
						$dPstring = substr($dPstring,0,-1);
						echo $dPstring;
					?>
					]})});

				
		</script>
		
        
	<!-- 3. Add the container -->

<div id="dialogTitle" style="background-color:#7AC143; height:30px; position:fixed;">
    <div style="float:left; padding-left:10px; padding-top:5px; font-size:11px; font-weight:bold;">
    	<?php echo str_replace(array('<br>','<br />', '<br/>'),'',$title);?>
    </div>
        <div style="margin:0px; padding:0px; float:right;" id="imageIcon">
        
        <img src="../../Include/images/backNew_green_18.jpg" border="0" onClick="return populateDetails('<?php echo $period; ?>','<?php echo $startDate;?>', '<?php echo $employeID;?>', '<?php echo $sectionDetailID;?>', '<?php echo $height;?>', '<?php echo $width;?>', 'report','', '<?php echo $isBar;?>', '<?php echo $isPie;?>'); return false;" style="cursor:hand; position:fixed; padding-top:6px;" title="back"/>
        
                    <img src="../../Include/images/roundCloseSmall_green_20.jpg" border="0" onClick="return closeMask(); return false;" style="cursor:hand; position:fixed; padding-top:5px; margin-left:30px;" title="close" />
        </div>
</div>


<br clear="all" />
<br />
<div style="text-align:left; color:#0066CC; font-weight:bold; letter-spacing:1px; word-spacing:3px; padding-left:10px; padding-top:10px;">
<?php echo str_replace(array('<br>','<br />', '<br/>'),'',$subTitle);?></div>

<div style="margin:0px; padding:0px; width:<?php echo $width;?>px;" id="midConent">
	<div id="container" style="width:<?php echo $containerWidth;?>px;"></div>
</div>
<?php $agentScoreObj->closeConn(); ?>		
		
		
		