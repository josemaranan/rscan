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

/*echo '<pre>';
print_r($requiredData);
echo '</pre>';*/


if(empty($requiredData))
{ ?>
	<div style="position:relative; float:right; margin-right:10px;">
    <a href="#" onclick="return closeMask(); return false;">Close</a>
    </div>
	<div style="margin:150px 150px 150px 500px; font-size:20px; font-weight:bold;">No data available</div>
<?php exit(); }

	if($lob=='Totals')
	{
		foreach($requiredData as $rrow)
		{
	
			if($rrow['LOB']=='Totals')
			{
				//$CSATScore['CSAT Score'] = $agentScoreObj->numberFormatReturn($rrow['CSAT Score'],2);
				$HighlySatisfied['Highly Satisfied'] = $agentScoreObj->numberFormatReturn($rrow['Highly Satisfied'],2);
				$Satisfied['Satisfied'] = $agentScoreObj->numberFormatReturn($rrow['Satisfied'],2);
				$Neutral['Neutral'] = $agentScoreObj->numberFormatReturn($rrow['Neutral'],2);
				$Dissatisfied['Dissatisfied'] = $agentScoreObj->numberFormatReturn($rrow['Dissatisfied'],2);
				$HighlyDissatisfied['Highly Dissatisfied'] = $agentScoreObj->numberFormatReturn($rrow['Highly Dissatisfied'],2);
				$count++;
			}
		}
	} // if total
	else
	{
		foreach($requiredData as $rrow)
		{
	
			if($rrow['LOB']==$lob && $rrow['Date']=='Subtotals')
			{
				//$CSATScore['CSAT Score'] = $agentScoreObj->numberFormatReturn($rrow['CSAT Score'],2);
				$HighlySatisfied['Highly Satisfied'] = $agentScoreObj->numberFormatReturn($rrow['Highly Satisfied'],2);
				$Satisfied['Satisfied'] = $agentScoreObj->numberFormatReturn($rrow['Satisfied'],2);
				$Neutral['Neutral'] = $agentScoreObj->numberFormatReturn($rrow['Neutral'],2);
				$Dissatisfied['Dissatisfied'] = $agentScoreObj->numberFormatReturn($rrow['Dissatisfied'],2);
				$HighlyDissatisfied['Highly Dissatisfied'] = $agentScoreObj->numberFormatReturn($rrow['Highly Dissatisfied'],2);
				$count++;
			}
		}	
	} // else 



//$JCSATScore = $agentScoreObj->JsonKeyValueConvertion($CSATScore, 'number');
$JHighlySatisfied = $agentScoreObj->JsonKeyValueConvertion($HighlySatisfied, 'number');
$JSatisfied = $agentScoreObj->JsonKeyValueConvertion($Satisfied, 'number');
$JNeutral = $agentScoreObj->JsonKeyValueConvertion($Neutral, 'number');
$JDissatisfied = $agentScoreObj->JsonKeyValueConvertion($Dissatisfied, 'number');
$JHighlyDissatisfied = $agentScoreObj->JsonKeyValueConvertion($HighlyDissatisfied, 'number');




//echo $JcallsHandled.'<br>';
//exit;

//$FixedWidth = 1215;
//$ElementWidth = $FixedWidth / count($Jdates);

$elementWidth = 80;
$containerWidth = $elementWidth*$count;

if($width>$containerWidth)
{
	$containerWidth = $width;
}

//echo $containerWidth;


?>
	
		<script type="text/javascript">
		//var FixedWidth = 950;
		var chart;$(document).ready(function(){chart=new Highcharts.Chart({chart:{renderTo:'container',marginRight:50,defaultSeriesType: 'pie'},credits:{enabled:false},title:{text:''},tooltip: {
        	    formatter: function() {
                            return '"<b>'+ this.point.name +'</b>: '+ this.y+'% "';
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
                name: 'CSAT',
                data: [<?php echo $JHighlySatisfied;?>,<?php echo $JSatisfied;?>,<?php echo $JNeutral;?>,<?php echo $JDissatisfied;?>,<?php echo $JHighlyDissatisfied;?>]
            }]})});

				
		</script>
		
<div id = "dialogTitle" style="background:#7AC143; color:#FFF; font-size:11px; font-weight:bold; padding:5px; width:<?php echo $containerWidth;?>px;" class="outer" ><?php echo $title;?></div>


<div style="margin:0px; padding:0px; float:right;">
<div id="mapClose">
<a href="#" onclick="return populateDetails('<?php echo $type;?>', '<?php echo $measure;?>' , '<?php echo $period;?>' , '<?php echo $employeID;?>', '<?php echo $startDate;?>' , '<?php echo $endDate;?>' , '', '' ); return false;">View Report</a>
&nbsp;&nbsp;&nbsp;
<a href="#" onclick="return closeMask(); return false;">Close</a>
</div>
</div>

<br clear="all" />
<div style="text-align:center; color:#0066CC; font-weight:bold; letter-spacing:2px; word-spacing:5px;">
<?php 
		if($lob=='Totals')
		{
			echo $title;
		}
		else
		{
			echo $subTitle;	
		}
?>
</div>

<div style="margin:0px; padding:0px; height:<?php echo $height;?>px; width:<?php echo $width;?>px;">
	<div id="container" style="width:<?php echo $containerWidth;?>px; height:<?php echo $height;?>px;"></div>
</div>		
		
		