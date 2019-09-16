<?php
//ini_set('display_errors','1');
/**
 * @description : Employee Details page  (ADP)
 * @author : BhanuPrakash
 * @date : 03/03/2014
 * */
//session_start();

$locArray = $employeeeMaintenanceObj->getUsLocations();
$reportinglocArray = $employeeeMaintenanceObj->getUSReportingLocations();
$payGroupLocationArray = $employeeeMaintenanceObj->getUSPayGroupLocations();
$locArrayKeyValue = $employeeeMaintenanceObj->convertArrayKeyValuePair($locArray, 'location','description');
$reportinglocArrayKeyValue = $employeeeMaintenanceObj->convertArrayKeyValuePair($reportinglocArray, 'location','description');
$payGroupLocationArrayKeyValue = $employeeeMaintenanceObj->convertArrayKeyValuePair($payGroupLocationArray, 'location','paygroup');
$empStausArray = $employeeeMaintenanceObj->getEmploymentStatuses();
$missingEmpData = $employeeeMaintenanceObj->getMissingColumns();


$sqlMainQry = "
			SELECT
				e.description as educationLevelDescription
			FROM
					results.dbo.ctlEmployees a with (nolock)
			LEFT JOIN
					results.dbo.ctlEducationLevelDetails e WITH (NOLOCK)
				ON
					a.educationLevel = e.educationLevelID
			WHERE
				a.employeeID = ".$employeeID." ";
$rstMainQry = $employeeeMaintenanceObj->execute($sqlMainQry);

$educationLevelDescription = mssql_result($rstMainQry,0,0);
unset($rstMainQry);
unset($sqlMainQry);
$supName = $employData[0]['supervisorFirstName'].' '.$employData[0]['supervisorLastName'];


unset($sqlquery);
unset($rstMainQry);
unset($payGroupLocaitons03062013);
$sqlquery = " SELECT	
						DISTINCT [description] + ' (' + paygroupID + ')' [paygroup],
						[location]
					FROM	
						rnet.dbo.ctlADPPaygroupLocations WITH (NOLOCK)";
						
$rstMainQry = $employeeeMaintenanceObj->execute($sqlquery);

while($codes03062013 = mssql_fetch_assoc($rstMainQry))
{
	$payGroupLocaitons03062013[$codes03062013['location']] = $codes03062013['paygroup'];
}

echo $htmlTagObj->openTag('div','id="topHeading" class="outer"');
echo $topLevelHeading;
echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->openTag('div','id="businessRuleHeading" class="outer"');
echo 'Employee Summary';
echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->openTag('div','id="missingInfoContent" class="outer"');
echo "<p>NOTE: Any incorrect or missing information will delay the employee's pay.</p><p> ".$missingEmpData."</p>";
echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->openTag('div','id="emptyDiv" class="outer"');
echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->openTag('div','id="scrollingdatagrid" class="scrollingdatagrid" visible="true"');
echo $htmlTagObj->openTag('div','id="singlePixelBorder" style="padding:8px;"');

// define table and display...
$lblFirstName	= $htmlTextElement->addLabel($employData[0]['firstName'], 'First Name:', '#ff0000','');
$lblLastName	= $htmlTextElement->addLabel($employData[0]['lastName'], 'Last Name:', '#ff0000','');
$lblLocation	= $htmlTextElement->addLabel($employData[0]['locationDescription'], 'Location:', '#ff0000','');
$lblEmpId	= $htmlTextElement->addLabel($employData[0]['employeeID'], 'Employee ID:', '#ff0000','');
$lblEmpStatus	= $htmlTextElement->addLabel($employData[0]['careerStatus'], 'Employment Status:', '#ff0000','');
$lblPrimaryPos	= $htmlTextElement->addLabel($employData[0]['position'], 'Primary Position:', '#ff0000','');

$lblPhone	= $htmlTextElement->addLabel($employData[0]['homePhone'], 'Phone:', '#ff0000','');
$lblEmail	= $htmlTextElement->addLabel($employData[0]['emailAddress'], 'Email:', '#ff0000','');
$lblSupervisor	= $htmlTextElement->addLabel($supName, 'Supervisor:', '#ff0000','');
$lblEducation	= $htmlTextElement->addLabel($educationLevelDescription, 'Education:', '#ff0000','');
$lblFullPartTime	= $htmlTextElement->addLabel($employData[0]['fullTimePartTime'], 'Full-time / Part-time:', '#ff0000','');

$lblRace	= $htmlTextElement->addLabel($employData[0]['race'], 'Race/Ethnicity:', '#ff0000','');
$lblGender	= $htmlTextElement->addLabel(($employData[0]['gender'] == 'M') ? 'Male' : 'Female', 'Gender:', '#ff0000','');
$socialSecurity = '';
if(!empty($employADPData[0]['SSN']))
{
	$socialSecurity =  str_pad(substr($employADPData[0]['SSN'],5,4),9,'*',STR_PAD_LEFT);
}
$lblSocialSecurity	= $htmlTextElement->addLabel($socialSecurity, 'Social Security:', '#ff0000','');
$dob = '';
if(!empty($employData[0]['dob']))
{
	$dob = date('m/d/Y', strtotime($employData[0]['dob']));
}
$lblDOB	= $htmlTextElement->addLabel($dob, 'DOB:', '#ff0000','');

$hireDate = '';
if($employADPData[0]['hireDateReview']!='Y')
{
	$hireDate = '<div class="singlePixelRed" style="border: 1px solid #F00; width:120px;">
				<p style="text-align:left; padding:4px; color:#F00; margin:0px;">
				Not Reviewed by HR; <br />use Modify Employment Dates</p>
				</div>&nbsp;';
}
else
{
	if(!empty($employADPData[0]['hireDate']))
	{
		$hireDate = date('m/d/Y', strtotime($employADPData[0]['hireDate']));
	}
}

$lblHD	= $htmlTextElement->addLabel($hireDate, 'Hire Date:', '#ff0000','');
$lblPG	= $htmlTextElement->addLabel($payGroupLocaitons03062013[$employData[0]['payrollLocation']], 'Pay group:', '#ff0000','');
$lblWL	= $htmlTextElement->addLabel($locArrayKeyValue[$employADPData[0]['location']], 'Work Location:', '#ff0000','');
$lblRL	= $htmlTextElement->addLabel($reportinglocArrayKeyValue[$employADPData[0]['adpReportingLocation']], 'Reporting Location group:', '#ff0000','');
$lblGSS	= $htmlTextElement->addLabel( ($employData[0]['GSS'] == 'N') ? 'No' : 'Yes', 'GSS:', '#ff0000','');
$lblVirtual	= $htmlTextElement->addLabel( ($employData[0]['virtual'] == 'N') ? 'No' : 'Yes', 'Virtual:', '#ff0000','');
$lblAmount	= $htmlTextElement->addLabel( ($employADPData[0]['amount']=='1.00'?'compensation rate not displayed':$employADPData[0]['amount']), 'Comp Rate:', '#ff0000','');
$lblEmpType	= $htmlTextElement->addLabel($employData[0]['payTypeDescription'], 'Employee Type:', '#ff0000','');
$lblFreq	= $htmlTextElement->addLabel('Bi-weekly', 'Comp Frequency:', '#ff0000','');
$lblCompCode	= $htmlTextElement->addLabel($employData[0]['adpWorkersCompCode'], 'Worker&acute;s Comp Code:', '#ff0000','');
$lblEEO	= $htmlTextElement->addLabel($employData[0]['EEO1Class'], 'EEO Class:', '#ff0000','');
$lblFLSASts	= $htmlTextElement->addLabel($employData[0]['FLSASts'], 'FLSA:', '#ff0000','');

$lblSpace	= $htmlTextElement->addLabel('', '', '','');




$tableObj->tableId = 'searchTable';
$tableObj->tableClass = 'searchtab';
$tableObj->cellPadding = '5';// cellSpacing
$tableObj->maxCol = 2;
 
$tableObj->searchFields['lblFirstName'] = $lblFirstName;
$tableObj->searchFields['lblLastName'] = $lblLastName;
$tableObj->searchFields['lblLocation'] = $lblLocation;
$tableObj->searchFields['lblEmpId'] = $lblEmpId;
$tableObj->searchFields['lblEmpStatus'] = $lblEmpStatus;
$tableObj->searchFields['lblPrimaryPos'] = $lblPrimaryPos;
$tableObj->searchFields['lblPhone'] = $lblPhone;
$tableObj->searchFields['lblEmail'] = $lblEmail;
$tableObj->searchFields['lblSupervisor'] = $lblSupervisor;
$tableObj->searchFields['lblEducation'] = $lblEducation;
$tableObj->searchFields['lblFullPartTime'] = $lblFullPartTime;
$tableObj->searchFields['lblRace'] = $lblRace;
$tableObj->searchFields['lblGender'] = $lblGender;
$tableObj->searchFields['lblSocialSecurity'] = $lblSocialSecurity;
$tableObj->searchFields['lblDOB'] = $lblDOB;
$tableObj->searchFields['lblHD'] = $lblHD;
$tableObj->searchFields['lblSpace1'] = $lblSpace;
$tableObj->searchFields['lblSpace2'] = $lblSpace;
$tableObj->searchFields['lblPG'] = $lblPG;
$tableObj->searchFields['lblWL'] = $lblWL;
$tableObj->searchFields['lblRL'] = $lblRL;
$tableObj->searchFields['lblGSS'] = $lblGSS;
$tableObj->searchFields['lblVirtual'] = $lblVirtual;
$tableObj->searchFields['lblAmount'] = $lblAmount;
$tableObj->searchFields['lblEmpType'] = $lblEmpType;
$tableObj->searchFields['lblFreq'] = $lblFreq;
$tableObj->searchFields['lblCompCode'] = $lblCompCode;
$tableObj->searchFields['lblEEO'] = $lblEEO;
$tableObj->searchFields['lblFreq'] = $lblFreq;
$tableObj->searchFields['lblFLSASts'] = $lblFLSASts;

echo $tableObj->searchFormTableComponent();
// end table 

echo $htmlTagObj->closeTag('div');
echo $htmlTagObj->closeTag('div');




if($employeeeMaintenanceObj->UserDetails->User == $employeeID)
{
	$updateFirstLogin  = "
					IF EXISTS(SELECT 
									day1PresentDate 
								FROM 
									RNet.dbo.prmEmployeeCareerHistory WITH (NOLOCK) 
								WHERE 
									employeeID = '".$employeeID."'  
									AND 
									day1PresentDate IS NULL
									AND
									hireDate = '".$employADPData[0]['hireDate']."' 
								)
					BEGIN
						UPDATE 
							RNet.dbo.prmEmployeeCareerHistory
						SET day1PresentDate = '".date('Y-m-d H:i:s')."'
						WHERE
							employeeID = '".$employeeID."' 
							AND
							hireDate = '".$employADPData[0]['hireDate']."' 
						
					END
				";
				
	$rstMainQry = $employeeeMaintenanceObj->execute($updateFirstLogin);
}
if($_REQUEST['res'] == 'Termfailure')
{
	print '<script type="text/javascript">
	alert("You are setting a separation date for this employee in the past.  However, this employee has payroll hours in the system after the separation date.  Please have your payroll administrator remove these hours before setting the separation date.");</script>';
}
?>