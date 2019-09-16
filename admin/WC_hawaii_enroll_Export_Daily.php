<?php
session_start();

include_once($_SERVER["DOCUMENT_ROOT"]."/Include/Global.inc.php");

	$db=mssql_connect(MSSQL_HOST, MSSQL_USERNAME, MSSQL_PASSWORD);
	mssql_select_db(MSSQL_DB);
	$result = mssql_query($query, $db);

	//session_start();

	//$level=$_SESSION['UserLevel'];
	//if(!$level || $level='')
	//	{
	//		header("location:../../index.php");
	//		exit();
	//	}

	$today = date("Y-m-d");
	$Yesterday =  date("Y-m-d",mktime(0, 0, 0, date("m")  , date("d")-1, date("Y")));
	$time = date("H:i:s");
	
	$ftp_server = FTP_SERVER;
	$ftp_user_name = FTP_USERNAME;
	$ftp_user_pass = FTP_PASSWORD;

	$sql = "SELECT 				
				 ContactDate,
				 ID,
				 FirstName, 
				 LastName ,
				 FamilySize,
				 PCP, 
				 ProviderID,
				 NPI_ID,
				 ProviderFirst,
				 ProviderLast,
				 MedicaidNum,
				 ContactType,
				 RepName,
				 DateOfCall,
				 Issue
			FROM 	
				[RUO]..wc_hawaii_enrollment
			WHERE			
				Active=1" ;
			
				
	$export=mssql_query($sql,$db);
	if (!$export) 
		{
			//print $sql;
			die("bad query : ".$sql);
		}

	if (mssql_num_rows($export) == 0) {
		die("No Data Found to be exported!");
	}
	
	$header  ="ContactDate"."\t";
	$header .="ID"."\t";
	$header .="FirstName"."\t";
	$header .="LastName"."\t";
	$header .="FamilySize"."\t";
	$header .="PCP"."\t";
	$header .="OhanaProviderID"."\t";
	$header .="NPI_ID"."\t";
	$header .="ProviderLast"."\t";
	$header .="ProviderLast"."\t";
	$header .="MedicaidNum"."\t";
	$header .="ContactType"."\t";
	$header .="RepNameID"."\t";
	$header .="TimeStamp"."\t";
	$header .="Issue"."\t";
	
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
		$usql = "UPDATE 
			[RUO]..wc_hawaii_enrollment
		SET
			Active = 0
		WHERE
			ID = ".$row[1];

		$update=mssql_query($usql,$db);
		if (!$update) 
			{die("bad query : ".$usql);}
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