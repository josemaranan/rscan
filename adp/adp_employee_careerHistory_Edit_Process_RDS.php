<?php
//ini_set('display_errors','1');
//include_once($_SERVER["DOCUMENT_ROOT"].'/Include/logADPPayrollChangesClass.inc.php');
include_once($_SERVER['DOCUMENT_ROOT']."/RNetIncludes/class1/logADPPayrollChangesClass.inc.php");
$employeeeMaintenanceObj = new ADPPayroll();
include_once($_SERVER["DOCUMENT_ROOT"]."/Include/tempTableStructure.inc.php");
//include_once($_SERVER["DOCUMENT_ROOT"]."/disableLdapUser.php");
//echo 'hello...'; exit;
$employeeID = $_REQUEST['employeeID'];
$termdate = $_REQUEST['termDate'];

$type = '';

if(isset($_REQUEST['type']))
{
	$type = $_REQUEST['type'];
}

$selectedhireDate = $_REQUEST['hireDate'];
$hdnOriginalHireDate = $_REQUEST['hdnOriginalHireDate'];

	/*echo '<pre>';
	print_r($_REQUEST);
	echo '</pre>';
	exit;*/

$chkConfirmHireDate = $_REQUEST['chkConfirmHireDate'];
$chkOriginalHireDate = $_REQUEST['chkOriginalHireDate'];


	
	
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
	//header("Location: employee_careerHistory.php?employeeID=".$employeeID."&res=Termfailure&type=$type");
	header("Location: index_test.php?hdnEmployeeID=".$employeeID."&res=Termfailure&type=$type&adpMode=hr&adpTask=empDetails");	
}
else
{
	$employeeID = $_REQUEST['employeeID'];
	$termdate = $_REQUEST['termDate'];
	$hireDate=$_REQUEST['hireDate'];
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
		$Rehireable = $_REQUEST['ddlRehireable'];
	}
	if($_REQUEST["ddlvoluntary"])
	{
		$voluntary = $_REQUEST['ddlvoluntary'];
	}
	if($_REQUEST["notes"])
	{
		$notes = addslashes($_REQUEST['notes']);
	}
	if($_REQUEST["hdnPrevStatus"])
	{
		$PrevStatus = $_REQUEST['hdnPrevStatus'];
	}
	if($_REQUEST["hdnhDate"])
	{
		$hdnhDate = $_REQUEST['hdnhDate'];
	}
	if($_REQUEST["hdntDate"])
	{
		$hdntDate = $_REQUEST['hdntDate'];
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
		
		
		
	if($hdnhDate != $hireDate)
	{
		$queryCheck = "SELECT * FROM RNet.dbo.prmEmployeeCareerHistory WITH (NOLOCK) WHERE employeeID = $employeeID and hireDate = '$hireDate'";
		$rstCheck = $employeeeMaintenanceObj->execute($queryCheck);
		$numCheck=$employeeeMaintenanceObj->getNumRows($rstCheck);
		while($rowConf=mssql_fetch_assoc($rstCheck)) 
		{	
			$supervisorTermConfirmed = $rowConf[supervisorTermConfirmed];
		}
		mssql_free_result($rstCheck);	
		
		if($numCheck != 0) 
		{	//header("Location: employee_careerHistory_Edit.php?res=hireDateAlreadyExisted&employeeID=$employeeID&hireDate=$hdnhDate&hireDate1=$hireDate&type=$type");
			header("Location: index_test.php?hdnEmployeeID=".$employeeID."&res=hireDateAlreadyExisted&hireDate=$hdnhDate&hireDate1=$hireDate&type=$type&adpMode=hr&adpTask=empDatesEdit");
			exit();
		}
			
		$queryCheck3 = "SELECT * FROM RNet.dbo.prmEmployeeCareerHistory WITH (NOLOCK) WHERE employeeID = $employeeID AND '$hireDate' between hireDate and termDate";
		$rstCheck3 = $employeeeMaintenanceObj->execute($queryCheck3);
		$numCheck3=$employeeeMaintenanceObj->getNumRows($rstCheck3);
		mssql_free_result($rstCheck3);
				
		if($numCheck3 != 0) 
		{	
			//header("Location: employee_careerHistory_Edit.php?res=hireDateExistedBetween&employeeID=$employeeID&hireDate=$hdnhDate&hireDate1=$hireDate&type=$type");
			header("Location: index_test.php?hdnEmployeeID=".$employeeID."&res=hireDateExistedBetween&hireDate=$hdnhDate&hireDate1=$hireDate&type=$type&adpMode=hr&adpTask=empDatesEdit");
			
			exit();
		}
		
		
		if(empty($termDate))
		{
			//UPDATING EMPLOYMENT STATUS
			unset($sqlQry);
			$sqlQry = "	UPDATE 
							ctlEmployeeStatuses
						SET
							effectiveDate = '".$hireDate."',
							modifiedBy = '".$sesEmployID."',
							modifiedDate = '".$modifiedDateTime."'
						WHERE
							employeeID = $employeeID
						AND
							effectiveDate = '".$hdnhDate."' ";
			//echo $sqlQry;exit;
			$employeeeMaintenanceObj->execute($sqlQry);
			
			//UPDATING EMPLOYEE POSITIONS
			unset($sqlQry);
			$sqlQry = "	UPDATE 
							ctlEmployeePositions
						SET
							effectiveDate = '".$hireDate."',
							modifiedBy = '".$sesEmployID."',
							modifiedDate = '".$modifiedDateTime."'
						WHERE
							employeeID = $employeeID
						AND
							effectiveDate = '".$hdnhDate."' ";
			//echo $sqlQry;exit;
			$employeeeMaintenanceObj->execute($sqlQry);
			
			//UPDATING EMPLOYEE SUPERVISORS
			unset($sqlQry);
			$sqlQry = "	UPDATE 
							ctlEmployeeSupervisors
						SET
							effectiveDate = '".$hireDate."',
							modifiedBy = '".$sesEmployID."',
							modifiedDate = '".$modifiedDateTime."'
						WHERE
							employeeID = $employeeID
						AND
							effectiveDate = '".$hdnhDate."' ";
			//echo $sqlQry;exit;
			$employeeeMaintenanceObj->execute($sqlQry);
		}
			
	}
	$regularization = strtotime(date("m/d/Y", strtotime($hireDate)) . " +6 month");
	$regularizationDate = date("m/d/Y",$regularization);
	$supervisorConfirm = $_REQUEST['chkSupervisorConfirm'];
	$supervisorID = $_SESSION[empID];
	$supervisorConfirmDate = date("Y-m-d H:i:s");
	
	$employeeeMaintenanceObj->synchronizeHireDates($employeeID , $hireDate , $hdnhDate, 'edit');
	
	/*******************Newly Added as a part of the task #29010***************************/
	unset($sqlPayrolUpd);
	$sqlPayrolUpd = "	UPDATE 
							a
						SET
							a.effectiveDate = '".$hireDate."'
						FROM 
							RNet.dbo.prmEmployeePayrollLocations a WITH (NOLOCK) 
						WHERE
							a.employeeID = $employeeID 
						AND
							a.effectiveDate = '".$hdnhDate."' ";
	//echo $sqlPayrolUpd;exit;
	$employeeeMaintenanceObj->execute($sqlPayrolUpd);
	/*******************END of newly Added as a part of the task #29010***********************/
	
	
	$queryHistories = "	UPDATE 
							RNet.dbo.prmEmployeeCareerHistory 
						SET 
							hireDate = '".$hireDate."',
							regularizationDate = '".$regularizationDate."',
							notes ='".$notes."',
							modifiedBy = '".$_SESSION[empID]."',
							modifiedDate = '".date("m/d/Y")."' "; 
	if(!empty($termDate))
	{
		$queryHistories .= ",termDate = '".$termDate."'";
	}
	if(!empty($TerminationReasons))
	{
		$queryHistories .= " ,terminationReasonID = '$TerminationReasons'";
	}
	if(!empty($Rehireable))
	{
		$queryHistories .= " ,canBeRehired = '$Rehireable'";
	}
	if(!empty($voluntary))
	{
		$queryHistories .= " ,voluntaryTermination = '$voluntary'";
	}
	if(!empty($ddlvolumeReduction))
	{
		$queryHistories .= " ,clientVolumeReduction = '$ddlvolumeReduction'";
	}
	if(!empty($txtClientName))
	{
		$queryHistories .= " ,clientName = '$txtClientName'";
	}
	else
	{
		$queryHistories .= " ,clientName = 'N/A' ";
	}
	if(!empty($txtCategory))
	{
		$queryHistories .= " ,clientCategory = '$txtCategory'";
	}
	if(!empty($ddlNCNS))
	{
		$queryHistories .= " ,NCNS = '$ddlNCNS'";
	}
	if($supervisorTermConfirmed != 'Y')
	{
		if($supervisorConfirm == 'Y')
		{
			$queryHistories .= " ,supervisorID = '$supervisorID'";
			$queryHistories .= " ,supervisorTermConfirmed = 'Y'";
			$queryHistories .= " ,supervisorConfirmDate = '$supervisorConfirmDate'";
		}
	}
	
	/* if(!empty($chkConfirmHireDate))
	{
		$queryHistories .= " , hireDateReviewedBy = '".$_SESSION[empID]."',  hireDateReviewedDate = '".date("m/d/Y")."'	"; 
	}
	*/
	
	$queryHistories .= " , hireDateReviewedBy = '".$_SESSION[empID]."',  hireDateReviewedDate = '".date("m/d/Y")."'	"; 
	
	
	/*if(!empty($chkOriginalHireDate))
	{
			$queryHistories .= " ,hireDateIsOrigHIreDate = 'Y'";
	}*/
		
		
	$queryHistories .= " WHERE employeeID = '$employeeID' and hireDate = '$hdnhDate' ";
	
	
	
	$rst = $employeeeMaintenanceObj->execute($queryHistories);
	
	/* The below logic may need to optimize. Need to revisit this code*/
	
	
	unset($sqlQuery);
	unset($resultSet);
	
	$sqlQuery = " UPDATE 
							Rnet.dbo.prmEmployeeCareerHistory 
						SET 
							hireDateIsOrigHIreDate = 'N' WHERE employeeID = '".$employeeID."' ";
		$resultSet = $employeeeMaintenanceObj->execute($sqlQuery);
		
		unset($sqlQuery);
		unset($resultSet);
		
		$sqlQuery = " UPDATE 
							ctlEmployees 
						SET 
							origHireDate = '".$hdnOriginalHireDate."' WHERE employeeID = '".$employeeID."' ";
					
		$resultSet = $employeeeMaintenanceObj->execute($sqlQuery);
		
		
		unset($sqlQuery);
		unset($resultSet);
		
		$sqlQuery = " UPDATE 
							Rnet.dbo.prmEmployeeCareerHistory 
						SET 
							hireDateIsOrigHIreDate = 'Y' WHERE employeeID = '".$employeeID."' AND hiredate = '".$hdnOriginalHireDate."' ";
		
		$resultSet = $employeeeMaintenanceObj->execute($sqlQuery);
		
		unset($sqlQuery);
		unset($resultSet);
	/*if(!empty($chkOriginalHireDate))
	{
		$sqlQuery = " UPDATE 
							Rnet.dbo.prmEmployeeCareerHistory 
						SET 
							hireDateIsOrigHIreDate = 'N' WHERE employeeID = '".$employeeID."' ";
		$resultSet = $employeeeMaintenanceObj->execute($sqlQuery);
		
		unset($sqlQuery);
		unset($resultSet);
		
		$sqlQuery = " UPDATE 
							ctlEmployees 
						SET 
							origHireDate = '".$selectedhireDate."' WHERE employeeID = '".$employeeID."' ";
		$resultSet = $employeeeMaintenanceObj->execute($sqlQuery);
		
	}
	else
	{
		$sqlQuery = " UPDATE 
							Rnet.dbo.prmEmployeeCareerHistory 
						SET 
							hireDateIsOrigHIreDate = 'N' WHERE employeeID = '".$employeeID."' ";
		$resultSet = $employeeeMaintenanceObj->execute($sqlQuery);
		
		unset($sqlQuery);
		unset($resultSet);
		
		$sqlQuery = " SELECT min(hireDate) minHireDate 
						FROM
							Rnet.dbo.prmEmployeeCareerHistory  WITH (NOLOCK)
						WHERE 
								employeeID = '".$employeeID."' ";
		$resultSet = $employeeeMaintenanceObj->execute($sqlQuery);
		
		$systemMinHireDate = mssql_result($resultSet,0,0);
		
		unset($sqlQuery);
		unset($resultSet);
		
		$sqlQuery = " UPDATE 
							Rnet.dbo.prmEmployeeCareerHistory 
						SET 
							hireDateIsOrigHIreDate = 'Y' WHERE employeeID = '".$employeeID."' AND hiredate = '".$systemMinHireDate."' ";
		$resultSet = $employeeeMaintenanceObj->execute($sqlQuery);
		
		
		unset($sqlQuery);
		unset($resultSet);
		
				$sqlQuery = " UPDATE 
							ctlEmployees 
						SET 
							origHireDate = '".$systemMinHireDate."' WHERE employeeID = '".$employeeID."' ";
		$resultSet = $employeeeMaintenanceObj->execute($sqlQuery);
		
		
		
	}*/
	
	/* Log table entry */
	
	if(!empty($termDate))
	{
		$effectiveDateOnly = date('m/d/Y', strtotime($termDate));
	}
	else if(!empty($hireDate))
	{
		$effectiveDateOnly = date('m/d/Y', strtotime($hireDate));
	}
	else
	{
		$effectiveDateOnly = date('m/d/Y');
	}
	
	$requiredArray = $employeeeMaintenanceObj->updateLogFiles($_REQUEST, $employeeID, $effectiveDateOnly);
	
	/*if($hdnhDate != $hireDate)
	{*/
		unset($hireDateOnly);
		$hireDateOnly = date('m/d/Y', strtotime($hireDate));
		$employeeeMaintenanceObj->updateRehireData($employeeID, $hireDateOnly, '', 'Update');
	//}
	/* Log table entry */
	mssql_free_result($rst);

	//updating ctlEmployeeStatuses table
	$queryUpdateSt1 = "UPDATE ctlEmployeeStatuses
					   SET 
							effectiveDate = '".$hireDate."',
							modifiedBy = '".$_SESSION['empID']."',
							modifiedDate = '".date("m/d/Y")."'
					   WHERE employeeID = '$employeeID' AND effectiveDate = '$hdnhDate' ";
	$rstst1 = $employeeeMaintenanceObj->execute($queryUpdateSt1);
	mssql_free_result($rstst1);
					
	//updating ctmEmployeeStatuses table status as 'terminated'
	if(!empty($termDate))
	{
		$mDate=date("Y-m-d H:i:s");
		// 2) Updating ctlEmployees table 
		$queryEmployees = " UPDATE ctlEmployees SET 
							Locked = 'Y', Enabled = 'N', Internal = 'N', [External] = 'N',
							modifiedDate = '".$mDate."',  modifiedBy = '$empID' 
							WHERE employeeID = '$employeeID'";
		$rstEmployees = $employeeeMaintenanceObj->execute($queryEmployees);
		mssql_free_result($rstEmployees);
		
		// 3) Removing training dates effective immediately for employee in a class
		$sqlRemTraingDates = " UPDATE ctlEmployeeTrainingByDays 
							   SET status = 'terminated', modifiedDate = '".$mDate."',
							   modifiedBy = '$empID' WHERE employeeID = '$employeeID' 
							   AND trainingDate > '".$termDate."'";
		$rst1 = $employeeeMaintenanceObj->execute($sqlRemTraingDates);
		mssql_free_result($rst1);
						
		// 4) updating dropdate to ctlEmployeeTrainings table
		//$sqlUpdateTraingClass = "UPDATE ctlEmployeeTrainings 
								//SET dropDate = '".$termDate."' WHERE employeeID = '".$employeeID."'";
		$sqlUpdateTraingClass ="UPDATE a SET dropDate = '".$termDate."'
								FROM ctlEmployeeTrainings a WITH (NOLOCK) 
								JOIN ctlTrainings b WITH (NOLOCK) 
								ON a.TrainingClassID = b.TrainingClassID WHERE 
								employeeID = $employeeID AND b.trainingStartDate >= '".$termDate."' ";
		$rst2 = $employeeeMaintenanceObj->execute($sqlUpdateTraingClass);
		mssql_free_result($rst2);
						
		// 5) Updating ctlEmployeeEmailDistributionLists table
		$sqlUpdateEDL = "UPDATE ctlEmployeeEmailDistributionLists 
						 SET endDate = '".$termDate."' WHERE employeeID = '".$employeeID."'";
		$rstEDL = $employeeeMaintenanceObj->execute($sqlUpdateEDL);
		mssql_free_result($rstEDL);
						
		// 7) Updating ctlRodLocations table
		$sqlUpdateRODLoc = "UPDATE ctlRodLocations  
							SET endDate = '".$termDate."' WHERE employeeID = '".$employeeID."'";
		$rstRODLoc = $employeeeMaintenanceObj->execute($sqlUpdateRODLoc);
		mssql_free_result($rstRODLoc);
						
		// 8) Updating prmEmployeePositionClients table.
		$sqlUpdatePositionClients = "UPDATE Rnet.dbo.prmEmployeePositionClients  
								 SET endDate = '".$termDate."' WHERE employeeID = '".$employeeID."' 
								 AND endDate IS NULL ";
		$sqlUpdatePositionRes = $employeeeMaintenanceObj->execute($sqlUpdatePositionClients);
		mssql_free_result($sqlUpdatePositionRes);
						
		// 9 ) Updating prmEmployeePositionLocations table.
		$sqlUpdatePositionLocations = "UPDATE Rnet.dbo.prmEmployeePositionLocations  
								 SET endDate = '".$termDate."' WHERE employeeID = '".$employeeID."' 
								 AND endDate IS NULL ";
		$sqlUpdatePositionLocationsRes = $employeeeMaintenanceObj->execute($sqlUpdatePositionLocations);
		mssql_free_result($sqlUpdatePositionLocationsRes);
		
		
		
		
		include_once('updateOrigHireDate.php');
						
	///////////////////////////////////////////////////////////////////////////////////////
	//Disable LDAP Acount Code Start
	//////////////////////////////////////////////////////////////////////////////////////
	
	/*$sqlUserName = "SELECT userName FROM ctlEmployeeApplications WITH (nolock) WHERE applicationName = 'AD' AND employeeid =  '$employeeID' ";
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
	
		//header("Location: employee_careerHistory.php?employeeID=".$employeeID);
		
		// Below 
		
		header("Location: index_test.php?hdnEmployeeID=".$employeeID."&adpMode=hr&adpTask=empDates&error=DataIUpdate&activeLink=20");
		
	
}

function sendTerminationNotification($employeeID,$hireDate, $employeeeMaintenanceObj)
{
	$payrollLocationID = $employeeeMaintenanceObj->getEmployeeCurrentPayrollLocation($employeeID);
	
	$qryApp = "SELECT applicationName,userName FROM ctlEmployeeApplications WITH (NOLOCK) WHERE 
			   employeeID='".$employeeID."' ORDER BY applicationName";
	$eRstApp = $employeeeMaintenanceObj->execute($qryApp);
	while($rowApp=mssql_fetch_array($eRstApp))
	{
		$empApplications .= $rowApp[applicationName]." - ".$rowApp[userName]."\n";
	}
	mssql_free_result($eRstApp);
	
	$qry = "SELECT * FROM ctlEmployees WITH (NOLOCK) WHERE employeeID='".$employeeID."'";
	$eRst = $employeeeMaintenanceObj->execute($qry);
	
	while($row1=mssql_fetch_assoc($eRst))
	{
		$name = $row1['firstName']." ".$row1['lastName'];
		$locationID = $row1['location'];
		
		$locqry = "SELECT * FROM ctlLocations WITH (NOLOCK) WHERE location='".$locationID."'";
		$locRst = $employeeeMaintenanceObj->execute($locqry);
		while($rowLoc=mssql_fetch_assoc($locRst))
		{
			$location = $rowLoc[description];
		}
	}
	mssql_free_result($eRst);

	$sqlPOS = "
	DECLARE @effDate as DATETIME
	SET @effDate = (SELECT MAX(effectiveDate) FROM ctlEmployeePositions  WITH (NOLOCK) WHERE employeeID = '$employeeID') 
	
	SELECT 
		positionID 
	FROM 
		ctlEmployeePositions WITH (NOLOCK) 
	WHERE  
		employeeID = '$employeeID' 
	AND 
		effectiveDate = @effDate
	AND 
		ISNULL(positionID, '') != '' ";
	$rstPOS = $employeeeMaintenanceObj->execute($sqlPOS);
	while ($row=mssql_fetch_assoc($rstPOS)) 
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
	/*
	-- Enable Here..
	
	@mail('terminations@resultstel.com', $EmailSubject, $MESSAGE_BODY, $headers);
	@mail('Juan.Ponder@resultstel.com', $EmailSubject, $MESSAGE_BODY, $headers) or die ("Failure"); */
	@mail('vengal.sivvannagari@resultstel.com', $EmailSubject, $MESSAGE_BODY, $headers); 
	@mail('vasudev.sarvepalli@resultstel.com', $EmailSubject, $MESSAGE_BODY, $headers);
	//@mail('bhanu.prakash@resultstel.com', $EmailSubject, $MESSAGE_BODY, $headers); 
	
	 
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
								a.trainingStartDate >= '$hireDate' ";
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