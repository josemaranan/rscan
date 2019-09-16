<?php
include_once($_SERVER['DOCUMENT_ROOT']."/agentScoreCard/includes/agentScoreCard.class.inc.php");
$agentScoreObj = new agentScoreCard();
include_once($_SERVER['DOCUMENT_ROOT']."/agentScoreCard/includes/postVariables.php");

if($requiredData==0)
{ ?>
<div style="position:relative; float:right; margin-right:10px;">
    <a href="#" onclick="return closeMask(); return false;">Close</a>
    </div>
	<div style="margin:150px 150px 150px 500px; font-size:20px; font-weight:bold;">No data available</div>
<?php exit(); }
foreach($requiredData as $rrow)
{
	if($rrow['LOB']==$lob && $rrow['Date']!='Subtotals')
	{
		$dates[] = $rrow['Date'];
		$numberOfSueveys[] = $rrow['Number of Surveys'];
		$CSATScore[] = $agentScoreObj->numberFormatReturn($rrow['CSAT Score']);
		$HighlySatisfied[] = $agentScoreObj->numberFormatReturn($rrow['Highly Satisfied']);
		$Satisfied[] = $agentScoreObj->numberFormatReturn($rrow['Satisfied']);
		$Neutral[] = $agentScoreObj->numberFormatReturn($rrow['Neutral']);
		$Dissatisfied[] = $agentScoreObj->numberFormatReturn($rrow['Dissatisfied']);
		$HighlyDissatisfied[] = $agentScoreObj->numberFormatReturn($rrow['Highly Dissatisfied']);
		$CSATGoal[] = $agentScoreObj->numberFormatReturn($rrow['CSAT Goal']);

		
		
		$count++;
	}
}
mssql_free_result($resultsSet);

/*echo '<pre>';
print_r($holdTime);
echo '</pre>';
exit;*/


/*$categories = array('April', 'May', 'June', 'July', 'August', 'September', 'October', 'November');
$NCH = array(1252, 1530, 2969, 5903, 4566, 3351, 5016, 3082);
$Apps = array(872, 1131, 2274, 4652, 3544, 2382, 3622, 2167);
$CGross = array(69.65, 73.92, 76.59, 78.81, 77.62, 71.08, 72.21, 70.31);*/

$Jdates = $agentScoreObj->JsonConvertion($dates , 'string');
$JnumberOfSueveys = $agentScoreObj->JsonConvertion($numberOfSueveys, 'number');
$JCSATScore = $agentScoreObj->JsonConvertion($CSATScore, 'number');
$JHighlySatisfied = $agentScoreObj->JsonConvertion($HighlySatisfied, 'number');
$JSatisfied = $agentScoreObj->JsonConvertion($Satisfied, 'number');
$JNeutral = $agentScoreObj->JsonConvertion($Neutral, 'number');
$JDissatisfied = $agentScoreObj->JsonConvertion($Dissatisfied, 'number');
$JHighlyDissatisfied = $agentScoreObj->JsonConvertion($HighlyDissatisfied, 'number');
$JCSATGoal = $agentScoreObj->JsonConvertion($CSATGoal, 'number');




/*echo $Jlobs.'<br>';
echo $JnumberOfSueveys.'<br>';
echo $JCSATScore.'<br>';
echo $JHighlySatisfied.'<br>';
echo $JSatisfied.'<br>';
echo $JNeutral.'<br>';
echo $JDissatisfied.'<br>';
echo $JHighlyDissatisfied.'<br>';
exit;
*/
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
		
		var chart;
				$(document).ready(function() {
				   chart = new Highcharts.Chart({
					  chart: {
						 renderTo: 'container',
						 marginRight:0,
						 marginLeft:5	

					  },
					  credits: {
							enabled: false
					},
					  title: {
						 text: ''
					  },
					  xAxis: {
				 categories: <?php echo $Jdates; ?>/*,
				 labels: {
                    rotation: -25,
                    align: 'right'
                 }*/

					  },
				 yAxis: [{ // left y axis
                title: {
                    text: null
                },
                labels: {
                    formatter: function() {
                        return Highcharts.numberFormat(this.value, 0);
                    }
                }
				
            }, { // right y axis
                gridLineWidth: 0,
                opposite: true,
                title: {
                    text: null
                },
                labels: {
                    
                    formatter: function() {
                       // return this.value +' %';
					   return Highcharts.numberFormat(this.value, 0);
                    }
                }
               /*showFirstLabel: false,
				tickInterval:0.10,
				max:1*/
				
            }],

					 legend: {
							verticalAlign:'top',marginTop:25
							},
										  
					  tooltip: {
						 formatter: function() {
							return '<b>'+ this.x +'</b><br/>'+
								this.series.name +': '+ this.y +
								(this.series.name == 'Fax Productivity' ? '' : '');
						 }
					  },
					  plotOptions: {
						 column: {
							stacking: 'normal'
						 }
					  },
					   
					series: [
					{
						name: 'No of Surveys', // Dynamic
						//color: '#4E81BD', // Dynamic
						type: 'column',
						yAxis: 0,
						data: <?php echo $JnumberOfSueveys;?>
						
					},
					
					{
						name: '% Highly Satisfied', // Dynamic
						
						//color: '#5B065B', // Dynamic
						type: 'line',
						yAxis: 0,
						data: <?php echo $JHighlySatisfied;?>	// Dynamic	 
					},
					{
						name: '% Satisfied', // Dynamic
						
						//color: '#5B065B', // Dynamic
						type: 'line',
						yAxis: 0,
						data: <?php echo $JSatisfied;?>	// Dynamic	 
					},
					{
						name: '% Neutral', // Dynamic
						
						//color: '#5B065B', // Dynamic
						type: 'line',
						yAxis: 0,
						data: <?php echo $JNeutral;?>	// Dynamic	 
					},
					{
						name: '% CSAT Goal', // Dynamic
						
						//color: '#5B065B', // Dynamic
						type: 'line',
						yAxis: 0,
						data: <?php echo $JCSATGoal;?>	// Dynamic	 
					}]
					
					
				   });
				   
				   
				});

				
		</script>
		
        
	<!-- 3. Add the container -->
<div id = "dialogTitle" style="background:#7AC143; color:#FFF; font-size:11px; font-weight:bold; padding:5px; width:<?php echo $containerWidth;?>px;" class="outer" ><?php echo $title;?></div>


<div style="margin:0px; padding:0px; float:right;">
<div id="mapClose">
<a href="#" onclick="return populateDetails('<?php echo $type;?>', '<?php echo $measure;?>' , '<?php echo $period;?>' , '<?php echo $employeID;?>', '<?php echo $startDate;?>' , '<?php echo $endDate;?>' , '' , '' ); return false;">View Report</a>
&nbsp;&nbsp;&nbsp;
<a href="#" onclick="return closeMask(); return false;">Close</a>
</div>
</div>

<br clear="all" />
<div style="text-align:center; color:#0066CC; font-weight:bold; letter-spacing:2px; word-spacing:5px;"><?php echo $subTitle;?></div>

<div style="margin:0px; padding:0px; height:<?php echo $height;?>px; width:<?php echo $width;?>px;">
	<div id="container" style="width:<?php echo $containerWidth;?>px; height:<?php echo $height;?>px;"></div>
</div>
		