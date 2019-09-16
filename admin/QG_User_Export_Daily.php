#!/usr/bin/php
<?php
	require_once('../Connections/veriosql.php');
/*
	admin/QG_User_Export_Daily.php -- builds a txt file to export to ResultsNet (via ftp)
	CHU -- 03/02/2007
*/

	if( !$db )	
		{ 
			die("Error connecting to the Server");
			exit;
		}

	$result = mysql_select_db("ResultsTraining", $db);
	if( !$result )
		{
			die("Error selecting Database");
			exit;
		}

	$ftp_server = FTP_SERVER;
	$ftp_user_name = FTP_USERNAME;
	$ftp_user_pass = FTP_PASSWORD;

	$sql = "SELECT 
				Employee, Location, FirstName, LastName, Phone, Email, Password, Active, UserLevel, DateUpdated, Previous_ID, LastCall_Date, LastQuickGuide_Date 				
			FROM 	
				SiteUsers";

	$res=mysql_query($sql,$db);
	if (!res) 
		{
			print_a($res);
			die("bad query : ".$res->getMessage().$sql);
		}

	if (mysql_num_rows($res) == 0) {die("Problem - QuickGuide Users NOT exported!");}

	$temp = tempnam("/var/www/localhost/htdocs/ResultsUniversity/admin/","exp_");	
	$fp = fopen($temp,"r+");
	$string='Employee|Location|FirstName|LastName|Phone|Email|Password|Active|UserLevel|DateUpdated|Previous_ID|LastCall_Date|LastQuickGuide_Date' . "\r\n";
	while( $row = mysql_fetch_row($res))
		{
			$string = $string . implode("|",$row) . "\r\n";
		}
	fputs($fp,$string);
	fclose($fp);

	$newname = dirname($temp)."/QG_User_Export_".date("Ymd").".txt";
	if (!rename($temp,$newname)) die ("Unable to rename tmpfile.");

	$source_file = $newname;
	$destination_file = basename($source_file);

	$conn_id = ftp_connect($ftp_server); 
	$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass); 
	
	if ((!$conn_id) || (!$login_result)) 
		{ 
			echo "Ftp connection has failed!<br>";
			echo "Attempted to connect to $ftp_server for user $ftp_user_name<br>";
			mail('cusher@resultstel.com','FTP has failed','Attempted to connect to $ftp_server for user $ftp_user_name');
			die; 
		} else { echo "Connected to $ftp_server, for user $ftp_user_name<br>"; }

	$upload = ftp_put($conn_id, $destination_file, $source_file, FTP_BINARY); 
	if (!$upload) 
		{ 
			echo "Ftp upload has failed!<br>";
			mail('cusher@resultstel.com','FTP upload has failed','Attempted to upload $source_file to $ftp_server for user $ftp_user_name');
			die;
		} else {echo "Uploaded $source_file to $ftp_server as $destination_file<br>";}
 
	ftp_close($conn_id);
	print "Successfully exported $destination_file<br>";
?>