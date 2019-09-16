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

/*echo '<pre>';
print_r($drilldownMappings);
echo '</pre>';

echo '<pre>';
print_r($requiredData);
echo '</pre>';*/


/*exit;*/

//echo 'vvvvvvvvvvvvv'.$measure;

for($z=0; $z<$drilldownMappingsCount; $z++)
{
	if($drilldownMappings[$z]['isCalculatedField']=='Y')
	{
		$drilldownMappingsCalculatedFiled[] = $drilldownMappings[$z];
	}
	$convertDrillDowmMap[$drilldownMappings[$z]['ResultsSetMapping']] = $drilldownMappings[$z];
}

$isDisplayPer = true;
if($measure=='Calls Handled')
{
	$metricTail = 'Calls Handled';
	$isDisplayPer = false;
}

if($measure=='AHT')
{
	$metricTail = 'hours';
	$isDisplayPer = false;
}

/*else
{
	$metricTail = 'hours';	
}
*/
if($measure=='Calls Handled' &&  $_SESSION[agentScoreClient]=='Wellcare') // This if condition only for wellcare client.
{
	
	
		
		for($k=0; $k<$requiredDataCount; $k++)
		{
				if($requiredData[$k]['initialDisplay']=='1' && $requiredData[$k]['LOB']!='Totals')
				{
					
					$lobLevel[][$requiredData[$k]['LOB']] = $requiredData[$k]['Calls Handled'];
				}
			
			
		}
		
	
	
	/*echo '<pre>';
	print_r($lobLevel);
	echo '</pre>';*/
	//exit;
	
	unset($JlobLevel);
	foreach($lobLevel as $allDataKey=>$allDataVal)
	{
		if(!empty($allDataVal))
		{
			$JallData[] = $agentScoreObj->JsonKeyValueConvertion($allDataVal, 'number');
		}
	}
	/*echo 'sssssssssssssss';
	echo '<pre>';
	print_r($JallData);
	echo '</pre>';*/
	
	
	
}
else
{
	for($i=0; $i<$drilldownMappingsCount; $i++)
	{
		
		for($k=0; $k<$requiredDataCount; $k++)
		{
			if($requiredData[$k]['LOB']=='Totals')
			{
				if($drilldownMappings[$i]['dataType']=='decimal')
				{
					$allData[][$drilldownMappings[$i]['ResultsSetMapping']] = $agentScoreObj->numberFormatReturn($requiredData[$k][$drilldownMappings[$i]['ResultsSetMapping']]);
				}
				else
				{
					$allData[][$drilldownMappings[$i]['ResultsSetMapping']] = $requiredData[$k][$drilldownMappings[$i]['ResultsSetMapping']];
				}
			}
			
			
			}
		
	}
		
		/*echo '<pre>';
		print_r($allData);
		echo '</pre>';*/
		
		
	foreach($allData as $allDataKey=>$allDataVal)
	{
		if($allDataKey!='LOB')
		{
			
			
			
			switch($convertDrillDowmMap[key($allDataVal)]['dataType'])
			{
				
				
				case 'int':
				case 'decimal':
					$JallData[$allDataKey]=	$agentScoreObj->JsonKeyValueConvertion($allDataVal , 'number');	
				break;
				
				case 'string':	
				default:
					$JallData[$allDataKey]=	$agentScoreObj->JsonKeyValueConvertion($allDataVal , 'string');
				break;
				
			}
			/*if($allDataKey=='Date')
			{
				$JallData[$allDataKey]=	$agentScoreObj->JsonKeyValueConvertion($allDataVal , 'string');
			}
			else
			{
				$JallData[$allDataKey]=	$agentScoreObj->JsonKeyValueConvertion($allDataVal , 'number');	
			}*/
			
			
		}
	}
	
		/*echo '<pre>';
		print_r($JallData);
		echo '</pre>';*/
		
		
}



unset($dPstring);
$dPstring = '[';
foreach($JallData as $JallDataKey=>$JallDataVal)
{
			
	$dPstring .= $JallDataVal.',';	
		
	
}
$dPstring = substr($dPstring,0,-1);
$dPstring .= ']';
//$dPstring = substr($dPstring,0,-1);
//echo $dPstring;

                    
?>
	
		<script type="text/javascript">
		//var FixedWidth = 950;
		var chart;$(document).ready(function(){chart=new Highcharts.Chart({chart:{renderTo:'container',marginRight:50,defaultSeriesType: 'pie'},credits:{enabled:false},title:{text:''},tooltip: {
        	   	formatter: function() {
							<?php
								if($isDisplayPer)
								{
							?>
								return '"<b>'+ this.point.name +'</b>: '+ parseFloat(this.percentage).toFixed(2) +'%"';
							<?php } else { ?>
								return '"<b>'+ this.point.name +'</b>: '+ this.y+' <?php  echo $metricTail;?>"';
							<?php } ?>
		                      }
            	
            },plotOptions: {
                pie: {
					allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        color: '#000000',
                        connectorColor: '#000000',
                        formatter: function() {
                            return '<b>'+ this.point.name +'</b>: '+ parseFloat(this.percentage).toFixed(2) +' %';
                        }
                    }
                }
            },series:[{
					  type: 'pie',
               		  name: 'Calls Handled',
					  data: <?php echo $dPstring;?>
					  
					  
					  }]})});

				
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
	<div id="container" style="width:<?php echo $width;?>px;"></div>
</div>
<?php $agentScoreObj->closeConn();  ?>
		
		
		