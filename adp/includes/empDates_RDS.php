<?php
//$employeeeMaintenanceObj->setUSLocations();

//$employData = $employeeeMaintenanceObj->getEmployeeInformation();
//$employADPData = $employeeeMaintenanceObj->getEmployeeADPInformation();

unset($sqlMainQry);
unset($rstMainQry);
$emppayrollLocation = $employeeeMaintenanceObj->getEmployeeCurrentPayrollLocation($employeeID);
/*echo 'PayrollLocation'.$emppayrollLocation;
exit;*/


$sqlMainQry = "	
				IF OBJECT_ID('tempdb.dbo.#tempEmployeeDates') IS NOT NULL
				DROP TABLE #tempEmployeeDates
				
				CREATE TABLE #tempEmployeeDates
				(
					hireDate DATETIME NULL,
					termDate DATETIME NULL,
					notes VARCHAR(MAX) NULL,
					evolvFileName VARCHAR(MAX) NULL,
					canBeRehired CHAR(3) NULL,
					voluntaryTermination CHAR(3) NULL,
					terminationReason VARCHAR(300) NULL,
					modifiedDate DATETIME NULL,
					modifiedBy VARCHAR(300) NULL,
					empPayRollLocation INT NULL,
					isFinalized CHAR(1) NULL,
					hireDateReviewedBy CHAR(1) NULL,
					day1PresentDate DATETIME NULL
				)
				
				INSERT INTO #tempEmployeeDates
				SELECT 
					ech.hireDate, 
					ech.termDate, 
					ech.notes, 
					ech.evolvFileName, 
					CASE ech.canBeRehired WHEN 1 THEN 'Yes'WHEN 0 THEN 'No' ELSE NULL END canBeRehired,
					CASE ech.voluntaryTermination WHEN 1 THEN 'Yes' WHEN 0 THEN 'No' ELSE NULL END voluntaryTermination,
					tr.terminationReason,
					CONVERT(VARCHAR(10),ech.modifiedDate,101) modifiedDate,
					emp.firstName+' '+emp.lastName modifiedBy,
					'".$emppayrollLocation."',
					'Y',
					CASE WHEN ISNULL(ech.hireDateReviewedBy ,'')<>'' THEN 'Y' ELSE 'N' END hireDateReviewedBy,
					day1PresentDate 
					FROM 
						RNet.dbo.prmEmployeeCareerHistory ech WITH (NOLOCK) 
					LEFT JOIN
						ctlTerminationReasons tr WITH (NOLOCK) 
					ON
						ech.terminationReasonID = tr.terminationReasonID
					LEFT JOIN
						ctlEmployees emp WITH (NOLOCK)
					ON
						ech.modifiedBy = emp.employeeID
					WHERE
						ech.employeeID = '".$employeeID."' 
					ORDER BY
						ech.hireDate DESC 
			
				
				UPDATE b
						SET isFinalized = 'N'
				FROM
						results.dbo.ctlLocationPayDateSchedules a WITH (NOLOCK)
				JOIN
						#tempEmployeeDates b WITH (NOLOCK)
				ON
						a.location = b.empPayRollLocation
				WHERE
						b.hireDate BETWEEN a.startDate AND a.endDate
				AND
						a.isFinalized IS NULL
						
					
				 SELECT * FROM #tempEmployeeDates (NOLOCK) ORDER BY hireDate DESC ";
//  echo $sqlMainQry;

	$rstMainQry = $employeeeMaintenanceObj->execute($sqlMainQry);
	//$rowsMainNum = mssql_num_rows($rstMainQry);
	$rowsMainNum = $employeeeMaintenanceObj->getNumRows($rstMainQry);
	if($rowsMainNum>=1)
	{
		$employmentDatesArray = $employeeeMaintenanceObj->bindingInToArray($rstMainQry);
	}
	mssql_free_result($rstMainQry);

$temphireDateFlags = $employeeeMaintenanceObj->riginalHiredateFlag($employeeID);

echo $htmlTagObj->openTag('div', 'id="topHeading" class="outer"');
echo $topLevelHeading;
echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->openTag('div', 'id="businessRuleHeading" class="outer"');
echo 'Employment Dates';
echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->openTag('div', 'id="emptyDiv" class="outer"');
echo $htmlTagObj->closeTag('div');

$employeeeMaintenanceObj->getTopLevelEmployeeInfo();

echo $htmlTagObj->openTag('div', 'id="blue_button" style="width:auto;" class="outer"');
echo $htmlTagObj->anchorTag('#', 'Add Employment Dates', 'onclick="gotoNextPage(\'' . $employeeID . '\', \'hr\', \'empDatesAdd\'); return false;"');
echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->openTag('div', 'id="emptyDiv" class="outer"');
echo $htmlTagObj->closeTag('div');

//echo $htmlTagObj->openTag('div', 'id="scrollingdatagrid" class="scrollingdatagrid" visible="true"');
echo $htmlTagObj->openTag('div', 'visible="true"');

include_once($_SERVER['DOCUMENT_ROOT'] . '/RNetIncludes/class/ReportTable.inc.php');

$Table=new ReportTable();
$Table->Width="90%";
$Table->Align="left";
$Table->Spacing="2";

$Col=& $Table->AddColumn("Column1");
$Col=& $Table->AddColumn("Column2");
$Col=& $Table->AddColumn("Column3");
$Col=& $Table->AddColumn("Column4");
$Col=& $Table->AddColumn("Column5");
$Col=& $Table->AddColumn("Column6");
$Col=& $Table->AddColumn("Column7");
$Col=& $Table->AddColumn("Column8");
$Col=& $Table->AddColumn("Column9");
$Col=& $Table->AddColumn("Column10");
$Col=& $Table->AddColumn("Column11");
$Col=& $Table->AddColumn("Column12");

$Row=& $Table->AddHeader();
$Row->Cells["Column1"]->Value="";
$Row->Cells["Column2"]->Value="Hire Date";
$Row->Cells["Column3"]->Value="";
$Row->Cells["Column4"]->Value="";
$Row->Cells["Column5"]->Value="";
$Row->Cells["Column6"]->Value="Term date";
$Row->Cells["Column7"]->Value="Term Reason";
$Row->Cells["Column8"]->Value="Can Be Rehired?";
$Row->Cells["Column9"]->Value="Voluntary Termination";
$Row->Cells["Column10"]->Value="Notes";
$Row->Cells["Column11"]->Value="Modified By";
$Row->Cells["Column12"]->Value="Modified Date";

$i=0;
foreach($employmentDatesArray as $mainArrayK => $mainArrayV) 
{
	$hrReviewedBy = 'Y';
	
	if($mainArrayV['hireDateReviewedBy']=='N') 
	{
		$hrReviewedBy = 'N';	
	}
	
	$hireDate = $mainArrayV['hireDate'];
	
	if(!empty($hireDate)) 
	{
		$hireDate = date('m/d/Y',strtotime($hireDate));
	}
	$termDate = $mainArrayV['termDate'];
	if(!empty($termDate)) 
	{
		$termDate = date('m/d/Y',strtotime($termDate));
	}
	$notes = $mainArrayV['notes'];
	$terminationReason = $mainArrayV['terminationReason'];
	$canBeRehired = $mainArrayV['canBeRehired'];
	$voluntaryTermination = $mainArrayV['voluntaryTermination'];
	$evolvFileName = $mainArrayV['evolvFileName'];
	$modifiedBy = $mainArrayV['modifiedBy'];
	$day1PresentDate = $mainArrayV['day1PresentDate'];
	if(!empty($mainArrayV['modifiedDate'])) 
	{
		$modifiedDate = date('m/d/Y',strtotime($mainArrayV['modifiedDate']));
	}

	$Row=& $Table->AddRow();
	
	$column1Data = '';
	
	if($rowsMainNum > 1) 
	{
		if($day1PresentDate <> '') 
		{
			$column1Data .= '&nbsp;';
		} 
		else if($evolvFileName == '') 
		{
			$column1Data .= $htmlTagObj->anchorTag('#', 'Delete', 'onclick="return confirmDelete(\'' . $employeeID . '\', \'' . $hireDate . '\', \'' . $termDate . '\', \'delete\'); return false;" target="_self"'); 	
		} 
		else 
		{ 
			$column1Data .= 'Hired from Evolv system';
		}
	} 
	else 
	{
		$column1Data .= '&nbsp;';
	}
	$Row->Cells["Column1"]->Value = $column1Data;
	
	$column2Data = '';
	if($i==0) 
	{
		if($hrReviewedBy == 'Y') 
		{
			$column2Data .= $htmlTagObj->anchorTag('#', $hireDate, 'onclick="return editEmployeeDates(\'' . $employeeID . '\', \'' . $hireDate . '\', \'' . $hrReviewedBy . '\'); return false;" target="_self"');
		} 
		else 
		{
			$column2Data .= $htmlTagObj->anchorTag('#', 'Hire Date not reviewed by HR', 'onclick="return editEmployeeDates(\'' . $employeeID . '\', \'' . $hireDate . '\', \'' . $hrReviewedBy . '\'); return false;" target="_self" style="color:#F00; font-weight:bold; text-decoration:underline;"');
			$column2Data .= '&nbsp; &nbsp;';
			$column2Data .= $htmlTagObj->openTag('span', 'style="color:#000;"');
			$column2Data .= $hireDate;
			$column2Data .= $htmlTagObj->closeTag('div');
		}
	} 
	else 
	{
		if($mainArrayV['isFinalized'] != 'Y') 
		{	
			if($hrReviewedBy == 'Y') 
			{
				$column2Data .= $htmlTagObj->anchorTag('#', $hireDate, 'onclick="return editEmployeeDates(\'' . $employeeID . '\', \'' . $hireDate . '\', \'' . $hrReviewedBy . '\'); return false;" target="_self"');
			} 
			else 
			{
				$column2Data .= $htmlTagObj->anchorTag('#', 'Hire Date not reviewed by HR', 'onclick="return editEmployeeDates(\'' . $employeeID . '\', \'' . $hireDate . '\', \'' . $hrReviewedBy . '\'); return false;" target="_self" style="color:#F00; font-weight:bold; text-decoration:underline;"');
				$column2Data .= '&nbsp; &nbsp;';
				$column2Data .= $htmlTagObj->openTag('span', 'style="color:#000;"');
				$column2Data .= $hireDate;
				$column2Data .= $htmlTagObj->closeTag('div');
			}
		} 
		else 
		{
			$column2Data = $htmlTagObj->anchorTag('#', $hireDate, 'class="jQuerytoolTipDiv"');
		}
	}
	
	$Row->Cells["Column2"]->Value = $column2Data;
	
	
	$column3Data = '';
	if($temphireDateFlags['OR'] == $hireDate) 
	{
		$column3Data .= $htmlTagObj->imgTag('../../Include/images/handshake_20px.png', 'border="0"');
	}	
	$Row->Cells["Column3"]->Value = $column3Data;
	
	$column4Data = '';
	if($temphireDateFlags['RE'] == $hireDate) 
	{
		$column4Data .= $htmlTagObj->imgTag('../../Include/images/re_hire_20px.png', 'border="0"');
	}	
	$Row->Cells["Column4"]->Value = $column4Data;
	
	$column5Data = '';
	if($temphireDateFlags['SE'] == $hireDate) 
	{
		$column5Data .= $htmlTagObj->imgTag('../../Include/images/seniority_date_20px.png', 'border="0"');
	}	
	$Row->Cells["Column5"]->Value = $column5Data;

	$Row->Cells["Column6"]->Value = !empty($termDate) ? $termDate : '&nbsp;';
	$Row->Cells["Column7"]->Value = !empty($terminationReason) ? $terminationReason : '&nbsp;';
	$Row->Cells["Column8"]->Value = !empty($canBeRehired) ? $canBeRehired : '&nbsp;';
	$Row->Cells["Column9"]->Value = !empty($voluntaryTermination) ? $voluntaryTermination : '&nbsp;';
	$Row->Cells["Column10"]->Value = !empty($notes) ? wordwrap($notes,200,"<br />\n") : '&nbsp;';
	$Row->Cells["Column11"]->Value = !empty($modifiedBy) ? $modifiedBy : '&nbsp;';
	$Row->Cells["Column12"]->Value = !empty($modifiedDate) ? $modifiedDate : '&nbsp;';
	//*/
	$i++;
}

echo $Table->Display();

echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->openTag('div', 'id="picture"');
echo $htmlTagObj->imgTag('../../Include/images/Picture1.png', 'border="0"');
//echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->closeTag('div');

?>

<script type="text/javascript">
$(document).ready(function() {
 
	var changeTooltipPosition = function(event) {
	  var tooltipX = event.pageX - 8;
	  var tooltipY = event.pageY + 8;
	  $('div.tooltip').css({top: tooltipY, left: tooltipX});
	};
 
	var showTooltip = function(event) {
	  $('div.tooltip').remove();
	  $('<div class="tooltip">This record cannot be updated, because this data is in an older pay period that has already been sent to the payroll provider.</div>')
            .appendTo('body');
	  changeTooltipPosition(event);
	};
 
	var hideTooltip = function() {
	   $('div.tooltip').remove();
	};
 
	$(".jQuerytoolTipDiv").bind({
	   mousemove : changeTooltipPosition,
	   mouseenter : showTooltip,
	   mouseleave: hideTooltip
	});
});
 
</script>