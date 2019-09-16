#!/usr/bin/php
<?php
	require_once('../Connections/veriosql.php');

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
	
	//Final Outcome of Call
	$Retained_Member='0'; 
	$Member_Not_Eligible_For_SEP='0';
	$Member_Enrolling_Another_Plan='0';
	$Sent_Disenrollment_Form='0';
	$Member_To_Send_Disenrollment_Form='0';
	$Misdirected='0';
	$Disenrolled_Auto_Enrolled_Plan='0';
	$Hang_Up='0';
	$Not_Retained='0';
	$Prev_Requested_Disenrollment='0';
	$OC_Other='0';
	
	//Reasons For Disenrollment
	$Plan_Cost_Premium='0';
	$Plan_Cost_Copay='0';
	$Drug_Coverage='0';
	$Pharmacy_Issue='0';
	$Service_Issue='0';
	$Benefits='0';
	$Billing_Issues='0';
	$Employee_Plans='0';
	$Enrolled_In_Another_Plan='0';
	$Loss_Subsidy='0';
	$VA_TriCare_Benefits='0';
	$Other='0';
	
	/*$usql = "UPDATE 
				DisenrollmentAnswers2
			SET
				TransferComments = ''  
			WHERE
				TransferComments = '(Enter Comment, who/what)'";

			$update=mysql_query($usql,$veriosql);
			if (!$update) 
				{die("bad query : ".$usql);}*/

	$sql = "SELECT 
				ID, 
				Date, 
				Time, 
				opID, 
				MemberName, 
				MemberID, 
				Q01, 
				Q02, 
				Q03, 
				Q04,
				Q05,
				Q06,
				Q07,
				Q08,
				Q09,
				Q10,
				Q11,
				Q12,
				Q12Issue,
				Q13,
				Q14,
				Benefits,
				DrugName,
				Transfer,
				TransferComments,
				misdirect,
				stayWithWC			
			FROM 	
				DisenrollmentAnswers2
			WHERE			
				Export_Date IS NULL AND Export_Time IS NULL" ;
			
				
	$export=mysql_query($sql,$veriosql);
	if (!$export) 
		{
			print $sql;
			die("bad query : ".$sql);
		}

	if (mysql_num_rows($export) == 0) {die("No Data Found to be exported!");}

	$stemp = tempnam("/var/www/localhost/htdocs/ResultsUniversity/admin/","Disenrollment_Summary_");	
	$sfp = fopen($stemp,"r+");
	
	$temp = tempnam("/var/www/localhost/htdocs/ResultsUniversity/admin/","Disenrollment_Answers_");	
	$fp = fopen($temp,"r+");
	
	$header  ="ID"."\t";
	$header .="Date"."\t";
	$header .="Time"."\t";
	$header .="opID"."\t";
	$header .="Member Name"."\t";
	$header .="MemberID"."\t";
	$header .="Did member qualify for SEP?"."\t";
	$header .="Disenrollment Option"."\t";
	$header .="Reason"."\t";
	$header .="Other reasons"."\t";
	$header .="Other Plan?"."\t";
	$header .="Which plan will be providing your coverage? "."\t";
	$header .="Premium Comparison"."\t";
	$header .="Co-Pay Comparison"."\t";
	$header .="Drug Coverage Comparison"."\t";
	$header .="Was member drug covered?"."\t";
	$header .="Alternative Drugs? "."\t";
	$header .="pharmacy join the network?"."\t";
	$header .="Pharmacy Info"."\t";
	$header .="Service Notes"."\t";
	$header .="Call Outcome"."\t";
	$header .="Benefits"."\t";
	$header .="Drug Name"."\t";
	$header .="Transfer"."\t";
	$header .="Transfer Comments"."\t";
	$header .="misdirect"."\t";
	$header .="stay with WC"."\r\n";

	fputs($fp,$header);
	$string="";
	while( $row = mysql_fetch_row($export))
		{
			$row=str_replace("\r\n"," ",$row);
			$row['4'] = strtoupper($row['4']);
			$string = $string . implode("\t",$row)."\r\n";
			
			switch(trim($row['20']))
				{
					case "Retained Member": $Retained_Member++ ; break;
					case "Member not eligible for SEP": $Member_Not_Eligible_For_SEP++ ; break;
					case "Member enrolling in another Plan": $Member_Enrolling_Another_Plan++; break;
					case "Sent Disenrollment Request Form": $Sent_Disenrollment_Form++; break;
					case "Member will send in Disenrollment Request Letter": $Member_To_Send_Disenrollment_Form++; break;
					case "Disenrolled because automatically enrolled in plan": $Disenrolled_Auto_Enrolled_Plan++; break;
					case "Hang Up" : $Hang_Up++; break;
					case "Not Retained" : $Not_Retained++; break;
					case "Previuosly requested disenrollment" : $Prev_Requested_Disenrollment++; break;
					case "Misdirected": $Misdirected++; break;
					default: $OC_Other++; break;
				}
	
			switch(trim($row["8"]))
				{
					case "Plan Cost/Premium": $Plan_Cost_Premium++; break;
					case "Plan Cost/Copay": $Plan_Cost_Copay++; break;
					case "Drug Coverage": $Drug_Coverage++; break;
					case "Pharmacy Issues": $Pharmacy_Issue++; break;
					case "Service Issue": $Service_Issue++; break;
					case "Benefits": $Benefits++; break;
					case "Billing Issues": $Billing_Issues++; break;
					case "Employee Plans": $Employee_Plans++; break;
					case "Enrolled in another plan": $Enrolled_In_Another_Plan++; break;
					case "Loss Subsidy": $Loss_Subsidy++; break;
					case "VA TriCare Benefits": $VA_TriCare_Benefits++; break;
					default: $Other++; break;
				}
				
			$usql = "UPDATE 
				DisenrollmentAnswers2
			SET
				Export_Date = '$today', 
				Export_Time = '$time'
			WHERE
				ID = ".$row['0'];

			$update=mysql_query($usql,$veriosql);
			if (!$update) 
				{die("bad query : ".$usql);}
				
		}
	fputs($fp,$string);
	fclose($fp);

	//Output Summary File
	fputs($sfp,"Retention Summary For ".$Yesterday."\n\r");
	fputs($sfp,""."\n\r");
	fputs($sfp,"Total Requests For Disenrollment"."\t".($Retained_Member+$Member_Not_Eligible_For_SEP+$Member_Enrolling_Another_Plan+$Sent_Disenrollment_Form+$Member_To_Send_Disenrollment_Form+$Misdirected+$Disenrolled_Auto_Enrolled_Plan+$Hang_Up+$Not_Retained+$Prev_Requested_Disenrollment+$OC_Other)."\n\r");
	fputs($sfp,"Retained Member"."\t".$Retained_Member."\n\r");
	fputs($sfp,"Member not eligible for SEP"."\t".$Member_Not_Eligible_For_SEP."\n\r");
	fputs($sfp,"Member enrolling in another Plan"."\t".$Member_Enrolling_Another_Plan."\n\r");
	fputs($sfp,"Sent Disenrollment Request Form"."\t".$Sent_Disenrollment_Form."\n\r");
	fputs($sfp,"Member will send in Disenrollment Request Letter"."\t".$Member_To_Send_Disenrollment_Form."\n\r");
	fputs($sfp,"Misdirected"."\t".$Misdirected."\n\r");
	fputs($sfp,"Disenrolled because automatically enrolled in plan"."\t".$Disenrolled_Auto_Enrolled_Plan."\n\r");
	fputs($sfp,"Hang Up"."\t".$Hang_Up."\n\r");
	fputs($sfp,"Not Retained"."\t".$Not_Retained."\n\r");
	fputs($sfp,"Previously requested Disenrollment"."\t".$Prev_Requested_Disenrollment."\n\r");
	fputs($sfp,"Other"."\t".$OC_Other."\n\r");
	fputs($sfp,""."\n\r");
	fputs($sfp,"Reasons For Disenrolment"."\n\r");
	fputs($sfp,"Plan Cost/Premium"."\t".$Plan_Cost_Premium."\n\r");
	fputs($sfp,"Plan Cost/Copay"."\t".$Plan_Cost_Copay."\n\r");
	fputs($sfp,"Drug Coverage"."\t".$Drug_Coverage."\n\r");
	fputs($sfp,"Pharmacy Issues"."\t".$Pharmacy_Issue."\n\r");
	fputs($sfp,"Service Issue"."\t".$Service_Issue."\n\r");
	fputs($sfp,"Benefits"."\t".$Benefits."\n\r");
	fputs($sfp,"Billing Issues"."\t".$Billing_Issues."\n\r");
	fputs($sfp,"Employee Plans"."\t".$Employee_Plans."\n\r");
	fputs($sfp,"Enrolled in another plan"."\t".$Enrolled_In_Another_Plan."\n\r");
	fputs($sfp,"Loss Subsidy"."\t".$Loss_Subsidy."\n\r");
	fputs($sfp,"VA TriCare Benefits"."\t".$VA_TriCare_Benefits."\n\r");
	fputs($sfp,"Other"."\t".$Other."\n\r");
	fclose($sfp);
	
	$newname = dirname($temp)."/Disenrollment_Answers_Export_".$Yesterday.".txt";
	if (!rename($temp,$newname)) die ("Unable to rename tmpfile.");

	$source_file = $newname;
	$destination_file = basename($source_file);

	$conn_id = ftp_connect($ftp_server); 
	$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass); 
	
	if ((!$conn_id) || (!$login_result)) 
		{ 
			echo "Ftp connection has failed!<br>";
			echo "Attempted to connect to $ftp_server for user $ftp_user_name<br>";
			mail('cusher@resultstel.com','FTP has failed','Attempted to connect to $ftp_server for Wellcare Daily Disenrollment Answers Export $ftp_user_name');
			die; 
		} else { echo "Connected to $ftp_server, for user $ftp_user_name<br>"; }

	$upload = ftp_put($conn_id, $destination_file, $source_file, FTP_BINARY); 
	if (!$upload) 
		{ 
			echo "Ftp upload has failed!<br>";
			mail('cusher@resultstel.com','FTP upload has failed','Attempted to upload $source_file to $ftp_server for user $ftp_user_name');
			die;
		} else {echo "Uploaded $source_file to $ftp_server as $destination_file<br>";}
		
	$newname = dirname($stemp)."/Disenrollment_Summary_".$Yesterday.".txt";
	if (!rename($stemp,$newname)) die ("Unable to rename tmpfile.");

	$source_file = $newname;
	$destination_file = basename($source_file);

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