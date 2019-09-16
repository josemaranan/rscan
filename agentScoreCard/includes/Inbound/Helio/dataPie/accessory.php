<?php
include_once($_SERVER['DOCUMENT_ROOT']."/agentScoreCard/includes/agentScoreCard.class.inc.php");
$agentScoreObj = new agentScoreCard();
include_once($_SERVER['DOCUMENT_ROOT']."/agentScoreCard/includes/postVariables.php");
if(empty($requiredData))
{ ?>
	<div style="position:relative; float:right; margin-right:10px;">
    <a href="#" onclick="return closeMask(); return false;">Close</a>
    </div>
	<div style="margin:150px 150px 150px 500px; font-size:20px; font-weight:bold;">No data available</div>
<?php exit(); }

	foreach($requiredData as $rrow)
	{
		if($rrow['LOB']=='Totals')
		{
			$GA['GA'] = $agentScoreObj->numberFormatReturn($rrow['GA'],2);
			$UPG['UPG'] = $agentScoreObj->numberFormatReturn($rrow['UPG'],2);
			$PURACCY['PURACCY'] = $agentScoreObj->numberFormatReturn($rrow['PURACCY'],2);
			
			
			$count++;
		}
	}



$JUPG = $agentScoreObj->JsonKeyValueConvertion($UPG, 'number');
$JPURACCY = $agentScoreObj->JsonKeyValueConvertion($PURACCY, 'number');
$JGA = $agentScoreObj->JsonKeyValueConvertion($GA, 'number');




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
                            return '"<b>'+ this.point.name +'</b>: '+ this.y+' "';
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
                name: 'Browser share',
                data: [<?php echo $JUPG;?>,<?php echo $JPURACCY;?>,<?php echo $JGA;?>]
            }]})});

				
		</script>
		
<div id = "dialogTitle" style="background:#7AC143; color:#FFF; font-size:11px; font-weight:bold; padding:5px; width:<?php echo $containerWidth;?>px;" class="outer" ><?php echo $title;?></div>


<div style="margin:0px; padding:0px; float:right;">
<div id="mapClose">
<a href="#" onclick="return populateDetails('<?php echo $type;?>', '<?php echo $measure;?>' , '<?php echo $period;?>' , '<?php echo $employeID;?>', '<?php echo $startDate;?>' , '<?php echo $endDate;?>' , 'Helio', '' ); return false;">View Report</a>
&nbsp;&nbsp;&nbsp;
<a href="#" onclick="return closeMask(); return false;">Close</a>
</div>
</div>

<br clear="all" />
<div style="text-align:center; color:#0066CC; font-weight:bold; letter-spacing:2px; word-spacing:5px;"><?php echo $title;?></div>

<div style="margin:0px; padding:0px; height:<?php echo $height;?>px; width:<?php echo $width;?>px;">
	<div id="container" style="width:<?php echo $containerWidth;?>px; height:<?php echo $height;?>px;"></div>
</div>		
		
		