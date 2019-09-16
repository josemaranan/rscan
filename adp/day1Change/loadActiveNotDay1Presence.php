<?php
$locationID = '';

if(isset($_REQUEST['locationID']))
{
	$locationID = $_REQUEST['locationID'];
}

if($locationID=='')
{
	echo 'Error';
	exit();
}

include_once($_SERVER['DOCUMENT_ROOT']."/Include/logADPPayrollChangesClass.inc.php");
$employeeeMaintenanceObj = new ADPPayroll();

unset($sqlQuery);
unset($resultsSet);
unset($empDay1Data);
unset($empday1Count);
$curDate = date('m/d/Y');
$curDatePlus90Day = date('m/d/Y', strtotime($curDate. ' + 90 day'));
		
		
$sqlQuery = " 
				IF OBJECT_ID('tempdb.dbo.#tempEmployeesPayrollLocation') IS NOT NULL  
						  DROP TABLE #tempEmployeesPayrollLocation  
						  
						 CREATE TABLE #tempEmployeesPayrollLocation  
						 (  
						  employeeID INT,  
						  date DATETIME,  
						  payrollLocation VARCHAR(3) NULL,  
						  payrollLocationEffectiveDate DATETIME NULL  
						
						 )  
						EXEC RNET.dbo.standard_spEmployeesPayrollLocation '".$locationID."','%','".$curDatePlus90Day."','".$curDatePlus90Day."'

				IF OBJECT_ID('tempdb.dbo.#tempDay1PresentEmployees') IS NOT NULL
				DROP TABLE #tempDay1PresentEmployees
				
				CREATE TABLE #tempDay1PresentEmployees
				(
					employeeID INT NULL,
					hireDate DATETIME NULL,
					termDate DATETIME NULL,
					day1PresentDate DATETIME NULL
				)
				
				INSERT INTO #tempDay1PresentEmployees
				SELECT
						a.employeeID,
						a.hireDate,
						a.termDate,
						a.day1PresentDate
				FROM
						Rnet.dbo.PrmEmployeeCareerHistory a WITH (NOLOCK)
				JOIN
						#tempEmployeesPayrollLocation b WITH (NOLOCK)
				ON
						a.employeeID = b.employeeID
				AND
						a.termDate IS NULL
				AND
						a.day1PresentDate IS NULL
					
				
				
				IF OBJECT_ID('tempdb.dbo.#tempDay1PresentEmployeesMaxHireDate') IS NOT NULL
				DROP TABLE #tempDay1PresentEmployeesMaxHireDate
				
				CREATE TABLE #tempDay1PresentEmployeesMaxHireDate
				(
					employeeID INT NULL,
					hireDate DATETIME NULL
				)
				
				INSERT INTO #tempDay1PresentEmployeesMaxHireDate
				SELECT
						employeeID , 
						max(hireDate)
				FROM
						#tempDay1PresentEmployees (NOLOCK)
				GROUP BY
						employeeID
				
				
				
				SELECT 
					a.firstName+' '+a.lastName employeeName,
					c.position positionDescription,
					CONVERT(VARCHAR(10),hr.hireDate,101) hireDate,
					hr.employeeID
				FROM
					Results.dbo.ctlEmployees a WITH (NOLOCK)
				JOIN
					Results.dbo.ctlEmployeePositions b WITH (NOLOCK)
				ON
					a.employeeID = b.employeeID
				AND
					b.isPrimary = 'Y'
				AND
					b.endDate IS NULL
				JOIN
					Results.dbo.ctlPositions c WITH (NOLOCK)
				ON
					b.positionID = c.positionID
				JOIN
					#tempDay1PresentEmployeesMaxHireDate hr WITH (NOLOCK)
				ON
					a.employeeID = hr.employeeID ";
//echo $sqlQuery;
//exit;

$resultsSet = $employeeeMaintenanceObj->ExecuteQuery($sqlQuery);
$empday1Count = mssql_num_rows($resultsSet);
if($empday1Count<=0)
{
		echo 'No Records';
		exit;
}
else
{
	$empDay1Data = $employeeeMaintenanceObj->bindingInToArray($resultsSet);	
}


if($empday1Count>0)
{ 
		echo '<table border="0" style="margin-bottom:5px;">';

		foreach($empDay1Data as $empDay1DataK=>$empDay1DataV)
        {
			echo '<tr>
					<td style="width:50px; text-align:left; padding-left:15px;">
						<input type="checkbox" name="chkemp[]" id="emp'.$empDay1DataV[employeeID].'" value="'.$empDay1DataV[employeeID].'##'.$empDay1DataV[hireDate].'"  />
					</td>
					
					<td style="text-align:left;">
						'.$empDay1DataV[employeeName].'('.$empDay1DataV[employeeID].') , '.$empDay1DataV[positionDescription].' , '.$empDay1DataV[hireDate].'
					</td>
					
				</tr>';	
		}
		echo '<tr><td><input type="hidden" name="hdnLocation" id="hdnLocation" value="'.$locationID.'"></td></tr>';
		echo '</table>';
}


?>