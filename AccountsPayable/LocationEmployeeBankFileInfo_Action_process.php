<?
session_start();
include($_SERVER['DOCUMENT_ROOT']."/Include/authenticate.inc.php");
include_once($_SERVER["DOCUMENT_ROOT"]."/Include/Global.inc.php");
if($_POST)
{
	$db=mssql_connect(MSSQL_HOST, MSSQL_USERNAME, MSSQL_PASSWORD);
	mssql_select_db(MSSQL_DB);
	
	// for maintaining view state
	
	if($_POST["hdnFNameVS"])
	{
		$fNameVS = $_POST["hdnFNameVS"];
	}
	if($_POST["hdnLNameVS"])
	{
		$lNameVS = $_POST["hdnLNameVS"];
	}
	if($_POST["hdnEmpIDVS"])
	{
		$empIDVS = $_POST["hdnEmpIDVS"];
	}
	if($_POST["hdnAvayaIDVS"])
	{
		$avayaIDVS = $_POST["hdnAvayaIDVS"];
	}
	if($_POST["hdnEmpStatVS"])
	{
		$empStatVS = $_POST["hdnEmpStatVS"];
	}
	if($_POST["hdnLocation"])
	{
		$location = $_POST["hdnLocation"];
	} 
	// end of variables for maintaining view state
	

	$employeeID = $_POST["hdnEmployeeID"];
	$status = $_POST["ddlStatus"];
	$department = $_POST["ddlDepartments"];
	$category = $_POST["ddlCategories"];
	$monthlyRate = trim($_POST["txtMonthlyRate"]);
	$dailyRate = trim($_POST["txtDailyRate"]);
	$sSSNo = trim($_POST["txtSSSNo"]);
	$philHealthNo = trim($_POST["txtPhilHealthNo"]);
	$pagIbigNo = trim($_POST["txtPagIbigNo"]);
	$taxIDNo = trim($_POST["txtTaxIDNo"]);
	$birthDay = trim($_POST["txtBirthDay"]);
	$civilStatus = $_POST["ddlCivilStatus"];
	$gender = $_POST["ddlGender"];
	$taxStatus = $_POST["ddlTaxStatus"];
	$shiftCode = $_POST["txtShiftCode"];
	
	
	$QryCAIWorkID= "select username from ctlemployeeapplications (NOLOCK) WHERE employeeID=".$employeeID." 
	   and applicationName = 'CAI Work ID'";
	$rstsCAIWorkID=mssql_query($QryCAIWorkID, $db);
	if($CAIWork=mssql_fetch_array($rstsCAIWorkID))
	{
		$CAIWorkID = trim($CAIWork[username]);
	}
	
	//$CAIWorkID = "PH0012";
	
	$empDetailsQuery = "SELECT DISTINCT cast(a.[employeeID] as varchar)[employeeID], 
	a.[firstName],
	a.[lastName], 
	a.[middle], 
	a.[hireDate],
	eh2.[effectiveDate] dateSeparated,
	ep.phone,
	eAdd.street1,
	eAdd.street2,
	eAdd.city,
	eAdd.state,
	eAdd.zip
	--,[COMMON].dbo.fn_getEmployeePositions1(a.employeeID) [position]
	from  ctlEmployees (NOLOCK) a 
	LEFT OUTER JOIN (
					SELECT 
					eh1.* FROM ctlEmployeeCareerHistory (NOLOCK) eh1 
					JOIN ( SELECT	employeeID, MAX(hireDate) [maxDate] FROM ctlEmployeeCareerHistory (NOLOCK) GROUP By employeeID) maxeh 
					ON eh1.employeeID = maxeh.employeeID	
					and eh1.hireDate = maxeh.maxDate) eh 
					ON a.employeeID = eh.employeeID  
					LEFT OUTER JOIN	ctlemploymentStatuses es 
					ON es.employmentStatus = eh.employmentStatus
	LEFT OUTER JOIN	(SELECT employeeID, phoneType, phone FROM ctlEmployeePhones (NOLOCK) WHERE (phoneType = 'home')) ep 
	ON ep.employeeId = a.employeeId  
	LEFT OUTER JOIN	(SELECT * FROM ctlEmployeeAddresses (NOLOCK) WHERE (addressType = 'home')) eAdd 
	ON eAdd.employeeId = a.employeeId 
	LEFT OUTER JOIN 
	ctlEmployeeCareerHistory (NOLOCK) eh2
	ON a.employeeID = eh2.employeeID and eh2.employmentStatus = 2
	WHERE a.employeeID=".$employeeID;

	$rstEmpDetailsQuery=mssql_query(str_replace("\'","''",$empDetailsQuery), $db);
	$middleInitial = '';
	$address = '';
	if($row=mssql_fetch_array($rstEmpDetailsQuery)) 
	{
		$lastName = trim($row[lastName]);
		$firstName = trim($row[firstName]);
		$middleName = trim($row[middle]);
		if(trim($row[middle]) != '' || trim($row[middle]) != NULL)
		{
			$middleInitial = strtoupper(substr(trim($row[middle]), 0, 1)).'.';
		}
		$dateHired = $row[hireDate];
		$datSeparated = $row[dateSeparated];
		//$position = $row[position];
		if(trim($row[street1]) != "" || trim($row[street1]) != NULL)
		{
			$address .= trim($row[street1]).', ';
		}
		if(trim($row[street2]) != "" || trim($row[street2]) != NULL)
		{
			$address .= trim($row[street2]).', ';
		}
		if(trim($row[city]) != "" || trim($row[city]) != NULL)
		{
			$address .= trim($row[city]).', ';
		}
		if(trim($row[state]) != "" || trim($row[state]) != NULL)
		{
			$address .= trim($row[state]).', ';
		}
		if(trim($row[zip]) != "" || trim($row[zip]) != NULL)
		{
			$address .= trim($row[zip]).', ';
		}
		$address = substr($address, 0, -2);
		$phone = $row[phone];
	}
	
	//positon field is name of the deparment as per the excel sheet provided
	
	$position = '';
	if($department != "")
	{
		$QryPosition= "select deptDescription from ctlEmployeeBankFile_departments (NOLOCK) WHERE deptCode=".$department;
		$rstsPosition=mssql_query($QryPosition, $db);
		if($pos=mssql_fetch_array($rstsPosition))
		{
			$position = trim($pos[deptDescription]);
		}
	}
	
	$payrollMode = 0; //mandatory 0 mode for bank payroll as per the excel sheet provided
	$customPayrollSetupCode = 'Custom1'; //should be default to Custom1 as per the excel sheet provided
	$monthlyAllowance = ''; //should be 12.50 per hours worked as per the excel sheet provided
	$bankCode = 'UCPB001'; // should be default to UCPB001 as per the excel sheet provided
	
	$QryPayType= "SELECT a.payTypeID from ctlPayTypes (NOLOCK) a
					LEFT OUTER JOIN ctlEmployeePayrollRates (NOLOCK) b
					ON a.payTypeID = b.payType and b.endDate IS NULL 
					WHERE employeeID =".$employeeID;
	$rstPayType=mssql_query($QryPayType, $db);
	if($rowPayType=mssql_fetch_array($rstPayType))
	{
		$payType = $rowPayType[payTypeID];
	}
	if($payType == 1)
	{
		$flexiTime = 1;
		$computeTardiness = 0;
		$computeUndertime = 0;
	}
	else if($payType == 2)
	{
		$flexiTime = 0;
		$computeTardiness = 1;
		$computeUndertime = 1;
	}
	
	$suffixName = '';
	$dailyAllowance = '';
	$withSSSDeduction = 1;
	$withPhilHealthDeduction = 1;
	$withTaxDeduction = 1;
	$withPagibig = 1;
	
	$payrollTerms = 0;
	$computeOvertime = 1;
	$attendanceExempted = 0;
	
	
	$chkquery = "SELECT * from ctlEmployeeBankFile_SilverCity (NOLOCK) WHERE employeeID = '".$CAIWorkID."'";
	$chkrst=mssql_query(str_replace("\'","''",$chkquery), $db);
	$chknum =mssql_num_rows($chkrst);
	if($chknum == 0) 
	{
		$query = "INSERT INTO ctlEmployeeBankFile_SilverCity
				([employeeID]
				,[lastName]
				,[firstName]
				,[middleName]
				,[middleInitial]
				,[suffixName]
				,[dateHired]
				,[dateSeparated]
				,[employmentStatus]
				,[payrollMode]
				,[departmentCode]
				,[category]
				,[customPayrollSetupCode]
				,[monthlyRate]
				,[dailyRate]
				,[monthlyAllowance]
				,[dailyAllowance]
				,[SSSNo]
				,[philHealthNo]
				,[pagIbigNo]
				,[taxIDNo]
				,[bankCode]
				,[position]
				,[addressLine1]
				,[telephone1]
				,[birthday]
				,[civilStatus]
				,[gender]
				,[withSSSDeduction]
				,[withPhilHealthDeduction]
				,[withTaxDeduction]
				,[withPagibig]
				,[taxStatus]
				,[payrollTerms]
				,[shiftCode]
				,[flexiTime]
				,[computeTardiness]
				,[computeOvertime]
				,[computeUndertime]
				,[attendanceExempted]
				)
				VALUES
				('$CAIWorkID'
				,'$lastName'
				,'$firstName'";
	 if($middleName != "")
	 {			
	 	$query .=",'$middleName'";
	 }
	 else
	 {
	 	$query .=",NULL";
	 }					
 	 if($middleInitial != "")
	 {			
	 	$query .=",'$middleInitial'";
	 }
	 else
	 {
	 	$query .=",NULL";
	 }						
	 if($suffixName != "")
	 {			
	 	$query .=",'$suffixName'";
	 }
	 else
	 {
	 	$query .=",NULL";
	 }						
	 if(isset($dateHired))
	 {			
	 	$query .=",'$dateHired'";
	 }
	 else
	 {
	 	$query .=",NULL";
	 }				
	 if(isset($datSeparated))
	 {			
	 	$query .=",'$datSeparated'";
	 }
	 else
	 {
	 	$query .=",NULL";
	 }	
	 if($status != "")
	 {			
	 	$query .=",'$status'";
	 }
	 else
	 {
	 	$query .=",NULL";
	 }	
	 $query .= " ,'$payrollMode'";
	 if($department != "")
	 {			
		$query .=",'$department'";
	 }
	 else
	 {
		$query .=",NULL";
	 }	
	 if($category != "")
	 {			
		$query .=",'$category'";
	 }
	 else
	 {
		$query .=",NULL";
	 }	
	$query .= "	,'$customPayrollSetupCode'";
	 if($monthlyRate != "")
	 {			
		$query .=",$monthlyRate";
	 }
	 else
	 {
		$query .=",NULL";
	 }	
	 if($dailyRate != "")
	 {			
		$query .=",$dailyRate";
	 }
	 else
	 {
		$query .=",NULL";
	 }
	 if($monthlyAllowance != "")
	 {			
		$query .=",$monthlyAllowance";
	 }
	 else
	 {
		$query .=",NULL";
	 }	
	 if($dailyAllowance != "")
	 {			
		$query .=",$dailyAllowance";
	 }
	 else
	 {
		$query .=",NULL";
	 }			 	
	 if($sSSNo != "")
	 {			
		$query .=",'$sSSNo'";
	 }
	 else
	 {
		$query .=",NULL";
	 }	
	 if($philHealthNo != "")
	 {			
		$query .=",'$philHealthNo'";
	 }
	 else
	 {
		$query .=",NULL";
	 }	
	 if($pagIbigNo != "")
	 {			
		$query .=",'$pagIbigNo'";
	 }
	 else
	 {
		$query .=",NULL";
	 }	
	 if($taxIDNo != "")
	 {			
		$query .=",'$taxIDNo'";
	 }
	 else
	 {
		$query .=",NULL";
	 }	
	  $query .=",'$bankCode'";
	 if(isset($position))
	 {			
	 	$query .=",'$position'";
	 }
	 else
	 {
	 	$query .=",NULL";
	 }					  
	 if(isset($address))
	 {			
	 	$query .=",'$address'";
	 }
	 else
	 {
	 	$query .=",NULL";
	 }	
	if(isset($phone))
	 {			
	 	$query .=",'$phone'";
	 }
	 else
	 {
	 	$query .=",NULL";
	 }					 
	 if($birthDay != '')
	 {			
	 	$query .=",'$birthDay'";
	 }
	 else
	 {
	 	$query .=",NULL";
	 }
	 if($civilStatus != "")
	 {			
		$query .=",'$civilStatus'";
	 }
	 else
	 {
		$query .=",NULL";
	 }
	 if($gender != "")
	 {			
		$query .=",'$gender'";
	 }
	 else
	 {
		$query .=",NULL";
	 }	
	
  	 $query .= ",'$withSSSDeduction'
				,'$withPhilHealthDeduction'
				,'$withTaxDeduction'
				,'$withPagibig'";
	if($taxStatus != "")
	 {			
		$query .=",'$taxStatus'";
	 }
	 else
	 {
		$query .=",NULL";
	 }	
	 $query .= ",'$payrollTerms'";
	 if($shiftCode != "")
	 {			
		$query .=",'$shiftCode'";
	 }
	 else
	 {
		$query .=",NULL";
	 }	 
	 if(isset($flexiTime))
	 {			
	 	$query .= ",'$flexiTime'";
	 }
	 else
	 {
	 	$query .=",NULL";
	 }					  
 	 if(isset($computeTardiness))
	 {			
	 	$query .= ",'$computeTardiness'";
	 }
	 else
	 {
	 	$query .=",NULL";
	 }					  

	 $query .= ",'$computeOvertime'";
 	 if(isset($computeUndertime))
	 {			
	 	$query .= ",'$computeUndertime'";
	 }
	 else
	 {
	 	$query .=",NULL";
	 }					  
		$query .=",'$attendanceExempted')";
		
		mssql_query(str_replace("\'","''",$query), $db);
		header("Location:LocationEmployeeBankFileInfo.php?res=recordAdded&fNameVS=$fNameVS&lNameVS=$lNameVS&empIDVS=$empIDVS&avayaIDVS=$avayaIDVS&empStatVS=$empStatVS&location=$location");
	}
	else
	{
		$updateQuery = "UPDATE ctlEmployeeBankFile_SilverCity SET
						[lastName] 			= '$lastName'
						,[firstName]	 	= '$firstName'";
			 	 if($middleName != "")
				 {			
					 $updateQuery .= ",[middleName] = '$middleName'";
				 }
				 else
				 {
					 $updateQuery .= ",[middleName] = NULL";
				 }					
				 if($middleInitial != "")
				 {			
					$updateQuery .= ",[middleInitial] = '$middleInitial'";
				 }
				 else
				 {
					$updateQuery .= ",[middleInitial] = NULL";
				 }						
				 if($suffixName != "")
				 {			
					$updateQuery .= ",[suffixName] = '$suffixName'";
				 }
				 else
				 {
					$updateQuery .= ",[suffixName] = NULL";
				 }				
				 if(isset($dateHired))
				 {			
					 $updateQuery .= ",[dateHired] 	= '$dateHired'";
				 }
				 else
				 {
					 $updateQuery .= ",[dateHired] 	= NULL ";
				 }
				 if(isset($datSeparated))
				 {			
					 $updateQuery .= ",[dateSeparated] 	= '$datSeparated'";
				 }
				 else
				 {
					 $updateQuery .= ",[dateSeparated] 	= NULL ";
				 }
				 if($status != "")
				 {			
					 $updateQuery .= ",[employmentStatus] 	= '$status'";
				 }
				 else
				 {
					 $updateQuery .= ",[employmentStatus] 	= NULL ";
				 }		 	 
	   $updateQuery .= ",[payrollMode]		= '$payrollMode'";
	   			 if($department != "")
				 {			
					$updateQuery .= ",[departmentCode] 	= '$department'";
				 }
				 else
				 {
					 $updateQuery .= ",[departmentCode] 	= NULL ";
				 }	
				 if($category != "")
				 {			
					$updateQuery .= ",[category] 	= '$category'";
				 }
				 else
				 {
					 $updateQuery .= ",[category] 	= NULL ";
				 }	
	$updateQuery .= ",[customPayrollSetupCode] = '$customPayrollSetupCode'";
					 if($monthlyRate != "")
					 {			
						$updateQuery .= ",[monthlyRate] 	= $monthlyRate";
					 }
					 else
					 {
						 $updateQuery .= ",[monthlyRate] 	= NULL ";
					 }	
					 if($dailyRate != "")
					 {			
						$updateQuery .= ",[dailyRate] 	= $dailyRate";
					 }
					 else
					 {
						 $updateQuery .= ",[dailyRate] 	= NULL ";
					 }
					 if($monthlyAllowance != "")
					 {			
						$updateQuery .= ",[monthlyAllowance] 	= $monthlyAllowance";
					 }
					 else
					 {
						 $updateQuery .= ",[monthlyAllowance] 	= NULL ";
					 }	
					 if($dailyAllowance != "")
					 {			
						$updateQuery .= ",[dailyAllowance] 	= $dailyAllowance";
					 }
					 else
					 {
						 $updateQuery .= ",[dailyAllowance] 	= NULL ";
					 }			 	
					 if($sSSNo != "")
					 {			
						$updateQuery .= ",[SSSNo] 	= '$sSSNo'";
					 }
					 else
					 {
						 $updateQuery .= ",[SSSNo] 	= NULL ";
					 }	
					 if($philHealthNo != "")
					 {			
						$updateQuery .= ",[philHealthNo] 	= '$philHealthNo'";
					 }
					 else
					 {
						 $updateQuery .= ",[philHealthNo] 	= NULL ";
					 }	
					 if($pagIbigNo != "")
					 {			
						$updateQuery .= ",[pagIbigNo] 	= '$pagIbigNo'";
					 }
					 else
					 {
						 $updateQuery .= ",[pagIbigNo] 	= NULL ";
					 }	
					 if($taxIDNo != "")
					 {			
						$updateQuery .= ",[taxIDNo] 	= '$taxIDNo'";
					 }
					 else
					 {
						 $updateQuery .= ",[taxIDNo] 	= NULL ";
					 }	

	   $updateQuery .= ",[bankCode]		= '$bankCode'";
					 if(isset($position))
					 {			
						$updateQuery .= ",[position] 	= '$position'";
					 }
					 else
					 {
						 $updateQuery .= ",[position] 	= NULL ";
					 }					  
					 if(isset($address))
					 {			
						$updateQuery .= ",[addressLine1] 	= '$address'";
					 }
					 else
					 {
						 $updateQuery .= ",[addressLine1] 	= NULL ";
					 }	
					if(isset($phone))
					 {			
						$updateQuery .= ",[telephone1] 	= '$phone'";
					 }
					 else
					 {
						 $updateQuery .= ",[telephone1] = NULL ";
					 }	
					 
					 if($birthDay != '')
					 {			
						 $updateQuery .= ",[birthday] 	= '$birthDay'";
					 }
					 else
					 {
						 $updateQuery .= ",[birthday] 	= NULL ";
					 }
			 					 				 
					 if($civilStatus != "")
					 {			
						$updateQuery .= ",[civilStatus] = '$civilStatus'";
					 }
					 else
					 {
						 $updateQuery .= ",[civilStatus] = NULL ";
					 }
					 if($gender != "")
					 {			
						$updateQuery .= ",[gender] 	= '$gender'";
					 }
					 else
					 {
						 $updateQuery .= ",[gender] = NULL ";
					 }	

	   $updateQuery .= ",[withSSSDeduction] = '$withSSSDeduction'
						,[withPhilHealthDeduction] = '$withPhilHealthDeduction'
						,[withTaxDeduction]	= '$withTaxDeduction'
						,[withPagibig]		= '$withPagibig'";
					 if($taxStatus != "")
					 {			
						$updateQuery .= ",[taxStatus] 	= '$taxStatus'";
					 }
					 else
					 {
						 $updateQuery .= ",[taxStatus] 	= NULL ";
					 }	
	  $updateQuery .= ",[payrollTerms] = '$payrollTerms'";
					 if($shiftCode != "")
					 {			
						$updateQuery .= ",[shiftCode] 	= '$shiftCode'";
					 }
					 else
					 {
						 $updateQuery .= ",[shiftCode] 	= NULL ";
					 }	 
					 if(isset($flexiTime))
					 {			
						$updateQuery .= ",[flexiTime] 	= '$flexiTime'";
					 }
					 else
					 {
						 $updateQuery .= ",[flexiTime] 	= NULL ";
					 }					  
					 if(isset($computeTardiness))
					 {			
						$updateQuery .= ",[computeTardiness] 	= '$computeTardiness'";
					 }
					 else
					 {
						 $updateQuery .= ",[computeTardiness] 	= NULL ";
					 }					  
				
	$updateQuery .= ",[computeOvertime] = '$computeOvertime'";
					 if(isset($computeUndertime))
					 {			
						$updateQuery .= ",[computeUndertime] 	= '$computeUndertime'";
					 }
					 else
					 {
						 $updateQuery .= ",[computeUndertime] 	= NULL ";
					 }					  

	 $updateQuery .= ",[attendanceExempted] = '$attendanceExempted'	
						WHERE employeeID = '".$CAIWorkID."'";
						
				$updateRst=mssql_query(str_replace("\'","''",$updateQuery), $db);
				if($updateRst)
				{
					header("Location:LocationEmployeeBankFileInfo.php?res=recordEdited&fNameVS=$fNameVS&lNameVS=$lNameVS&empIDVS=$empIDVS&avayaIDVS=$avayaIDVS&empStatVS=$empStatVS&location=$location");
				}				
	}

	
	
}
?>
