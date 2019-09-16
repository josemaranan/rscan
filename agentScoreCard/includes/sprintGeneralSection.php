<div id = "General">
		<div id = "generalHeading">General</div>
        <div id = "generalBodyContent">
        <table border="0"  bgcolor="#FFFFFF" cellspacing="0" class="report" width="99%;">
        <thead>
          <tr>
            
            <th align="center"><strong>Date Range</strong></th>
            <th align="center" colspan="2"><strong>AHT</strong></th>
            <th align="center" colspan="2"><strong>Production Hours</strong></th>
            <th align="center" colspan="2"><strong>Schedule Adherence</strong></th>
            <th align="center" colspan="2"><strong>PIP <br />Level</strong></th>
            <th align="center" colspan="2"><strong>Unread <br />Notifications</strong></th>
             <th align="center"><strong>Calls <br />Handled</strong></th>
            <th align="center"><strong>Agent <br />Lifecycle</strong></th>
            
          </tr>
        </thead>
        
        <tbody>
        <?php
		if(!empty($topLevelGeneralData))
		{
			$g = 0;
			
			foreach($topLevelGeneralData as $topLevelGeneralDataVal)
			{
					if($g!=0 && $g%2==0)
					{
						$g=0;	
					}
					
		?>
			<?php echo $trStryleArray[$g]; ?>
                <td style="text-align:left;"><?php 
						if(array_key_exists($topLevelGeneralDataVal['Period'], $weektoDate))
						{
							echo $weektoDate[$topLevelGeneralDataVal['Period']];
						}
						else
						{
							echo $topLevelGeneralDataVal['Period']; 
						}
					?></td>
               
                <td><?php echo $topLevelGeneralDataVal['AHT'];?></td>
                <td style="text-align:center;"><img src="<?php echo $indicatorArray[$g][$topLevelGeneralDataVal['ahtIndicator']];?>" width="20" height="20" ></td>
                
                <td><?php echo $topLevelGeneralDataVal['ProductionHours'];?></td>
                <td style="text-align:center;"><img src="<?php echo $indicatorArray[$g][$topLevelGeneralDataVal['ProdHoursGoalIndicator']];?>" width="20" height="20" ></td>


				<td><?php echo $topLevelGeneralDataVal['ScheduleAdherence'];?></td>
                <td style="text-align:center;"><img src="<?php echo $indicatorArray[$g][$topLevelGeneralDataVal['SchdAdhrIndicator']];?>" width="20" height="20" ></td>
                
               
                <td><?php echo $topLevelGeneralDataVal['PIPLevel'];?></td>
                <td style="text-align:center;"><img src="<?php echo $indicatorArray[$g][$topLevelGeneralDataVal['PIPLevelIndicator']];?>" width="20" height="20" ></td>
                
                <td><?php echo $topLevelGeneralDataVal['Notifications'];?></td>
               
                <td style="text-align:center;"><?php 
						if($topLevelGeneralDataVal['Notifications']>0)
						{?>
							<a href="https://<?php echo $_SERVER['HTTP_HOST'];?>/Clients/Results/viewReviewMessagesPage.php"><img src="<?php echo $notificationIndicator[$g];?>"	 width="30" height="20" border="0" /></a>
						<?php } else {
							echo '&nbsp;';	
						}
				?></td>
                
                <td style="text-align:center;"> <? echo $topLevelGeneralDataVal['CallsHandled']; ?></td>
                <td style="text-align:center;"><a href="scoreCardLifeCycle.php">View</a></td>
                

        	</tr>
        
        <?php 
			$g++;
			} // for
		} else { ?>
        
        	<tr><td colspan="10" style="text-align:center;">No data found.</td></tr>
        <?php } ?>
        </tbody>
        </table>
        </div>
        
    </div>