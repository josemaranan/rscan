<?php
//header("Location: index_test.php?hdnEmployeeID=".$employeeID."&res=Termfailure&type=$type&adpMode=hr&adpTask=empDatesAdd");
//include_once($_SERVER["DOCUMENT_ROOT"].'/Include/logADPPayrollChangesClass.inc.php');
include_once($_SERVER['DOCUMENT_ROOT']."/RNetIncludes/class1/logADPPayrollChangesClass.inc.php");
$employeeeMaintenanceObj = new ADPPayroll();
include_once($_SERVER["DOCUMENT_ROOT"]."/Include/tempTableStructure.inc.php");
//include_once($_SERVER["DOCUMENT_ROOT"]."/disableLdapUser.php");
//echo 'hello.'; exit;
$employeeID = $_REQUEST['employeeID'];
$termdate = $_REQUEST['termDate'];
$type = $_REQUEST['type'];
unset($_SESSION['deletedUserName']);

$numEmpTrngDays='';

if(!empty($termdate))
{
	$sqlEmpTrngDays = " SELECT hours FROM ctlEmployeeTrainingByDays WITH (NOLOCK) WHERE employeeID = $employeeID AND 
	trainingDate > '".$termdate."' AND hours > 0 ";
	$rstEmpTrngDays = $employeeeMaintenanceObj->execute($sqlEmpTrngDays);
	$numEmpTrngDays = $employeeeMaintenanceObj->getNumRows($rstEmpTrngDays);
}
if($numEmpTrngDays>=1)
{
//	header("Location: employee_careerHistory.php?employeeID=".$employeeID."&res=Termfailure&type=$type");
header("Location: index_test.php?hdnEmployeeID=".$employeeID."&res=Termfailure&type=$type&adpMode=hr&adpTask=empDetails");	
exit;
	
}
else
{
		$employeeID = $_REQUEST["employeeID"];
		//$type = $_REQUEST['type'];
		$hireDate=$_REQUEST['hireDate'];
		$termdate=$_REQUEST['termDate'];
		$ddlvolumeReduction = '';
		$txtClientName = '';
		$txtCategory = '';
		
		if($_REQUEST["hireDate"])
		{
			$hireDate = $_REQUEST['hireDate'];
		}
		if($_REQUEST["termDate"])
		{
			$termDate = $_REQUEST['termDate'];
		}
		if($_REQUEST["ddlTerminationReasons"])
		{
			$TerminationReasons = $_REQUEST['ddlTerminationReasons'];
		}
		if($_REQUEST["ddlRehireable"])
		{
			$Rehireable = $_POST['ddlRehireable'];
		}
		if($_REQUEST["ddlvoluntary"])
		{
			$voluntary = $_REQUEST['ddlvoluntary'];
		}
		if($_REQUEST["notes"])
		{
			$notes = addslashes($_REQUEST['notes']);
		}
		if($_REQUEST["ddlJobStatus"])
		{
			$employmentStatus = $_REQUEST['ddlJobStatus'];
		}
		if($_REQUEST["hdnPrevStatus"])
		{
			$PrevStatus = $_REQUEST['hdnPrevStatus'];
		}
		if($_REQUEST['ddlvolumeReduction'])
		{
			$ddlvolumeReduction = trim($_REQUEST['ddlvolumeReduction']);
		}
		if($_REQUEST['txtClientName'])
		{
			$txtClientName = trim($_REQUEST['txtClientName']);
		}
		if($_REQUEST['txtCategory'])
		{
			$txtCategory = trim($_REQUEST['txtCategory']);
		}
		if($_REQUEST['ddlNCNS'])
		{
			$ddlNCNS = trim($_REQUEST['ddlNCNS']);
		}
			
			
	$queryCheck = "SELECT * FROM RNet.dbo.prmEmployeeCareerHistory WITH (NOLOCK) WHERE employeeID = $employeeID and hireDate = '$hireDate'";
	$rstCheck = $employeeeMaintenanceObj->execute($queryCheck);
	$numCheck=$employeeeMaintenanceObj->getNumRows($rstCheck);
	mssql_free_result($rstCheck);	
	
	if($numCheck == 0) 
	{	
		$queryCheck2 = "
		DECLARE @date as DATETIME
		SET @date = GETDATE()
		".$sqlTemEmployeeCareerHistoryStructure."
		EXEC RNet.dbo.[standard_spEmployeeCareerHistory] '%','$employeeID','Active',@date
		SELECT * FROM #tempEmployeeCareerHistory ";						
		$rstCheck2 = $employeeeMaintenanceObj->execute($queryCheck2);
		$numCheck2=$employeeeMaintenanceObj->getNumRows($rstCheck2);
		mssql_free_result($rstCheck2);
		if($numCheck2 == 0) 
		{	
			$queryCheck3 = "SELECT * from RNet.dbo.prmEmployeeCareerHistory WITH (NOLOCK) WHERE employeeID = $employeeID AND '$hireDate' between hireDate and termDate";
			$rstCheck3 = $employeeeMaintenanceObj->execute($queryCheck3);
			$numCheck3=$employeeeMaintenanceObj->getNumRows($rstCheck3);
			mssql_free_result($rstCheck3);
					
			if($numCheck3 != 0) 
			{	
				header("Location: index_test.php?hdnEmployeeID=".$employeeID."&res=hireDateExistedBetween&hireDate=$hireDate&type=$type&adpMode=hr&adpTask=empDatesAdd");	
				
				//header("Location: employee_careerHistory_Add.php?res=hireDateExistedBetween&employeeID=$employeeID&hireDate=$hireDate&type=$type");
				exit();
			}
			
			$regularization = strtotime(date("m/d/Y", strtotime($hireDate)) . " +6 month");
			$regularizationDate = date("m/d/Y",$regularization);
			$supervisorConfirm = $_REQUEST['chkSupervisorConfirm'];
			$supervisorID = $_SESSION['empID'];
			$supervisorConfirmDate = date("Y-m-d H:i:s");
	
			///////////////////START 2013/02/13
			unset($sqlGetHireDate);
			unset($rstGetHireDate);
			unset($rowGetHireDate);
			unset($chkRecCount);
			$sqlGetHireDate = " SELECT COUNT(*) AS recCount FROM RNet.dbo.prmEmployeeCareerHistory WITH (NOLOCK) WHERE employeeID = '$employeeID' AND termDate IS NULL";
			//echo $sqlGetHireDate;exit;
			$rstGetHireDate = $employeeeMaintenanceObj->execute($sqlGetHireDate);
			while($rowGetHireDate = mssql_fetch_assoc($rstGetHireDate))
			{
				$chkRecCount = $rowGetHireDate['recCount'];
			}
			unset($sqlGetHireDate);
			unset($rowGetHireDate);
			mssql_free_result($rstGetHireDate);
			///////////////////END 2013/02/13
			
			
			$queryHistories = "INSERT INTO RNet.dbo.prmEmployeeCareerHistory 	
			(employeeID,hireDate,regularizationDate,notes,modifiedBy,modifiedDate, hireDateReviewedBy , hireDateReviewedDate ";
			if(!empty($termDate))
			{
				$queryHistories .= " ,termDate ";
			}
			if(!empty($TerminationReasons))
			{
				$queryHistories .= " ,terminationReasonID ";
			}
			if(!empty($Rehireable))
			{
				$queryHistories .= " ,canBeRehired ";
			}
			if(!empty($voluntary))
			{
				$queryHistories .= " ,voluntaryTermination ";
			}
			if(!empty($ddlvolumeReduction))
			{
				$queryHistories .= " ,clientVolumeReduction ";
			}
			/*if(!empty($txtClientName))
			{*/
				$queryHistories .= " ,clientName ";
			//}
			if(!empty($txtCategory))
			{
				$queryHistories .= " ,clientCategory ";
			}
			if(!empty($ddlNCNS))
			{
				$queryHistories .= " ,NCNS ";
			}
			if($supervisorConfirm == 'Y')
			{
				$queryHistories .= " ,supervisorID, supervisorTermConfirmed, supervisorConfirmDate ";
			}
	
			$queryHistories .= ")values 
			(".$employeeID.",'".$hireDate."','".$regularizationDate."','".$notes."','".$_SESSION['empID']."' , '".date("m/d/Y")."', '".$_SESSION['empID']."' , '".date("m/d/Y")."'";
			
			if(!empty($termDate))
			{
				$queryHistories .= " ,'$termDate' ";
			}
			if(!empty($TerminationReasons))
			{
				$queryHistories .= " ,'$TerminationReasons' ";
			}
			if(!empty($Rehireable))
			{
				$queryHistories .= " ,'$Rehireable' ";
			}
			if(!empty($voluntary))
			{
				$queryHistories .= " ,'$voluntary' ";
			}
			if(!empty($ddlvolumeReduction))
			{
				$queryHistories .= " , '$ddlvolumeReduction'";
			}
			if(!empty($txtClientName))
			{
				$queryHistories .= " , '$txtClientName'";
			}
			else
			{
				$queryHistories .= " , 'N/A' ";
			}
			if(!empty($txtCategory))
			{
				$queryHistories .= " , '$txtCategory'";
			}
			if(!empty($ddlNCNS))
			{
					$queryHistories .= " , '$ddlNCNS'";;
			}
			if($supervisorConfirm == 'Y')
			{
				$queryHistories .= " ,'$supervisorID', 'Y', '$supervisorConfirmDate' ";
			}
			$queryHistories .= " ) ";
			$rst = $employeeeMaintenanceObj->execute($queryHistories);
				
			/* Log table entry */
			//$effectiveDateOnly = date('m/d/Y', strtotime($hireDate));
			//$requiredArray = $employeeeMaintenanceObj->updateLogFiles($_REQUEST, $employeeID, $effectiveDateOnly);
			
			///////////////////START 2013/02/13
			
			
			/* if($chkRecCount==0)
			{
				// code hrere....
				
				//$employeeeMaintenanceObj->synchronizeHireDates($employeeID , $hireDate , 
			}
			*/
			///END TASK 29761
			///////////////////END 2013/02/13
			
			
			/* Log table entry */
			unset($sqlIsEADP);
			unset($rstIsEADP);
			unset($rowIsEADP);
			unset($isExistInADP);
			
			$sqlIsEADP = " SELECT isExistInADP FROM ctlEmployees WITH (NOLOCK) WHERE employeeID = '$employeeID' ";
			$rstIsEADP = $employeeeMaintenanceObj->execute($sqlIsEADP);
			while($rowIsEADP = mssql_fetch_assoc($rstIsEADP))
			{
				$isExistInADP = $rowIsEADP['isExistInADP'];
			}
			
			$effectiveDateOnly = date('m/d/Y', strtotime($hireDate));
			if($isExistInADP!='Y')
			{
				$requiredArray = $employeeeMaintenanceObj->updateRehireData($employeeID, $effectiveDateOnly, '');
			}
			else
			{
				$requiredArray = $employeeeMaintenanceObj->updateLogFiles($_REQUEST, $employeeID, $effectiveDateOnly);
			}
			/* Log table entry */
			mssql_free_result($rst);
			mssql_free_result($rstIsEADP);
			unset($sqlIsEADP);
			unset($rowIsEADP);
			unset($isExistInADP);
			
			
			///////////////////START 2013/02/11
			unset($sqlMaxPEffDate);
			unset($rstMaxPEffDate);
			unset($rowMaxPEffDate);
			unset($getMaxEffDate);
			$sqlMaxPEffDate = " SELECT MAX(effectiveDate) maxEffDate FROM RNet.dbo.prmEmployeePayrollLocations WITH  (NOLOCK) WHERE employeeID = '$employeeID' ";
			$rstMaxPEffDate = $employeeeMaintenanceObj->execute($sqlMaxPEffDate);
			while($rowMaxPEffDate = mssql_fetch_assoc($rstMaxPEffDate))
			{
				$getMaxEffDate = date('m/d/Y',strtotime($rowMaxPEffDate['maxEffDate']));
			}
			mssql_free_result($rstMaxPEffDate);
			
			unset($sqlPayrolUpd);
			$sqlPayrolUpd = "UPDATE 
								 a
							 SET
								 a.effectiveDate = '".$hireDate."',
								 a.modifiedBy = '".$sesEmployID."',
								 a.modifiedDate = '".$modifiedDateTime."'
							 FROM 
								 RNet.dbo.prmEmployeePayrollLocations a WITH (NOLOCK) 
							 WHERE
								 a.employeeID = '$employeeID'
							 AND
								 CONVERT(VARCHAR(10),a.effectiveDate,101) = '".$getMaxEffDate."' ";
			//echo $sqlPayrolUpd;exit;
			$employeeeMaintenanceObj->execute($sqlPayrolUpd);
			///////////////////END 2013/02/11.
			
			/* Log table entry */
			mssql_free_result($rst);
					
			if(!empty($termDate))
			{
				$mDate=date("Y-m-d H:i:s");
				// 2) Updating ctlEmployees table 
				$queryEmployees = "	UPDATE ctlEmployees SET
									Locked = 'Y', Enabled = 'N', Internal = 'N', [External] = 'N',
									modifiedDate = '".$mDate."',  modifiedBy = '$empID'  WHERE 
									employeeID = '$employeeID'";
				$rstEmployees = $employeeeMaintenanceObj->execute($queryEmployees);
				mssql_free_result($rstEmployees);
			
				// 3) Removing training dates effective immediately for employee in a class
				$sqlRemTraingDates = "	UPDATE ctlEmployeeTrainingByDays 
										SET status = 'terminated', modifiedDate = '".$mDate."',
										modifiedBy = '$empID'  WHERE employeeID = '$employeeID' AND 
										trainingDate > '".$termDate."'";
				$rst1 = $employeeeMaintenanceObj->execute($sqlRemTraingDates);
				mssql_free_result($rst1);
			
				// 4) updating dropdate to ctlEmployeeTrainings table
				$sqlUpdateTraingClass = "UPDATE a SET dropDate = '".$termDate."'
										FROM ctlEmployeeTrainings a WITH (NOLOCK) 
										JOIN ctlTrainings b WITH (NOLOCK) 
										ON a.TrainingClassID = b.TrainingClassID WHERE employeeID = $employeeID 
										AND b.trainingStartDate >= '".$termDate."' ";
				$rst2 = $employeeeMaintenanceObj->execute($sqlUpdateTraingClass);
				mssql_free_result($rst2);
			
				// 5) Updating ctlEmployeeEmailDistributionLists table
				$sqlUpdateEDL = "UPDATE ctlEmployeeEmailDistributionLists 
								 SET endDate = '".$termDate."' WHERE employeeID = '".$employeeID."'";
				$rstEDL = $employeeeMaintenanceObj->execute($sqlUpdateEDL);
				mssql_free_result($rstEDL);
				
				// 7) Updating ctlRodLocations table
				$sqlUpdateRODLoc = "UPDATE 
										ctlRodLocations  
									SET 
											endDate = '".$termDate."' 
									WHERE 
										employeeID = '".$employeeID."'";
				$rstRODLoc = $employeeeMaintenanceObj->execute($sqlUpdateRODLoc);
				mssql_free_result($rstRODLoc);
				
				// 8) Updating prmEmployeePositionClients table.
				$sqlUpdatePositionClients =" UPDATE Rnet.dbo.prmEmployeePositionClients  
											 SET endDate = '".$termDate."' WHERE 
											 employeeID = '".$employeeID."' AND endDate IS NULL ";
				$sqlUpdatePositionRes = $employeeeMaintenanceObj->execute($sqlUpdatePositionClients);
				mssql_free_result($sqlUpdatePositionRes);
	
				// 9 ) Updating prmEmployeePositionLocations table.
				$sqlUpdatePositionLocations = "	UPDATE Rnet.dbo.prmEmployeePositionLocations  
												SET endDate = '".$termDate."' WHERE 
												employeeID = '".$employeeID."' AND endDate IS NULL ";
				$sqlUpdatePositionLocationsRes = $employeeeMaintenanceObj->execute($sqlUpdatePositionLocations);
				mssql_free_result($sqlUpdatePositionLocationsRes);
				
				///////////////////////////////////////////////////////////////////////////////////////
				//Disable LDAP Acount Code Start
				//////////////////////////////////////////////////////////////////////////////////////
				
				/*$sqlUserName = "SELECT userName FROM ctlEmployeeApplications WITH (NOLOCK) WHERE 
								applicationName = 'AD' AND employeeid =  '$employeeID' ";
				$rstUserName=mssql_query($sqlUserName, $db);
				while ($rowUserName=mssql_fetch_array($rstUserName)) 
				{
					unset($_SESSION['deletedUserName']);
					$userName = $rowUserName[userName];
					$_SESSION['deletedUserName']  = $userName;
				}
				mssql_free_result($rstUserName);
				
				if($userName != '')
				{
					$du = disableLdapUser($userName);
					
					if($du == '')
					{
						  $du = disableLdapUser($userName);
					}
					
					if($du == '')
					{
						$queryLdap = "Insert into logLDAPEnabledTerminatedUsers 
									(employeeID,userName,modifiedBy,modifiedDate)
									values 
									(".$employeeID.",'".$userName."',".$_SESSION[empID]." , '".date("m/d/Y")."')";
						$rstLdap=mssql_query(str_replace("\'","''",$queryLdap), $db);
						mssql_free_result($rstLdap);
					 
					 }
				}*/
				///////////////////////////////////////////////////////////////////////////////////////
				//Disable LDAP Acount Code END
				//////////////////////////////////////////////////////////////////////////////////////
				
					sendTerminationNotification($employeeID,$hireDate, $employeeeMaintenanceObj);
				}
				else
				{
					//Inserting Employment Status
					$queryStatuses = "	INSERT INTO ctlEmployeeStatuses
										(employeeID,effectiveDate,employmentStatus,modifiedBy,modifiedDate)
										VALUES
										(".$employeeID.",'".$hireDate."', $employmentStatus, '".$_SESSION[empID]."', 
										'".date("m/d/Y")."')";
					$rst2 = $employeeeMaintenanceObj->execute($queryStatuses);
					mssql_free_result($rst2);
			
					$queryEmployees = "	UPDATE ctlEmployees SET
										Locked = NULL, Enabled = 'Y', Internal = 'Y', [External] = 'Y',
										modifiedDate = '".$mDate."', modifiedBy = '$empID' WHERE 
										employeeID = '$employeeID'";
					$rstEmployees = $employeeeMaintenanceObj->execute($queryEmployees);
					mssql_free_result($rstEmployees);
				}
				
				//include_once('updateOrigHireDate.php');
				/*if($type=='ADP')
				{
					header("Location: ../../../Payroll/employeeADP/employeeAuthentication.php?employeeID=".$employeeID);
					exit;
				}
				else if($type == 'rehire')
				{
					header("Location: employeeMasterView.php?employeeID=".$employeeID);
				}
				else
				{
					header("Location: employee_careerHistory.php?employeeID=".$employeeID);
				}*/
				
				header("Location: index_test.php?hdnEmployeeID=".$employeeID."&adpMode=hr&adpTask=empDates&error=DataInsert&activeLink=20");
				exit;
				
			}
			else
			{
				//header("Location: employee_careerHistory_Add.php?res=updateTermdate&employeeID=$employeeID&hireDate=$hireDate&type=$type");
				header("Location: index_test.php?hdnEmployeeID=".$employeeID."&res=updateTermdate&hireDate=$hireDate&type=$type&adpMode=hr&adpTask=empDatesAdd");
				exit;
				
				
			}
		}
		else
		{
			header("Location: index_test.php?hdnEmployeeID=".$employeeID."&res=hireDateAlreadyExisted&hireDate=$hireDate&type=$type&adpMode=hr&adpTask=empDatesAdd");
			exit;
			
			
			//header("Location: employee_careerHistory_Add.php?res=hireDateAlreadyExisted&employeeID=$employeeID&hireDate=$hireDate&type=$type");
		}
	
}


function sendTerminationNotification($employeeID,$hireDate, $employeeeMaintenanceObj)
{
	$payrollLocationID = $employeeeMaintenanceObj->getEmployeeCurrentPayrollLocation($employeeID);
	
	$qryApp = "SELECT applicationName,userName FROM ctlEmployeeApplications WITH (NOLOCK) WHERE 
				employeeID = '".$employeeID."' ORDER BY applicationName";
	$eRstApp = $employeeeMaintenanceObj->execute($qryApp);
	while($rowApp=mssql_fetch_array($eRstApp))
	{
		$empApplications .= $rowApp[applicationName]." - ".$rowApp[userName]."\n";
	}
	mssql_free_result($eRstApp);
	
	$qry = "SELECT * FROM ctlEmployees WITH (NOLOCK) WHERE employeeID = '".$employeeID."'";
	$eRst = $employeeeMaintenanceObj->execute($qry);
	while($row1=mssql_fetch_assoc($eRst))
	{
		$name = $row1['firstName']." ".$row1['lastName'];
		$locationID = $row1['location'];
		
		
		$locqry = "SELECT * FROM ctlLocations WITH (NOLOCK) WHERE location='".$locationID."'";
		$locRst = $employeeeMaintenanceObj->execute($locqry);
		while($rowLoc=mssql_fetch_assoc($locRst))
		{
			$location = $rowLoc['description'];
		}
	}
	mssql_free_result($eRst);
	
	$sqlPOS = "	SELECT positionID FROM ctlEmployeePositions WITH (NOLOCK)
				WHERE employeeID = '$employeeID'  AND 
				effectiveDate = (SELECT MAX(effectiveDate) FROM ctlEmployeePositions WITH (NOLOCK) WHERE 
								employeeID = '$employeeID') 
				AND ISNULL(positionID, '') != ''";
	$rstPOS = $employeeeMaintenanceObj->execute($sqlPOS);
	while($row=mssql_fetch_assoc($rstPOS)) 
	{	
		$positionID = $row['positionID'];
	}
	mssql_free_result($rstPOS);
	
	$posqry = "SELECT * FROM ctlPositions WITH (NOLOCK) WHERE positionID='".$positionID."'";
	$posRst = $employeeeMaintenanceObj->execute($posqry);
	while($rowPos=mssql_fetch_assoc($posRst))
	{
		$position = $rowPos['position'];
		$businessFunction = $rowPos['businessFunction'];						
	}
	mssql_free_result($posRst);	
	
	/* AS per the #53861 added the below code to send notificaitonto LRPS*/
	unset($totalDisList);
	unset($sqlQuery);
	unset($resultsSet);
	unset($numRows);
	unset($fzqu);
	
	$sqlQuery = " SELECT RNet.dbo.[fn_getLocationPositionEmailIDs] ('0','40') as emailDistro ";
	$resultsSet = $employeeeMaintenanceObj->execute($sqlQuery);
	$numRows = $employeeeMaintenanceObj->getNumRows($resultsSet);
	if($numRows>0)
	{
		while ($fzqu=mssql_fetch_assoc($resultsSet)) 
		{	
			$totalDisList .= $fzqu['emailDistro'];
		}
	}
	
	unset($sqlQuery);
	unset($resultsSet);
	unset($numRows);
	unset($fzqu);
	
	$sqlQuery = " SELECT RNet.dbo.[fn_getLocationPositionEmailIDs] ('".$locationID."','5') as emailDistro ";
	$resultsSet = $employeeeMaintenanceObj->execute($sqlQuery);
	$numRows = $employeeeMaintenanceObj->getNumRows($resultsSet);
	if($numRows>0)
	{
		if(!empty($totalDisList))
		{
			$totalDisList .= $totalDisList.',';	
		}
		while ($fzqu=mssql_fetch_assoc($resultsSet)) 
		{	
			$totalDisList .= $fzqu['emailDistro'];
		}
	}
	
	//echo 'Total DistributionList'.$totalDisList;
	//exit();
	
	
	/* */
	
	$mailAddress = "rnet-system@resultstel.com";

	//$ToEmail = "loginadmin@resultstel.com";
	/*while($row2=mssql_fetch_array($loopMailRst))
	{
		 $ToEmail .= ",".trim($row2[emailAddress]);
	}*/
	$EmailSubject = "Termination Notification - ".$name." (".$location.")"; 
					 
	 // Always set content-type when sending HTML email
	$headers = "MIME-Version: 1.0" . "\r\n";
	//$headers .= "Content-type: text/html; charset=iso-8859-1" . "\r\n";
	//$headers .= 'Bcc: Juan.Ponder@resultstel.com, vengalrao.sivvannagari@resultstel.com' . "\r\n";
	// More headers
	$headers .= "From: ".$mailAddress . "\r\n";
	$MESSAGE_BODY = "Hi, \n\n";
	$MESSAGE_BODY .= $name.", a ".$position ." in the ".$location." office is no longer an employee with Results.  Please take the appropriate actions to remove this individual's access to Results and client systems.";
	$MESSAGE_BODY .="\n";
	$MESSAGE_BODY .="\n";
	$MESSAGE_BODY .='Employee\'s known applications:';
	$MESSAGE_BODY .="\n";
	$MESSAGE_BODY .="\n";
	$MESSAGE_BODY .= $empApplications;
			
	$MESSAGE_BODY .= "\n\n\nThank you.\n";
	
	@mail($totalDisList, $EmailSubject, $MESSAGE_BODY, $headers);

	//@mail('Juan.Ponder@resultstel.com', $EmailSubject, $MESSAGE_BODY, $headers);

	@mail('vengal.sivvannagari@resultstel.com', $EmailSubject, $MESSAGE_BODY, $headers);
	@mail('vasudev.sarvepalli@resultstel.com', $EmailSubject, $MESSAGE_BODY, $headers);
		
	$qryDeleteADLogin = "
	
	
	INSERT INTO 
		RNet.dbo.prmHistoricalEmployeeApplications
		(
		 	employeeID,
		 	applicationName,
		 	userName,
			password,
			modifiedBy,
			modifiedDate,
		 	hireDate,
			removedBy,
			removedDate
		 )
		SELECT
			employeeID,
		 	applicationName,
		 	userName,
			password,
			modifiedBy,
			modifiedDate,
			'".$hireDate."',
			'".$_SESSION['empID']."',
			'".date('Y-m-d H:i:s')."'
		FROM 
			ctlEmployeeApplications WHERE applicationName='AD' AND employeeID='".$employeeID."' 
	
	
	
	
	DELETE FROM ctlEmployeeApplications WHERE applicationName='AD' AND employeeID='".$employeeID."'";
	$deleteRst = $employeeeMaintenanceObj->execute($qryDeleteADLogin);
	mssql_free_result($loopMailRst);
	mssql_free_result($deleteRst);
		
	/*Newly Added On December 22nd 2011 (Seperation Email)*/

	$seperatorID = $_SESSION['empID'];
	$trainingClassID = '';
	
	$trainingClassQuery = "SELECT TOP 1 a.trainingClassID FROM ctltrainings a WITH (NOLOCK)
							JOIN
								ctlemployeetrainings b WITH (NOLOCK)
							ON
								a.trainingClassID = b.trainingClassID
							WHERE
								b.employeeID = '".$employeeID."'
							AND
								(a.trainingtype = 'New Hire'	OR a.trainingtype ='New Hire (attrition)')
							AND
								a.trainingStartDate >= '$hireDate'";
	$trainingClassRes = $employeeeMaintenanceObj->execute($trainingClassQuery);
	$trainingClassID = mssql_result($trainingClassRes,0,0);
	
	////////////////////////////////////TASK NO 27936//////////////////////////////////////////////////////////////
	//include_once('separationNotification.php');
	////////////////////////////////////END OF TASK NO 27936///////////////////////////////////////////////////////
	
} // eof 
	
function getSeperationEmailAddress($location,$employeeID, $employeeeMaintenanceObj)
{
	
	$SqlQuery = " EXEC RNET.dbo.[report_spSeperationEmailAddressesDistroList] $location, $employeeID ";
	$ResultSet = $employeeeMaintenanceObj->execute($SqlQuery);
	$numRows = $employeeeMaintenanceObj->getNumRows($ResultSet);
	if($numRows>0)
	{
		while($resultsAugh = mssql_fetch_assoc($ResultSet))
		{
			$authorizedemp[] = $resultsAugh['emailAddress'];
		}
	}
	return $authorizedemp;
}
mssql_close();


?>