<?php
//$employeeeMaintenanceObj->setUSLocations();
//ini_set('display_errors', '1'):
unset($sqlMainQry);
unset($rstMainQry);

$sqlPersonalInfo = "SELECT 
							a.firstName,
							a.lastName,
							CONVERT(VARCHAR(20),a.dob,101) dob,
							a.gender,
							a.middle,
							common.dbo.fn_rnetV3_Decrypt(a.secureSSN,'".DBMS_PASSWORD."') [secureSSN],
							a.ethnicity,
							a.maritalStatus,
							a.educationLevel,
							a.citizenshipStatus,
							a.visaType,
							e.description as educationLevelDescription
						FROM 
							results.dbo.ctlEmployees a WITH (NOLOCK) 
						LEFT JOIN
							results.dbo.ctlEducationLevelDetails e WITH (NOLOCK)
						ON
							a.educationLevel = e.educationLevelID
						WHERE 
							a.employeeID = '$employeeID' ";

	//echo $sqlPersonalInfo;
	//exit();
	$rstPersonalInfo = $employeeeMaintenanceObj->execute($sqlPersonalInfo);	
	
	//echo 'here';
	//exit();
	
	
	while($rowPersonalInfo = mssql_fetch_assoc($rstPersonalInfo))
	{	
		$employeePersonalInfo[] = $rowPersonalInfo;
	}
	mssql_free_result($rstPersonalInfo);
	
	//RACE QRY
	$sqlRaceEthnicity = " SELECT * FROM ctlRaceEthnicityDetails WITH (NOLOCK) ORDER BY description ";
	$rstRaceEthnicity = $employeeeMaintenanceObj->execute($sqlRaceEthnicity);
	$rowsRaceEthnicityNum = mssql_num_rows($rstRaceEthnicity);
	if($rowsRaceEthnicityNum>=1)
	{
		$raceEthnicityArray = $employeeeMaintenanceObj->bindingInToArray($rstRaceEthnicity);
	}
	mssql_free_result($rstRaceEthnicity);
	
	//EDUCATION LEVELS QRY
	$sqlEducationLevels = " SELECT * FROM ctlEducationLevelDetails WITH (NOLOCK)  ORDER BY description ";
	$rstEducationLevels = $employeeeMaintenanceObj->execute($sqlEducationLevels);
	$rowsEducationLevelsNum = mssql_num_rows($rstEducationLevels);
	if($rowsEducationLevelsNum>=1)
	{
		$educationLevelArray = $employeeeMaintenanceObj->bindingInToArray($rstEducationLevels);
	}
	mssql_free_result($rstEducationLevels);

	//CITIZENSHIP QRY
	$sqlCitizenShip = " SELECT * FROM ctlCitizenShipStatuses WITH (NOLOCK) ORDER BY [description] ";
	$rstCitizenShip = $employeeeMaintenanceObj->execute($sqlCitizenShip);
	$rowsCitizenShipNum = mssql_num_rows($rstCitizenShip);
	if($rowsCitizenShipNum>=1)
	{
		$citizenShipArray = $employeeeMaintenanceObj->bindingInToArray($rstCitizenShip);
	}
	mssql_free_result($rstCitizenShip);
	
	//VISATYPE QRY
	$sqlVisaType = " SELECT * FROM ctlVisaTypes WITH (NOLOCK) ORDER BY [description] ";
	$rstVisaType = $employeeeMaintenanceObj->execute($sqlVisaType);
	$rowsVisaTypeNum = mssql_num_rows($rstVisaType);
	if($rowsVisaTypeNum>=1)
	{
		$visaTypeArray = $employeeeMaintenanceObj->bindingInToArray($rstVisaType);
	}
	mssql_free_result($rstVisaType);
	
	//MARITALSTATUS QRY
	$sqlMaritalStatus = " SELECT * FROM ctlMaritalStatuses WITH (NOLOCK) ORDER BY [description] ";
	$rstMaritalStatus = $employeeeMaintenanceObj->execute($sqlMaritalStatus);
	$rowsMaritalStatusNum = mssql_num_rows($rstMaritalStatus);
	if($rowsMaritalStatusNum>=1)
	{
		$maritalStatusArray = $employeeeMaintenanceObj->bindingInToArray($rstMaritalStatus);
	}
	mssql_free_result($rstMaritalStatus);
	
	
	unset($_SESSION['emloyeeAuthReveal']);
	$_SESSION['emloyeeAuthReveal'] = $employeePersonalInfo[0]['secureSSN'];
	
?>
<style type="text/css">
#adpsearchTable th{
	padding-left:50px;
}

</style>
<div id="topHeading" class="outer"><?php echo $topLevelHeading;?></div>
<div id="businessRuleHeading" class="outer">Modify Personal Information</div>
<div class="outer" id="emptyDiv"></div>
<?php $employeeeMaintenanceObj->getTopLevelEmployeeInfo(); ?>
<div id="topHeading">Modify Personal Information</div>
<div class="outer" id="emptyDiv"></div>
<div class="scrollingdatagrid" id="scrollingdatagrid" visible="true">
    <div id="singlePixelBorder" style="padding:8px;">
<form action="employeePersonalInfo_process.php" name="employeePersonalinfo" id="searchForm" method="post">
    
    <div class="empAddressmainTable" style="width:95%;">
          
        <table id="adpsearchTable" cellspacing="2">
        <tr>
        <td>&nbsp;</td>
        <td>&nbsp;  
        </td>
        
        <th>Effective Date</th>
        <td><input name="txtPerEffecDate" type="text" id="txtPerEffecDate" readonly="readonly" value="<?php echo date('m/d/Y')?>" style="width:75px;"/>
        <img id="imgEffecDate" alt="Choose Date" onclick=        "javascript:displayCalendar(document.getElementById('txtPerEffecDate'),'mm/dd/yyyy',document.getElementById('imgEffecDate'))" 
        src="https://<?php echo $_SERVER['HTTP_HOST']?>/Include/images/calendar.gif" style="border-top-width: 0px; 
        border-left-width: 0px; border-bottom-width: 0px; border-right-width: 0px; " /></td>
        </tr>
        
    	<tr>
        <th>First Name&nbsp;</th>
        <td><input type="text" name="txtPerFirstName" id="txtPerFirstName" value="<?php echo stripslashes($employeePersonalInfo[0]['firstName']); ?>" readonly="readonly" onfocus="MoveFocus('ddlPerMaritalStatus'); return false;" />
        <?php $employeeeMaintenanceObj->gethiddenValues('txtPerFirstName', stripslashes($employeePersonalInfo[0]['firstName']) ,'results', 'ctlEmployees', 'firstName' , 'ctlEmployees#firstName');?>
        </td>
        
		<th>Middle Name/Initial&nbsp;</th>
        <td><input type="text" name="txtPerMiddleName" id="txtPerMiddleName" value="<?php echo stripslashes($employeePersonalInfo[0]['middle']); ?>" readonly="readonly" onfocus="MoveFocus('ddlPerMaritalStatus'); return false;" />
        <?php $employeeeMaintenanceObj->gethiddenValues('txtPerMiddleName', stripslashes($employeePersonalInfo[0]['middle']) ,'results', 'ctlEmployees', 'middle' , 'ctlEmployees#middle');?>
        </td>
    </tr>
    
    <tr>
    	<th>Last Name&nbsp;</th>
        <td><input type="text" name="txtPerLastName" id="txtPerLastName" value="<?php echo stripslashes($employeePersonalInfo[0]['lastName']); ?>" readonly="readonly" onfocus="MoveFocus('ddlPerMaritalStatus'); return false;" />
        <?php $employeeeMaintenanceObj->gethiddenValues('txtPerLastName', stripslashes($employeePersonalInfo[0]['lastName']) ,'results', 'ctlEmployees', 'lastName' , 'ctlEmployees#lastName');?>
        </td>
        
        <th>Date of Birth&nbsp;</th>
        <td><input name="txtPerDOB" type="text" id="txtPerDOB" style="width:75px" value="<?php if(!empty($employeePersonalInfo[0]['dob'])) echo $employeePersonalInfo[0]['dob']; ?>" readonly="readonly" onfocus="MoveFocus('ddlPerMaritalStatus'); return false;" />
        
        <img id="imgDob" alt="Choose Date" onclick=        "javascript:displayCalendar(document.getElementById('txtPerDOB'),'mm/dd/yyyy',document.getElementById('imgDob'))" 
        src="https://<?php echo $_SERVER['HTTP_HOST']?>/Include/images/calendar.gif" style="border-top-width: 0px; 
        border-left-width: 0px; border-bottom-width: 0px; border-right-width: 0px; " />
        
        <?php $employeeeMaintenanceObj->gethiddenValues('txtPerDOB', stripslashes($employeePersonalInfo[0]['dob']) ,'results', 'ctlEmployees', 'dob' , 'ctlEmployees#DOB');?>
          </td>
        
	</tr> 
    
    <tr>
        <th>Marital Status&nbsp;<span style="color:#F00;">*</span></th>
        <td>
        <select name="ddlPerMaritalStatus" id="ddlPerMaritalStatus">
        <option value="">Please choose</option>
        <?php
		foreach($maritalStatusArray as $maritalStatusArrayK=>$maritalStatusArrayV)
		{?>
			<option value="<?php echo $maritalStatusArrayV['maritalStatus'];?>"><?php echo $maritalStatusArrayV['description'];?></option>
		 <?php 
		}?>
        </select>
        <?php $employeeeMaintenanceObj->gethiddenValues('ddlPerMaritalStatus', stripslashes($employeePersonalInfo[0]['maritalStatus']) ,'results', 'ctlEmployees', 'maritalStatus' , 'ctlEmployees#maritalStatus');?>
        <script type="text/javascript">
            document.getElementById('ddlPerMaritalStatus').value='<?php echo $employeePersonalInfo[0]['maritalStatus']; ?>';
        </script>
        </td>
        
		<th>Race&nbsp;<span style="color:#F00;">*</span></th>
        <td>
        <select name="ddlPerRaceEthnicity" id="ddlPerRaceEthnicity" style="width:auto;">
        <option value="">Please choose</option>
        <?php
		foreach($raceEthnicityArray as $raceEthnicityArrayK=>$raceEthnicityArrayV)
		{?>
			<option value="<?php echo $raceEthnicityArrayV['raceEthnicityID'];?>"><?php echo $raceEthnicityArrayV['description'];?></option>
		 <?php 
		}?>
        </select>
        <?php $employeeeMaintenanceObj->gethiddenValues('ddlPerRaceEthnicity', stripslashes($employeePersonalInfo[0]['ethnicity']) ,'results', 'ctlEmployees', 'ethnicity' , 'ctlEmployees#ethnicity');?>
        <script type="text/javascript">
            document.getElementById('ddlPerRaceEthnicity').value='<?php echo $employeePersonalInfo[0]['ethnicity']; ?>';
        </script>
        </td>
        </tr>
        
        <tr>
        
    	<th>Highest Education Level&nbsp;<span style="color:#F00;">*</span></th>
        <td>
        <select name="ddlPerHigestEducation" id="ddlPerHigestEducation" style="width:auto;">
        <option value="">Please choose</option>
        <?php
		foreach($educationLevelArray as $educationLevelArrayK=>$educationLevelArrayV)
		{?>
			<option value="<?php echo $educationLevelArrayV['educationLevelID'];?>"><?php echo $educationLevelArrayV['description'];?></option>
		 <?php 
		}?>
        </select>
        <?php $employeeeMaintenanceObj->gethiddenValues('ddlPerHigestEducation', stripslashes($employeePersonalInfo[0]['educationLevel']) ,'results', 'ctlEmployees', 'educationLevel' , 'ctlEmployees#educationLevel');?>
        <script type="text/javascript">
            document.getElementById('ddlPerHigestEducation').value='<?php echo $employeePersonalInfo[0]['educationLevel']; ?>';
        </script>
		</td>
       
       
		<th>Gender&nbsp;<span style="color:#F00;">*</span></th>
        <td>
        <select name="ddlPerGender" id="ddlPerGender">
        <option value="">Please choose</option>
        <option value="M">Male</option>
        <option value="F">Female</option>
        </select>
        <?php $employeeeMaintenanceObj->gethiddenValues('ddlPerGender', stripslashes($employeePersonalInfo[0]['gender']) ,'results', 'ctlEmployees', 'gender' , 'ctlEmployees#gender');?>
        <script type="text/javascript">
            document.getElementById('ddlPerGender').value='<?php echo $employeePersonalInfo[0]['gender']; ?>';
        </script>
		</td>
	    </tr>
        
        <tr>    
	    <th>Citizenship Status&nbsp;</th>
        <td>
        <select name="ddlPerCitizenStatus" id="ddlPerCitizenStatus" onchange="setoDefultValue(this.id, '<?php echo $employeePersonalInfo[0]['citizenshipStatus']; ?>'); return false;">
        <option value="">Please choose</option>
        <?php
		foreach($citizenShipArray as $citizenShipArrayK=>$citizenShipArrayV)
		{?>
			<option value="<?php echo $citizenShipArrayV['citizenShipStatus'];?>"><?php echo $citizenShipArrayV['description'];?></option>
		 <?php 
		}?>
        </select>
        <?php $employeeeMaintenanceObj->gethiddenValues('ddlPerCitizenStatus', stripslashes($employeePersonalInfo[0]['citizenshipStatus']) ,'results', 'ctlEmployees', 'citizenshipStatus' , 'ctlEmployees#citizenshipStatus');?>
        <script type="text/javascript">
		document.getElementById('ddlPerCitizenStatus').value='<?php echo $employeePersonalInfo[0]['citizenshipStatus']; ?>';
        </script></td>
       
        <th>Visa Type&nbsp;</th>
        <td>
        <select name="ddlPerVisaType" id="ddlPerVisaType" onchange="setoDefultValue(this.id, '<?php echo $employeePersonalInfo[0]['visaType']; ?>'); return false;">
        <option value="">Please choose</option>
        <?php
		foreach($visaTypeArray as $visaTypeArrayK=>$visaTypeArrayV)
		{?>
			<option value="<?php echo $visaTypeArrayV['visaType'];?>"><?php echo $visaTypeArrayV['description'];?></option>
		 <?php 
		}?>
        </select>
        <?php $employeeeMaintenanceObj->gethiddenValues('ddlPerVisaType', stripslashes($employeePersonalInfo[0]['visaType']) ,'results', 'ctlEmployees', 'visaType' , 'ctlEmployees#visaType');?>
        <script type="text/javascript">
            document.getElementById('ddlPerVisaType').value='<?php echo $employeePersonalInfo[0]['visaType']; ?>';
        </script></td>
		</tr>
        
        <tr>
        <th>Social Security Number&nbsp;</th>
        <td><input type="text" name="txtPerSSNumber" id="txtPerSSNumber" value="<?php if(!empty($employeePersonalInfo[0]['secureSSN'])) echo str_pad(substr($employeePersonalInfo[0]['secureSSN'],5,4),9,'*',STR_PAD_LEFT); ?>" maxlength="9" onkeypress="return onlyNumbers();" onfocus="javaScript:if(this.value=='<?php echo str_pad(substr($employeePersonalInfo[0]['secureSSN'],5,4),9,'*',STR_PAD_LEFT); ?>') this.value='';" onblur="javaScript:if(this.value=='') this.value='<?php if(!empty($employeePersonalInfo[0]['secureSSN'])) echo str_pad(substr($employeePersonalInfo[0]['secureSSN'],5,4),9,'*',STR_PAD_LEFT); ?>'; "  />
        
        <a href="#" onclick="return revealFunction();" id="revealID">Reveal SSN</a>
		
        <input type="hidden" name="hdnPerSSNumber" id="hdnPerSSNumber" value="<?php if(!empty($employeePersonalInfo[0]['secureSSN'])) { echo 'Y'; } else { echo 'N'; }?>"  />
        <input type="hidden" name="hdnLastFourDigits" id="hdnLastFourDigits" value="<?php if(!empty($employeePersonalInfo[0]['secureSSN'])) { echo substr($employeePersonalInfo[0]['secureSSN'],5,4); } ?>"  />
        
        <?php $employeeeMaintenanceObj->gethiddenValues('txtPerSSNumber', str_pad(substr($employeePersonalInfo[0]['secureSSN'],5,4),9,'*',STR_PAD_LEFT) ,'results', 'ctlEmployees', 'secureSSN' , 'ctlEmployees#secureSSN');?>
        </td>
	</tr>
    
    <!--<tr>        
        <td>(Type SSN again to confirm)&nbsp;</td>
        <td colspan="5"><input type="password" name="txtPerAgainSSNumber" id="txtPerAgainSSNumber" value="" maxlength="9" />
        <?php //$employeeeMaintenanceObj->gethiddenValues('txtPerAgainSSNumber', stripslashes($employeePersonalInfo[0]['secureSSN']) ,'results', 'ctlEmployees', 'secureSSN' , 'ctlEmployees#secureSSN');?>
        </td>
	</tr>-->
    
   
    
    
</table>
          
		
        </div>
      
    <div style="text-align:left;">
        <input type="hidden" name="hdnEmployeeID" value="<?php echo $employeeID;?>" />
        <input type="hidden" name="fromPage" value="emp" />
        <input type="Submit" name="Submit" value="Save" onclick="return validatePersonalInfoEmp('<?php echo $curDate;?>');" >
    </div>
    
</form>
</div>
</div>

<script type="text/javascript">
function setoDefultValue(selectid, selectedValue)
{
		document.getElementById(selectid).value=selectedValue;
}

function htmlDataNew(i)
{
	//alert(i);
			   $.post("checkAuthReveal.php",   
			   { 
			   	p:i
			   },   
			   function(data)
			   { 
			   		var result = data.split('||');
					
			   		if(result[0]!='yes')
					{
						//$('#revealSSN').show();
						$('#revealID').hide();
						$('#txtPerSSNumber').val(result[1]);
					}
					else
					{
						$('#revealID').show();
						$('#txtPerSSNumber').val(result[1]);
						//$('#revealSSN').hide();	
					}
			   } 	
); 
		
		return false;
}

function revealFunction()
{
	htmlDataNew('load');
	setTimeout('htmlDataNew(\'next\')', 4000); 
}
</script>