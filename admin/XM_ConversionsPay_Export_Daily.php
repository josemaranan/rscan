<?php
	require_once('../Connections/veriosql.php');
	mysql_select_db($database_veriosql, $veriosql);
	
	$today = date("Y-m-d");
	$Yesterday =  date("Y-m-d",mktime(0, 0, 0, date("m")  , date("d")-1, date("Y")));
	$time = date("H:i:s");
	//exit();
	
	$sql = "INSERT INTO xm_paybydate
			SELECT 
				RepID, 
				'$Yesterday' AS Paydate,
				if((COUNT(*) - 8)>0, ((COUNT(*)-8)*2.5), 0)  AS Amount,
				opID, 
				RepName,
				Company
			FROM xm_oem_conversion 
			WHERE 
			DateCreated='$Yesterday' 
			AND Active='1'
			GROUP By opID
			ORDER BY  Amount DESC";	
			
	$export=mysql_query($sql, $veriosql) or die(mysql_error());
	if (!$export) 
		{
			print_a($sql);
			die("bad query : ".$sql);
		}
	else {
		print "XM Conversions Pay Exported Successfully for date: $Yesterday";
	}

// XM_ConversionsPay_Export_Daily.php
?>