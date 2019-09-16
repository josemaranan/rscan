<?php
require_once('../Connections/veriosql.php');
$database_veriosql = "ResultsTraining";
$today = date("Y-m-d");
$i=78;
while($i>0)
{
	$Yesterday =  date("Y-m-d",mktime(0, 0, 0, date("m")  , date("d")-$i, date("Y")));
	$time = date("H:i:s");
	//exit();
	
	$sql = "INSERT INTO xm_paybydate
			SELECT 
			
				RepID, 
				'$Yesterday' AS Paydate,
				if((COUNT(*) - 8)>0, ((COUNT(*)-8)*2.5), 0)  AS Amount,
				opID,
				RepName
			FROM xm_oem_conversion
			WHERE 
			DateCreated='$Yesterday' 
			AND Active='1'
			GROUP By opID
			ORDER BY  Amount DESC";	
			
	$export=mysql_query($sql) or die(mysql_error());
	if (!$export) 
		{
			print_a($sql);
			die("bad query : ".$sql);
		}
	else {
		print "XM Conversions Pay Exported Successfully for date: $Yesterday<br />";
	}
	$i--;
}
// XM_ConversionsPay_Export_Daily.php
?>