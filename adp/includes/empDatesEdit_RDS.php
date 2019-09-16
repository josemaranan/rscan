<?php
//$employeeeMaintenanceObj->setUSLocations();

//$employData = $employeeeMaintenanceObj->getEmployeeInformation();
//$employADPData = $employeeeMaintenanceObj->getEmployeeADPInformation();

unset($sqlMainQry);
unset($rstMainQry);
//echo $datePlus30Days;exit;
unset($show);
$show = 'N';

unset($datePlus30Days);
$datePlus30Days = date('m/d/Y',strtotime('+30 days'));

//$hireDate = $_GET["hireDate"];

//$hireDate = date('m/d/Y',strtotime($hireDate));

//limited access to locations for juan.ponder(user)
	
// Get ClientName Dynamically
unset($dynamicClient);
unset($dynamicClientName);
unset($sqlQuery);
unset($resultsSet);

$sqlQuery = "SELECT Rnet.dbo.[fn_spGetEmployeePrimaryClient] ('".$employeeID."', '".$hireDate ."') ";
$resultsSet = $employeeeMaintenanceObj->execute($sqlQuery);
$dynamicClientName = mssql_result($resultsSet,0,0);
unset($sqlQuery);
unset($resultsSet);

$sqlQuery = "SELECT [description] FROM ctlclients with (nolock) where clientName = '".$dynamicClientName."' ";
$resultsSet = $employeeeMaintenanceObj->execute($sqlQuery);
$dynamicClient = mssql_result($resultsSet,0,0);
unset($sqlQuery);
unset($resultsSet);

$Filter .= " employeeID = '".$employeeID."' ";
$Filter .= " and location in".$employeeeMaintenanceObj->UserDetails->Locations;
$query="SELECT [employeeID] FROM  [ctlEmployees] WITH (NOLOCK) where ".$Filter; 
$rst = $employeeeMaintenanceObj->execute($query);
$num =$employeeeMaintenanceObj->getNumRows($rst);
if($num==0)
{
	echo "<script type='text/javascript'>window.location='index.php';</script>";
}
mssql_free_result($rst);

$sqlEmpDet = "	SELECT 
					e.firstName, e.lastName, loc.description 
				FROM 
					ctlEmployees e WITH (NOLOCK)
				LEFT JOIN
					ctlLocations loc WITH (NOLOCK)
				ON
					e.location = loc.location
				WHERE 
					employeeID = '$employeeID' ";
$rstEmpDet = $employeeeMaintenanceObj->execute($sqlEmpDet);
while ($rowEmpDet=mssql_fetch_array($rstEmpDet)) 
{
	$empName = $rowEmpDet[firstName]." ".$rowEmpDet[lastName];
	$locDesc = $rowEmpDet[description];
}
mssql_free_result($rstEmpDet);
	
$sqlHireTerm = "SELECT
					termDate,
					notes,
					terminationReasonID,
					CASE canBeRehired WHEN 1 THEN 'Yes' WHEN 0 THEN 'No' ELSE NULL END canBeRehired,
					CASE voluntaryTermination WHEN 1 THEN 'Yes' WHEN 0 THEN 'No' ELSE NULL END voluntaryTermination,
					NCNS
				FROM 
					RNet.dbo.prmEmployeeCareerHistory WITH (NOLOCK)
				WHERE 
					employeeID = '$employeeID' 
				AND
					hireDate = '$hireDate' ";
$rstHireTerm = $employeeeMaintenanceObj->execute($sqlHireTerm);
while ($rowHireTerm=mssql_fetch_array($rstHireTerm)) 
{
	$tDate = $rowHireTerm[termDate];
	if(!empty($tDate))
	{
		$tDate = date('m/d/Y',strtotime($tDate));
		$show = 'yes';
	}
	$TerminationReasons = $rowHireTerm[terminationReasonID];
	$Rehireable = $rowHireTerm[canBeRehired];
	$voluntaryTermination = $rowHireTerm[voluntaryTermination];
	$notes = $rowHireTerm[notes];
	$NCNS = $rowHireTerm[NCNS];
}
mssql_free_result($rstHireTerm);

//----------Termination process , Populate Termination reason if Emp in suspension,pending review.----------//
	$termReasonQry = 	'SELECT TOP 1 TP.employeeID, TP.terminationReasonID AS reasonID, 
					 	TR.terminationReason FROM ctlTerminationReasons AS TR WITH (NOLOCK)
						LEFT JOIN 
						RNet.dbo.prmEmpTerminationProcess AS TP WITH (NOLOCK)
						ON 
						TP.terminationReasonID = TR.terminationReasonID 
						WHERE TP.employeeID = "'.$employeeID.'" ORDER BY TP.effectiveDate ASC;';
	
							
     $termReasonRes = $employeeeMaintenanceObj->execute($termReasonQry);	
	 $termReason = mssql_fetch_row($termReasonRes);	
	 $TerminationReasons = $termReason[1];
	 
	 if($TerminationReasons != ''){
		 $show = 'yes';
	 }

unset($visiblity);
if($show == 'yes')
{
	$visiblity = 'block';
}
else 
{
	$visiblity = 'none'; 
}

	unset($sqlQuery);
	unset($resultsSet);
	unset($dynamicString);
	$hireDateisOrigHireDate = 'N';
	$sqlQuery = " SELECT 
				a.firstName+' '+a.lastName confirmedBy,
				CONVERT(VARCHAR(10), b.hireDateReviewedDate,101) date,
				b.hireDateisOrigHireDate
		FROM
				results.dbo.ctlEmployees a WITH (NOLOCK)
		JOIN
				Rnet.dbo.prmEmployeeCareerHistory b WITH (NOLOCK)
		ON
				a.employeeID = b.hireDateReviewedBy
		WHERE
				b.employeeID = ".$employeeID."
		AND
				b.hireDate = '".$hireDate."' ";
				
		$resultsSet = $employeeeMaintenanceObj->execute($sqlQuery);
		$numRowsLatest = $employeeeMaintenanceObj->getNumRows($resultsSet);
		
		if($numRowsLatest>0)
		{
			$dynamicString .= 'Confirmed by ';
			$dynamicString .=  mssql_result($resultsSet,0,0);
			$dynamicString .= ' on ';
			$dynamicString .=  mssql_result($resultsSet,0,1);
			$hireDateisOrigHireDate = mssql_result($resultsSet,0,2);
		}
		else
		{
			$dynamicString = '';	
		}

		
		unset($sqlQuery);
		unset($resultSet);
		
		$sqlQuery = " SELECT min(hireDate) minHireDate 
						FROM
							Rnet.dbo.prmEmployeeCareerHistory  WITH (NOLOCK)
						WHERE 
								employeeID = '".$employeeID."' ";
		$resultSet = $employeeeMaintenanceObj->execute($sqlQuery);
		
		$systemMinHireDate = mssql_result($resultSet,0,0);
		if(!empty($systemMinHireDate))
		{
			$systemMinHireDate = date('m/d/Y', strtotime($systemMinHireDate));
		}
		else
		{
			$systemMinHireDate = '';
			
		}
		unset($sqlQuery);
		unset($resultSet);
		
		
		$sqlQuery = " SELECT hireDate as originalHireDate
						FROM
							Rnet.dbo.prmEmployeeCareerHistory  WITH (NOLOCK)
						WHERE 
								employeeID = '".$employeeID."' 
						AND
								hireDateIsOrigHIreDate = 'Y' ";
		$resultSet = $employeeeMaintenanceObj->execute($sqlQuery);
		
		$systemoriginalHireDate = mssql_result($resultSet,0,0);
		if(!empty($systemoriginalHireDate))
		{
			$systemoriginalHireDate = date('m/d/Y', strtotime($systemoriginalHireDate));
		}
		else
		{
			$systemoriginalHireDate = '';	
		}

		unset($sqlQuery);
		unset($resultSet);
		
		if(empty($systemoriginalHireDate))
		{
				$systemoriginalHireDate = $systemMinHireDate;
		}
				//echo 'vvvvvvvvvvvvvvvvvvv'.$hireDateisOrigHireDate;
		
		$systemMinHireDateStamp = strtotime($systemMinHireDate);
		$systemoriginalHireDateStamp = strtotime($systemoriginalHireDate);


/* verify max hire date or not */

unset($sqlQuery);
unset($resultSet);

$sqlQuery = " 
			IF OBJECT_ID('tempdb.dbo.#tempMexhireDate') IS NOT NULL 
			DROP TABLE #tempMexhireDate
			
			CREATE TABLE #tempMexhireDate
			(
				employeeID INT NULL,
				maxHireDate DATETIME NULL
			)
			
			INSERT INTO #tempMexhireDate
			SELECT
				employeeID ,
				max(hireDate) as maxHireDate
			FROM
				RNet.dbo.prmEmployeeCareerHistory WITH (NOLOCK)
			WHERE
				employeeID = '".$employeeID."' 
			GROUP BY
				employeeID


			SELECT
					count(*) as NumRecrds
				FROM 
					RNet.dbo.prmEmployeeCareerHistory a WITH (NOLOCK)
				JOIN
					#tempMexhireDate  tmp WITH (NOLOCK) 
				ON
					a.employeeID = tmp.employeeID
				AND
					a.hiredate = tmp.maxHireDate	 	 
				WHERE 
					a.employeeID = '".$employeeID."' 
				AND
					CONVERT(VARCHAR(10),tmp.maxHireDate,101) = '".$hireDate."'  "	;
					
$resultSet = $employeeeMaintenanceObj->execute($sqlQuery);					
$numHistoryRecrds = mssql_result($resultSet,0,0);
//echo 'nnnn'.$numHistoryRecrds;

unset($sqlQuery);
unset($resultSet);

//################ Main body starts here
echo $htmlTagObj->openTag('div', 'id="topHeading" class="outer"');
echo $topLevelHeading;
echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->openTag('div', 'id="businessRuleHeading" class="outer"');
echo 'Employment Dates';
echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->openTag('div', 'id="emptyDiv" class="outer"');
echo $htmlTagObj->closeTag('div');

$employeeeMaintenanceObj->getTopLevelEmployeeInfo();
 
echo $htmlTagObj->openTag('div', 'id="scrollingdatagrid" class="scrollingdatagrid" visible="true"');
echo $htmlTagObj->openTag('div', 'id="adpsearchFieldSet"');
$htmlForm->fieldSet = TRUE;
$formLegend = $htmlForm->addLegend('Employment Dates');

$locationText =  $locDesc;

$employeeIdText = $employeeID;

$nameText = $empName;

if($hireDateReviewedBy=='Y')
{
	$hireDateData = $htmlTagObj->openTag('div', '');
} 
else 
{
	$hireDateData = $htmlTagObj->openTag('div', 'style="float:left; width:20%; border: 2px solid #F00; padding:2px;"');
}
$htmlTextElement->name = 'hireDate';
$htmlTextElement->id = 'hireDate';
$htmlTextElement->value = ($hireDateReviewedBy=='Y') ? $hireDate : '';
$htmlTextElement->readonly = 'true';
$htmlTextElement->size = '7';
$htmlTextElement->accesskey = 'true';
$htmlTextElement->onchange = 'return loghdnOriginalHireDate("chkOriginalHireDate", "' . $systemMinHireDate . '" , "' . $systemoriginalHireDateStamp . '", "' . $systemoriginalHireDate . '", "' . $type . '"); return false';
$hireDateData .= $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();
if($numHistoryRecrds==1 || empty($tDate)) 
{
	$employeeeMaintenanceObj->gethiddenValues('hireDate', $hireDate ,'Rnet', 'PrmEmployeeCareerHistory', 'hireDate' ,  'prmEmployeeCareerHistory#hireDate');
}
$hireDateData .= $htmlTagObj->closeTag('div');
$employeeeMaintenanceObj->gethiddenValues('hdnOriginalHireDate', $systemoriginalHireDate ,'results', 'ctlEmployees', 'origHireDate' ,  'ctlEmployees#origHireDate');

$htmlTextElement->type = 'hidden';
$htmlTextElement->name = 'hdnOriginalHireDate';
$htmlTextElement->id = 'hdnOriginalHireDate';
$htmlTextElement->value = $systemoriginalHireDate;
$hireDateData .= $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();

$htmlTextElement->type = 'hidden';
$htmlTextElement->name = 'hdnType';
$htmlTextElement->id = 'hdnType';
$htmlTextElement->value = $type;
$hireDateData .= $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();

$htmlTextElement->type = 'hidden';
$htmlTextElement->name = 'hdnDynamicString';
$htmlTextElement->id = 'hdnDynamicString';
$htmlTextElement->value = $dynamicString;
$hireDateData .= $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();

$htmlTextElement->name = 'termDate';
$htmlTextElement->id = 'termDate';
$htmlTextElement->value = $tDate;
$htmlTextElement->readonly = 'true';
$htmlTextElement->size = '7';
$htmlTextElement->accesskey = 'true';
$termDate = $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();
if($numHistoryRecrds==1 || empty($tDate)) 
{
	$employeeeMaintenanceObj->gethiddenValues('termDate', $tDate ,'Rnet', 'PrmEmployeeCareerHistory', 'termDate' ,  'prmEmployeeCareerHistory#termDate');
}


$sqlTermResaons = " SELECT * from ctlTerminationReasons WITH (NOLOCK) WHERE isActive = 'Y' ORDER BY terminationReason ";
$rstTermResaons = $employeeeMaintenanceObj->execute($sqlTermResaons);
$num_rows = $employeeeMaintenanceObj->getNumRows($rstTermResaons);

if($num_rows >= 1) 
{
		$resultTermination = $employeeeMaintenanceObj->bindingInToArray($rstTermResaons);
}

foreach($resultTermination as $id => $row)
{
	$terminationArray[$row['terminationReasonID']] = $row['terminationReason'];
}

$commonListBox->name = 'ddlTerminationReasons';
$commonListBox->id 	= 'ddlTerminationReasons';
$commonListBox->customArray = $terminationArray;
$commonListBox->optionKey = 'terminationReasonID';
$commonListBox->optionVal = 'description';
$commonListBox->onChange = 'return populateVolumeReduction(this.value); return false;';
$commonListBox->selectedItem = $TerminationReasons;
$ddlTerminationReasons .= $commonListBox->AddRow('', 'Please choose');
$ddlTerminationReasons .= $commonListBox->convertArrayToDropDown();
$commonListBox->resetProperties();

$volumereduction = $htmlTagObj->openTag('div', 'id="volumereductiondata" style="display:'.$visiblity.'"');
$volumereduction .= $htmlTagObj->closeTag('div');
$volumereductionLabel = !empty($dynamicClient) ? $dynamicClient : ''; 
$volumereductionLabel .= 'Volume Reduction ?';

$rehireableTHArray = array('0' => 'Yes', '1' => 'No');
$commonListBox->name = 'ddlRehireable';
$commonListBox->id 	= 'ddlRehireable';
$commonListBox->customArray = $rehireableTHArray;
$commonListBox->optionKey = 'ddlRehireable';
$commonListBox->optionVal = 'description';
$commonListBox->selectedItem = $Rehireable;
$ddlRehireable .= $commonListBox->AddRow('', 'Please choose');
$ddlRehireable .= $commonListBox->convertArrayToDropDown();
$commonListBox->resetProperties();

if($Rehireable == 'Yes') 
{
	$rehireNewFlag = 'True';
} 
else if($Rehireable == 'No') 
{
	$rehireNewFlag = 'False';
}
if($numHistoryRecrds==1 || empty($tDate)) 
{
	$employeeeMaintenanceObj->gethiddenValues('ddlRehireable', $rehireNewFlag ,'Rnet', 'PrmEmployeeCareerHistory', 'canBeRehired' ,  'prmEmployeeCareerHistory#canBeRehired');
}

$ddlvoluntary = $htmlTagObj->openTag('div', 'id="newIsVolTerm"');
$ddlvoluntary .= $htmlTagObj->closeTag('div');

$htmlTextElement->type = 'hidden';
$htmlTextElement->name = 'ddlvoluntary';
$htmlTextElement->id = 'ddlvoluntary';
$htmlTextElement->value = ($voluntaryTermination=='Yes') ? 'true' : 'false';
$ddlvoluntary .= $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();

if($show == 'yes') 
{
	$curDate =  date('m/d/Y');
	$sqlSUPERV = "	".$sqlTemEmployeeSupervisor."
				EXEC RNet.dbo.[standard_spEmployeeSupervisor] '%','$employeeID','$curDate'
				SELECT supervisorID FROM #tempEmployeeSupervisor WITH (NOLOCK)  ";
	$rstSUP = $employeeeMaintenanceObj->execute($sqlSUPERV);
	if($row=mssql_fetch_array($rstSUP)) 
	{	
		$supervisorID = $row[supervisorID];
	}
	mssql_free_result($rstSUP);
	$sqlSupConf = "	SELECT 
						supervisorTermConfirmed, 
						supervisorConfirmDate,
						b.firstName,
						b.lastName 
					FROM 
						RNet.dbo.prmEmployeeCareerHistory a WITH (NOLOCK) 
					LEFT JOIN 
						ctlEmployees b WITH (NOLOCK) 
					ON 
						a.supervisorID = b.employeeID 
					WHERE 
						a.employeeID = '$employeeID' 
					AND 
						a.hireDate = '$hireDate' ";
	$rstSUPCon = $employeeeMaintenanceObj->execute($sqlSupConf);
	while($rowConf=mssql_fetch_assoc($rstSUPCon)) 
	{	
		$supervisorTermConfirmed = $rowConf[supervisorTermConfirmed];
		$supName = $rowConf[firstName].' '.$rowConf[lastName];
		$supervisorConfirmDate =  $rowConf[supervisorConfirmDate];
	}
	mssql_free_result($rstSUPCon);
}
$htmlTextElement->name = 'chkSupervisorConfirm';
$htmlTextElement->id = 'chkSupervisorConfirm';
$htmlTextElement->value = 'Y';
if($supervisorID != $_SESSION['empID'] || $supervisorTermConfirmed == 'Y') 
{
	$htmlTextElement->disabled = 'disabled';
}
if($supervisorTermConfirmed == 'Y') 
{
	$htmlTextElement->checked = 'checked';
}
$htmlTextElement->type = 'checkbox'; //isDefaultChkd
$chkSupervisorConfirm = $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();
if($supervisorTermConfirmed == 'Y') 
{
	$chkSupervisorConfirm .= "Confirmed by ".$supName.", ".date('m/d/Y',strtotime($supervisorConfirmDate));
}

$notesTxt = $htmlTagObj->textAreatag('name="notes" id="notes" cols="40" rows="4"', $notes);

$htmlButtonElement->id = 'btnUpdate';
$htmlButtonElement->name = 'btnUpdate';
$htmlButtonElement->value = 'Update';
$htmlButtonElement->onclick = 'return Validate();';
$htmlButtonElement->style = 'text-align: left;';
$htmlButtonElement->type = 'submit';
$sbmButton = $htmlButtonElement->renderHtml();
$htmlButtonElement->resetProperties();


//###################Labeling the form elements
$locationText	= $htmlTextElement->addLabel($locationText, 'Location', '#ff0000','');
$employeeIdText	= $htmlTextElement->addLabel($employeeIdText, 'Employee ID', '#ff0000','');
$nameText		= $htmlTextElement->addLabel($nameText, 'Name:', '#ff0000','');
$hireDateData		= $htmlTextElement->addLabel($hireDateData, 'Hire Date', '#ff0000','');
$termDate		= $htmlTextElement->addLabel($termDate, 'Term Date', '#ff0000','');

$htmlTextElement->thStyle = 'display: '.$visiblity;
$htmlTextElement->tdStyle = 'display: '.$visiblity;
$htmlTextElement->rnetv4ThID = 'divTerminationDetailsTH';
$htmlTextElement->rnetv4TdID = 'divTerminationDetailsTD';
$ddlTerminationReasons  = $htmlTextElement->addLabel($ddlTerminationReasons, 'Termination Reasons', '#ff0000','');

$htmlTextElement->thStyle = 'display: none;';
$htmlTextElement->rnetv4ThID = 'volumereductionlable';
$volumereduction		  = $htmlTextElement->addLabel($volumereduction, $volumereductionLabel, '#ff0000','');

$ddlNCNSTD = $htmlTagObj->openTag('div', 'id="NCNSDATA" style="display:none"');
$commonListBox->name = 'ddlNCNS';
$commonListBox->id 	= 'ddlNCNS';
$commonListBox->optionKey = '';
$commonListBox->optionVal = '';
$ddlNCNSTD .= $commonListBox->convertArrayToDropDown();
$commonListBox->resetProperties();
$ddlNCNSTD .= $htmlTagObj->closeTag('div');

$htmlTextElement->thStyle 		= 'display: none';
//$htmlTextElement->tdStyle 		= 'display: none';
$htmlTextElement->rnetv4ThID 	= 'NCNSTD';
$ddlNCNSTD		 				= $htmlTextElement->addLabel($ddlNCNSTD, 'No Call, No Show ?', '#ff0000','');

$htmlTextElement->thStyle = 'display: '.$visiblity;
$htmlTextElement->tdStyle = 'display: '.$visiblity;
$htmlTextElement->rnetv4ThID = 'rehireableTH';
$htmlTextElement->rnetv4TdID = 'rehireableTD';
$ddlRehireable  = $htmlTextElement->addLabel($ddlRehireable, 'Re-Hireable?', '#ff0000','');

$htmlTextElement->thStyle = 'display: '.$visiblity;
$htmlTextElement->tdStyle = 'display: '.$visiblity;
$htmlTextElement->rnetv4ThID = 'wasTermVolTH';
$htmlTextElement->rnetv4TdID = 'wasTermVolTD';
$ddlvoluntary  = $htmlTextElement->addLabel($ddlvoluntary, 'Was termination voluntary?', '#ff0000','');

$htmlTextElement->thStyle = 'display: '.$visiblity;
$htmlTextElement->tdStyle = 'display: '.$visiblity;
$htmlTextElement->rnetv4ThID = 'supConfirmTH';
$htmlTextElement->rnetv4TdID = 'supConfirmTD';
$chkSupervisorConfirm  = $htmlTextElement->addLabel($chkSupervisorConfirm, 'Supervisor Confirmation', '#ff0000','');

$notesTxt  = $htmlTextElement->addLabel($notesTxt, 'Notes', '#ff0000','');

//Form block 1
$tableObj->tableId = 'adpsearchTable';
$tableObj->maxCol = 2;
$tableObj->border = 0;
$tableObj->cellSpacing = 2;
$tableObj->setTableClass('');
$tableObj->setTableAttr();

$tableObj->searchFields['locationText'] = $locationText;
$tableObj->searchFields['newLine'] = '\n';
$tableObj->searchFields['employeeIdText'] = $employeeIdText;
$tableObj->searchFields['newLine1'] = '\n';
$tableObj->searchFields['nameText'] = $nameText;
$tableObj->searchFields['newLine2'] = '\n';
$tableObj->searchFields['hireDate'] = $hireDateData;
$tableObj->searchFields['newLine3'] = '\n';
$tableObj->searchFields['termDate'] = $termDate;
$tableObj->searchFields['newLine4'] = '\n';
$tableObj->searchFields['ddlTerminationReasons'] = $ddlTerminationReasons;
$tableObj->searchFields['newLine5'] = '\n';
$tableObj->searchFields['volumereduction'] = $volumereduction;
$tableObj->searchFields['newLine6'] = '\n';
$tableObj->searchFields['ddlNCNSTD'] = $ddlNCNSTD;
$tableObj->searchFields['newLine7'] = '\n';
$tableObj->searchFields['ddlRehireable'] = $ddlRehireable;
$tableObj->searchFields['newLine8'] = '\n';
$tableObj->searchFields['ddlvoluntary'] = $ddlvoluntary;
$tableObj->searchFields['newLine9'] = '\n';
$tableObj->searchFields['chkSupervisorConfirm'] = $chkSupervisorConfirm;
$tableObj->searchFields['newLine10'] = '\n';
$tableObj->searchFields['notesTxt'] = $notesTxt;

$tableObj->searchFields['button'] = $sbmButton;

$searchForm = $tableObj->searchFormTableComponent();

//Html Form starts here
$htmlForm->action = 'adp_employee_careerHistory_Edit_Process_RDS.php?employeeID=' . $employeeID . '&type=' . $type;
$htmlForm->name = 'form_data';
$htmlForm->id = 'searchForm';
$htmlForm->method = 'POST';


echo $formLegend;
echo $htmlForm->startForm();

$htmlTextElement->type='hidden';
$htmlTextElement->name='hdnhDate';
$htmlTextElement->id='hdnhDate';
$htmlTextElement->value = $hireDate;
echo $hdnDate1	= $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();

$htmlTextElement->type='hidden';
$htmlTextElement->name='hdntDate';
$htmlTextElement->id='hdntDate';
$htmlTextElement->value = $tDate;
echo $hdnDate2	= $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();

$htmlTextElement->type='hidden';
$htmlTextElement->name='hdnDatePlus30Days';
$htmlTextElement->id='hdnDatePlus30Days';
$htmlTextElement->value = $datePlus30Days;
echo $hdnPlusDays	= $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();
echo $searchForm;

echo $htmlTagObj->closeTag('fieldset');
echo $htmlForm->endForm();
echo $htmlTagObj->closeTag('div');
echo $htmlTagObj->closeTag('div');




if($_GET[res] == 'hireDateAlreadyExisted')
{?>
	<script type="text/javascript">
    	alert('Employee already has previous hire date of <?php echo $_GET[hireDate1];?> ');
    </script>
<?php 
} 
else if($_GET[res] == 'updateTermdate')
{?>
	<script type="text/javascript">
    	alert('Employee must have a term date for existing hire date ');
    </script>
<?php 
}
else if($_GET[res] == 'hireDateExistedBetween')
{?>
	<script type="text/javascript">
    	alert('Hire date cannot be in pre-existing range for this employee');
    </script>
<?php 
}
?>

<script type="text/javascript">
var show = 'yes';
var termReason = <?php echo json_encode($TerminationReasons);?>;
//alert(document.form_data.termDate.value);
if((show == 'yes') && (termReason != null))
{	
loadNCNS('<?php echo $TerminationReasons;?>');
document.getElementById('ddlNCNS').value = '<?php echo $NCNS;?>';
}

$(function (){
	var hostUrl = '<?php echo "https://".$_SERVER["HTTP_HOST"]; ?>';
	$( "#hireDate, #termDate" ).datepicker({
		  showOn: "button",
		  buttonImage: hostUrl+"/Include/images/calendar.gif",
		  buttonText:'Calendar',
		  buttonImageOnly: true,
		  showWeek:true,
		  changeMonth:true,
		  changeYear:true,
		  showButtonPanel:true,
		  closeText: "Close"
	});
});


function Validate()
{
	
	/*if(document.getElementById('hdnType').value == 'ADP' && document.getElementById('hdnDynamicString').value=='')
		{
			
				if(document.getElementById('chkConfirmHireDate').checked==false )
				{
					alert('As a member of the Human Resources team, your confirmation of the correct hire date is required on this record');
					return false;
				}
				
				
		}*/
	if(document.form_data.hireDate.value == '')
	{
		alert('Plese select hire date');
		return false;
		
	}
	else if (document.form_data.termDate.value != "") 
	{
		document.getElementById("divTerminationDetailsTH").style.display = "block";
		document.getElementById("divTerminationDetailsTD").style.display = "block";
		document.getElementById("rehireableTH").style.display = "block";
		document.getElementById("rehireableTD").style.display = "block";
		document.getElementById("wasTermVolTH").style.display = "block";
		document.getElementById("wasTermVolTD").style.display = "block";
		document.getElementById("supConfirmTH").style.display = "block";
		document.getElementById("supConfirmTD").style.display = "block";
		
		var elementRef1 = document.getElementById('ddlTerminationReasons').value;
		var elementRef2 = document.getElementById('ddlRehireable').value;
		var elementRef3 = document.getElementById('ddlvoluntary').value;
		
		var selTermDate = new Date(document.form_data.termDate.value);
		var plus30 = new Date(document.getElementById('hdnDatePlus30Days').value);
		var ddlNCNS = document.getElementById('ddlNCNS').value;
		
		if(selTermDate>plus30)
		{
			alert("Term date should be limited to one month"); 
			document.form_data.termDate.focus();			
			return false;
		}
		else if(elementRef1 =="")
		{ 
			alert("Please Select Termination Reason"); 
			document.form_data.ddlTerminationReasons.focus();			
			return false;
		}
		else if(elementRef2 =="")
		{ 
			alert("Please Select Re-Hireable"); 
			document.form_data.ddlRehireable.focus();			
			return false;
		}
		else if(elementRef3 =="")
		{ 
			alert("Please Select Was Termination Voluntary"); 
			document.form_data.ddlvoluntary.focus();			
			return false;
		}
		else if(ddlNCNS =="")
		{ 
			alert("Please Select No Call , No Show"); 
			document.form_data.ddlNCNS.focus();			
			return false;
		}
		return ValidateDate('hireDate','termDate');
	}
	else if(document.getElementById('ddlTerminationReasons').value != '' && document.form_data.termDate.value == "")
	{
		alert("Please select term date");
		document.form_data.termDate.focus();
		return false;
	}
}
		
function ValidateDate(ctrlHDate,ctrlTDate)
{
	var HDate = document.getElementById(ctrlHDate).value;    	
	var TDate =  document.getElementById(ctrlTDate).value;		   
	var alertReason =  'Term Date must be greater than Hire Date.' 
	var endDate = new Date(TDate);    	
	var startDate= new Date(HDate);
	 
	if(HDate != '' && TDate != '' && startDate > endDate)
	{
		alert(alertReason);
		return false;
	}
}

 
function populateVolumeReduction(termIDID)
{
	//alert(termIDID);
	$('#volumereductionlable').show(); 
	$('#volumereductiondata').show();
	$('#volumereductiondata').html='';
	$('#ddlvoluntary').val('');
	/*$('#ddlvoluntaryDisp').val('');
	$('#ddlvoluntaryDisp').val('loading');*/
	$('#NCNSDATA').html('');
	$('#NCNSTD').hide(); 
	$('#NCNSDATA').hide();
	var comStr = '';
	
	document.getElementById('newIsVolTerm').innerHTML='';
	
	
	document.getElementById('volumereductiondata').innerHTML = '<img src="../../../Include/images/progress.gif">' + ' Please Wait...';	
		  
	$.post("populateVolumeReduction.php",   
	{ 
		terRID:termIDID,
		empID:'<?php  echo $employeeID;?>'
	},   
		function(data)
		{ 
			 if(data!='')
			 {
				$('#volumereductionlable').show(); 
				$('#volumereductiondata').show(); 
				$('#volumereductiondata').html(data);
			 } 
			 else 
			 {
				$('#volumereductionlable').hide(); 
				$('#volumereductiondata').hide(); 
				$('#volumereductiondata').html = '';
			 }
		} 
	); 
	
	populateVoluntary(termIDID);
	loadNCNS(termIDID);
	
	return false;
}

	/* Voluntary / Involuntary drop down */

function populateVoluntary(termIDID)
{


$.post("populateVoluntary.php",   
	{ 
		terRID:termIDID
	},   
		function(data)
		{ 
			if(data!='')
			{
				$('#ddlvoluntary').val(data);
				if(data=='true')
				{
					//$('#ddlvoluntaryDisp').val('Yes');
					document.getElementById('newIsVolTerm').innerHTML='Yes';
				}
				else
				{
					//$('#ddlvoluntaryDisp').val('No');	
					document.getElementById('newIsVolTerm').innerHTML='No';
				}
			}
			else
			{
				$('#ddlvoluntary').val('false');
				//$('#ddlvoluntaryDisp').val('No');
				document.getElementById('newIsVolTerm').innerHTML='No';
			}
			
		} 
	); 
	return false	
}

function loadNCNS(termID)
{	

	$.post("getYesNoFlag.php",   
	{ 
			terRID:termID
	},   
			function(data)
			{ 
			
				var comStr = '';
				comStr = '<select name="ddlNCNS" id="ddlNCNS" onchange="return loadRehireLogic(this.value); return false;" >';
				yesNoFlag = data;
				document.getElementById('NCNSTD').style.display = 'block';
				document.getElementById('NCNSDATA').style.display = 'block';
				
				//if(termID == 'V04' || termID == 'V09' || termID == 'V26')
				if(yesNoFlag=='Y')
				{
				//$("#ddlNCNS").html('<option value="Y">Yes</option>');
					comStr  +=  '<option value="Y">Yes</option>';
				}
				else
				{
				//("#ddlNCNS").html('<option value="">choose</option>');	
				//$("#ddlNCNS").html('<option value="Y">Yes</option>');
				//$("#ddlNCNS").html('<option value="N">No</option>');
				comStr  +=  '<option value="">choose</option>';
				comStr  +=  '<option value="Y">Yes</option>';
				comStr  +=  '<option value="N">No</option>';
				
				}
				
				comStr  += '</select>';
				//alert(comStr);
				$("#NCNSDATA").html(comStr);
				loadRehireLogic(yesNoFlag);
			} 
		); 
	
}

function loadRehireLogic(yesNoFlag)
{
	$("#rehireableTD").html('');
	
	var fstr = ' <select name="ddlRehireable"  id="ddlRehireable" style="width:auto;">';
	
	if(yesNoFlag=='Y')
	{
	//$("#ddlNCNS").html('<option value="Y">Yes</option>');
		fstr  +=  '<option value="False">No</option>';
		
	}
	else
	{
	//("#ddlNCNS").html('<option value="">choose</option>');	
	//$("#ddlNCNS").html('<option value="Y">Yes</option>');
	//$("#ddlNCNS").html('<option value="N">No</option>');
	fstr  +=  '<option value="">Please Choose</option>';
	fstr  +=  '<option value="True">Yes</option>';
	fstr  +=  '<option value="False">No</option>';
	
	}
	fstr  += '</select>';
				//alert(comStr);
	$("#rehireableTD").html(fstr);
}


function loghdnOriginalHireDate(logorigiID, minHireDate, origHireDateStamp,origHireDate,type)
{
	//alert(logorigiID);
	//alert(minHireDate);
	//alert(origHireDate);
	//alert(type);
	
	hireDateSystemStamp = document.getElementById('hdnhDateStamp').value;
	
	//alert(hireDateSystem);
	//var hireDateSystemStamp = new Date(hireDateSystem);
	//var origHireDateStamp = new Date(origHireDate);
	
	//alert(hireDateSystemStamp);
	//alert(origHireDateStamp);
	
	if(type=='ADP')
	{
		if(document.getElementById(logorigiID).checked==true)
		{
			document.getElementById('hdnOriginalHireDate').value = document.getElementById('hireDate').value;	
		}
		else
		{	if(hireDateSystemStamp==origHireDateStamp)
			{
				//alert(minHireDate);
				
				document.getElementById('hdnOriginalHireDate').value = minHireDate;	
			}
			else
			{
				//alert(origHireDate);
				
				document.getElementById('hdnOriginalHireDate').value = origHireDate;
			}
		}
	}
	else
	{
		//alert('p');
		if(hireDateSystemStamp==origHireDateStamp)
		{
			
		document.getElementById('hdnOriginalHireDate').value = document.getElementById('hireDate').value; 
		}
	}
	
	
}
</script>