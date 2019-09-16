<?php 
if($period=='WTD' || $period=='MTD')
{?>
	<td style="text-align:center;">
	<?php
	if($requiredDataVal['LOB']=='Totals')
	{ ?>
			 <input type="button" name="tGraph" value="Graph" onclick="return populateDetails('<?php echo $type;?>', '<?php echo $measure;?>' , '<?php echo $period;?>' , '<?php echo $employeID;?>', '<?php echo $startDate;?>' , '<?php echo $endDate;?>' , 'dataPie' , '' ); return false;" />
	<?php }
	else if ($requiredDataVal['Date']=='Subtotals')
	{ ?>
	
	<input type="button" name="tGraph" value="Graph" onclick="return populateDetails('<?php echo $type;?>', '<?php echo $measure;?>' , '<?php echo $period;?>' , '<?php echo $employeID;?>', '<?php echo $startDate;?>' , '<?php echo $endDate;?>' , 'data', '<?php echo $requiredDataVal['LOB'];?>' ); return false;" />
	
   
		
	<?php } ?>
	</td>
<?php } ?>