<?php
/*unset($selectQry);
$selectQry = "  SELECT 
					--MIN(hireDate) minHireDate, 
					MAX(hireDate) maxHireDate 
				FROM 
					RNet.dbo.prmEmployeeCareerHistory WITH (NOLOCK) 
				WHERE 
					employeeID = $employeeID ";
//echo $selectQry;exit;
$rstSelectQry = $employeeeMaintenanceObj->ExecuteQuery($selectQry);	
while($rowSelectQry = mssql_fetch_assoc($rstSelectQry))
{
	//$minHireDate = $rowSelectQry['minHireDate'];
	$maxHireDate = $rowSelectQry['maxHireDate'];
}
mssql_free_result($rstSelectQry);

unset($updateQry);
$updateQry = " 	UPDATE
					ctlEmployees
				SET
					hireDate = '".$maxHireDate."'
					--,origHireDate = '".$minHireDate."'
				WHERE
					employeeID = $employeeID ";
$employeeeMaintenanceObj->ExecuteQuery($updateQry);	*/
?>