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

if(empty($requiredData))
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
			$date[] = $rrow['Date'];
			$callsHandled[] = $rrow['Calls Handled'];
			$AHT[] = $agentScoreObj->numberFormatReturn($rrow['AHT']);
			$AHTGoal[] = $agentScoreObj->numberFormatReturn($rrow['AHT Goal']);
			$talkTime[] = $agentScoreObj->numberFormatReturn($rrow['ACD Time']);
			$holdTime[] = $agentScoreObj->numberFormatReturn($rrow['Hold Time']);
			$ACWTime[] = $agentScoreObj->numberFormatReturn($rrow['ACW Time']);
			//$OtherTime[] = $agentScoreObj->numberFormatReturn($rrow['Other Time']);
			$count++;
		}
	}


$Jdate = $agentScoreObj->JsonConvertion($date , 'string');
$JcallsHandled = $agentScoreObj->JsonConvertion($callsHandled, 'number');
$JAHT = $agentScoreObj->JsonConvertion($AHT, 'number');
$JAHTGoal = $agentScoreObj->JsonConvertion($AHTGoal, 'number');
$JtalkTime = $agentScoreObj->JsonConvertion($talkTime, 'number');
$JholdTime = $agentScoreObj->JsonConvertion($holdTime, 'number');
$JACWTime = $agentScoreObj->JsonConvertion($ACWTime, 'number');
//$JOtherTime = $agentScoreObj->JsonConvertion($OtherTime, 'number');

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
$containerWidth = $elementWidth*$count;

if($width>$containerWidth)
{
	$containerWidth = $width;
}

//echo $containerWidth;


?>
	
		<script type="text/javascript">
		//var FixedWidth = 950;
		var chart;$(document).ready(function(){chart=new Highcharts.Chart({chart:{renderTo:'container',marginRight:0, marginLeft:5},credits:{enabled:false},title:{text:''},xAxis:{categories:<?php echo $Jdate;?>},
		yAxis:[
			   	{title:{text:null},
				labels:{formatter:function()
				{return Highcharts.numberFormat(this.value,0)}},
				showFirstLabel:false},
				{gridLineWidth:0,
				opposite:true,title:{text:null},
				labels:{
					formatter:function(){return Highcharts.numberFormat(this.value,0)}},min:0}],legend:{verticalAlign:'top',marginTop:25},tooltip:{formatter:function(){return'<b>'+this.x+'</b><br/>'+this.series.name+': '+this.y+(this.series.name=='Fax Productivity'?'':'')}},plotOptions:{column:{stacking:'normal'}},series:[{name:'Calls Handled',type:'column',yAxis:0,data:<?php echo $JcallsHandled;?>},{name:'AHT',type:'line',yAxis:1,data:<?php echo $JAHT;?>},{name:'Talk Time',type:'line',yAxis:1,data:<?php echo $JtalkTime;?>},{name:'Hold Time',type:'line',yAxis:1,data:<?php echo $JholdTime;?>},{name:'ACW Time',type:'line',yAxis:1,data:<?php echo $JACWTime;?>},{name:'AHT Goal',type:'line',yAxis:1,data:<?php echo $JAHTGoal;?>}]})});

				
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
		
		
		
		