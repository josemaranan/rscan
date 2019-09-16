<?php
include_once($_SERVER['DOCUMENT_ROOT']."/agentScoreCard/includes/agentScoreCard.class.inc.php");
$agentScoreObj = new agentScoreCard();
$width = '900';
$height = '500';
$title = '';
include_once($_SERVER['DOCUMENT_ROOT']."/agentScoreCard/includes/postVariables.php");

/*echo 'Start Date'.$startDate.'<br>';
echo 'End Date'.$endDate.'<br>';
echo 'measure'.$measure.'<br>';
echo 'period'.$period.'<br>';
echo 'employeID'.$employeID.'<br>';
exit;*/



unset($sqlQuery);
unset($resultsSet);
unset($numRows);
unset($stackData);
unset($periodParameter);

if($period=='WTD')
{
	$title = 'Week-to-Date Inbound - '.$measure.' data';	
	$periodParameter = 'W';
}


if($period=='MTD')
{
	$title = 'Month-to-Date Inbound - '.$measure.' data';	
	$periodParameter = 'M';
}


$sqlQuery = "  EXEC WellcareCommon.dbo.report_spScorecardDrilldownAHTCallsHandled ".$employeID.", '".$startDate."', '".$periodParameter."' ";
//echo $sqlQuery;
$resultsSet = $agentScoreObj->ExecuteQuery($sqlQuery, '', 'LIVE');
$numRows = mssql_num_rows($resultsSet);


if($numRows==0)
{ ?>
	<div style="position:relative; float:right; margin-right:10px;">
    <a href="#" onclick="return closeMask(); return false;">Close</a>
    </div>
	<div style="margin:150px 150px 150px 500px; font-size:20px; font-weight:bold;">No data available</div>
<?php exit(); }
while($rrow = mssql_fetch_assoc($resultsSet))
{
	if($rrow['Date']=='Subtotals')
	{
		$lobs[] = $rrow['LOB'];
		$callsHandled[] = $rrow['Calls Handled'];
		$AHT[] = $agentScoreObj->numberFormatReturn($rrow['AHT']);
		$talkTime[] = $agentScoreObj->numberFormatReturn($rrow['Talk Time']);
		$holdTime[] = $agentScoreObj->numberFormatReturn($rrow['Hold Time']);
		$ACWTime[] = $agentScoreObj->numberFormatReturn($rrow['ACW Time']);
		$OtherTime[] = $agentScoreObj->numberFormatReturn($rrow['Other Time']);
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

$Jlobs = JsonConvertion($lobs , 'string');
$JcallsHandled = JsonConvertion($callsHandled, 'number');
$JAHT = JsonConvertion($AHT, 'number');
$JtalkTime = JsonConvertion($talkTime, 'number');
$JholdTime = JsonConvertion($holdTime, 'number');
$JACWTime = JsonConvertion($ACWTime, 'number');
$JOtherTime = JsonConvertion($OtherTime, 'number');

/*
echo $Jlobs.'<br>';
echo $JcallsHandled.'<br>';
echo $JtalkTime.'<br>';
echo $JholdTime.'<br>';
echo $JACWTime.'<br>';
exit;
*/
//$FixedWidth = 1215;
//$ElementWidth = $FixedWidth / count($Jdates);

$elementWidth = 80;
$totalLobs = count($lobs);
$containerWidth = $elementWidth*$totalLobs;

if($width>$containerWidth)
{
	$containerWidth = $width;
}

//echo $containerWidth;

function JsonConvertion ($Array, $type)
{
		unset($str);
		$str = "[";
		foreach($Array as $val)
		{
			if($type=='string')
			{
				$str .= '"'.$val.'",';
			}
			else
			{
				$str .= $val.',';	
			}
		}
		$str = substr($str, 0,-1);
		$str .= "]";
		return $str;
}
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
						 defaultSeriesType: 'line',
						 marginRight:0
					  },
					  credits: {
							enabled: false
					},
					  title: {
						 text: '<?php echo $title;?>'
					  },
					  xAxis: {
				 categories: <?php echo $Jlobs; ?>/*,
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
                },
                showFirstLabel: false/*,
				tickInterval:1,
				max:10*/
				
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
				tickInterval:10,
				max:100*/
				
            }],

					 legend: {
						layout: 'vertical',
						align: 'left',
						floating: true,
						x: -250, // = marginLeft - default spacingLeft
						labelFormatter: function() {
							CreateDiv(this.name, this.options, this.color);
						}
					
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
						name: 'Calls Handled', // Dynamic
						//color: '#4E81BD', // Dynamic
						type: 'line',
						yAxis: 1,
						data: <?php echo $JcallsHandled;?>
						
					},
					{
						name: 'AHT', // Dynamic
						//color: '#C1504C', // Dynamic
						type: 'line',
						yAxis:0,
						data: <?php echo $JAHT;?>		
						
					
					},
					{
						name: 'Talk Time', // Dynamic
						
						//color: '#9BBB5A', // Dynamic
						type: 'line',
						yAxis: 1,
						data: <?php echo $JtalkTime;?>	// Dynamic	 
					},
					{
						name: 'Hold Time', // Dynamic
						
						//color: '#5B065B', // Dynamic
						type: 'line',
						yAxis: 1,
						data: <?php echo $JholdTime;?>	// Dynamic	 
					},
					{
						name: 'ACW Time', // Dynamic
						
						//color: '#5B065B', // Dynamic
						type: 'line',
						yAxis: 1,
						data: <?php echo $JACWTime;?>	// Dynamic	 
					},
					{
						name: 'Other Time', // Dynamic
						
						//color: '#5B065B', // Dynamic
						type: 'line',
						yAxis: 1,
						data: <?php echo $JOtherTime;?>	// Dynamic	 
					}]
					
					
				   });
				   
				   
				});

				
		</script>
		
        
	<!-- 3. Add the container -->
    <div id = "dialogTitle" style="background:#7AC143; color:#FFF; font-size:11px; font-weight:bold; padding:5px; width:<?php echo $containerWidth;?>px;" 	class="outer" ><?php echo $title;?></div>


<div style="margin:0px; padding:0px; float:right;">
<div style="position:fixed; margin-left:-100px;">
<a href="#" onclick="return populateDetails('<?php echo $type;?>', '<?php echo $measure;?>' , '<?php echo $period;?>' , '<?php echo $employeID;?>', '<?php echo $startDate;?>' , '<?php echo $endDate;?>' , '' ); return false;">View Report</a>
&nbsp;&nbsp;&nbsp;
<a href="#" onclick="return closeMask(); return false;">Close</a>
</div>
</div>

<br clear="all" />
<div style="margin:0px; padding:0px; height:<?php echo $height;?>px; width:<?php echo $width;?>px;">

	<div id="container" style="width:<?php echo $containerWidth;?>px;"></div>
    <div id="LegendGraph" style="width:<?php echo $containerWidth;?>px;">
    <table border="1" cellpadding="0" cellspacing="0" id="LegendTable" >
    </table>
    </div> 					

</div>
		
		
		
		