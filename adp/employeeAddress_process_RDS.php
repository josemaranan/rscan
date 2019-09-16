<?php
//include_once($_SERVER["DOCUMENT_ROOT"].'/Include/logADPPayrollChangesClass.inc.php');
include_once($_SERVER['DOCUMENT_ROOT']."/RNetIncludes/class1/logADPPayrollChangesClass.inc.php");
$employeeeMaintenanceObj = new ADPPayroll();

$employeeID = $_REQUEST['hdnEmployeeID'];
$txtEffecDate = $_REQUEST['txtEffecDate'];

$requiredArray = $employeeeMaintenanceObj->updateLogFiles($_REQUEST, $employeeID, $txtEffecDate);

$txtAddress1 = addslashes($_REQUEST['txtAddress1']);	
$txtAddress2 = addslashes($_REQUEST['txtAddress2']);	
$txtCity = addslashes($_REQUEST['txtCity']);	
$ddlState = addslashes($_REQUEST['ddlState']);	
$txtzip = addslashes($_REQUEST['txtzip']);
$txtOtherAddress1 = addslashes($_REQUEST['txtOthAddress1']);	
$txtOtherAddress2 = addslashes($_REQUEST['txtOthAddress2']);	
$txtOtherCity = addslashes($_REQUEST['txtOthCity']);	
$ddlOtherState = addslashes($_REQUEST['ddlOthState']);	
$txtOtherzip = addslashes($_REQUEST['txtOthzip']);
$modBy = $_SESSION['empID'];
$modDate = date('m/d/Y');

$homePhone = addslashes($_REQUEST['txtPerHomePhone']); 
$mobilePhone = addslashes($_REQUEST['txtPerMobilePhone']); 
$email = addslashes($_REQUEST['txtPerEmail']); 

$hdnHomePhone = $_REQUEST['hdnHomePhone'];
$hdnMobilePhone = $_REQUEST['hdnMobilePhone'];
$hdnEmailAdd = $_REQUEST['hdnEmailAddress'];

$sqlHomeAddr = " SELECT COUNT(*) AS homeAddrCount FROM ctlEmployeeAddresses WITH (NOLOCK) WHERE addressType = 'home' AND employeeID = '$employeeID' ";
$rstHomeAddr = $employeeeMaintenanceObj->execute($sqlHomeAddr);
if($rowHomeAddr = mssql_fetch_array($rstHomeAddr))
{
	$homeAddrCount = $rowHomeAddr['homeAddrCount'];
}
mssql_free_result($rstHomeAddr);


$sqlOtherAddr = " SELECT COUNT(*) AS otherAddrCount FROM ctlEmployeeAddresses WITH (NOLOCK) WHERE addressType = 'other' AND employeeID = '$employeeID' ";
$rstOtherAddr = $employeeeMaintenanceObj->execute($sqlOtherAddr);
if($rowOtherAddr = mssql_fetch_array($rstOtherAddr))
{
	$otherAddrCount = $rowOtherAddr['otherAddrCount'];
}
mssql_free_result($rstOtherAddr);

if($homeAddrCount>0)
{
	$updateEmpAddQry = "UPDATE 
							ctlEmployeeAddresses  
						SET 
							street1 = '".$txtAddress1."', 
							street2 = '".$txtAddress2."',
							city = '".$txtCity."', 
							state = '".$ddlState."',
							zip = '".$txtzip."',
							country = 'U',
							effectiveDate = '".$txtEffecDate."',
							modifiedBy = '$modBy',
							modifiedDate = '".$modDate."'
						WHERE 
							employeeID= '".$employeeID."' 
						AND
							addressType='home'";
	//echo $updateEmpAddQry;exit;
	$rstEmpAddress = $employeeeMaintenanceObj->execute($updateEmpAddQry);
	mssql_free_result($rstEmpAddress);
}
else if($homeAddrCount==0)
{
	$insEmpHomeQry = "	INSERT INTO ctlEmployeeAddresses
						(
							employeeID,
							addressType,
							street1,
							street2,
							city,
							state,
							zip,
							country,
							effectiveDate,
							modifiedBy,
							modifiedDate
						)
						VALUES
						(
							'".$employeeID."',
							'home',
							'".$txtAddress1."',
							'".$txtAddress2."',
							'".$txtCity."', 
							'".$ddlState."',
							'".$txtzip."',
							 'U',
							'".$txtEffecDate."',
							'$modBy',
							'".$modDate."'
						) ";
	//echo $insEmpHomeQry;exit;						
	$rstInsEmpHomeQry = $employeeeMaintenanceObj->execute($insEmpHomeQry);
	mssql_free_result($rstInsEmpHomeQry);
}

if($otherAddrCount>0 && !empty($txtOtherAddress1))
{
	$updateEmpOtherAddQry = "UPDATE 
								ctlEmployeeAddresses  
							SET 
								street1 = '".$txtOtherAddress1."', 
								street2 = '".$txtOtherAddress2."',
								city = '".$txtOtherCity."', 
								state = '".$ddlOtherState."',
								zip = '".$txtOtherzip."',
								country = 'U',
								effectiveDate = '".$txtEffecDate."',
								modifiedBy = '$modBy',
								modifiedDate = '".$modDate."'
							WHERE 
								employeeID= '".$employeeID."' 
							AND
								addressType='other'";
	$rstEmpOtherAddress = $employeeeMaintenanceObj->execute($updateEmpOtherAddQry);
	mssql_free_result($rstEmpOtherAddress);
}
else if($otherAddrCount==0 && !empty($txtOtherAddress1))
{
	$insEmpOtherQry = "	INSERT INTO ctlEmployeeAddresses
						(
							employeeID,
							addressType,
							street1,
							street2,
							city,
							state,
							zip,
							country,
							effectiveDate,
							modifiedBy,
							modifiedDate
						)
						VALUES
						(
							'".$employeeID."',
							'other',
							'".$txtOtherAddress1."',
							'".$txtOtherAddress2."',
							'".$txtOtherCity."', 
							'".$ddlOtherState."',
							'".$txtOtherzip."',
							'U',
							'".$txtEffecDate."',
							'$modBy',
							'".$modDate."'
						) ";
	//echo $insEmpOtherQry;exit;						
	$rstInsEmpOtherQry = $employeeeMaintenanceObj->execute($insEmpOtherQry);
	mssql_free_result($rstInsEmpOtherQry);						
}

////////////////////////////////////////////Personal Email///////////////////////////////////
if(!empty($hdnEmailAdd) && !empty($email))
{
	$updEmail = "	UPDATE 
						ctlEmployeeEmailAddresses
					SET
						emailAddress = '".$email."',
						effectiveDate = '".$txtEffecDate."',
						modifiedBy = '".$modBy."',
						modifiedDate = '".$modDate."'
					WHERE 
						employeeID = $employeeID
					AND
						emailAddressType = 'personal'
					AND
						emailAddress = '".$hdnEmailAdd."' ";
	//echo $updEmail;exit;						
	$employeeeMaintenanceObj->execute($updEmail);							
}
else if(empty($hdnEmailAdd) && !empty($email))
{
	$insEmail = " 	INSERT INTO ctlEmployeeEmailAddresses
					(
						employeeID,
						emailAddressType,
						emailAddress,
						effectiveDate,
						modifiedBy,
						modifiedDate
					)
					VALUES
					(
						$employeeID,
						'personal',
						'".$email."',
						'".$txtEffecDate."',
						'".$modBy."',
						'".$modDate."'
					)";
	//echo $insEmail;exit;					
	$employeeeMaintenanceObj->execute($insEmail);						
}
else if(!empty($hdnEmailAdd) && empty($email))
{
	$delEmail = " DELETE FROM ctlEmployeeEmailAddresses WHERE employeeID = $employeeID AND emailAddressType = 'personal' AND emailAddress = '".$hdnEmailAdd."'";
	//echo $delEmail;exit;
	$employeeeMaintenanceObj->execute($delEmail);
}
////////////////////////////////////////////END of Personal Email////////////////////////

////////////////////////////////////////////Home Phone///////////////////////////////////
if(!empty($hdnHomePhone) && !empty($homePhone))
{
	$updHomePhone = "	UPDATE 
							ctlEmployeePhones
						SET
							phone = '".$homePhone."',
							effectiveDate = '".$txtEffecDate."',
							modifiedBy = '".$modBy."',
							modifiedDate = '".$modDate."'
						WHERE 
							employeeID = $employeeID
						AND
							phoneType = 'home'
						AND
							phone = '".$hdnHomePhone."' ";
	//echo $updHomePhone;exit;							
	$employeeeMaintenanceObj->execute($updHomePhone);
}
else if(empty($hdnHomePhone) && !empty($homePhone))
{
	$insHomePhone = " 	INSERT INTO ctlEmployeePhones
						(
							employeeID,
							phoneType,
							phone,
							effectiveDate,
							modifiedBy,
							modifiedDate
						)
						VALUES
						(
							$employeeID,
							'home',
							'".$homePhone."',
							'".$txtEffecDate."',
							'".$modBy."',
							'".$modDate."'
						)";
	//echo $insHomePhone;exit;							
	$employeeeMaintenanceObj->execute($insHomePhone);						
}
else if(!empty($hdnHomePhone) && empty($homePhone))
{
	$delPhones = " DELETE FROM ctlEmployeePhones WHERE employeeID = $employeeID AND phoneType = 'home' AND phone = '".$hdnHomePhone."'";
	//echo $delPhones;exit;	
	$employeeeMaintenanceObj->execute($delPhones);
}
////////////////////////////////////////////END of Home Phone//////////////////////////////

////////////////////////////////////////////Mobile Phone///////////////////////////////////
if(!empty($hdnMobilePhone) && !empty($mobilePhone))
{
	$updMobilePhone = "	UPDATE 
							ctlEmployeePhones
						SET
							phone = '".$mobilePhone."',
							effectiveDate = '".$txtEffecDate."',
							modifiedBy = '".$modBy."',
							modifiedDate = '".$modDate."'
						WHERE 
							employeeID = $employeeID
						AND
							phoneType = 'mobile'
						AND
							phone = '".$hdnMobilePhone."' ";
	//echo $updMobilePhone;exit;								
	$employeeeMaintenanceObj->execute($updMobilePhone);							
}
else if(empty($hdnMobilePhone) && !empty($mobilePhone))
{
	$insMobilePhone = " 	INSERT INTO ctlEmployeePhones
						(
							employeeID,
							phoneType,
							phone,
							effectiveDate,
							modifiedBy,
							modifiedDate
						)
						VALUES
						(
							$employeeID,
							'mobile',
							'".$mobilePhone."',
							'".$txtEffecDate."',
							'".$modBy."',
							'".$modDate."'
						)";
	//echo $insMobilePhone;exit;						
	$employeeeMaintenanceObj->execute($insMobilePhone);						
}
else if(!empty($hdnMobilePhone) && empty($mobilePhone))
{
	$delMobPhones = " DELETE FROM ctlEmployeePhones WHERE employeeID = $employeeID AND phoneType = 'mobile' AND phone = '".$hdnMobilePhone."'";
	//echo $delMobPhones;exit;		
	$employeeeMaintenanceObj->execute($delMobPhones);
}
////////////////////////////////////////////END of Mobile Phone/////////////////////////////

/*if($rstEmpAddress || $rstInsEmpHomeQry)
{*/
	mssql_close();
	//header("Location: employeeAuthentication.php?employeeID=$employeeID");	
	//exit;
if(isset($_REQUEST['fromPage']) && $_REQUEST['fromPage'] == 'myRnet')
{
		return true;
}
else
{
	header("Location: index_test.php?hdnEmployeeID=".$employeeID."&adpMode=emp&adpTask=empAddress&error=ContactInfoSucess&activeLink=20");
	exit;
}
//}


	
?>		
