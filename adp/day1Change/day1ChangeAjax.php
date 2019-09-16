
<?php

	//include_once($_SERVER['DOCUMENT_ROOT'] . '/RNetIncludes/class/config.inc.php');
	//include_once($_SERVER['DOCUMENT_ROOT'] . '/RNetIncludes/class/common.config.inc.php');
	include_once($_SERVER['DOCUMENT_ROOT'] . '/RNetIncludes/class1/common.config.inc.php');
	include_once($_SERVER['DOCUMENT_ROOT'] . '/RNetIncludes/class/ReportTable.inc.php');
	
	$RDSObj = new RDSData(MSSQL_HOST, MSSQL_USERNAME, MSSQL_PASSWORD, MSSQL_DB, MSSQL_TYPE, MSSQL_PORT, '');
	
	unset($tableHeaders);
	unset($task);
	unset($sqlQuery);
	unset($resultsSet);
	unset($numRows);
	unset($mainArray);
	/*$headerObj->jsSource = $jsFilesAjax;
	$jsFiles = $headerObj->getJsSourceFiles();
	echo $jsFiles;*/
	if(isset($_REQUEST['task']))
	{
		$task = $_REQUEST['task'];  
	}
	$parameters = $rnetAllObj->storeValues($_POST);
	$accessedLocations = substr($RDSObj->UserDetails->accessedLocations,1);
	
	switch($task)
	{
		case 'loadActiveNotDay1Presence':
			
			$headerObj->jsSource = $jsFilesAjax;
			$jsFiles = $headerObj->getJsSourceFiles();
			echo $jsFiles;		
					
			$curDate = date('m/d/Y');
			$curDatePlus90Day = date('m/d/Y', strtotime($curDate. ' + 90 day'));
			$locationID = $parameters['locationID'];	
					
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
							
							
							DELETE FROM #tempEmployeesPayrollLocation WHERE payrollLocation <> '".$locationID."' 
							
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
			
			$resultsSet = $RDSObj->execute($sqlQuery);
			$numRows = $RDSObj->getNumRows($resultsSet);
			if ($numRows >= 1)
			{
				$mainArray = $RDSObj->bindingInToArray($resultsSet);
			}
			
			//print_r($parameters);
			
			$Table=new ReportTable();
			$Table->Width="98%";
			
			$Col=& $Table->AddColumn("Column1");
			$Col=& $Table->AddColumn("Column2");
			
			$Row=& $Table->AddHeader();
			$Row->Cells["Column1"]->Value="";
			$Row->Cells["Column2"]->Value="Employee Details";
			
			
			
			foreach($mainArray as $mainArrayK=>$mainArrayV)
			{
				$Row=& $Table->AddRow();
				$Row->Cells["Column1"]->Value = "<input type='checkbox' name='".$mainArrayV[employeeID]."' id='".$mainArrayV[employeeID]."' value='".$mainArrayV[hireDate]."' />";
				$Row->Cells["Column2"]->Value = $mainArrayV[employeeName].'('.$mainArrayV[employeeID].') , '.$mainArrayV[positionDescription].' , '.$mainArrayV[hireDate];
				
			}
			//echo '<input type="button" value="" /><br/>';
			if($numRows > 0)
			{
				echo $htmlTagObj->anchorTag('#','Confirm Day 1 Presence', 'class="blue_button" style="margin-left:10px;" onclick="validateEmployeeDay1();"');
			}
			$footerInfo = $rnetAllObj->getTableGridFooterInfo($numRows);
			$Table->Display();
			echo $footerInfo;			
			break;
		
		case 'submitActiveNotDay1Presence':
			//print_r($parameters);
			
			unset($updateQuery);
			$curDate = date('m/d/Y');
			
			foreach($parameters as $employeeID=>$empHireDate)
			{				
				$updateQuery .= "
								UPDATE 
									Rnet.dbo.PrmEmployeeCareerHistory 
								SET 
									day1PresentDate = '".$curDate."' 
								WHERE 
									employeeID = '".$employeeID."' 
								AND 
									hireDate = '".$empHireDate."'; ";
				
			}
			$resultsSet = $RDSObj->execute($updateQuery);
			echo $resultsSet;
			break;
			
		default:
			echo 'Error Default:';
			break;
	}
	
?>