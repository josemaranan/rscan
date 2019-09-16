<?
include_once($_SERVER['DOCUMENT_ROOT']."/agentScoreCard/includes/agentScoreCard.class.inc.php");
$agentScoreObj2 = new agentScoreCard();


$agentScoreObj2->setComCastInboundData($employeeID , $requestedDate);
$inboundData = $agentScoreObj2->getComCastInboundData();



$indicatorArray = array('0'=>array('G'=>'includes/images/SmallGreen_blue.jpg', 'Y'=>'includes/images/SmallYellow_blue.jpg', 'R'=>'includes/images/SmallRed_blue.jpg', 'X'=>'includes/images/white_ball_blue.jpg'),'1'=>array('G'=>'includes/images/SmallGreen_grey.jpg', 'Y'=>'includes/images/SmallYellow_grey.jpg', 'R'=>'includes/images/SmallRed_grey.jpg', 'X'=>'includes/images/white_ball_grey.jpg'));

?>

<?php 
//if(empty($inboundData))
//{?>
<div class="section">
		<div class="sectionHeading">Internal Call Guide Metrics</div>
        <div class="sectionContent">
        <table border="0"  bgcolor="#FFFFFF" cellspacing="0" class="report">
        <thead>
          <tr>
            
            <th align="center"><strong>&nbsp;</strong></th>
            <th align="center"><strong>Calls <br>Handled</strong></th>
            <th align="center" colspan="2"><strong>FCR</strong></th>
            <th align="center" colspan="2"><strong>VOC</strong></th>
            <th align="center" colspan="2"><strong>AHT</strong></th>
            <th align="center"><strong>RGU <br>Attempt%</strong></th>
            <th align="center" colspan="2"><strong>TSR</strong></th>
            <th align="center" colspan="2"><strong>Transfer%</strong></th>
            <th align="center" colspan="2"><strong>Truck Roll</strong></th>
            <th align="center"><strong>Avg Credit <br>Adjustments</strong></th>
            <th align="center"><strong>Total Credit <br>Adjustments</strong></th>
            
            
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
						$folder = '';
					}	
					else
					{
						$folder = '';
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
                   
                <td>
                <?php echo $inboundDataVal['Calls Handled'];?>
                </td>
				

                <td><?php echo $inboundDataVal['FCR'];?></td>
                <td style="text-align:center;"><img src="<?php echo $indicatorArray[$i][$inboundDataVal['FCR Indicator']];?>" width="20" height="20" ></td>

               <td><?php echo $inboundDataVal['CSAT'];?></td>
                <td style="text-align:center;"><img src="<?php echo $indicatorArray[$i][$inboundDataVal['CSAT Indicator']];?>" width="20" height="20" ></td>


				<td><?php echo $inboundDataVal['AHT'];?></td>
                <td style="text-align:center;"><img src="<?php echo $indicatorArray[$i][$inboundDataVal['AHT Indicator']];?>" width="20" height="20" ></td>

                <td><?php echo $inboundDataVal['rguAttemptPercentage'];?></td>


                <td><?php echo $inboundDataVal['rguConversion'];?></td>
   <td style="text-align:center;"><img src="<?php echo $indicatorArray[$i][$inboundDataVal['rguConversion Indicator']];?>" width="20" height="20" ></td>                
                
                
               <td><?php echo $inboundDataVal['transferPercentage'];?></td>
                <td style="text-align:center;"><img src="<?php echo $indicatorArray[$i][$inboundDataVal['transferPercentage Indicator']];?>" width="20" height="20" ></td>
               



                <td><?php echo $inboundDataVal['truckRoll'];?></td>
				<td><?php echo $inboundDataVal['truckRollPrecentage'];?></td>



				<td><?php echo $inboundDataVal['averageCreditAdjustments'];?></td>				
				<td><?php echo $inboundDataVal['totalCreditAdjustments'];?></td>

                




                
				<?php 
				$i++;
				} // for
				
					
			?>
        
        
        </tbody>
        </table>
        </div>
        
    </div>
<?php 
$agentScoreObj2->closeConn(); 
			//} ?>
