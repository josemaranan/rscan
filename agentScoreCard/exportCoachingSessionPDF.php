<?php
include_once($_SERVER['DOCUMENT_ROOT']."/agentScoreCard/includes/agentScoreCard.class.inc.php");
$agentScoreObj = new agentScoreCard();

include_once($_SERVER['DOCUMENT_ROOT']."/Include/HTMLContent.class.inc.php");
$htmlObject = new HTMLClass();


//$coachingSessionID = $_REQUEST[coachSessID];

$coachingSessionID = $_REQUEST[hdnSessionID];
//$coachingSessionID = 645;


/*$coachQry = "
			SELECT
				a.employeeID,
				b.firstName,
				b.lastName,
				c.firstName as coachFirstName,
				c.lastName as coachLastName,
				a.startTime,
				a.setValue,
				a.checkValue,
				a.inspectValue,
				a.employeeCommitmentDate,
				a.coachFollowUpDate,
				d.mainBehaviorCoach,
				CAST(round(DATEDIFF(s,a.startTime,a.endTime)/60.000,2) as decimal(10,2)) - CAST(round(DATEDIFF(s,isnull(a.pause,0),isnull(a.resume,0))/60.000,2) as decimal(10,2)) as duration
			FROM
				rnet..prmEmployeeCoachingSessions a WITH (NOLOCK) 
			JOIN
				Results.dbo.ctlEmployees b WITH (NOLOCK)
			ON
				a.employeeID = b.employeeID
			JOIN
				Results.dbo.ctlEmployees c WITH (NOLOCK)
			ON
				a.coach = c.employeeID
			LEFT JOIN
				RNet.dbo.ctlEmployeeCoachingMainBehaviors d WITh (NOLOCK)
			ON
				a.mainBehaviourCoachID = d.mainBehaviorCoachID
			WHERE 
				coachSessionID = $coachingSessionID 
			";*/
	$coachQry = "EXEC Rnet.dbo.[rnet_spGetPopulateCoaches] '$coachingSessionID' ";
	
	$result = $agentScoreObj->ExecuteQuery($coachQry);

	$isExistingSession = 'N';

	while($row=mssql_fetch_assoc($result)) 
    {
       $employeeID =$row['employeeID'];
	   $empName =$row['firstName'].' '.$row['lastName'];
	   $coachName =$row['coachFirstName'].' '.$row['coachLastName'];
	   $startTime =$row['startTime'];
	   $duration=$row['duration'];

		$setValue=$row['setValue'];
		$checkValue=$row['checkValue'];
		$inspectValue=$row['inspectValue'];
		
		if($row['employeeCommitmentDate'] != '')
		{
			$employeeCommitmentDate=date('m/d/Y',strtotime($row['employeeCommitmentDate']));
		}
		
		if($row['coachFollowUpDate'] != '')
		{
				$coachFollowUpDate=date('m/d/Y',strtotime($row['coachFollowUpDate']));
		}
		
		
		$mainBehaviourCoachID=$row['mainBehaviorCoach'];






		$isExistingSession = 'Y';
	   
	}
	
	$coachSessions = " EXEC RNet.dbo.[report_viewCoachingDetails] $coachingSessionID ";
	
	$resultSess= $agentScoreObj->ExecuteQuery($coachSessions);
	$num_rowsSess = mssql_num_rows($resultSess);


	if($num_rowsSess>=1)
	{
		$sessArray = $agentScoreObj->bindingInToArray($resultSess);
	}
	mssql_free_result($resultSess);



$cssJsArray = array('CSS'=>array('readiNetAll.css', 'dhtmlgoodies_calendar.css?random=20051112'));
$htmlObject->loadCSSJsFiles($cssJsArray);
include_once($_SERVER['DOCUMENT_ROOT']."/MPDF/mpdf.php");

//include("../MPDF/mpdf.php");
$mpdf=new mPDF();//A4 page in portrait for landscape add -L.
//$mpdf->SetHeader('|Your Header here|');
$mpdf->setFooter('{PAGENO}');// Giving page number to your footer.
$mpdf->useOnlyCoreFonts = true;    // false is default
$mpdf->SetDisplayMode('fullpage');
// Buffer the following html with PHP so we can store it to a variable later
ob_start();
?>

<div style="border:0.5mm solid  #000;">
<table border="0" cellspacing="0" cellpadding="0" width="100%">
<tr>
<td colspan="4" bgcolor="#7AC143">
<strong>Coaching Session for <?php echo $empName;?></strong>
</td>
</tr>
<tr>
<td colspan="4">
</td>
</tr>

<tr>
    <th bgcolor="#7AC143">Employee ID:</th>
    <td><?php echo $employeeID;?></td>
	<th bgcolor="#7AC143">Coach:</th>
	<td><?php echo $coachName;?></td>
</tr>

<tr>
    <th bgcolor="#7AC143">Date/Time:</th>
    <td><?php echo date('F d, Y h:i A', strtotime($startTime));?></td>
    <th bgcolor="#7AC143">Duration:</th>
    <td><?php echo $duration;?> minutes</td>
</tr>
<tr>
<td colspan="4">
</td>
</tr>


</table>
</div>
<br />
<div style="border:0.5mm solid  #000; width:100%;">
<table id="searchTable" cellspacing="2" width="100%">

<tr>
<td colspan="2" bgcolor="#7AC143" style="border:1mm">
<strong>Success Plan</strong>
</td>
</tr>
<tr>
<td colspan="2">
</td>
</tr>


	<tr>
    <th bgcolor="#7AC143" style="text-align:right; width:35%">Employee Commitment Date:</th>
    <td><?php echo $employeeCommitmentDate;?></td>
 </tr>


<tr>
    <th bgcolor="#7AC143" style="text-align:right; width:35%">Coach Follow-up Date:</th>
    <td><?php echo $coachFollowUpDate;?></td>
 </tr>


<tr>
    <th bgcolor="#7AC143" style="text-align:right; width:35%">Main Behavior Coached:</th>
    <td><?php echo $mainBehaviourCoachID;?></td>
 </tr>


 
 <tr>
	<th bgcolor="#7AC143" style="text-align:right; width:35%">(C)heck:</th>
	<td><?php echo $checkValue;?></td>
 </tr>
 <tr>
   	<th bgcolor="#7AC143" style="text-align:right; width:35%">(S)et:</th>
	<td><?php echo $setValue;?></td>
</tr>


 <tr>
   	<th bgcolor="#7AC143" style="text-align:right; width:35%">(I)nspect:</th>
	<td><?php echo $inspectValue;?></td>
</tr>


<tr>
<td>
</td>
</tr>
</table>
</div>
<br />
<?php
if($num_rowsSess>=1)
{
?>




<?php
$ucID = '';
$type = '';
foreach($sessArray as $strArrayK=>$strArrayV)
{
$existUcID = $strArrayV[ucid];
$existingType = $strArrayV[type];

if($ucID != '' && $ucID != $existUcID)
{
	?>

</table>
</div>
<br />
<!-- </fieldset> -->

<?php
	
}


if($ucID != $existUcID)
{
?>
<div style="border:0.5mm solid  #000; width:100%;">
<table id="searchTable" cellspacing="2" width="100%">
<tr>
<td colspan="2" bgcolor="#7AC143" style="border:1mm">
<strong>Call (UCID <? echo $strArrayV[ucid];?>)</strong>
</td>
</tr>
<?	
}

if($type != $existingType)
{
?>
<tr>
<td colspan="2">
<strong><? echo $strArrayV[type];?></strong>
</td>
</tr>
<?	
}
?>
<tr>
    <th bgcolor="#7AC143" style="text-align:right; width:30%">KPI:</th>
    <td><?php echo $strArrayV[KPI];?></td>
 </tr>
 <tr>
   	<th bgcolor="#7AC143" style="text-align:right; width:30%">
    <? if($strArrayV[isPrimary] == 'Y')
    {
	echo 'Primary ';
	}
	?>
    Behavior:</th>
    
	<td><?php echo $strArrayV[Behaviour];?><br /></td>
</tr>
<?php 
if($strArrayV[type] == 'Opportunities')
{
?>

<tr>
    <th bgcolor="#7AC143" style="text-align:right; width:30%">Coaching Tool:</th>
    <td><?php echo $strArrayV[method];?></td>
 </tr>
 <tr>
   	<th bgcolor="#7AC143" style="text-align:right; width:30%">Action Plan:</th>
	<td><?php echo $strArrayV[actionPlan];?><br /></td>
</tr>

<?php	
}
?>
<tr>
<td>
<br />
</td>
</tr>
<?php
$ucID = $existUcID;
$type = $existingType;

}

?>
</table>
</div>
<?

}
?>




<?php 
$html = ob_get_contents();
ob_end_clean();
// send the captured HTML from the output buffer to the mPDF class for processing
$mpdf->WriteHTML($html);
//$mpdf->SetProtection(array(), 'user', 'password'); uncomment to protect your pdf page with password.
$fname = 'Coachingsession_'.$coachingSessionID.'.pdf';
//$mpdf->Output($fname,'D');
$mpdf->Output($fname,'D');


/*

$separator = md5(time()); 
	$eol = PHP_EOL; 
	$filename = "Coachingsession_".$coachingSessionID.".pdf"; 
	$pdfdoc = $mpdf->Output("", "S");
	$attachment = chunk_split(base64_encode($pdfdoc));  
	
	$from = 'rnet-system@resultstel.com';
	
	$message = "Please find the attachment for a summary of the coaching session between ".$coachName." (coach) and ".$_SESSION['rNetUserFirstName'].' '.$_SESSION['rNetUserLastName']." (agent) held on ".date('m/d/Y');
	
	$headers  = "From: ".$from.$eol;
	$headers .= "MIME-Version: 1.0".$eol; 
	$headers .= "Content-Type: multipart/mixed; boundary=\"".$separator."\"".$eol.$eol; 
	$headers .= "Content-Transfer-Encoding: 7bit".$eol;
	$headers .= "This is a MIME encoded message.".$eol.$eol; 
	
	// message
	$headers .= "--".$separator.$eol;
	$headers .= "Content-Type: text/html; charset=\"iso-8859-1\"".$eol;
	$headers .= "Content-Transfer-Encoding: 8bit".$eol.$eol;$headers .= $message.$eol.$eol; 
	
	// attachment
	$headers .= "--".$separator.$eol;$headers .= "Content-Type: application/octet-stream; name=\"".$filename."\"".$eol; 
	$headers .= "Content-Transfer-Encoding: base64".$eol;
	$headers .= "Content-Disposition: attachment".$eol.$eol;
	$headers .= $attachment.$eol.$eol;
	$headers .= "--".$separator."--"; 
	
	
	$EmailSubject = 'Coaching Session Summary: '.$coachName.'/'.$_SESSION['rNetUserFirstName'].' '.$_SESSION['rNetUserLastName'];
	
	$MESSAGE_BODY = "Please click the link below for a summary of the coaching session";
	
	
	mail('vengal.sivvannagari@resultstel.com', $EmailSubject, "", $headers); 



*/







/*

$mpdf->WriteHTML(utf8_encode($html)); 

 $content = $mpdf->Output('', 'S');

 $content = chunk_split(base64_encode($content));
 $mailto = 'vengal.sivvannagari@resultstel.com'; //Mailto here
 $from_name = 'ACME Corps Ltd'; //Name of sender mail
 $from_mail = 'rnet-system@resultstel.com'; //Mailfrom here
 $subject = 'subjecthere'; 
 $message = 'mailmessage';
 $filename = "yourfilename-".date("d-m-Y_H-i",time()); //Your Filename whit local date and time

 //Headers of PDF and e-mail
 $boundary = "XYZ-" . date("dmYis") . "-ZYX"; 

 $header = "--$boundary\r\n"; 
 $header .= "Content-Transfer-Encoding: 8bits\r\n"; 
 $header .= "Content-Type: text/html; charset=ISO-8859-1\r\n\r\n"; //plain 
 $header .= "$message\r\n";
 $header .= "--$boundary\r\n";
 $header .= "Content-Type: application/pdf; name=\"".$filename."\"\r\n";
 $header .= "Content-Disposition: attachment; filename=\"".$filename."\"\r\n";
 $header .= "Content-Transfer-Encoding: base64\r\n\r\n";
 $header .= "$content\r\n"; 
 $header .= "--$boundary--\r\n";

 $header2 = "MIME-Version: 1.0\r\n";
 $header2 .= "From: ".$from_name." \r\n"; 
 $header2 .= "Return-Path: $from_mail\r\n";
 $header2 .= "Content-type: multipart/mixed; boundary=\"$boundary\"\r\n";
 $header2 .= "$boundary\r\n";

 mail($mailto,$subject,$header,$header2, "-r".$from_mail);

 $mpdf->Output($filename ,'I');
 //exit;

exit;
*/
$agentScoreObj->closeConn();
?>