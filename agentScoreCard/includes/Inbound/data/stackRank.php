<?php
include_once($_SERVER['DOCUMENT_ROOT']."/agentScoreCard/includes/agentScoreCard.class.inc.php");
$agentScoreObj = new agentScoreCard();

$width = '900';
$height = '500';
$title = '';

if(isset($_REQUEST['dwidth']))
{
	$width = $_REQUEST['dwidth'];		
}

if(isset($_REQUEST['dheight']))
{
	$height = $_REQUEST['dheight'];		
}

if(isset($_REQUEST['dtitle']))
{
	$title = $_REQUEST['dtitle'];		
}

if(isset($_REQUEST['startDate']))
{
	$startDate = $_REQUEST['startDate'];	
}


if(isset($_REQUEST['endDate']))
{
	$endDate = $_REQUEST['endDate'];	
}


if(isset($_REQUEST['type']))
{
	$type = $_REQUEST['type'];	
}

if(isset($_REQUEST['measure']))
{
	$measure = $_REQUEST['measure'];	
}

if(isset($_REQUEST['period']))
{
	$period = $_REQUEST['period'];	
}

if(isset($_REQUEST['employeID']))
{
	$employeID = $_REQUEST['employeID'];	
}

if(isset($_REQUEST['defaultUrl']))
{
	$defaultUrl = $_REQUEST['defaultUrl'];	
}

$sqlQuery = " IF OBJECT_ID('tempdb.dbo.#tempFaxdata') IS NOT NULL
				DROP TABLE #tempFaxdata
				
				CREATE TABLE #tempFaxdata
				(
					[lobName] VARCHAR(255) NULL,
					[myRank] INT NULL,
					[myTeamRank] INT NULL,
					[mySiteRank] INT NULL,
					[cumulativeRank] INT NULL
				)
								
				INSERT INTO #tempFaxdata        SELECT 'WC_Behavioral Health ' ,5 ,2 ,1 ,3
				INSERT INTO #tempFaxdata        SELECT 'WC_CAID ' ,6 ,6 ,2 ,5
				INSERT INTO #tempFaxdata        SELECT 'WC_Caid Member ' ,8 ,9 ,5 ,7
				INSERT INTO #tempFaxdata        SELECT 'WC_CCP ' ,4 ,8 ,4 ,5
				INSERT INTO #tempFaxdata        SELECT 'WC_COE ' ,8 ,4 ,4 ,5
				INSERT INTO #tempFaxdata        SELECT 'WC_CTM ' ,6 ,1 ,8 ,5
				INSERT INTO #tempFaxdata        SELECT 'WC_DUAL ' ,3 ,5 ,9 ,6
				INSERT INTO #tempFaxdata        SELECT 'WC_Duals ' ,5 ,6 ,2 ,4
				INSERT INTO #tempFaxdata        SELECT 'WC_Health Services ' ,4 ,9 ,6 ,6
				INSERT INTO #tempFaxdata        SELECT 'WC_Hourly_NQ ' ,6 ,4 ,9 ,6
				INSERT INTO #tempFaxdata        SELECT 'WC_Lic Enrollment ' ,8 ,8 ,5 ,7
				INSERT INTO #tempFaxdata        SELECT 'WC_LTC ' ,2 ,2 ,7 ,4
				INSERT INTO #tempFaxdata        SELECT 'WC_MAPD ' ,4 ,8 ,8 ,7
				INSERT INTO #tempFaxdata        SELECT 'WC_Medicaid ' ,5 ,9 ,9 ,8
				INSERT INTO #tempFaxdata        SELECT 'WC_Outbound ' ,9 ,5 ,5 ,6
				INSERT INTO #tempFaxdata        SELECT 'WC_PAV ' ,8 ,3 ,2 ,4
				INSERT INTO #tempFaxdata        SELECT 'WC_PDP ' ,7 ,5 ,1 ,4
				
				SELECT * FROM #tempFaxdata (NOLOCK)";
	

//$rst=mssql_query(str_replace("\'","''",$SQL_prod), $db);
$rst = $agentScoreObj->ExecuteQuery($sqlQuery);
$numRecords = mssql_num_rows($rst);
if($numRecords==0)
{ ?>
	<div style="margin:150px 150px 150px 500px; font-size:20px; font-weight:bold;">No data available</div>
<?php exit(); }
while($rrow = mssql_fetch_assoc($rst))
{
	$lobs[] = $rrow['lobName'];
	$myRank[] = $rrow['myRank'];
	$myTeamRank[] = $rrow['myTeamRank'];
	$mySiteRank[] = $rrow['mySiteRank'];
	$cumulativeRank[] = $rrow['cumulativeRank'];
	
}
mssql_free_result($rst);

/*$categories = array('April', 'May', 'June', 'July', 'August', 'September', 'October', 'November');
$NCH = array(1252, 1530, 2969, 5903, 4566, 3351, 5016, 3082);
$Apps = array(872, 1131, 2274, 4652, 3544, 2382, 3622, 2167);
$CGross = array(69.65, 73.92, 76.59, 78.81, 77.62, 71.08, 72.21, 70.31);*/

$Jlobs = JsonConvertion($lobs , 'string');
$JmyRank = JsonConvertion($myRank, 'number');
$JmyTeamRank = JsonConvertion($myTeamRank, 'number');
$JmySiteRank = JsonConvertion($mySiteRank, 'number');
$JcumulativeRank = JsonConvertion($cumulativeRank, 'number');

//$FixedWidth = 1215;
//$ElementWidth = $FixedWidth / count($Jdates);

$elementWidth = 100;
$totalLobs = count($lobs);
$containerWidth = $elementWidth*$totalLobs;



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
			//var ElementWidth = parseInt(FixedWidth / Options.data.length);
			var ElementWidth = 100;
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
						 marginRight:50
					  },
					  credits: {
							enabled: false
					},
					  title: {
						 text: 'LOB wise - Stack Rank graph (date range : <?php echo $startDate.' - '.$endDate;?>)'
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
                showFirstLabel: false,
				tickInterval:1,
				max:10
				
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
						name: 'My Stack Rank', // Dynamic
						color: '#4E81BD', // Dynamic
						type: 'line',
						yAxis: 0,
						data: <?php echo $JmyRank;?>
						
					},
					{
						name: 'My Team\'s Stack Rank', // Dynamic
						color: '#C1504C', // Dynamic
						type: 'line',
						yAxis: 1,
						data: <?php echo $JmyTeamRank;?>		
						
					
					},
					{
						name: 'My Site\'s Stack Rank', // Dynamic
						
						color: '#9BBB5A', // Dynamic
						type: 'line',
						yAxis: 1,
						data: <?php echo $JmySiteRank;?>	// Dynamic	 
					},
					{
						name: 'Cumulative LOB Stack Rank', // Dynamic
						
						color: '#5B065B', // Dynamic
						type: 'line',
						yAxis: 1,
						data: <?php echo $JcumulativeRank;?>	// Dynamic	 
					}]
					
					
				   });
				   
				   
				});

				
		</script>
		
        
	<!-- 3. Add the container -->
    <div id = "dialogTitle" style="background:#7AC143; color:#FFF; font-size:11px; font-weight:bold; padding:5px; width:<?php echo $containerWidth;?>px;" 	class="outer" ><?php echo $title;?></div>

<div style="margin:0px; padding:0px; float:right;" id="testDiv" class="outer">
<div id="mapClose">
<a href="#" onclick="return populateDetails('<?php echo $type;?>', '<?php echo $measure;?>' , '<?php echo $period;?>' , '<?php echo $employeID;?>', '<?php echo $endDate;?>' , '<?php echo $endDate;?>' , '' ); return false;">Back</a>
&nbsp;&nbsp;&nbsp;
<a href="#" onclick="return closeMask(); return false;">Close</a>
</div>
</div>

<br clear="all" />
<div style="margin:0px; padding:0px; height:<?php echo $height;?>px; width:<?php echo $width;?>px; overflow-Y:scroll; overflow-x:scroll">

	<div id="container" style="width:<?php echo $containerWidth;?>px;"></div>
    <div id="LegendGraph" style="width:<?php echo $containerWidth;?>px;">
    <table border="1" cellpadding="0" cellspacing="0" id="LegendTable" >
    </table>
    </div> 					

</div>