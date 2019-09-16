<?php
include_once($_SERVER['DOCUMENT_ROOT']."/Include/DB.class.inc.php");
$employeeeMaintenanceObj = new ClassQuery();

$employeeID = $_REQUEST[employeeID];
$modifiedBy = $_SESSION['empID'];
$effectiveDate = date('m/d/y');

$query2 = "
			IF NOT EXISTS(SELECT * FROM rnet.dbo.logADPNewHires a WITH (NOLOCK) WHERE employeeID = '$employeeID' AND effectiveDate = '$effectiveDate'  AND isSentToADP <> 'Y' )
			BEGIN
			INSERT INTO rnet.dbo.logADPNewHires
					(
					 employeeID,
					 effectiveDate,
					 modifiedDate,
					 modifiedBy
					 )
				VALUES
					(
					 '$employeeID',
					 '$effectiveDate',
					 '$effectiveDate',
					 '$modifiedBy'
					 )
			END
";
$resultSet=$employeeeMaintenanceObj->ExecuteQuery($query2);
	
echo '<font color="#FF0000">In Queue for ADPC*</font>';
?>
