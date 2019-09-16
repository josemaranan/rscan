<?php
//session_start();
include_once($_SERVER["DOCUMENT_ROOT"]."/Include/tempTableStructure.inc.php");
include_once($_SERVER['DOCUMENT_ROOT'] . '/RNetIncludes/lib/RDSData/DbConfig.php');
//include_once($_SERVER["DOCUMENT_ROOT"].'/Include/logADPPayrollChangesClass.inc.php');
include_once($_SERVER['DOCUMENT_ROOT']."/RNetIncludes/class1/logADPPayrollChangesClass.inc.php");
$employeeeMaintenanceObj = new ADPPayroll();

function updateADpClientCode($employeeID, $clientName, $employeeeMaintenanceObj)
{
	
$effectiveDate = date('m/d/Y');
$modifiedDate = date('m/d/Y');
$modifiedBy = $employeeeMaintenanceObj->UserDetails->User;

unset($sqlQuery);
unset($resultsSet);
unset($numRows);
unset($adpCode);
$adpClientCode = '';


$sqlQuery = " SELECT clientCode FROM  RNet.dbo.ctlADPClientCodes WITH (NOLOCK) WHERE clientID = '".$clientName."'  ";
$resultsSet = $employeeeMaintenanceObj->execute($sqlQuery);
$numRows = $employeeeMaintenanceObj->getNumRows($resultsSet);
if($numRows>0)
{
	$adpClientCode = mssql_result($resultsSet,0,0);
}

if(!empty($adpClientCode))
{
	unset($sqlQuery);
	unset($resultsSet);
	unset($numRows);
	unset($adpCode);
	$adpFlag = false;
	
	$sqlQuery = " SELECT adpClientCode FROM Rnet.dbo.logADPNewHires (nolock) where employeeID = '$employeeID' AND adpClientCode IS NOT NULL ";
	$resultsSet = $employeeeMaintenanceObj->execute($sqlQuery);
	$numRows = $employeeeMaintenanceObj->getNumRows($resultsSet);
	
	if($numRows>0)
	{
		$adpFlag = true;
	}
	
	if(!$adpFlag)
	{
		
		unset($sqlQuery);
		unset($resultsSet);
		
		$sqlQuery = " UPDATE 
							Rnet.dbo.logADPNewHires
						SET
							adpClientCode = '".$adpClientCode."' 
						WHERE employeeID = '$employeeID' ";
		$resultsSet = $employeeeMaintenanceObj->execute($sqlQuery);
	
	}
	
		unset($sqlQuery);
		unset($resultsSet);
		unset($returnCountry);
		unset($numRows);
				
				
		/*	$sqlQuery = " SELECT 
									country
							FROM
									ctlLocations a WITH (NOLOCK)
							JOIN
									ctlEmployees b WITH (NOLOCK)
							ON
									a.location = b.Payrolllocation
							WHERE
									b.employeeID = ".$employeeID." ";
		
			
				
			$resultsSet= $employeeeMaintenanceObj->execute($sqlQuery);
				
				
				$numRows = $employeeeMaintenanceObj->getNumRows($resultsSet);
				
				if($numRows>0)
				{
					$returnCountry = mssql_result($resultsSet,0,0);	
				}
				*/
				unset($sqlQuery);
				unset($resultsSet);
				
				$returnCountry = $employeeeMaintenanceObj->getCountryName($employeeID);
				
						unset($sqlQuery);
						unset($resultsSet);
						unset($existingSupervisirID);
						
						$sqlQuery = " SELECT
										adpClientCode
									FROM
											ctlEmployees WITH (NOLOCK)
									WHERE
											employeeID = ".$employeeID." ";
						$resultsSet= $employeeeMaintenanceObj->execute($sqlQuery);
						$existingAdpClientCode = mssql_result($resultsSet,0,0);
						unset($sqlQuery);
						unset($resultsSet);
						mssql_free_result($resultsSet);
						
					$timePortion = date('h:i:s');
					$modifiedDateTime = $modifiedDate.' '.$timePortion;
					
					
					if($returnCountry=='United States of America')
					{
						
						
						unset($sqlQuery);
						unset($resultsSet);
							if($existingAdpClientCode!=$adpClientCode)
							{
								$sqlQuery = " INSERT INTO Rnet.dbo.[logADPPayrollChanges] ";	
									$sqlQuery .= "(	 [employeeID]
													,[databaseName]
													,[tableName]
													,[columnName]
													,[ADPColumnName]
													,[oldValue]
													,[newValue]
													,[effectiveDate]
													,[modifiedDate]
													,[modifiedBy]) ";
									$sqlQuery .= " VALUES (
															".$employeeID." 
														   ,'results'
														   ,'ctlEmployees'
														   ,'adpClientCode'
														   ,'COST_NUM'
														   ,'".$existingAdpClientCode."'
														   ,'".$adpClientCode."'
														   ,'".$effectiveDate."'
														   ,'".$modifiedDateTime."'
														   ,'".$modifiedBy."')";
									
									
									$resultsSet= $employeeeMaintenanceObj->execute($sqlQuery);
						}
					
					}
					
					// log the records into prmEmployeeDetails tables.
					if($existingAdpClientCode!=$adpClientCode)
					{
							/*$effectiveDate = date('m/d/Y');
							$modifiedDate = date('m/d/Y H:i:s');
							$modifiedBy = $employeeeMaintenanceObj->UserDetails->User;
							$timePortion = date('h:i:s');
							*/
							
							$maxModifiedDate = $employeeeMaintenanceObj->getprmEmployeeDetailsMaxModifiedDate($employeeID);
							if(!empty($maxModifiedDate))
							{
							$employeeeMaintenanceObj->insertRecordIntoprmEmployeeDetails($employeeID, $effectiveDate , $modifiedDateTime, $modifiedBy, $maxModifiedDate);
							}
							$maxModifiedDate = $employeeeMaintenanceObj->getprmEmployeeDetailsMaxModifiedDate($employeeID);	
						unset($query);
						unset($resultsSet);
						$query .= " UPDATE Rnet.dbo.prmEmployeeDetails SET adpClientCode = '".$adpClientCode."' ";
						$query .=	" WHERE employeeID = '".$employeeID."' AND modifiedDate = '".$maxModifiedDate."' ";	
						$resultsSet	 = $employeeeMaintenanceObj->execute($query);
						
					}
					// Updating CtlEmployee at the bottom bcz.
					// We need to get the old value from this table to insert logADPchanges table.
					
					$updateEmps = " UPDATE 
							ctlEmployees  
						SET 
							adpClientCode = '".$adpClientCode."',
							modifiedBy = '".$modifiedBy."',
							modifiedDate = '".$modifiedDate."'
						WHERE 
							employeeID= '".$employeeID."' ";
				//echo $updateEmps;exit;
				$rstUpdateEmps = $employeeeMaintenanceObj->execute($updateEmps);	
		
				
	} // !empty of Client Code
			
}



//exit;
if($_REQUEST)
{
// Escape bad characters--condCart

	$employeeID = $_REQUEST['employeeID'];
	$jobcode=str_replace("'","''",trim($_REQUEST['ddlJobCode']));
	$effectiveDate_AEClients = date('m/d/Y');
	$clientName = $_REQUEST['ddlClients'];
	
	if(!empty($_REQUEST['hdnType']))
	{
		$type = $_REQUEST['hdnType'];
	}
	
	if($_REQUEST['effectiveDate'])
	{
		$effectiveDate=$_REQUEST['effectiveDate'];
	}
	else
	{
		$effectiveDate=date("m/d/Y");
	}
	
	if($_REQUEST['ddlAgentClassification'])
	{
		$Classification=$_REQUEST['ddlAgentClassification'];
	}
	else
	{
		$Classification=NULL;
	}
	
/*$db1=mssql_connect(MSSQL_HOST, MSSQL_USERNAME, MSSQL_PASSWORD);
mssql_select_db(MSSQL_DB);
global $db1;*/		
		
		
	if($jobcode && $effectiveDate)
	{	
		$query3 = " SELECT COUNT(*) as NUM FROM ctlEmployeePositions WITH (NOLOCK) WHERE positionID = '".$jobcode."' and employeeID =".$employeeID." AND endDate IS NULL "; 
		
		//$rst3=mssql_query($query3, $db1);
		$rst3 = $employeeeMaintenanceObj->execute($query3);
		
		$num3 = mssql_result($rst3,0,0);
				
	if($num3 == 0)
	{
				
		/*$db=mssql_connect(MSSQL_HOST, MSSQL_USERNAME, MSSQL_PASSWORD);
		mssql_select_db(MSSQL_DB);*/
				
				
		if($_POST[isPrimary])
		{
			$isPrimary = $_POST[isPrimary];
		}
		else
		{
			$isPrimary = 'N'; 
		}
				
				
		if($isPrimary == 'Y')
		{
				$queryUpdate = "UPDATE ctlEmployeePositions SET isPrimary = 'N' WHERE employeeID =".$employeeID;
				
				//$rstUpdate=mssql_query($queryUpdate, $db);
				$rstUpdate = $employeeeMaintenanceObj->execute($queryUpdate);
					
		}
				
		if($jobcode==10)
		{
			unset($sqlQuery);
			unset($resultsSet);
			unset($employeExists);
			$today1978 = date('m/d/Y');
			
			$sqlQuery = $sqlTemEmployeeSupervisors ; 
			$sqlQuery .= " EXEC RNet.dbo.[standard_spEmployeeSupervisors] '%','".$employeeID."','".$today1978."'			   SELECT supervisorID FROM #tempEmployeeSupervisors WITH (NOLOCK) WHERE isCurrent = 'Y' ";
			
			//$resultsSet=mssql_query($sqlQuery, $db);
			$resultsSet = $employeeeMaintenanceObj->execute($sqlQuery);
			
			$employeExists = $employeeeMaintenanceObj->getNumRows($resultsSet);
			
			if($employeExists==0)
			{
				header('Location:index.php?hdnEmployeeID='.$employeeID.'&supNotExist=Y&returnPositionID='.$jobcode.'&returneffectiveDate='.$effectiveDate.'&adpMode=hr&adpTask=empPosition');	
				exit();	
			}
			
		}
				
		$query = "Insert into ctlEmployeePositions(employeeID,positionID,effectiveDate,isPrimary,modifiedBy,modifiedDate) values(".$employeeID.",'".$jobcode."' , '".$effectiveDate."','".$isPrimary."','".$_SESSION[empID]."' , '".date("m/d/Y h:i:s")."')";

		//$rst=mssql_query($query, $db);
		$rst = $employeeeMaintenanceObj->execute($query);
		
	
	
/*-----------------------Newly Added By Murali as per task # 6251 on Dec 28th 2011------------------------*/
if($jobcode==132 || $jobcode==47 || $jobcode==357 || $jobcode==322)
{
	$nwempID = $_REQUEST['hdnEmployeeID'];
	$hdnLocs = $_REQUEST['hdnRawLocs'];
	$locsArry = explode(',',$hdnLocs);
	
	foreach($locsArry as $locsArryVal)
	{
		unset($locEffectiveDate);
		unset($locEndDate);
		foreach($_REQUEST['effectiveDate_'.$locsArryVal] as $effVal)
		{
			if($effVal!='')
			{
				$locEffectiveDate[] = $effVal;
			}
		}
		foreach($_REQUEST['endDate_'.$locsArryVal] as $endVal)
		{
			if($endVal!='')
			{
				$locEndDate[] = $endVal;
			}
		}
		
		if(!empty($_REQUEST[$locsArryVal]))
		{
			$locssCount = count($_REQUEST[$locsArryVal]);
			for($i=0;$i<$locssCount;$i++)
			{
				$empPosLocations .= " INSERT INTO  RNet.dbo.prmEmployeePositionLocations (employeeID,positionID,location,effectiveDate,endDate) VALUES ('$nwempID','$jobcode','".$locsArryVal."','".$locEffectiveDate[$i]."',";
					if(!empty($locEndDate[$i]))
					{
						$empPosLocations .= "	'".$locEndDate[$i]."')";
					}
					else
					{
						$empPosLocations .= "	NULL)";
					}
			}
		}
	}
	
	//$resempPosLocations = mssql_query(str_replace("\'","''",$empPosLocations), $db);
	$resempPosLocations = $employeeeMaintenanceObj->execute($empPosLocations);
	
}
	
if($jobcode==30 || $jobcode==132 || $jobcode==47 || $jobcode==356 || $jobcode==357 || $jobcode==322)
{
	
	
	/*echo '<pre>';
	print_r($_REQUEST);
	echo '</pre>';*/
	
	
	
	$nwempID = $_REQUEST['hdnEmployeeID'];
	$hdnClients = $_REQUEST['hdnRawClients'];
	$clntsArry = explode(',',$hdnClients);
	
	foreach($clntsArry as $clntsArryVal)
	{
		$clntsArryVal = str_replace(' ','_',$clntsArryVal); // Vasu added newly.
		
		unset($cliEffectiveDate);
		unset($clientEndDate);
		unset($clientAEIsPrimary);
		foreach($_REQUEST['effectiveDate_'.$clntsArryVal] as $effCliVal)
		{
			$cliEffectiveDate[] = $effCliVal;
		}
		foreach($_REQUEST['endDate_'.$clntsArryVal] as $val)
		{
			$clientEndDate[] = $val;
		}
		if(!empty($_REQUEST[$clntsArryVal]))
		{
			$clntsCount = count($_REQUEST[$clntsArryVal]);
			for($i=0;$i<$clntsCount;$i++)
			{
				$dispCltNameRaw = explode('****',$_REQUEST[$clntsArryVal][$i]);
				$splitAll = explode('##',$dispCltNameRaw[0]);
				$innerCountArry = explode('$$$',$dispCltNameRaw[1]);
				$innerCount = $innerCountArry[1];
				
				$empPositionClients .= " INSERT INTO RNet.dbo.prmEmployeePositionClients (employeeID, positionID, clientName, lob_id, channelID, effectiveDate, endDate)  VALUES ($nwempID, '$jobcode', '".$splitAll[0]."', '".$splitAll[1]."' , '".$splitAll[2]."', '".$cliEffectiveDate[$innerCount]."' , ";
																																																																						   				//echo 'Query'.$empPositionClients. '<br>';
					if(!empty($clientEndDate[$innerCount]))
					{
						$empPositionClients .= "	'".$clientEndDate[$innerCount]."')";
					}
					else
					{
						$empPositionClients .= "	NULL)";
					}
			}
		}
	}
	
	//$resEmpPositionClients = mssql_query(str_replace("\'","''",$empPositionClients), $db);
	$resEmpPositionClients = $employeeeMaintenanceObj->execute($empPositionClients);
	
	//exit;
	
	
	// New logic ------  Is primary.
	unset($chkisPrimary);
	unset($queryUpdate);
	unset($rstUpdate);
	$chkisPrimary = $_REQUEST['hdnIsPrimary'];
	
	$queryUpdate = "UPDATE Rnet.dbo.prmEmployeePositionClients SET isPrimary = 'Y' WHERE employeeID =".$employeeID." AND clientName = '".$chkisPrimary."' and positionID = ".$jobcode." ";
	
	//$rstUpdate=mssql_query($queryUpdate, $db);
	$rstUpdate = $employeeeMaintenanceObj->execute($queryUpdate);
	
	// Updating the adpClientCode ========================
		if(!empty($chkisPrimary))
		{
			updateADpClientCode($employeeID, $chkisPrimary, $employeeeMaintenanceObj);
		}
	
	// End of updating adpClientCode ======================= 
	
	if(!empty($_REQUEST['chkIsPrimaryAE']))
	{
		foreach($_REQUEST['chkIsPrimaryAE'] as $primaryAEVal)
		{
			unset($isPrimaryAE1);
			unset($rstIsPrimAE1);
			unset($isPrimaryAE2);
			unset($rstIsPrimAE2);
			
			$isPrimaryAE1 = "UPDATE Rnet.dbo.prmEmployeePositionClients SET isPrimaryAE = NULL WHERE clientName = '".$primaryAEVal."' and positionID = ".$jobcode." ";
			//$rstIsPrimAE1 = mssql_query($isPrimaryAE1, $db);
			$rstIsPrimAE1 = $employeeeMaintenanceObj->execute($isPrimaryAE1);
			//$val .= $primaryAEVal.'<br>';
			$isPrimaryAE2 = "UPDATE Rnet.dbo.prmEmployeePositionClients SET isPrimaryAE = 'Y' WHERE employeeID =".$employeeID." AND clientName = '".$primaryAEVal."' and positionID = ".$jobcode." ";
			
			//$rstIsPrimAE2 = mssql_query($isPrimaryAE2, $db);
			$rstIsPrimAE2 = $employeeeMaintenanceObj->execute($isPrimaryAE2);
		}
	}
	
}
/*--------------------END Of Newly Added By Murali as per task # 6251 on Dec 28th 2011------------------*/


//-------------------------------------Newly Added by Murali as per task # 7125--------------------------

$clNQ = " SELECT clientName FROM ctlPositions WITH (NOLOCK) WHERE positionID = '".$_REQUEST['ddlJobCode']."' AND position LIKE 'Client%' AND clientName IS NOT NULL ";

//$clNRst=mssql_query(str_replace("\'","''",$clNQ), $db);
$clNRst = $employeeeMaintenanceObj->execute($clNQ);

if ($clNRow=mssql_fetch_array($clNRst)) 
{
	$cNameE = $clNRow['clientName'];
}
if(!empty($cNameE))
{
	$ifExistsQ="SELECT employeeID FROM ctlEmployeeClients WITH (NOLOCK) WHERE employeeID = $employeeID AND clientName='".$cNameE."' ";
	
	//$ifExistsRst=mssql_query(str_replace("\'","''",$ifExistsQ), $db);
	$ifExistsRst = $employeeeMaintenanceObj->execute($ifExistsQ);
	
	$ifExistsNum = $employeeeMaintenanceObj->getNumRows($ifExistsRst);
	if($ifExistsNum==0)
	{
			 $empClINS = " INSERT INTO ctlEmployeeClients (employeeID,clientName,modifiedBy,modifiedDate) VALUES ($employeeID, '".$cNameE."','".$_SESSION['empID']."','".date('m/d/Y')."') ";
			 
			// $resEmpClINS = mssql_query(str_replace("\'","''",$empClINS), $db);
			 $resEmpClINS = $employeeeMaintenanceObj->execute($empClINS);
			 
	}
}

//---------------------End of Newly Added by Murali as per task # 7125-----------------------------------


		
	}
	else
	{
		header("Location: index.php?type=PositionExist&hdnEmployeeID=".$employeeID."&adpMode=hr&adpTask=empPosition");
		exit;
	}
				

/*	$con=mssql_connect(MSSQL_HOST, MSSQL_USERNAME, MSSQL_PASSWORD);
	mssql_select_db(MSSQL_DB);*/

	$qry = "SELECT location FROM ctlEmployeeLocations WITH (NOLOCK) WHERE employeeID=".$employeeID;
	
	//$rstQry = mssql_query($qry, $con);
	$rstQry = $employeeeMaintenanceObj->execute($qry);
	 
	 
	$numLoc = $employeeeMaintenanceObj->getNumRows($rstQry);
	if($numLoc >0)
	{
		while ($locRow=mssql_fetch_array($rstQry)) 
		{	
			$loc = $locRow[location];
	
			if($jobcode == 42 || $jobcode == 21 || $jobcode == 17 || $jobcode == 4)
			{
				$recQry1 = "select * from ctlEmailDistributionLists WITH (NOLOCK) where location=$loc and emailDistributionList like '%Recruiters'";
				
				//$rstQry1 = mssql_query($recQry1, $con);
				$rstQry1 = $employeeeMaintenanceObj->execute($recQry1);
				
					if ($locDL1=mssql_fetch_array($rstQry1)) 
					{	
						$eDL = $locDL1[emailDistributionList];
					}
			}
	
			if($jobcode == 12 || $jobcode == 13 || $jobcode == 19 || $jobcode == 21 || $jobcode == 23)
			{
			$recQry1 = "select * from ctlEmailDistributionLists WITH (NOLOCK) where location=$loc and emailDistributionList like '%Trainers'";
			
			//$rstQry2 = mssql_query($recQry1, $con);
			$rstQry2 = $employeeeMaintenanceObj->execute($recQry1);
			
				if ($locDL2=mssql_fetch_array($rstQry2)) 
				{	
					$eDL = $locDL2[emailDistributionList];
				}
			}
	
			if($eDL)
			{
				$verifyQry = "SELECT * FROM ctlEmployeeEmailDistributionLists WITH (NOLOCK) WHERE emailDistributionList='".$eDL."' and employeeID='".$employeeID."'";
				
				//$rstVerify=mssql_query(str_replace("\'","''",$verifyQry), $con);
				$rstVerify = $employeeeMaintenanceObj->execute($verifyQry);
				
				$numVerify =$employeeeMaintenanceObj->getNumRows($rstVerify);
			
				if($numVerify == 0)
				{		
					$insQry = "INSERT INTO ctlEmployeeEmailDistributionLists(emailDistributionList,employeeID) 
								VALUES('".$eDL."',$employeeID)";
								
								$insQryRes = $employeeeMaintenanceObj->execute($insQry);
								
					//mssql_query($insQry, $con);
				}		
			}
		}
	}
					
	$qry2 = "SELECT location FROM ctlEmployees WITH (NOLOCK) WHERE employeeID=".$employeeID;
	
	//$rstQry2 = mssql_query($qry2, $con);
	$rstQry2 = $employeeeMaintenanceObj->execute($qry2);
	
	if ($locRow2=mssql_fetch_array($rstQry2)) 
	{	
		$loc2 = $locRow2[location];
		
		if($jobcode == 42 || $jobcode == 22 || $jobcode == 21 || $jobcode == 17 || $jobcode == 4)
		{
			$recQry2 = "select * from ctlEmailDistributionLists WITH (NOLOCK) where location=$loc2 and emailDistributionList like '%Recruiters'";
			
			//$rstQry2 = mssql_query($recQry2, $con);
			$rstQry2 = $employeeeMaintenanceObj->execute($recQry2);
		
			if ($locDL12=mssql_fetch_array($rstQry2)) 
			{	
				$eDL2 = $locDL12[emailDistributionList];
			}
		}
		
		if($jobcode == 12 || $jobcode == 13 || $jobcode == 19 || $jobcode == 21 || $jobcode == 23)
		{
			$recQry3 = "select * from ctlEmailDistributionLists WITH (NOLOCK) where location=$loc2 and emailDistributionList like '%Trainers'";
			
			//$rstQry3 = mssql_query($recQry3, $con);
			$rstQry3 = $employeeeMaintenanceObj->execute($recQry3);
		
			if ($locDL3=mssql_fetch_array($rstQry3)) 
			{	
				$eDL2 = $locDL3[emailDistributionList];
			}
		}
		
		if($eDL2)
		{
			$verifyQry2 = "SELECT * FROM ctlEmployeeEmailDistributionLists WITH (NOLOCK) WHERE emailDistributionList='".$eDL2."' and employeeID='".$employeeID."'";
			
			//$rstVerify2=mssql_query(str_replace("\'","''",$verifyQry2), $con);
			$rstVerify2 = $employeeeMaintenanceObj->execute($verifyQry2);
		
			$numVerify2 =$employeeeMaintenanceObj->getNumRows($rstVerify2);
			if($numVerify2 == 0)
			{				
				$insQry2 = "INSERT INTO ctlEmployeeEmailDistributionLists(emailDistributionList,employeeID) 
					VALUES('".$eDL2."',$employeeID)";
					
					$insQry2Res = $employeeeMaintenanceObj->execute($insQry2);
					
				//mssql_query($insQry2, $con);	
			}
		}
	}	
				
	}

	
	 /* Vasudev added new logic for employee training class notifications */
	$sqlQuery = " EXEC Rnet.dbo.[process_spEmployeeTrainingClassNotificationsPositionUpdate] '$jobcode' , '$employeeID' ";
	
	//echo $sqlQuery;
	//exit;
	
	//$resultSet = mssql_query($sqlQuery, $db);
	$resultSet = $employeeeMaintenanceObj->execute($sqlQuery);
	
	if($isPrimary == 'Y')
	{
		$effectiveDateOnly = date('m/d/Y', strtotime($effectiveDate));
		$requiredArray = $employeeeMaintenanceObj->updateLogFiles($_REQUEST, $employeeID, $effectiveDateOnly);
	}

}

	header("Location: index.php?hdnEmployeeID=".$employeeID."&adpMode=hr&adpTask=empPosition&error=DataIUpdate&activeLink=20");
	exit;

?>
