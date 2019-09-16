<?php
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////TASK NO 27936//////////////////////////////////////////////////////////////
unset($sqlEarlyPOChk);
unset($rstEarlyPOChk);
unset($numEarlyPOChk);
/*$sqlEarlyPOChk = "	DECLARE @date as DATETIME
					SET @date = GETDATE()
					
					IF OBJECT_ID('tempdb.dbo.#tempEmployeeInformation') IS NOT NULL 
					DROP TABLE #tempEmployeeInformation 
					CREATE TABLE #tempEmployeeInformation
					(
						[employeeID] [int] NOT NULL,
						[firstName] [varchar](50) NULL,
						[lastName] [varchar](50) NULL,
						[location] [varchar](3) NULL,
						[emailAddress] [varchar](100) NULL,
						[extension] [varchar](50) NULL,
						[mobile] [varchar](50) NULL,
						[fax] [varchar](50) NULL,
						[displayName] [varchar](100) NULL,
						[ssn] [varchar](50) NULL,
						[dob] [datetime] NULL,
						[gender] [char](1) NULL,
						[workAtHome] [bit] NULL,
						[computerCerts] [varchar](100) NULL,
						[shiftPreferenceID] [int] NULL,
						[skill] [int] NULL,
						[channelID] [int] NULL,
						[productionDate] [datetime] NULL,
						[MU] [varchar](50) NULL,
						[applicantID] [int] NULL,
						[Locked] [varchar](3) NULL,
						[Enabled] [varchar](3) NULL,
						[External] [varchar](3) NULL,
						[Internal] [varchar](3) NULL,
						[middle] [varchar](50) NULL,
						[agentTypeIDStart] [int] NULL,
						[agentTypeIDEnd] [int] NULL,
						[imageUrl] [varchar](255) NULL,
						[modifiedBy] [int] NULL,
						[modifiedDate] [datetime] NULL,
						[suffixName] [varchar](3) NULL,
						[employmentStatusStart] [varchar](1) NULL,
						[employmentStatusEnd] [varchar](1) NULL,
						[payrollMode] [varchar](1) NULL,
						[departmentCode] [varchar](50) NULL,
						[category] [varchar](1) NULL,
						[customPayrollSetupCode] [varchar](max) NULL,
						[SSSNo] [varbinary](max) NULL,
						[philHealthNo] [varchar](50) NULL,
						[pagIbigNo] [varchar](20) NULL,
						[taxIDNo] [varbinary](max) NULL,
						[bankCode] [varchar](50) NULL,
						[civilStatus] [varchar](1) NULL,
						[withSSSDeduction] [varchar](1) NULL,
						[withPhilHealthDeduction] [varchar](1) NULL,
						[withTaxDeduction] [varchar](1) NULL,
						[withPagIbig] [varchar](1) NULL,
						[taxStatus] [varchar](50) NULL,
						[payrollTerms] [varchar](1) NULL,
						[shiftCode] [varchar](50) NULL,
						[flexiTime] [varchar](1) NULL,
						[computeTardiness] [varchar](1) NULL,
						[computeOvertime] [varchar](1) NULL,
						[computeUndertime] [varchar](1) NULL,
						[attendanceExempted] [varchar](1) NULL,
						[secureSSN] [varbinary](max) NULL,
						[payrollLocation] [varchar](3) NULL,
						[includeInCostSummary] [varchar](1) NULL,
						[bankAccount] [varbinary](max) NULL,
						[channel] [varchar](50) NULL,
						[agentTypeDescriptionStart] [varchar](50) NULL,
						[agentTypeDescriptionEnd] [varchar](50) NULL,
						[locationDescription] [varchar](50) NULL,
						[avayaIDPrefix] [varchar](2) NULL,
						[shiftPreference] [varchar](50) NULL,
						[positionID] [int] NULL,
						[positionEffectiveDate] [datetime] NULL,
						[positionEndDate] [datetime] NULL,
						[position] [varchar](100) NULL,
						[positionDescription] [varchar](100) NULL,
						[businessFunction] [varchar](50) NULL,
						[department] [varchar](50) NULL,
						[laborCategory] [varchar](20) NULL,
						[jobCode] [varchar](50) NULL,
						[jobTitle] [varchar](50) NULL,
						[jobClass] [varchar](20) NULL,
						[supervisorID] [int] NULL,
						[supervisorLastName] [varchar](50) NULL,
						[supervisorFirstName] [varchar](50) NULL,
						[employmentStatusDescriptionStart] [varchar](50) NULL,
						[employmentStatusDescriptionEnd] [varchar](50) NULL,
						[hireDate] [datetime] NULL,
						[termDate] [datetime] NULL,
						[terminationReasonID] [varchar](50) NULL,
						[canBeRehired] [bit] NULL,
						[voluntaryTermination] [bit] NULL,
						[terminationReason] [varchar](100) NULL,
						[terminationCategory] [varchar](50) NULL,
						[careerStatus] [varchar](20) NULL,
						[adLogin] [varchar](50) NULL,
						[avayaID] [varchar](50) NULL,
						[caiID] [varchar](50) NULL,
						[educationLevel] [varchar](50) NULL,
						[payRateEffectiveDate] [datetime] NULL,
						[baseWage] [decimal](10, 2) NULL,
						[secondaryWage] [decimal](10, 2) NULL,
						[payType] [int] NULL,
						[payTypeDescription] [varchar](50) NULL,
						[monthlyRate] [decimal](10, 2) NULL,
						[homePhone] [varchar](50) NULL,
						[mobilePhone] [varchar](50) NULL,
						[street1] [varchar](50) NULL,
						[street2] [varchar](50) NULL,
						[city] [varchar](50) NULL,
						[state] [varchar](50) NULL,
						[zip] [varchar](50) NULL,
						[country] [varchar](50) NULL,
						[dataIssues] [int] NULL,
						[dataIssuesDescription] [varchar](max) NULL,
						[corporateAccess] [varchar](1) NULL
					)
					
					EXEC RNET.dbo.standard_spEmployeeInformation '','$employeeID',@date,@date 
					
					SELECT 
						ei.employeeID,
						ei.[State],
						ei.terminationCategory, 
						ei.termDate,
						pts.daysForVoluntaryTerm,
						pts.daysForInvoluntaryTerm
					FROM 
						#tempEmployeeInformation ei
					JOIN
						Rnet.dbo.ctlPayrollTerminationStateRules pts WITH (NOLOCK)
					ON
						ei.state = pts.state
					WHERE 
						(
							(ei.terminationCategory = 'Voluntary' AND pts.daysForVoluntaryTerm IS NOT NULL)
							OR
							(ei.terminationCategory = 'Involuntary' AND pts.daysForInvoluntaryTerm IS NOT NULL)
						)
					ORDER BY
						termDate ";*/
$sqlEarlyPOChk = "	
DECLARE @date as DATETIME
SET @date = GETDATE()
IF OBJECT_ID('tempdb.dbo.#tempEmployeeInformation') IS NOT NULL 
DROP TABLE #tempEmployeeInformation 
CREATE TABLE #tempEmployeeInformation
(
	[employeeID] [int] NOT NULL,
	[firstName] [varchar](50) NULL,
	[lastName] [varchar](50) NULL,
	[location] [varchar](3) NULL,
	[emailAddress] [varchar](100) NULL,
	[extension] [varchar](50) NULL,
	[mobile] [varchar](50) NULL,
	[fax] [varchar](50) NULL,
	[displayName] [varchar](100) NULL,
	[ssn] [varchar](50) NULL,
	[dob] [datetime] NULL,
	[gender] [char](1) NULL,
	[workAtHome] [bit] NULL,
	[computerCerts] [varchar](100) NULL,
	[shiftPreferenceID] [int] NULL,
	[skill] [int] NULL,
	[channelID] [int] NULL,
	[productionDate] [datetime] NULL,
	[MU] [varchar](50) NULL,
	[applicantID] [int] NULL,
	[Locked] [varchar](3) NULL,
	[Enabled] [varchar](3) NULL,
	[External] [varchar](3) NULL,
	[Internal] [varchar](3) NULL,
	[middle] [varchar](50) NULL,
	[agentTypeIDStart] [int] NULL,
	[agentTypeIDEnd] [int] NULL,
	[imageUrl] [varchar](255) NULL,
	[modifiedBy] [int] NULL,
	[modifiedDate] [datetime] NULL,
	[suffixName] [varchar](3) NULL,
	[employmentStatusStart] [varchar](1) NULL,
	[employmentStatusEnd] [varchar](1) NULL,
	[payrollMode] [varchar](1) NULL,
	[departmentCode] [varchar](50) NULL,
	[category] [varchar](1) NULL,
	[customPayrollSetupCode] [varchar](max) NULL,
	[SSSNo] [varbinary](max) NULL,
	[philHealthNo] [varchar](50) NULL,
	[pagIbigNo] [varchar](20) NULL,
	[taxIDNo] [varbinary](max) NULL,
	[bankCode] [varchar](50) NULL,
	[civilStatus] [varchar](1) NULL,
	[withSSSDeduction] [varchar](1) NULL,
	[withPhilHealthDeduction] [varchar](1) NULL,
	[withTaxDeduction] [varchar](1) NULL,
	[withPagIbig] [varchar](1) NULL,
	[taxStatus] [varchar](50) NULL,
	[payrollTerms] [varchar](1) NULL,
	[shiftCode] [varchar](50) NULL,
	[flexiTime] [varchar](1) NULL,
	[computeTardiness] [varchar](1) NULL,
	[computeOvertime] [varchar](1) NULL,
	[computeUndertime] [varchar](1) NULL,
	[attendanceExempted] [varchar](1) NULL,
	[secureSSN] [varbinary](max) NULL,
	[payrollLocation] [varchar](3) NULL,
	[includeInCostSummary] [varchar](1) NULL,
	[bankAccount] [varbinary](max) NULL,
	[channel] [varchar](50) NULL,
	[agentTypeDescriptionStart] [varchar](50) NULL,
	[agentTypeDescriptionEnd] [varchar](50) NULL,
	[locationDescription] [varchar](50) NULL,
	[avayaIDPrefix] [varchar](2) NULL,
	[shiftPreference] [varchar](50) NULL,
	[positionID] [int] NULL,
	[positionEffectiveDate] [datetime] NULL,
	[positionEndDate] [datetime] NULL,
	[position] [varchar](100) NULL,
	[positionDescription] [varchar](100) NULL,
	[businessFunction] [varchar](50) NULL,
	[department] [varchar](50) NULL,
	[laborCategory] [varchar](20) NULL,
	[jobCode] [varchar](50) NULL,
	[jobTitle] [varchar](50) NULL,
	[jobClass] [varchar](20) NULL,
	[supervisorID] [int] NULL,
	[supervisorLastName] [varchar](50) NULL,
	[supervisorFirstName] [varchar](50) NULL,
	[employmentStatusDescriptionStart] [varchar](50) NULL,
	[employmentStatusDescriptionEnd] [varchar](50) NULL,
	[hireDate] [datetime] NULL,
	[termDate] [datetime] NULL,
	[terminationReasonID] [varchar](50) NULL,
	[canBeRehired] [bit] NULL,
	[voluntaryTermination] [bit] NULL,
	[terminationReason] [varchar](100) NULL,
	[terminationCategory] [varchar](50) NULL,
	[careerStatus] [varchar](20) NULL,
	[adLogin] [varchar](50) NULL,
	[avayaID] [varchar](50) NULL,
	[caiID] [varchar](50) NULL,
	[educationLevel] [varchar](50) NULL,
	[payRateEffectiveDate] [datetime] NULL,
	[baseWage] [decimal](10, 2) NULL,
	[secondaryWage] [decimal](10, 2) NULL,
	[payType] [int] NULL,
	[payTypeDescription] [varchar](50) NULL,
	[monthlyRate] [decimal](10, 2) NULL,
	[homePhone] [varchar](50) NULL,
	[mobilePhone] [varchar](50) NULL,
	[street1] [varchar](50) NULL,
	[street2] [varchar](50) NULL,
	[city] [varchar](50) NULL,
	[state] [varchar](50) NULL,
	[zip] [varchar](50) NULL,
	[country] [varchar](50) NULL,
	[dataIssues] [int] NULL,
	[dataIssuesDescription] [varchar](max) NULL,
	[corporateAccess] [varchar](1) NULL,
	[reHireDate] [datetime] NULL,
	[seniorityDate] [datetime] NULL,
	[payrollLocationDescription] [varchar](50) NULL,
	[fullPartTime] [varchar](1) NULL,
	[street1Other] [varchar](50) NULL,
	[street2Other] [varchar](50) NULL,
	[cityOther] [varchar](50) NULL,
	[stateOther] [varchar](50) NULL,
	[zipOther] [varchar](50) NULL,
	[origHireDate] [datetime] NULL,
	[maritalStatus] [varchar](50) NULL,
	[personalEmailAddress] [varchar](255) NULL,
	[ethnicity] [varchar](250) NULL,
	[highestEducationLevel] [varchar](150) NULL,
	[citizenshipstatus] [varchar](50) NULL,
	[visaType] [varchar](50) NULL
)

EXEC RNET.dbo.standard_spEmployeeInformation '','$employeeID',@date,@date 

SELECT 
	d.employeeID,
	d.[State],
	d.terminationCategory, 
	d.termDate,
	c.daysForVoluntaryTerm,
	c.daysForInvoluntaryTerm
FROM 
	RNet.dbo.ctlADPPaygroupLocations a WITH (NOLOCK)
JOIN
	[ctlLocations] b WITH (NOLOCK) 				
ON
	a.location = b.location
JOIN
	Rnet.dbo.ctlPayrollTerminationStateRules c WITH (NOLOCK)
ON
	b.state = c.state
JOIN
	#tempEmployeeInformation d
ON
	c.state = d.state
WHERE
	b.country = 'United States of America'   
AND 
	b.active ='Y' AND b.switch ='N' 
AND
	b.state IS NOT NULL
AND
	(
		(d.terminationCategory = 'Voluntary' AND c.daysForVoluntaryTerm IS NOT NULL)
		OR
		(d.terminationCategory = 'Involuntary' AND c.daysForInvoluntaryTerm IS NOT NULL)
	)
ORDER BY
	d.termDate ";
//echo $sqlEarlyPOChk;exit;
$rstEarlyPOChk = $employeeeMaintenanceObj->ExecuteQuery($sqlEarlyPOChk);
$numEarlyPOChk = mssql_num_rows($rstEarlyPOChk);
while($rowEarlyPOChk = mssql_fetch_assoc($rstEarlyPOChk))
{
	$sepEmpState = $rowEarlyPOChk['State'];
}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////END OF TASK NO 27936///////////////////////////////////////////////////////


$sepSP = " EXEC RNet.dbo.[report_spTrainingClassesSeparationNotificationDetails] '$seperatorID','$employeeID','$trainingClassID'";	
//echo $sepSP;exit;
$rstSepSP = $employeeeMaintenanceObj->ExecuteQuery($sepSP);
$notificationNumRows = mssql_num_rows($rstSepSP);
if($notificationNumRows>0)
{
	while($rowSepSP = mssql_fetch_assoc($rstSepSP))
	{
		$sepFstName =  $rowSepSP['separatorFirstName'];
		$sepLstName =  $rowSepSP['separatorLastName'];
		$sepEmail =  $rowSepSP['separatoremailAddress'];
		$sepDate = date('m/d/Y',strtotime($rowSepSP['separationDate']));
		$terEmpFstName = $rowSepSP['employeeFirstName'];
		$terEmpLstName = $rowSepSP['employeeLastName'];
		$terEmpPP = $rowSepSP['employeePrimaryPosition'];
		$termReason = $rowSepSP['terminationReason'];
		$terEmpClient = $rowSepSP['employeeclient'];
		$terEmpLOB = $rowSepSP['employeeLOB'];
		$terEmpAD = $rowSepSP['employeeADLogin'];
		$terEmpHireDate = date('m/d/Y',strtotime($rowSepSP['employeehireDate']));
		$terEvolvCareColor = $rowSepSP['evolvcarecolor'];
		$terEvolvSaleColor = $rowSepSP['evolvsalecolor'];
		$terEmpTrainer = $rowSepSP['trainer'];
		$terEmpDean = $rowSepSP['dean'];
		$terEmpCurSup = $rowSepSP['currentSupervisor'];
		$deanEmailAddress = $rowSepSP['deanEmailAddress'];
		$trainerEmailAddress = $rowSepSP['trainerEmailAddress'];
		$supervisorEmailAddress = $rowSepSP['supervisorEmailAddress'];
		
		if(!empty($rowSepSP['supervisorConfirmDate']))
		{
				$terSupConfDate = date('m/d/Y H:i:s',strtotime($rowSepSP['supervisorConfirmDate']));
		} else {
	
				$terSupConfDate = '';
		}
		
		$termEmpName = $terEmpFstName.' '.$terEmpLstName;
		$sepEmpName = $sepFstName.' '.$sepLstName;
		$seperationLocation = $rowSepSP['location']; 
	}
	
	$cDate = $sepDate;
	$date1 = new DateTime($terEmpHireDate); 
	$date2 = new DateTime($cDate); 
	$interval = $date1->diff($date2); 
	if($interval->y != 1)
	{
		$tenure = $interval->y.' years, ';	
	}
	else
	{
		$tenure = $interval->y.' year, ';
	}
	if($interval->m != 1)
	{
		$tenure .= $interval->m.' months, ';	
	}
	else
	{
		$tenure .= $interval->m.' month, ';
	}
	if($interval->d != 1)
	{
		$tenure .= $interval->d.' days';	
	}
	else
	{
		$tenure .= $interval->d.' day';
	}
	
	
	//////////////////////////////////////////////TRAINING CLASS MAIL///////////////////////////////////////

	if(!empty($trainingClassID))
	{
		
		$SeperationSub =  " Separation : ".$termEmpName." , ".$terEmpPP." - ".$terEmpClient." ";

		$FromEmail = $sepEmail;
		
		$mailheader1 = "MIME-Version: 1.0"."\r\n";
		$mailheader1 .= "Content-type: text/html; charset=iso-8859-1"."\r\n";
		if(!empty($FromEmail))
		{
			$mailheader1 .= "From: ".$FromEmail.""."\r\n"; 
		}
		else
		{
			$mailheader1 .= "From: rnet-system@resultstel.com"."\r\n"; 
		}
		$SeperationBody =  "Team,<br><br>";
		$SeperationBody .= "Please note the separation of ".$terEmpPP." ".$termEmpName." by ".$sepEmpName." on ".$sepDate." for ".$termReason.".<br><br>"; 
		$SeperationBody .= "This ".$terEmpPP." was working on ".$terEmpClient.", ".$terEmpLOB.", so please deactivate all logins tool access.<br><br>";
		$SeperationBody .="<strong><u>Employee Profile:</u></strong><br>";
		$SeperationBody .="RTI Username: ".$_SESSION['deletedUserName']."<br>";
		$SeperationBody .="Date of Hire: ".$terEmpHireDate."<br>";
		$SeperationBody .="Tenure at Separation: ".$tenure."<br>";
		$SeperationBody .="Evolv Care Color: ".$terEvolvCareColor."<br>";
		$SeperationBody .="Evolv Sales Color: ".$terEvolvSaleColor."<br>";
		$SeperationBody .="New Hire Trainer: ".$terEmpTrainer."<br>";
		$SeperationBody .="Dean: ".$terEmpDean."<br><br><br>";
		if(!empty($terSupConfDate))
		{
			$SeperationBody .="Current Supervisor, ".$terEmpCurSup.", has confirmed this Separation on ".$terSupConfDate.".<br><br><br>";
		}
		else
		{
			$SeperationBody .="Current Supervisor, ".$terEmpCurSup.", has not confirmed this Separation.<br><br><br>";
		}
		$SeperationBody .="Thanks,<br>";
		$SeperationBody .=$sepFstName;
		$sepauthorizedemp = getSeperationEmailAddress($seperationLocation,$employeeID, $employeeeMaintenanceObj);
		
		foreach($sepauthorizedemp as $sepauthorizedempVal)
		{
			@mail($sepauthorizedempVal, $SeperationSub, $SeperationBody, $mailheader1);
		}
		
		if($deanEmailAddress != '')
		{
			@mail($deanEmailAddress, $SeperationSub, $SeperationBody, $mailheader1);
		}
						
		if($trainerEmailAddress != '')
		{
			mail($trainerEmailAddress, $SeperationSub, $SeperationBody, $mailheader1);
		}
						
		if($supervisorEmailAddress != '')
		{
			@mail($supervisorEmailAddress, $SeperationSub, $SeperationBody, $mailheader1);
		}
	
		
		/*-- Enable here.*/
		//@mail('Juan.Ponder@resultstel.com', $SeperationSub, $SeperationBody, $mailheader1);
		@mail('vengal.sivvannagari@resultstel.com', $SeperationSub, $SeperationBody, $mailheader1);
		@mail('vasudev.sarvepalli@resultstel.com', $SeperationSub, $SeperationBody, $mailheader1);

	}
	
	//////////////////////////////EARLY TERMINATION NOTIFICATION FOR HR TEAM///////////////	
	if($numEarlyPOChk>0)
	{
		unset($sqlLoc);
		$sqlLoc = " SELECT [description] FROM ctlLocations WITH (NOLOCK) WHERE location = $seperationLocation ";
		$rstLocD = $employeeeMaintenanceObj->ExecuteQuery($sqlLoc);
		$sepLocDesc = mssql_result($rstLocD,0,0);
		mssql_free_result($rstLocD);
		
		unset($sqlState);
		$sqlState = " SELECT [description] FROM ctlStates WITH (NOLOCK) WHERE [state] = '".$sepEmpState."' ";
		$rstSepState = $employeeeMaintenanceObj->ExecuteQuery($sqlState);
		$sepStateDesc = mssql_result($rstSepState,0,0);
		mssql_free_result($rstSepState);
		
		$pageURL .= 'http';
		if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
		$pageURL .= "://";
		$pageURL .= $_SERVER["SERVER_NAME"];
		$pageURL .= "/Payroll/FinalizePayroll/EPIPterms.php?ID=0";
		
		
		$earlyPOSub =  " ".$sepLocDesc." Separation Notification : ".$termEmpName." , ".$terEmpPP." - ".$terEmpClient." ";

		$FromEmail2 = $sepEmail;
		
		$mailheader2 = "MIME-Version: 1.0"."\r\n";
		$mailheader2 .= "Content-type: text/html; charset=iso-8859-1"."\r\n";
		if(!empty($FromEmail2))
		{
			$mailheader2 .= "From: ".$FromEmail2.""."\r\n"; 
		}
		else
		{
			$mailheader2 .= "From: rnet-system@resultstel.com"."\r\n"; 
		}
		$earlyPOBody =  "Team,<br><br>";
		$earlyPOBody .= "Please note the separation of ".$terEmpPP." ".$termEmpName." by ".$sepEmpName." on ".$sepDate." for ".$termReason."."; 
		
		$earlyPOBody .= "The state of ".$sepStateDesc." requires an early payout for this employee.  This terminated employee will need to be paid separately from the end of period EPIP file.  The early termination process can be started <a href='".$pageURL."'> here </a>.<br><br>";
		
		$tEmLOB = '';
		if(!empty($terEmpLOB))
		{
			$tEmLOB = ','.$terEmpLOB;
		}
		
		if(!empty($terEmpClient))
		{
			$earlyPOBody .= "This ".$terEmpPP." was working on ".$terEmpClient." ".$tEmLOB.".<br><br>";
		}
		
		$earlyPOBody .="<strong><u>Employee Profile:</u></strong><br>";
		$earlyPOBody .="Employee ID: ".$employeeID."<br>";
		$earlyPOBody .="RTI Username: ".$_SESSION['deletedUserName']."<br>";
		$earlyPOBody .="Date of Hire: ".$terEmpHireDate."<br>";
		$earlyPOBody .="Tenure at Separation: ".$tenure."<br><br>";
		
		if(!empty($terEmpCurSup))
		{
			if(!empty($terSupConfDate))
			{
				$earlyPOBody .="Current Supervisor, ".$terEmpCurSup.", has confirmed this Separation on ".$terSupConfDate.".<br><br><br>";
			}
			else
			{
				$earlyPOBody .="Current Supervisor, ".$terEmpCurSup.", has not confirmed this Separation.<br><br><br>";
			}
		}
		$earlyPOBody .="Thanks,<br>";
		$earlyPOBody .=$sepFstName;
		
		
		
		$locPositions = '4';
		$remPositions = '188,166';
		$earlyHRCauthorizedemp = getHRCEmailAddress($seperationLocation,$locPositions,$remPositions, $employeeeMaintenanceObj);
				
		foreach($earlyHRCauthorizedemp as $earlyHRCauthorizedempV)
		{
			@mail($earlyHRCauthorizedempV, $earlyPOSub, $earlyPOBody, $mailheader2);
		}
		
		
		/*-- Enable here.*/
		//@mail('Juan.Ponder@resultstel.com', $earlyPOSub, $earlyPOBody, $mailheader2);
		@mail('vengal.sivvannagari@resultstel.com', $earlyPOSub, $earlyPOBody, $mailheader2);
		@mail('vasudev.sarvepalli@resultstel.com', $earlyPOSub, $earlyPOBody, $mailheader2);

	}
	
	/*END of Added On December 22nd 2011 (Seperation Email)*/		
} // if $notificationNumRows >0 


function getHRCEmailAddress($location,$locPositions,$remPositions, $employeeeMaintenanceObj)
{
	$SqlQuery = " SELECT RNet.dbo.[fn_getLocationPositionEmailIDs] ('$location','$locPositions') AS hrCEmails ";
	$ResultSet = $employeeeMaintenanceObj->ExecuteQuery($SqlQuery);
	$numRows = mssql_num_rows($ResultSet);
	if($numRows>0)
	{
		while($resultsAugh = mssql_fetch_assoc($ResultSet))
		{
			$authorizedemp = $resultsAugh['hrCEmails'];
		}
	}
	
	$authorizedempArr1 = explode(',',$authorizedemp);
	
	
	$SqlQuery2 = " SELECT RNet.dbo.[fn_getPositionEmailIDs] ('$remPositions') AS remEmails ";
	$ResultSet2 = $employeeeMaintenanceObj->ExecuteQuery($SqlQuery2);
	$numRows2 = mssql_num_rows($ResultSet2);
	if($numRows2>0)
	{
		while($resultsAugh2 = mssql_fetch_assoc($ResultSet2))
		{
			$authorizedemp2 = $resultsAugh2['remEmails'];
		}
	}
	
	$authorizedempArr2 = explode(',',$authorizedemp2);
	
	
	$finalArr = array_merge($authorizedempArr1,$authorizedempArr2);
	
	return $finalArr;
}
?>