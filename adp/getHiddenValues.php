<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/RNetIncludes/lib/RDSData/DbConfig.php');
include_once($_SERVER['DOCUMENT_ROOT']."/RNetIncludes/class1/logADPPayrollChangesClass.inc.php");
//include_once($_SERVER["DOCUMENT_ROOT"].'/Include/logADPPayrollChangesClass.inc.php');
$employeeeMaintenanceObj = new ADPPayroll();

$positionID = $_REQUEST['posID'];
$employeID = $_REQUEST['hdnEmployeID'];
unset($sqlQuery);
unset($resultsSet);

//$sqlQuery = " SELECT department , businessFunction FROM ctlPositions where positionID = ".$positionID." ";

$sqlQuery =  " SELECT 
				a.adpBusinessTitle,
				a.adpFLSAStatus,
				a.adpEEOExclusion,
				b.adpDepartmentNumber,
				a.adpWorkersCompCode,
				a.businessFunction
			FROM
	 			ctlPositions a WITH (nolock) 
			JOIN
				ctlDepartments b WITH (NOLOCK)
			ON
				a.departmentCode = b.departmentCode
			WHERE
				a.positionID = ".$positionID." ";
$resultsSet = $employeeeMaintenanceObj->execute($sqlQuery);
while($rows = mssql_fetch_assoc($resultsSet))
{
		$adpBusinessTitleNew = $rows['adpBusinessTitle'];
		$adpFLSAStatusNew = $rows['adpFLSAStatus'];
		$adpEEOExclusionNew =  $rows['adpEEOExclusion'];
		$adpDepartmentNumberNew =  $rows['adpDepartmentNumber'];
		$adpWorkersCompCodeNew =  $rows['adpWorkersCompCode'];
		$businessFunction1 = $rows['businessFunction'];
		
}



unset($sqlQuery);
unset($resultsSet);

$sqlQuery = " SELECT 
					a.adpBusinessTitle,
					a.adpFLSAStatus,
					a.adpEEOExclusion,
					c.adpDepartmentNumber,
					a.adpWorkersCompCode,
					a.businessFunction
				FROM 
					ctlPositions a WITH (NOLOCK)
				JOIN
					ctlEmployeePositions b WITH (NOLOCK) 
				ON
					a.positionID = b.positionID
				JOIN
					ctlDepartments c WITH (NOLOCK)
				ON
				a.departmentCode = c.departmentCode

				WHERE
						b.isPrimary = 'Y' AND b.employeeID = '".$employeID."'  ";

$resultsSet = $employeeeMaintenanceObj->execute($sqlQuery);
while($rows1 = mssql_fetch_assoc($resultsSet))
{
		$adpBusinessTitleold = $rows1['adpBusinessTitle'];
		$adpFLSAStatusold = $rows1['adpFLSAStatus'];
		$adpEEOExclusionold =  $rows1['adpEEOExclusion'];
		$adpDepartmentNumberold =  $rows1['adpDepartmentNumber'];
		$adpWorkersCompCodeold =  $rows1['adpWorkersCompCode'];
		$businessFunction = $rows1['businessFunction'];
		
		
}


//$employeeeMaintenanceObj->gethiddenValues('ddlDepartment', $department ,'results', 'ctlPositions', 'department' , 'ctlPositions#department');
?>
<!--<input type="hidden" name="ddlbusiness<?php //echo $positionID;?>" value="<?php //echo $businessFunction1;?>" />-->
<?php
//$employeeeMaintenanceObj->gethiddenValues('ddlbusiness'.$positionID, $businessFunction ,'results', 'ctlPositions', 'businessFunction' , 'ctlPositions#businessFunction');
?>

<input type="hidden" name="adpBusinessTitle<?php echo $positionID;?>" value="<?php echo $adpBusinessTitleNew;?>" />
<?php
$employeeeMaintenanceObj->gethiddenValues('adpBusinessTitle'.$positionID, $adpBusinessTitleold ,'results', 'ctlPositions', 'adpBusinessTitle' , 'ctlPositions#adpBusinessTitle');
?>

<input type="hidden" name="adpFLSAStatus<?php echo $positionID;?>" value="<?php echo $adpFLSAStatusNew;?>" />
<?php
$employeeeMaintenanceObj->gethiddenValues('adpFLSAStatus'.$positionID, $adpFLSAStatusold ,'results', 'ctlPositions', 'adpFLSAStatus' , 'ctlPositions#adpFLSAStatus');
?>

<!--<input type="hidden" name="adpEEOExclusion<?php echo $positionID;?>" value="<?php //echo $adpEEOExclusionNew;?>" />-->
<?php
//$employeeeMaintenanceObj->gethiddenValues('adpEEOExclusion'.$positionID, $adpEEOExclusionold ,'results', 'ctlPositions', 'adpEEOExclusion' , 'ctlPositions#adpEEOExclusion');
?>

<input type="hidden" name="adpDepartmentNumber<?php echo $positionID;?>" value="<?php echo $adpDepartmentNumberNew;?>" />
<?php
$employeeeMaintenanceObj->gethiddenValues('adpDepartmentNumber'.$positionID, $adpDepartmentNumberold ,'results', 'ctlPositions', 'adpDepartmentNumber' , 'ctlPositions#adpDepartmentNumber');
?>

<input type="hidden" name="adpWorkersCompCode<?php echo $positionID;?>" value="<?php echo $adpWorkersCompCodeNew;?>" />
<?php
$employeeeMaintenanceObj->gethiddenValues('adpWorkersCompCode'.$positionID, $adpWorkersCompCodeold ,'results', 'ctlPositions', 'adpWorkersCompCode' , 'ctlPositions#adpWorkersCompCode');

exit();

?>