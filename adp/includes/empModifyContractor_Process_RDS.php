<?php

//include_once($_SERVER["DOCUMENT_ROOT"].'/Include/logADPPayrollChangesClass.inc.php');
//include_once($_SERVER['DOCUMENT_ROOT'] . '/RNetIncludes/class/config.inc.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/RNetIncludes/class1/config.inc.php');
$RDSObj = new RDSData(MSSQL_HOST, MSSQL_USERNAME, MSSQL_PASSWORD, MSSQL_DB, MSSQL_TYPE, MSSQL_PORT, '');
//$payrollObj = new ADPPayroll();

$modifiedDate = date('m/d/Y');
$modifiedBy = $RDSObj->UserDetails->User; //$_SESSION[empID];

$empID = $_REQUEST['eid'];
$cmp = $_REQUEST['company'];
$edate = date("Y-m-d", strtotime($_REQUEST['edate']));

$result = array();
$result['success'] = 'false';

if( isset($empID) && $empID != '')
{
	//echo "json..".$empID.$cmp.$edate.$modifiedBy;
	$chkQuery = "
				select 
					employeeID 
				from 
					RNet.dbo.prmEmployeePayrollLocations with (nolock) 
				where 
					employeeID = '".$empID."' 
				AND 
					effectiveDate = '".$edate."'";
					
	$cntRows = $RDSObj->execute($chkQuery);
	unset($query);
	if( (($RDSObj->getNumRows($cntRows))) == 0)  // add payroll location
	{
		$query = "INSERT INTO RNet.dbo.prmEmployeePayrollLocations 
										(
											employeeID,
											payrollLocation,
											effectiveDate,
											modifiedBy,
											modifiedDate,
											contractorCompany
										)
										VALUES
										(
											'".$empID."',
											'1',
											'".$edate."',
											'".$modifiedBy."',
											'".$modifiedDate."',
											'".$cmp."'
										)";
		
		$result['msg'] = 'Added successfully.';
	}
	else   // edit payroll location.
	{
		$query = 	"UPDATE 
						RNet.dbo.prmEmployeePayrollLocations 
					set 
						contractorCompany = '".$cmp."',
						payrollLocation = '1',
						modifiedBy = '".$modifiedBy."',
						modifiedDate = '".$modifiedDate."'
					where 
						employeeID = '".$empID."' 
					AND 
						effectiveDate = '".$edate."'";
						
		$result['msg'] = 'Updated successfully...';		
	}
	$res = $RDSObj->execute($query);
	mssql_free_result($res);
	$result['success'] = 'true';
}

echo json_encode($result);

?>
