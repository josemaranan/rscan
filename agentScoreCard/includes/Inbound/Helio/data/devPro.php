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
		$GA[] = $agentScoreObj->numberFormatReturn($rrow['GA']);
		$UPG[] = $agentScoreObj->numberFormatReturn($rrow['UPG']);
		$DEVPRO[] = $agentScoreObj->numberFormatReturn($rrow['DEVPRO']);
		
		
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
$JGA = $agentScoreObj->JsonConvertion($GA , 'number');
$JUPG = $agentScoreObj->JsonConvertion($UPG, 'number');
$JDEVPRO = $agentScoreObj->JsonConvertion($DEVPRO, 'number');

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
		function CreateDiv(name, Options, color)
		{
			var ElementWidth = parseInt(<?php echo $containerWidth;?> / Options.data.length);
			//var ElementWidth = 100;
			var ElementHeading=250;
			//alert(ElementWidth);			
			//$("#Legend").append('<div>'+name+'</div>');
			//Options.data.length
			var str = '<tr height="1"><td class= "SeriesName" style="width:'+ElementHeading+'px;"><span style="background-color:'+color+'; margin-right:4px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>'+name+'</td>';
			for(var x=0; x<Options.data.length;x++)
			{
				//str += '<td class="fWidth" style="width:'+ElementWidth+'px;">'+Options.data[x];
				//str += 	(name == 'Conversion Gross %' ? ' %' : '');
		str += '<td class="fWidth" style="width:'+ElementWidth+'px;">';
		//str += 	(name == 'Fax Productivity' ? ''+Options.data[x]+'' : ''+Options.data[x].toFixed(2)+' % ' );
		str += Options.data[x]
				
				str += '</td>';
			}
				str += '</tr>';
		
				
			//alert(str);
	$("#LegendTable").append(str);
			

		}
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
                }/*,
                showFirstLabel: false,
				tickInterval:1,
				max:2*/
				
            }, { // right y axis
                gridLineWidth: 0,
                opposite: true,
                title: {
                    text: null
                },
                labels: {
                    
                    formatter: function() {
                        return this.value +' %';
					   //return Highcharts.numberFormat(this.value, 0);
                    }
                }
                
				
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
						name: 'GA', // Dynamic
						//color: '#4E81BD', // Dynamic
						type: 'column',
						yAxis: 0,
						data: <?php echo $JGA;?>
						
					},
					{
						name: 'UPG', // Dynamic
						//color: '#4E81BD', // Dynamic
						type: 'line',
						yAxis: 0,
						data: <?php echo $JUPG;?>
						
					},
					{
						name: 'DEV PRO', // Dynamic
						//color: '#4E81BD', // Dynamic
						type: 'line',
						yAxis: 1,
						data: <?php echo $JDEVPRO;?>
						
					}]
					
					
				   });
				   
				   
				});

				
		</script>
		
        
	<!-- 3. Add the container -->
<div id = "dialogTitle" style="background:#7AC143; color:#FFF; font-size:11px; font-weight:bold; padding:5px; width:<?php echo $containerWidth;?>px;" class="outer" ><?php echo $title;?></div>


<div style="margin:0px; padding:0px; float:right;">
<div id="mapClose">
<a href="#" onclick="return populateDetails('<?php echo $type;?>', '<?php echo $measure;?>' , '<?php echo $period;?>' , '<?php echo $employeID;?>', '<?php echo $startDate;?>' , '<?php echo $endDate;?>' , 'Helio' , '' ); return false;">View Report</a>
&nbsp;&nbsp;&nbsp;
<a href="#" onclick="return closeMask(); return false;">Close</a>
</div>
</div>

<br clear="all" />
<div style="text-align:center; color:#0066CC; font-weight:bold; letter-spacing:2px; word-spacing:5px;"><?php echo $subTitle;?></div>

<div style="margin:0px; padding:0px; height:<?php echo $height;?>px; width:<?php echo $width;?>px;">
	<div id="container" style="width:<?php echo $containerWidth;?>px; height:<?php echo $height;?>px;"></div>
</div>
		
		
		