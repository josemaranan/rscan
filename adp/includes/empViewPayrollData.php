<?php
//$employeeeMaintenanceObj->setUSLocations();
$locArray = $employeeeMaintenanceObj->getUsLocations();
$reportinglocArray = $employeeeMaintenanceObj->getUSReportingLocations();
$payGroupLocationArray = $employeeeMaintenanceObj->getUSPayGroupLocations();
$allLocationArray = $employeeeMaintenanceObj->getAllLocations();

$locArrayKeyValue = $employeeeMaintenanceObj->convertArrayKeyValuePair($locArray, 'location','description');
$reportinglocArrayKeyValue = $employeeeMaintenanceObj->convertArrayKeyValuePair($reportinglocArray, 'location','description');
$payGroupLocationArrayKeyValue = $employeeeMaintenanceObj->convertArrayKeyValuePair($payGroupLocationArray, 'location','paygroup');

$allLocationArrayKeyValue = $employeeeMaintenanceObj->convertArrayKeyValuePair($allLocationArray, 'location','description');


//$employData = $employeeeMaintenanceObj->getEmployeeInformation();
//$employADPData = $employeeeMaintenanceObj->getEmployeeADPInformation();


?>
<div id="topHeading" class="outer"><?php echo $topLevelHeading;?></div>
<div id="businessRuleHeading" class="outer">ADP Data</div>
<div class="outer" id="emptyDiv"></div>
<?php $employeeeMaintenanceObj->getTopLevelEmployeeInfo(); ?>
<div class="scrollingdatagrid" id="scrollingdatagrid" visible="true">
<div id="singlePixelBorder" style="padding:8px;">
<div id="topHeading">View Payroll Information</div>

    <table id="adpsearchTable" cellspacing="3">
    <tr>
        <th><strong>Job Code&nbsp;<span style="color:#F00;">*</span></strong></th>
        <td ><?php echo stripslashes($employADPData[0]['adpJobCode']); ?></td>
        
   
    	<th><strong>Pay Group (ADP)&nbsp;<span style="color:#F00;">*</span></strong></th>
        <td>
        <?php 
		echo $payGroupLocationArrayKeyValue[$employData[0]['payrollLocation']];
		?>
		</td>
       </tr>
    
   	 <tr> 
        
        <th><strong>Work Location&nbsp;<span style="color:#F00;">*</span></strong></th>
        <td>
        <?php 
		echo $allLocationArrayKeyValue[$employADPData[0]['location']];
		?>
        
        </td>
	
        <th><strong>Reporting Location&nbsp;<span style="color:#F00;">*</span></strong></th>
        <td>
        <?php 
		echo $reportinglocArrayKeyValue[$employADPData[0]['adpReportingLocation']];
		?>
        
        </td>
         </tr>
    
    <tr>
        <th><strong>File Number&nbsp;<span style="color:#F00;">*</span></strong></th>
        <td><?php echo str_pad($employeeID,6,0,STR_PAD_LEFT);?></td>
	
        <th><strong>Compensation Rate&nbsp;</strong></th>
        <td><?php echo ($employADPData[0]['amount']=='1.00'?'compensation rate not displayed':$employADPData[0]['amount']);?>
        
        
        </td>
       </tr>
    
    <tr>
        <th><strong>Employee Type&nbsp;</strong></th>
        <td>
        <?php
		echo($employData[0]['payType']==1?'Salary (Exempt)':'Hourly (Non-Exempt)');
		?>
               
        </td>    
	
        <th ><strong>Compensation Frequency&nbsp;<span style="color:#F00;">*</span></strong></th>
        <td>Bi-weekly</td>
         </tr>
    
    <tr>
        <th><strong>Worker's Comp Code&nbsp;</strong></th>
        <td><?php echo $employADPData[0]['adpWorkersCompCode'];?></td>    
	
        <th ><strong>EEO Class&nbsp;</strong></th>
        <td ><?php echo $employADPData[0]['EEO1Class'];?></td>
      </tr>
    
    <tr>
       
        <th ><strong>FLSA&nbsp;</strong></th>
        <td ><?php echo $employADPData[0]['FLSASts'];?></td>    
	</tr>

	 
    </table>

 
</div>
</div>
