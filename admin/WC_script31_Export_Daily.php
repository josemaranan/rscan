<?php
session_start();

include_once($_SERVER["DOCUMENT_ROOT"]."/Include/Global.inc.php");

	$db=mssql_connect(MSSQL_HOST, MSSQL_USERNAME, MSSQL_PASSWORD);
	mssql_select_db(MSSQL_DB);
	$result = mssql_query($query, $db);

/*	session_start();

	$level=$_SESSION['UserLevel'];
	if(!$level || $level='')
	{
		header("location:../index.php");
		exit();
	}*/

	$today = date("Y-m-d");
	$Yesterday =  date("Y-m-d",mktime(0, 0, 0, date("m")  , date("d")-1, date("Y")));
	$time = date("H:i:s");

	$sql = "select * from [RUO]..wc_script_31_replica";			
		
	$export=mssql_query($sql,$db) or die(mssql_get_last_message());
	if (!$export) 
		{
			print_a($sql);
			die("bad query : ".$sql);
		}

	if (mssql_num_rows($export) == 0) 
	{
	die("No Data Found to be exported!");
	}
	;	

	$header ="ConfirmationNumber"."\t";
	$header .="SubmitDate"."\t";
	$header .="ContractID"."\t";
	$header .="PlanID"."\t";	
	$header .="SegmentID"."\t";	
	$header .="ApplicantTitle"."\t";	
	$header .="ApplicantFirstName"."\t";	
	$header .="ApplicantMiddleInitial"."\t";	
	$header .="ApplicantLastName"."\t";	
	$header .="ApplicantBirthDate"."\t";	
	$header .="ApplicantGender"."\t";
	$header .="ApplicantAddress1"."\t";
	$header .="ApplicantAddress2"."\t";
	$header .="ApplicantAddress3"."\t";
	$header .="ApplicantCity"."\t";
	$header .="ApplicantState"."\t";
	$header .="ApplicantZip"."\t";
	$header .="ApplicantPhone"."\t";
	$header .="ApplicantEmailAddress"."\t";
	$header .="ApplicantHICN"."\t";	
	$header .="ApplicantSSN"."\t";
	$header .="MailingAddress1"."\t";
	$header .="MailingAddress2"."\t";	
	$header .="MailingAddress3"."\t";	
	$header .="MailingCity"."\t";	
	$header .="MailingState"."\t";	
	$header .="MailingZip"."\t";	
	$header .="MedicarePartA"."\t";
	$header .="MedicarePartB"."\t";
	$header .="EmergencyContact"."\t";
	$header .="EmergencyPhone"."\t";	
	$header .="EmergencyRelationship"."\t";	
	$header .="PremiumDeducted"."\t";
	$header .="PremiumSource"."\t";	
	$header .="OtherCoverage"."\t";	
	$header .="OtherCoverageName"."\t";	
	$header .="OtherCoverageID"."\t";	
	$header .="LongTerm"."\t";
	$header .="LongTermName"."\t";	
	$header .="LongTermAddress"."\t";	
	$header .="LongTermPhone"."\t";	
	$header .="AuthorizedRepName"."\t";	
	$header .="AuthorizedRepAddress"."\t";	
	$header .="AuthorizedRepCity"."\t";	
	$header .="AuthorizedRepState"."\t";	
	$header .="AuthorizedRepZip"."\t";	
	$header .="AuthorizedRepPhone"."\t";	
	$header .="AuthorizedRepRelationship"."\t";	
	$header .="Language"."\t";	
	$header .="OtherCoverageGroup"."\t";	
	$header .="AgentID"."\t";	
	$header .="SubmitTime"."\t";	
	$header .="PartDSubAppInd"."\t";	
	$header .="DeemedInd"."\t";	
	$header .="SubsidyPercentage"."\t";	
	$header .="DeemedReasonCode"."\t";
	$header .="LISCopayLevelID"."\t";	
	$header .="DeemedCopayLevelID"."\t";	
	$header .="PartDOptOutSwitch"."\t";	
	$header .="SEPReasonCode"."\t";	
	$header .="SEPCMSReason"."\t";	
	$header .="PremiumDirectPay"."\t";	
	$header .="EnrollmentPlanYear"."\t";

	
while($row = mssql_fetch_row($export)) { 
$line = ''; 
foreach($row as $value) {                                             
	if ((!isset($value)) OR ($value == "")) { 
		$value = "\t"; 
	} else { 
		$value = str_replace('"', '""', $value);
		$value = str_replace(chr(10), " ", $value); //remove carriage returns
		$value = str_replace(chr(13), " ", $value); //remove carriage returns
		$value = '"' . $value . '"' . "\t"; 
	} 
	$line .= $value;
		//$usql = "UPDATE 
			//wc_script_31_replica
		//SET
			//ConfirmationNumber = 1
		//WHERE
			//ID = ".$row[1];

		/*$update=mysql_query($usql,$veriosql);
		if (!$update) 
			{die("bad query : ".$usql);}*/
	} 
	$data .= trim($line)."\n"; 
} 
//$data = str_replace("\r","",$data); 
	
header("Content-type: application/x-msdownload"); 
header("Content-Disposition: attachment; filename=extraction.xls"); 
header("Pragma: no-cache"); 
header("Expires: 0"); 
print "$header\n$data"; 
?>