<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT']."/agentScoreCard/includes/agentScoreCard.class.inc.php");
$agentScoreObj3 = new agentScoreCard();


require($_SERVER["DOCUMENT_ROOT"]."/Include/class/multicell_table.php");






$coachLocation = $_REQUEST[coachLocation];
$coachPosition = $_REQUEST[coachPosition];
$coach = $_REQUEST[coach];
$employeeID = $_SESSION[empID];


$coachingClient = $_SESSION[agentScoreClient];
$coachingLob = $_SESSION[agentScoreCardLob_id];
$scoreCardEntityID = $_SESSION['scoreCardEntityID'];



if($_REQUEST[type] == 'start')
{
	$startTimer = date("m/d/Y H:i:s");
	$cdate = date("m/d/Y");


$query =" EXEC RNet.dbo.process_spStartEmployeeCoachingSession '$employeeID', '$coachLocation', '$coachPosition', '$coach', '$cdate', '$startTimer', '$coachingClient', '$coachingLob' , '$scoreCardEntityID' ";

$result = $agentScoreObj3->ExecuteQuery($query);
		$v=mssql_fetch_array($result);
		$sessID=$v[0];
		
		echo $sessID.'|'.'start'.'|'.$startTimer;

}
else
{	
	$sessID = $_REQUEST[hdnSessionID];
	$endTimer = date("m/d/Y H:i:s");
		
	$mainBehCoached = $_REQUEST[ddlMainBehCoached];
	$check = addslashes($_REQUEST[txtCheck]);
	$set = addslashes($_REQUEST[txtSet]);
	$inspect = addslashes($_REQUEST[txtInspect]);
	$empComDate = $_REQUEST[txtEmpCommitDate];
	$coachFollowdate = $_REQUEST[txtCoachFollowUpDate];
	
	
	$query =" EXEC RNet.dbo.[process_spEndEmployeeCoachingSession] '$endTimer', '$set', '$check', '$inspect', '$empComDate', '$coachFollowdate', '$mainBehCoached', '$sessID' ";
				
	$result = $agentScoreObj3->ExecuteQuery($query);



///SENDING EMAIL NOTIFICATIONS WITH ATTACHMENT


$coachQry = " EXEC RNet.dbo.[report_spCompletedEmployeeCoachingSessionDetails] '$sessID' ";

	
	$result = $agentScoreObj3->ExecuteQuery($coachQry);

	$isExistingSession = 'N';

	while($row=mssql_fetch_assoc($result)) 
    {
       $employeeID =$row['employeeID'];
	   $empName =$row['firstName'].' '.$row['lastName'];
	   $coachName =$row['coachFirstName'].' '.$row['coachLastName'];
	   $startTime =$row['startTime'];
	   $duration=$row['duration'];
		$coachID=$row['coach'];
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
	
	$coachSessions = " EXEC RNet.dbo.[report_viewCoachingDetails] $sessID ";
	
	$resultSess= $agentScoreObj3->ExecuteQuery($coachSessions);
	$num_rowsSess = mssql_num_rows($resultSess);


	if($num_rowsSess>=1)
	{
		$sessArray = $agentScoreObj3->bindingInToArray($resultSess);
	}
	mssql_free_result($resultSess);




//GETTING COACH EMAIL ADDRESS
		
	$qryCoachEA = " SELECT rnet.dbo.fn_getEmployeeEmailID ($coachID) as emailAddress ";
	
	$resultEA = $agentScoreObj3->ExecuteQuery($qryCoachEA);

	$coachEmail = '';
	
	while($row2=mssql_fetch_assoc($resultEA)) 
    {
       $coachEmail =  $row2['emailAddress'];
	}		

	@mssql_free_result($resultEA);




include_once($_SERVER['DOCUMENT_ROOT']."/MPDF/mpdf.php");

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
$fname = 'Coachingsession_'.$sessID.'.pdf';
//$mpdf->Output($fname,'D');




$separator = md5(time()); 
	$eol = PHP_EOL; 
	$filename = "Coachingsession_".$sessID.".pdf"; 
	$pdfdoc = $mpdf->Output("", "S");
	$attachment = chunk_split(base64_encode($pdfdoc));  
	
	$from = 'rnet-system@resultstel.com';
	
	$message = "Please find the attachment for a summary of the coaching session between ".$coachName." (coach) and ".$empName." (agent) held on ".date('m/d/Y');
	
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
	
	
	$EmailSubject = 'Coaching Session Summary: '.$coachName.'/'.$empName;
	
	$MESSAGE_BODY = "Please click the link below for a summary of the coaching session";
	
	
	
		if(!empty($coachEmail))
	{
		@mail($coachEmail, $EmailSubject, "", $headers); 
	}
	
	$agentEmail = $_SESSION['rNetUserEMailAddress'];
		//echo $agentEmail;
		if(!empty($agentEmail))
		{
			@mail($agentEmail, $EmailSubject, $MESSAGE_BODY, $headers);
		}
	
	mail('vengal.sivvannagari@resultstel.com', $EmailSubject, "", $headers); 

///END EMAIL FUNCTIONALITY
	//$agentScoreObj3->closeConn();

header("Location: viewMyScoreCard.php");


	
}


?>