<?php
$locArray = $employeeeMaintenanceObj->getUsLocations();
$empStausArray = $employeeeMaintenanceObj->getEmploymentStatuses();



if(isset($_REQUEST['search']) == 'Generate Report')
{
	if (isset($_REQUEST['ddlLocations']))
	{
		$location = addslashes($_REQUEST['ddlLocations']);
	}
	else
	{
		$location = '';
		
	}
	
	if (isset($_REQUEST['txtFirstName']))
	{
		$firstName = addslashes($_REQUEST['txtFirstName']);
	}
	else
	{
		$firstName = '';	
	}
	
	if (isset($_REQUEST['txtLastName']))
	{
		$lastName = addslashes($_REQUEST['txtLastName']);
	}
	else
	{
		$lastName = '';	
	}
	if (isset($_REQUEST['txtEmployeeID']))
	{
		$employeeIDADPC = addslashes($_REQUEST['txtEmployeeID']);
	}
	else
	{
		$employeeIDADPC = '';	
	}
	
	
	
	//Main Qry
	foreach($locArray as $locArrayK=>$locArrayV)
	{
		$usaLocs .= $locArrayV['location'].',';
	}
	//echo substr($usaLocs,0,-1);exit;
	
	$accessedLocations = substr($usaLocs,0,-1);
	//$sqlMainQry = "EXEC  RNet.dbo.[report_spSearchPayrollEmployees]   '$location','$firstName','$lastName','$employeeID','','$avayaID','','$employmentStatus', '$accessedLocations', '$RestrctEmpId' ";
	/* $sqlMainQry = " EXEC  RNet.dbo.[report_spSearchManageEmployees]   '$location','$firstName','$lastName','$employeeID','','','$avayaID','','$employmentStatus', '$accessedLocations' ";*/
	
	$sqlMainQry = " EXEC  RNet.dbo.[report_spSearchManageEmployeesADPPayrollUS]   '$location','$firstName','$lastName','$employeeIDADPC','','','','','', '$accessedLocations' ";
		
	
	//echo $sqlMainQry;
	$rstMainQry = $employeeeMaintenanceObj->ExecuteQuery($sqlMainQry);
	$rowsMainQryNum = mssql_num_rows($rstMainQry);
	if($rowsMainQryNum>=1)
	{
		$mainArray = $employeeeMaintenanceObj->bindingInToArray($rstMainQry);
	}
	//END of Main Qry
	mssql_free_result($rstMainQry);
	unset($rowsMainQryNum);

unset($sqlQuery);
unset($resultsSet);
unset($adpcCount);
unset($existedQueEmployes);
unset($existedQueEmployesKeyValuePair);

$sqlQuery = " SELECT 
                  employeeID 
                FROM 
					rnet.dbo.logADPNewHires a WITH (NOLOCK)  
				WHERE 
					isSentToADP <> 'Y' ";

$resultsSet = $employeeeMaintenanceObj->ExecuteQuery($sqlQuery);
$adpcCount = mssql_num_rows($resultsSet);
	if($adpcCount>=1)
	{
		$existedQueEmployes = $employeeeMaintenanceObj->bindingInToArray($resultsSet);
	}
$existedQueEmployesKeyValuePair = $employeeeMaintenanceObj->convertArrayKeyValuePair($existedQueEmployes, 'employeeID','employeeID');

/*echo '<pre>';
print_r($existedQueEmployesKeyValuePair);
echo '</pre>';*/

}
?>

<div id="topHeading" class="outer"><?php echo $topLevelHeading;?></div>
<div id="businessRuleHeading" class="outer">Business Rules</div>
<div id="businessRuleContent" class="outer">
<p>&nbsp;

</p>
</div>
<div class="outer" id="emptyDiv"></div>
<div id="formSearchFields" class="outer">
<form method="POST" action="<?php echo $_SERVER['PHP_SELF'];?>" name="form_data" id="searchForm">
<table id="adpsearchTable">
    <tr>
    <th>Location <span style="color: #ff0000">*</span></th>
    <td>
    <select name="ddlLocations"  id="ddlLocations" style="width:auto;">
    <option value="%" <?php if($location == "%") print " selected";?> >All Locations</option>
    <?php
    foreach($locArray as $locArrayK=>$locArrayV)
    {?>
        <option value="<?php echo $locArrayV[location];?>"
        <?php	
        if ($locArrayV[location] == $location) 
        { 
            print "selected";
        }?>
        ><?php echo $locArrayV[description];?></option>
        <?php 
    }?>
    </select></td>
        
        <th>First Name</th>
        <td><input type="text" id="txtFirstName" name="txtFirstName" value="<?php echo $firstName;?>" /></td>
    </tr>
    
      <tr>
        <th>Last Name</th>
        <td><input type="text" id="txtLastName" name="txtLastName" value="<?php echo $lastName;?>" /></td>
        
        <th>Employee ID</th>
        <td><input type="text" id="txtEmployeeID" name="txtEmployeeID" value="<?php echo $employeeIDADPC; ?>" onkeypress="return onlyNumbers();"  /></td>
      </tr>
      
      
      
      
      <tr>
        <td>&nbsp;</td>
        <td style="text-align: left;" colspan="3">
        <input type="hidden" name="adpMode" id="adpMode" value="<?php echo $adpMode;?>"/>
		<input type="hidden" name="adpTask" id="adpTask" value="<?php echo $adpTask;?>"/>
        <input class="WSGInputButton" type="submit" name="search" value="Generate Report" />
        </td>
      </tr>
      
</table>

</form>

</div>
<div class="outer" id="emptyDiv"></div>
<div class="scrollingdatagrid" id="scrollingdatagrid" visible="true">
<table width="100%" border="0" align="left" cellpadding="0" bgcolor="#FFFFFF" cellspacing="0" class="report table-autosort table-stripeclass:alternate"> 
<thead>
<tr>
	<th class="locked" align="center"><strong>Actions</strong></th>
    <th class="table-sortable:numeric locked" align="center"><strong>Employee ID</strong></th>
    <th class="table-sortable:alphanumeric" align="center"><strong>FirstName</strong></th>
    <th class="table-sortable:alphanumeric" align="center"><strong>LastName </strong></th>
    <th class="table-sortable:alphanumeric" align="center"><strong>Location</strong></th>
    <th class="table-sortable:alphanumeric" align="center"><strong>AvayaID</strong></th>
    <th class="table-sortable:alphanumeric" align="center"><strong>Status</strong></th>
</tr>
</thead>
<tbody>
<?php 
$i=0;
foreach($mainArray as $mainArrayK=>$mainArrayV)
{
	$employID = $mainArrayV[employeeID];
	$opID = $mainArrayV[avayaIDs];
	$rnetFirstName = $mainArrayV[firstName];
	$rnetLastName = $mainArrayV[lastName];
	$locDescription = $mainArrayV[locationDescription]; 
	$locationID = $mainArrayV[location];
	if($employmentStatus == 2)
	{
		$empStatus = 'TERMINATED';
	}
	else
	{
		$empStatus = $mainArrayV[employmentStatusDescriptionEnd];
	}
	  
	if($i % 2) 
	{ //this means if there is a remainder 
		echo "<tr bgcolor=\"#D0D8E8\">"; 
	} else 
	{ //if there isn't a remainder we will do the else 
		echo "<tr bgcolor=\"#E9EDF4\">"; 
	}?>

   <td class="locked" style="text-align:center;">
   <?php
   if(in_array($employID , $existedQueEmployesKeyValuePair))
   {
	echo '<font color="#FF0000">In Queue for ADPC*</font>';  
   }else
   {
   ?>
   <div id='dv<?=$employID;?>'>
   <a href="#" onclick="return pushToADPC('<?php echo $employID;?>','dv<?php echo $employID?>');">Push to ADPC</a>
   </div>
   <?php } ?>
   </td>
    <td class="locked" style="text-align:right;"><?php echo $employID;?></td>
    <td style="text-align:left;"><?php echo $rnetFirstName;?></td>
    <td style="text-align:left;"><?php echo $rnetLastName;?></td>
    <td style="text-align:left;"><?php echo $locDescription;?></td>
    <td style="text-align:right;"><?php if(!empty($opID)) { echo $opID; } else { echo '&nbsp;';}?></td>	    
    <td style="text-align:left;"><?php if(!empty($empStatus)) { echo $empStatus; } else { echo '&nbsp;';}?></td>
    
</tr>
    
<?php
//finish the while loop
$i++;
}
?>
</tbody>
</table>
</div>