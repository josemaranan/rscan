<?php
unset($sqlMainQry);
unset($rstMainQry);

if(empty($hireDate))
{
	header('Location: index.php?hdnEmployeeID='.$employeeID.'&adpMode=hr&adpTask=empDates&error=DataNotUpdate&activeLink=20');
	exit;	
}

if($function !='delete')
{
	header('Location: index.php?hdnEmployeeID='.$employeeID.'&adpMode=hr&adpTask=empDates&error=DataNotUpdate&activeLink=20');
	exit;	
}

$sqlMainQry = "	INSERT INTO 
							RNet.dbo.prmEmployeeCareerHistory_removed  
						(
							employeeID, hireDate, regularizationDate, termDate,terminationReasonID, canBeRehired, 
							voluntaryTermination, modifiedBy, modifiedDate, notes, regularizationModifiedBy, 
							regularizationModifiedDate, supervisorID, supervisorTermConfirmed, supervisorConfirmDate,offerDate
						)
						SELECT 
							employeeID, hireDate, regularizationDate, termDate, terminationReasonID, canBeRehired, 
							voluntaryTermination, modifiedBy, modifiedDate, notes, regularizationModifiedBy, 
							regularizationModifiedDate, supervisorID, supervisorTermConfirmed, supervisorConfirmDate,offerDate 
						FROM 
							RNet.dbo.prmEmployeeCareerHistory WITH (NOLOCK) 
						WHERE 
							employeeID = ".$employeeID." 
						AND 
							hireDate = '".$hireDate."'";
	//echo $prmCareerHis;exit;
$rstMainQry = $employeeeMaintenanceObj->execute($sqlMainQry);
	
unset($sqlMainQry);
unset($rstMainQry);

$sqlMainQry = "DELETE FROM RNet.dbo.prmEmployeeCareerHistory WHERE employeeID= '".$employeeID."' AND hireDate = '".$hireDate."' ";

$rstMainQry = $employeeeMaintenanceObj->execute($sqlMainQry);

	
unset($sqlMainQry);
unset($rstMainQry);

	
$sqlMainQry = "DELETE FROM ctlEmployeeStatuses WHERE employeeID='".$employeeID."' AND effectiveDate = '".$hireDate."' ";
$rstMainQry = $employeeeMaintenanceObj->execute($sqlMainQry);

unset($sqlMainQry);
unset($rstMainQry);


	
	if(!empty($termDate))
	{
		
		$sqlMainQry = "DELETE FROM ctlEmployeeStatuses WHERE employeeID= '".$employeeID."' AND effectiveDate = '".$termDate."' ";
		$rstMainQry = $employeeeMaintenanceObj->execute($sqlMainQry);
	}

unset($sqlMainQry);
unset($rstMainQry);


	$sqlMainQry = "
	DECLARE @date as DATETIME
	SET @date = GETDATE() ";

	$sqlMainQry .= $sqlTemEmployeeCareerHistoryStructure;

	$sqlMainQry .= " EXEC RNet.dbo.[standard_spEmployeeCareerHistory] '%','$employeeID','Termed',@date

	SELECT * FROM #tempEmployeeCareerHistory ";						
	//echo $queryCheck2;exit;
	$rstMainQry = $employeeeMaintenanceObj->execute($sqlMainQry);
	$numCheck2 = mssql_num_rows($rstMainQry);
	//echo $numCheck2;exit;
	if($numCheck2 != 0) 
	{
			$mDate = date('Y-m-d H:i:s');
			unset($sqlMainQry);
			unset($rstMainQry);

			$sqlMainQry = "	UPDATE 
									ctlEmployees 
								SET
									Locked = 'Y', Enabled = 'N', Internal = 'N', [External] = 'N',
									modifiedDate = '".$mDate."', modifiedBy = '".$_SESSION['empID']."' 
								WHERE 
									employeeID = '$employeeID'";
			$rstMainQry = $employeeeMaintenanceObj->execute($sqlMainQry);
			
	}

if($rstMainQry)
{
	header('Location: index.php?hdnEmployeeID='.$employeeID.'&adpMode=hr&adpTask=empDates&error=DataDelete&activeLink=20');
	exit;	
}
?>