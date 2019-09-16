<?php
 //update trainee info with self close
//include_once($_SERVER["DOCUMENT_ROOT"].'/Include/logADPPayrollChangesClass.inc.php');
include_once($_SERVER['DOCUMENT_ROOT']."/RNetIncludes/class1/logADPPayrollChangesClass.inc.php");
$employeeeMaintenanceObj = new ADPPayroll();
/*echo '<pre>';
print_r($_REQUEST);
echo '</pre>';
exit;*/

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
				$returnCountry = $employeeeMaintenanceObj->getCountryName($employeeID);
				unset($sqlQuery);
				unset($resultsSet);
				
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
							$timePortion = date('h:i:s');*/
							
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

// Check that the main variables are not empty
if(!empty($_POST['hdnEmployeeID']))
{
	if(!empty($_POST['hdnPositionID']) ||!empty($_POST['hdnEffectiveDate']) )
	{    
	// Escape bad characters
		$employeeID = $_POST[hdnEmployeeID];
		$positionID=$_POST[hdnPositionID]; 
		$effDate=$_POST[hdnEffectiveDate]; 
		
		if(!empty($_REQUEST['hdnType']))
		{
			$type = $_REQUEST['hdnType'];
		}
		
		if(!empty($_POST['startDate_'.$positionID.'_'.$effDate]))
		{
			$startDate = $_POST['startDate_'.$positionID.'_'.$effDate];
		}
		
		if(!empty($_POST['endDate_'.$positionID.'_'.$effDate]))
		{
			$endDate = $_POST['endDate_'.$positionID.'_'.$effDate];
		}	
		
		
		if($_POST['isPrimary_'.$positionID.'_'.$effDate])
		{
			$isPrimary2 = $_POST['isPrimary_'.$positionID.'_'.$effDate];
		}
		else
		{
			$isPrimary2 = 'N'; 
		}
		
		if($effDate == '12/31/1969')
		{
			$effDate = '01/01/1900';
		}

		
		



//---------------------End of Newly Added by Murali as per task # 7125-----------------------------------

	if($positionID==132 || $positionID==47)
	{
		$hdnLocs = $_REQUEST['hdnRawLocs'];
		$locsArry = explode(',',$hdnLocs);

		if(!empty($hdnLocs))
		{
			$delQuery = "DELETE FROM RNet.dbo.prmEmployeePositionLocations WHERE employeeID = ".$employeeID." AND positionID = '$positionID' AND endDate IS NULL ";
	//echo $delQuery;exit;
	//$rstDel=mssql_query($delQuery, $db);
	$rstDel = $employeeeMaintenanceObj->execute($delQuery);
		}
		
		if(!empty($endDate))
		{
			$updateLocs = " UPDATE
								RNet.dbo.prmEmployeePositionLocations
							SET
								endDate = '".$endDate."'
							WHERE 
								employeeID = ".$employeeID."
							AND
								positionID = '$positionID' 
							AND
								endDate IS NULL";
			//$rstUpdLocs = mssql_query($updateLocs, $db);
			$rstUpdLocs = $employeeeMaintenanceObj->execute($updateLocs);
			
		}
		
	/*echo '<pre>';
	print_r($_REQUEST);
	echo '</pre>';exit;*/
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
					$empPosLocations .= " INSERT INTO  RNet.dbo.prmEmployeePositionLocations (employeeID,positionID,location,effectiveDate,endDate) VALUES ('$employeeID','$positionID','".$locsArryVal."','".$locEffectiveDate[$i]."',";
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


	if($positionID==30 || $positionID==132 || $positionID==47)
	{
		$hdnClients = $_REQUEST['hdnRawClients'];
		$clntsArry = explode(',',$hdnClients);
		
		if(!empty($hdnClients))
		{
			$delQuery = "DELETE FROM RNet.dbo.prmEmployeePositionClients WHERE employeeID = ".$employeeID." AND positionID = '$positionID'  AND endDate IS NULL ";
			//echo $delQuery;exit;
			//$rstDel=mssql_query($delQuery, $db);
			$rstDel = $employeeeMaintenanceObj->execute($delQuery);
		}
		
		if(!empty($endDate))
		{
			$updateClnts = "UPDATE
								RNet.dbo.prmEmployeePositionClients
							SET
								endDate = '".$endDate."'
							WHERE 
								employeeID = ".$employeeID."
							AND
								positionID = '$positionID' 
							AND
								endDate IS NULL";
			//$rstUpdateClnts = mssql_query($updateClnts, $db);
			$rstUpdateClnts = $employeeeMaintenanceObj->execute($updateClnts);
			
			//echo $updateClnts;exit;
		}

	/*echo '<pre>';
	print_r($_REQUEST);
	echo '</pre>';exit;*/
		foreach($clntsArry as $clntsArryVal)
		{
				unset($cliEffectiveDate);
				unset($clientEndDate);
			$clntsArryVal = str_replace(' ','_',$clntsArryVal); // Vasu added newly.
			
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
					
					$empPositionClients .= " INSERT INTO RNet.dbo.prmEmployeePositionClients (employeeID, positionID, clientName, lob_id, channelID, effectiveDate, endDate)  VALUES ($employeeID, '$positionID', '".$splitAll[0]."', '".$splitAll[1]."' , '".$splitAll[2]."', '".$cliEffectiveDate[$innerCount]."' , ";
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
		
		
		// New logic ------  Is primary.
	if(!empty($hdnClients))
	{		
			unset($chkisPrimary);
			unset($queryUpdate);
			unset($rstUpdate);
			$queryUpdate = "UPDATE  Rnet.dbo.prmEmployeePositionClients SET isPrimary = NULL WHERE employeeID =".$employeeID." AND positionID = ".$positionID." ";
			//$rstUpdate=mssql_query($queryUpdate, $db);
			$rstUpdate = $employeeeMaintenanceObj->execute($queryUpdate);
			
			unset($chkisPrimary);
			unset($queryUpdate);
			unset($rstUpdate);
				
			$chkisPrimary = $_REQUEST['chkisPrimary'][0];
				
			$queryUpdate = "UPDATE Rnet.dbo.prmEmployeePositionClients SET isPrimary = 'Y' WHERE employeeID =".$employeeID." AND clientName = '".$chkisPrimary."' and positionID = ".$positionID." ";
			
			
			//$rstUpdate=mssql_query($queryUpdate, $db);
			$rstUpdate = $employeeeMaintenanceObj->execute($queryUpdate);
			
			
			// Updating the adpClientCode ========================
			if(!empty($chkisPrimary))
			{
				updateADpClientCode($employeeID, $chkisPrimary, $employeeeMaintenanceObj);
			}
		
		// End of updating adpClientCode ======================= 
		
		
	}
	
	
	if(!empty($_REQUEST['chkIsPrimaryAE']))
	{
		foreach($_REQUEST['chkIsPrimaryAE'] as $primaryAEVal)
		{
			unset($isPrimaryAE1);
			unset($rstIsPrimAE1);
			unset($isPrimaryAE2);
			unset($rstIsPrimAE2);
			
			$isPrimaryAE1 = "UPDATE Rnet.dbo.prmEmployeePositionClients SET isPrimaryAE = NULL WHERE clientName = '".$primaryAEVal."' and positionID = ".$positionID." ";
			
			//$rstIsPrimAE1 = mssql_query($isPrimaryAE1, $db);
			$rstIsPrimAE1 = $employeeeMaintenanceObj->execute($isPrimaryAE1);
			
			
			//$val .= $primaryAEVal.'<br>';
			$isPrimaryAE2 = "UPDATE Rnet.dbo.prmEmployeePositionClients SET isPrimaryAE = 'Y' WHERE employeeID =".$employeeID." AND clientName = '".$primaryAEVal."' and positionID = ".$positionID." ";
			//$rstIsPrimAE2 = mssql_query($isPrimaryAE2, $db);
			$rstIsPrimAE2 = $employeeeMaintenanceObj->execute($isPrimaryAE2);
		}
	}
	
	
	}


//Updating previous IsPrimary to 'N' if the current isPrimary is set as 'Y'. Single position to be defined as the primary
			if($isPrimary2 == 'Y')
			{
				$queryUpdate = "UPDATE ctlEmployeePositions SET isPrimary = 'N' WHERE employeeID =".$employeeID;
				//$rstUpdate=mssql_query($queryUpdate, $db);
				$rstUpdate = $employeeeMaintenanceObj->execute($queryUpdate);
			}
		
			//echo $employeeID.'<br/>'.$positionID.'<br/>'.$effDate.'<br/>'.$startDate.'<br/>'.$endDate;
			//exit(0);
		
			$query1 = "UPDATE ctlEmployeePositions SET
					[effectiveDate]='".$startDate."' 
					,modifiedBy = '".$_SESSION['empID']."'
					,modifiedDate = '".date('m/d/Y')."'";

					
			if(!empty($endDate))
			{
				$query1 .= 	" ,[endDate]='".$endDate."' ";
			}
			else
			{
				$query1 .= 	" ,[endDate]=NULL ";
			}
			
			if(!empty($isPrimary2))
			{
				$query1 .= 	" ,[isPrimary]='".$isPrimary2."' ";
			}
					
			$query1 .= 	" WHERE employeeID=$employeeID AND positionID=$positionID AND effectiveDate='".$effDate."'";
			//echo $query1;exit;
			//mssql_query($query1, $db);
			$query1Rs = $employeeeMaintenanceObj->execute($query1);


//-------------------------------------Newly Added by Murali as per task # 7125--------------------------

$clNQ = " SELECT clientName FROM ctlPositions WITH (NOLOCK) WHERE positionID = $positionID AND position LIKE 'Client%' AND clientName IS NOT NULL ";
//$clNRst=mssql_query(str_replace("\'","''",$clNQ), $db);
$clNRst = $employeeeMaintenanceObj->execute($clNQ);

if ($clNRow=mssql_fetch_array($clNRst)) 
{
	$cNameE = $clNRow['clientName'];
}

if(!empty($endDate))
{
	if(!empty($cNameE))
	{
		$ifExistsQ="DELETE FROM ctlEmployeeClients WHERE employeeID = $employeeID AND clientName='".$cNameE."' ";
		//$ifExistsRst=mssql_query(str_replace("\'","''",$ifExistsQ), $db);
		$ifExistsRst = $employeeeMaintenanceObj->execute($ifExistsQ);
	}
}

if(empty($endDate))
{
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
}



			///UPDATING THE EMAIL DISTRIBUTION LIST BASED ON POSITION
			///******************************************************
			
			$qryLoc = "SELECT location FROM ctlEmployees WITH (NOLOCK) WHERE employeeID=".$employeeID;
						//$rstLoc = mssql_query($qryLoc, $db);
						 $rstLoc = $employeeeMaintenanceObj->execute($qryLoc);
						 
						if ($locLoc=mssql_fetch_array($rstLoc)) 
						{	
							$loc = $locLoc[location];
						}
			
			
			if($positionID == 42 || $positionID == 21 || $positionID == 17 || $positionID == 4)
			{
				$recQry2 = "select * from ctlEmailDistributionLists WITH (NOLOCK) where location=$loc and emailDistributionList like '%Recruiters'";
				//$rstQry2 = mssql_query($recQry2, $db);
				$rstQry2 = $employeeeMaintenanceObj->execute($recQry2);
				
				if ($locDL2=mssql_fetch_array($rstQry2)) 
				{	
					$eDL2 = $locDL2[emailDistributionList];
				}
			}
			if($positionID == 12 || $positionID == 13 || $positionID == 19 || $positionID == 21 || $positionID == 23)
			{
				$recQry3 = "select * from ctlEmailDistributionLists WITH (NOLOCK) where location=$loc and emailDistributionList like '%Trainers'";
				//$rstQry3 = mssql_query($recQry3, $db);
				$rstQry3 = $employeeeMaintenanceObj->execute($recQry3);
				if ($locDL3=mssql_fetch_array($rstQry3)) 
				{	
					$eDL2 = $locDL3[emailDistributionList];
				}
			}
			
		
			
			
			
			
			if($eDL2)
			{
				if(!empty($endDate))
				{
					$queryUpdateDstro = "UPDATE ctlEmployeeEmailDistributionLists SET endDate = '$endDate' WHERE employeeID = $employeeID AND  	
										emailDistributionList='".$eDL2."'";
					//$rstUpdateDistro=mssql_query($queryUpdateDstro, $db);
					$rstUpdateDistro = $employeeeMaintenanceObj->execute($queryUpdateDstro);
				}
				else
				{
					$queryUpdateDstro = "UPDATE ctlEmployeeEmailDistributionLists SET endDate = NULL WHERE employeeID = $employeeID 
					                     AND emailDistributionList='".$eDL2."'";
					//$rstUpdateDistro=mssql_query($queryUpdateDstro, $db);
					$rstUpdateDistro = $employeeeMaintenanceObj->execute($queryUpdateDstro);

				
				}
				
				
			}
			
			

/* Vasudev added new logic for employee training class notifications */

if(empty($endDate))
{
	//echo 'No End Date';	
	$sqlQuery = " DELETE FROM ctlEmployeeTrainingClassNotifications
					WHERE employeeID = ".$employeeID." 
					AND
						positionID = ".$positionID." 
					AND wasRead IS NULL ";
	//$resultSet = mssql_query($sqlQuery, $db);
	$resultSet = $employeeeMaintenanceObj->execute($sqlQuery);
	
	unset($sqlQuery);
	unset($resultSet);
		
	$sqlQuery = " EXEC Rnet.dbo.[process_spEmployeeTrainingClassNotificationsPositionUpdate] '$positionID' , '$employeeID' ";
	//$resultSet = mssql_query($sqlQuery, $db);
	$resultSet = $employeeeMaintenanceObj->execute($sqlQuery);
} else {
	
	// Delete was read NULL records from employee training class notifications table.
	
	$sqlQuery = " DELETE FROM ctlEmployeeTrainingClassNotifications
					WHERE employeeID = ".$employeeID." 
					AND
						positionID = ".$positionID." 
					AND wasRead IS NULL ";
	//$resultSet = mssql_query($sqlQuery, $db);
	$resultSet = $employeeeMaintenanceObj->execute($sqlQuery);
}

/* End Vasudev added new logic for employee training class notifications */


if($isPrimary2 == 'Y')
{
	$effectiveDateOnly = date('m/d/Y', strtotime($effDate));
	$requiredArray = $employeeeMaintenanceObj->updateLogFiles($_REQUEST, $employeeID, $effectiveDateOnly);
}
									
						
			
			
			
			///*****************************************************
	
		header("Location: index.php?hdnEmployeeID=".$employeeID."&adpMode=hr&adpTask=empPosition&error=DataIUpdate&activeLink=20");
		exit;
		

	}
}
?>	