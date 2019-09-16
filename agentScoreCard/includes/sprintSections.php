<?
include_once($_SERVER['DOCUMENT_ROOT']."/agentScoreCard/includes/agentScoreCard.class.inc.php");
$agentScoreObj2 = new agentScoreCard();


$agentScoreObj2->setSprintInboundData($employeeID , $requestedDate);
$inboundData = $agentScoreObj2->getSprintInboundData();



$indicatorArray = array('0'=>array('G'=>'includes/images/SmallGreen_blue.jpg', 'Y'=>'includes/images/SmallYellow_blue.jpg', 'R'=>'includes/images/SmallRed_blue.jpg', 'X'=>'includes/images/white_ball_blue.jpg'),'1'=>array('G'=>'includes/images/SmallGreen_grey.jpg', 'Y'=>'includes/images/SmallYellow_grey.jpg', 'R'=>'includes/images/SmallRed_grey.jpg', 'X'=>'includes/images/white_ball_grey.jpg'));

?>

<?php 
//if(empty($inboundData))
//{?>
<div class="section">
		<div class="sectionHeading" style="text-align:left">
        <table border="0" cellpadding="0" cellspacing="0" width="400px;"><tr>
        <td style="text-align:left; width:20%">
        Telesales Directors Cup
        </td>
        </tr>
        </table>
        </div>
        <div class="sectionContent">
        <table border="0"  bgcolor="#FFFFFF" cellspacing="0" class="report">
        <thead>
          <tr>
            
           
            <th align="center"><strong>Date Range</strong></th>

            <th align="center" colspan="2"><strong>GACR %<br />Target: 8.5%<br />Weight:50%</strong></th>
            <th align="center" colspan="2"><strong>C-10 Defect<br />Target-<10%<br>Weight:5%</strong></th>
            <th align="center" colspan="2"><strong>Dev Pro<br />Target: 60%<br />Weight:5% </strong></th>
            <th align="center" colspan="2"><strong>CSAT Top 2 Box<br />Target: 82%<br />Weight:15%</strong></th>
            <th align="center" colspan="2"><strong>Accessory: 5.0%<br />Target: 50%<br />Weight:5% </strong></th>
            <th align="center" colspan="2"><strong>Take back %<br />Target: 6%<br />Weight:15% </strong></th>
            <th align="center" colspan="2"><strong>Upgrade %<br />Target: 4%<br />Weight:5% </strong></th>
            <th align="center"><strong>Big 3 Segmentation</strong></th>
            <th align="center"><strong>Score#</strong></th>
            <th align="center"><strong>Ranking#</strong></th>
            
          </tr>
        </thead>
        
        <tbody>
        
        <?php 
			
				$i=0;

				foreach($inboundData as $inboundDataVal)
				{ 
					if($i!=0 && $i%2==0)
					{
						$i=0;	
					}
					
					
					if($inboundDataVal['Period']=='MTD' || $inboundDataVal['Period']=='WTD')
					{
						//$folder = 'data';
						$folder = $client;
					}	
					else
					{
						$folder = $client;
					}
					
				?>
                <?php echo $trStryleArray[$i]; ?>
                <td style="text-align:left;"><?php 
						if(array_key_exists($inboundDataVal['Period'], $weektoDate))
						{
							echo $weektoDate[$inboundDataVal['Period']];
						}
						else
						{
							echo $inboundDataVal['Period']; 
						}
						?></td>
                   
 
				



               <td><a href="#" onclick="return populateDetails('Inbound','NGACR','<?php echo $inboundDataVal['Period'];?>','<?php echo $employeeID;?>', '<?php echo $requestedDate;?>' , '<?php echo $requestedDate;?>', '<?php echo $folder;?>', ''); return false;"><?php echo $inboundDataVal['NGACRGoalAchievement'];?></a></td>
               
                <td style="text-align:center;"><img src="<?php echo $indicatorArray[$i][$inboundDataVal['NGACRIndicator']];?>" width="20" height="20" ></td>


				<td><a href="#" onclick="return populateDetails('Inbound','C10DefectsGoalAchievement','<?php echo $inboundDataVal['Period'];?>','<?php echo $employeeID;?>', '<?php echo $requestedDate;?>' , '<?php echo $requestedDate;?>', '<?php echo $folder;?>', ''); return false;"><?php echo $inboundDataVal['C10DefectsGoalAchievement'];?></a></td>
                <td style="text-align:center;"><img src="<?php echo $indicatorArray[$i][$inboundDataVal['C10Indicator']];?>" width="20" height="20" ></td>



                <td><a href="#" onclick="return populateDetails('Inbound','DevPro','<?php echo $inboundDataVal['Period'];?>','<?php echo $employeeID;?>', '<?php echo $requestedDate;?>' , '<?php echo $requestedDate;?>', '<?php echo $folder;?>', ''); return false;"><?php echo $inboundDataVal['DevProGoalAchievement'];?></a></td>
   <td style="text-align:center;"><img src="<?php echo $indicatorArray[$i][$inboundDataVal['DEVPROIndicator']];?>" width="20" height="20" ></td>                
                
                
               <td><a href="#" onclick="return populateDetails('Inbound','CSATSprint','<?php echo $inboundDataVal['Period'];?>','<?php echo $employeeID;?>', '<?php echo $requestedDate;?>' , '<?php echo $requestedDate;?>', '<?php echo $folder;?>', ''); return false;"><?php echo $inboundDataVal['CSAT'];?></a></td>
                <td style="text-align:center;"><img src="<?php echo $indicatorArray[$i][$inboundDataVal['CSATIndicator']];?>" width="20" height="20" ></td>


               <td><a href="#" onclick="return populateDetails('Inbound','Accessory','<?php echo $inboundDataVal['Period'];?>','<?php echo $employeeID;?>', '<?php echo $requestedDate;?>' , '<?php echo $requestedDate;?>', '<?php echo $folder;?>', ''); return false;"><?php echo $inboundDataVal['AccessoryGoalAchievement'];?></a></td>
                <td style="text-align:center;"><img src="<?php echo $indicatorArray[$i][$inboundDataVal['AccessoryIndicator']];?>" width="20" height="20" ></td>


               <td><a href="#" onclick="return populateDetails('Inbound','TakeBacks','<?php echo $inboundDataVal['Period'];?>','<?php echo $employeeID;?>', '<?php echo $requestedDate;?>' , '<?php echo $requestedDate;?>', '<?php echo $folder;?>', ''); return false;"><?php echo $inboundDataVal['TakebackgoalAchievement'];?></a></td>
                <td style="text-align:center;"><img src="<?php echo $indicatorArray[$i][$inboundDataVal['TakebackIndicator']];?>" width="20" height="20" ></td>


               <td><a href="#" onclick="return populateDetails('Inbound','Upgrade','<?php echo $inboundDataVal['Period'];?>','<?php echo $employeeID;?>', '<?php echo $requestedDate;?>' , '<?php echo $requestedDate;?>', '<?php echo $folder;?>', ''); return false;"><?php echo $inboundDataVal['UpgradeGoalAchievement'];?></a></td>
                <td style="text-align:center;"><img src="<?php echo $indicatorArray[$i][$inboundDataVal['UpgradeIndicator']];?>" width="20" height="20" ></td>
                
                
                <td style="text-align:center;"><?php echo $inboundDataVal['Big3Segment'];?></td>
                <td style="text-align:center;"><?php echo $inboundDataVal['Score'];?></td>
                <td style="text-align:center;"><?php echo $inboundDataVal['ranking'];?></td>
                
				<?php 
				$i++;
				} // for
				
					
			?>
        
        
        </tbody>
        </table>
        
       
        </div>
        
        <br />
        <div class="sectionContent">
        <table border="0"  bgcolor="#FFFFFF" cellspacing="0" class="report">
        <thead>
          <tr>
            
           
            <th colspan="10" align="center"><strong>Scorecard Paid 4 Performance: Cost per Sale</strong></th>
          </tr>
        </thead>
        <tr>
            <td style="text-align:center"><strong>Scorecard Range</strong></td>
            <td style="text-align:center"><strong>PTG Ranking</strong></td>
            <td style="text-align:center"><strong>PTG Factor</strong></td>
            <td style="text-align:center"><strong>Segment</strong></td>
            <td style="text-align:center"><strong>Seg Bonus</strong></td>
            <td style="text-align:center"><strong>GA</strong></td>
            <td style="text-align:center"><strong>Dev Pro</strong></td>
            <td style="text-align:center"><strong>Accessory</strong></td>
            <td style="text-align:center"><strong>Segment Definition</strong></td>
            <td style="text-align:center"><strong>Qualifier</strong></td>
		</tr>
        <tr>
        	<td style="text-align:center">3.0-3.15</td>
            <td style="text-align:center">Below 90%</td>
            <td style="text-align:center">1</td>
            <td style="text-align:center">C</td>
            <td style="text-align:left">PHP&nbsp;&nbsp;&nbsp;-</td>
            <td style="text-align:center">PHP 100.00</td>
            <td style="text-align:center">PHP 50.00</td>
            <td style="text-align:center">PHP 50.00</td>
            <td style="text-align:center">Met GACR Only</td>
            <td style="text-align:center">3.0 Overall Scorecard</td>
        </tr>

        <tr>
        	<td style="text-align:center">3.16 - 3.49</td>
            <td style="text-align:center">90 - 99.99%</td>
            <td style="text-align:center">2</td>
            <td style="text-align:center">B-</td>
            <td style="text-align:left">PHP&nbsp;&nbsp;&nbsp;-</td>
            <td style="text-align:center">PHP 125.00</td>
            <td style="text-align:center">PHP 50.00</td>
            <td style="text-align:center">PHP 50.00</td>
            <td style="text-align:center">Missed GACR, Met other 2 metrics</td>
            <td style="text-align:center">105%> GA% for Dev& Accy</td>
        </tr>


        <tr>
        	<td style="text-align:center">3.5 - 3.74</td>
            <td style="text-align:center">100 - 104.99%</td>
            <td style="text-align:center">3</td>
            <td style="text-align:center">B</td>
            <td style="text-align:left">PHP&nbsp;&nbsp;&nbsp;-</td>
            <td style="text-align:center">PHP 150.00</td>
            <td style="text-align:center">PHP 50.00</td>
            <td style="text-align:center">PHP 50.00</td>
            <td style="text-align:center">Met GACR +1 Other Metric</td>
            <td style="text-align:center"> 85%+ Scheduled Adherence</td>
        </tr>


        <tr>
        	<td style="text-align:center">3.75 - 3.99</td>
            <td style="text-align:center">105 - 119.99%</td>
            <td style="text-align:center">4</td>
            <td style="text-align:center">A</td>
            <td style="text-align:left">PHP&nbsp;&nbsp;&nbsp;-</td>
            <td style="text-align:center">PHP 175.00</td>
            <td style="text-align:center">PHP 50.00</td>
            <td style="text-align:center">PHP 50.00</td>
            <td style="text-align:center">All 3 Metrics Met </td>
            <td></td>
        </tr>


  		<tr>
        	<td style="text-align:center">4 and above</td>
            <td style="text-align:center">120% and above</td>
            <td style="text-align:center">5</td>
            <td style="text-align:center">A+</td>
            <td style="text-align:left">PHP 5,000</td>
            <td style="text-align:center">PHP 200.00</td>
            <td style="text-align:center">PHP 50.00</td>
            <td style="text-align:center">PHP 50.00</td>
            <td style="text-align:center">All 3 Metrics Met </td>
            <td></td>
        </tr>


        
        
        
        </table>
</div>
        
        
        
    
<?php //} ?>
<br />

		<div id="sprintlegendContent" style="border:2px solid #7AC143; width:20%; text-align:center;">
    	<strong>Paid for Performance Calculator</strong>
        
        <table border="3" cellpadding="3" cellspacing="3">
        <tr>
        <td>Segment</td><td>A</td></tr>
        <td>Score</td><td>2.0</td></tr>
        <td>P4P Amount</td><td>TBD</td>
        </tr>
        </table>
        
        </div></div>

<?php $agentScoreObj2->closeConn();  ?>