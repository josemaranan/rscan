<?php

unset($sqlSalaryInfo);
unset($rstSalaryInfo);
unset($rowsSalaryInfoNum);
unset($salaryInfoArray);
$emppayrollLocation = $employeeeMaintenanceObj->getEmployeeCurrentPayrollLocation($employeeID);

$sqlSalaryInfo = " IF OBJECT_ID('tempdb.dbo.#tempEmpPayrollRates') IS NOT NULL
				DROP TABLE #tempEmpPayrollRates
				
				CREATE TABLE #tempEmpPayrollRates 
				(
					employeeID INT NULL,
					startDate DATETIME NULL,
					payType INT NULL,
					Amount DECIMAL(10,2) NULL,
					Amount2 DECIMAL(10,2) NULL,
					contractedMonthlySalary DECIMAL(10,2) NULL,
					payChangeReason VARCHAR(300) NULL,
					modifiedBy VARCHAR(300) NULL,
					modifiedDate DATETIME NULL,
					compEntryDate DATETIME NULL,
					empPayRollLocation INT NULL,
					isFinalized CHAR(1) NULL
				)
				
				INSERT INTO #tempEmpPayrollRates
				SELECT 
					 a.employeeID
					,a.startDate
					,a.payType
					,a.Amount
					,a.Amount2
					,a.contractedMonthlySalary
					,b.description payChangeReason
					,c.firstName+' '+c.lastName modifiedBy
					,a.modifiedDate
					,a.compEntryDate
					,'".$emppayrollLocation."'
					,'Y'
				FROM 
					results.dbo.ctlEmployeePayrollRates a WITH (NOLOCK) 
				LEFT JOIN
					ctlPayChangeReasons b WITH (NOLOCK) 
				ON
					a.payChangeReason = b.reasonID
				LEFT JOIN
					ctlEmployees c WITH (NOLOCK)
				ON
					a.modifiedBy = c.employeeID
				WHERE 
					a.employeeID = '".$employeeID."'
				ORDER BY 
					a.startDate DESC
				
						
				
				UPDATE b
						SET isFinalized = 'N'
				FROM
						results.dbo.ctlLocationPayDateSchedules a WITH (NOLOCK)
				JOIN
						#tempEmpPayrollRates b WITH (NOLOCK)
				ON
						a.location = b.empPayRollLocation
				WHERE
						b.startDate BETWEEN a.startDate AND a.endDate
				AND
						a.isFinalized IS NULL
										
									
				 SELECT * FROM #tempEmpPayrollRates (NOLOCK) ORDER BY startDate DESC ";
	//echo $sqlSalaryInfo;
$rstSalaryInfo = $employeeeMaintenanceObj->execute($sqlSalaryInfo);
$rowsSalaryInfoNum = $employeeeMaintenanceObj->getNumRows($rstSalaryInfo);
if($rowsSalaryInfoNum>=1)
{
	$salaryInfoArray = $employeeeMaintenanceObj->bindingInToArray($rstSalaryInfo);
}
mssql_free_result($rstSalaryInfo);



echo $htmlTagObj->openTag('div','id="topHeading" class="outer"');
echo $topLevelHeading;
echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->openTag('div','id="businessRuleHeading" class="outer"');
echo 'Employment Compensation Information';
echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->openTag('div','class="outer" id="emptyDiv"');
echo $htmlTagObj->closeTag('div');

$employeeeMaintenanceObj->getTopLevelEmployeeInfo(); 



echo $htmlTagObj->openTag('div','id="blue_button" class="outer" style="width:auto;"');
echo "<a href='#' onclick='modalWindow(\"add\", \"".$employeeID."\", \"".$topLevelHeading."\",\"\",\"\", \"".$emppayrollLocation."\", \"".$employData[0][hireDate]."\", \"".$employData[0][termDate]."\"); return false;' id='addNewSalary' />Add New Compensation Record</a>";
echo $htmlTagObj->closeTag('div');
echo '<br/><br/>';
echo $htmlTagObj->openTag('div','id="emptyDiv class="outer"');
echo $htmlTagObj->closeTag('div');

//echo $htmlTagObj->openTag('div','id="singlePixelBorder" class="outer"');
echo $htmlTagObj->openTag('div','id="topHeading"');
echo 'Modify Compensation';
echo $htmlTagObj->closeTag('div');
//echo $htmlTagObj->closeTag('div');

//echo $htmlTagObj->openTag('div','class="scrollingdatagrid" id="scrollingdatagrid" visible="true"');
//echo $htmlTagObj->openTag('div','id="report_content" style="float:left;margin-left:150px;"');
echo $htmlTagObj->openTag('div');
// starts table 
$tableObj->headers[] = 'Effective Date';			
$tableObj->headers[] = 'Compensation Change <br />Effective Date';
$tableObj->headers[] = 'Compensation Rate';
$tableObj->headers[] = 'Employee Type';
if($payrollLocation == '384'  || $payrollLocation == '75' || $payrollLocation == '72' || $payrollLocation == '802') 
{
	$tableObj->headers[] = 'Contracted Monthly Salary';
}
$tableObj->headers[] = 'Pay Change Reason';  	
$tableObj->headers[] = 'Modified By';
$tableObj->headers[] = 'Modified Date'; 

$tableObj->tableId = '';
$tableObj->width = '30%';
$tableObj->border = 0;
$tableObj->align = 'left';
$tableObj->cellPadding = '0';
$tableObj->bgColor = '#FFFFFF';
$tableObj->cellSpacing = '0';
//$tableObj->tableClass = "report";// table-autosort table-stripeclass:alternate";
$tableObj->zebra = 1;
$tableObj->fixedCol = 1;

if($rowsSalaryInfoNum!=0)
{
	$restrict = 1;
	
    foreach($salaryInfoArray as $salaryInfoArrayK=>$salaryInfoArrayV)
	{
		$lastRecord = 'No';
		
		if($rowsSalaryInfoNum==$restrict)
		{
			$lastRecord = 'Yes';	
		}
		
		if(!empty($salaryInfoArrayV['startDate']))
		{
			$salaryInfoStDate = date('m/d/Y',strtotime($salaryInfoArrayV['startDate']));
		}
		else
		{
			$salaryInfoStDate = '';	
		}
		$salInfoPayTye = $salaryInfoArrayV['payType'];
		$salInfoBaseWage = $salaryInfoArrayV['Amount'];
		$salInfoSecWage = $salaryInfoArrayV['Amount2'];
		$contractedMonthlySalary = $salaryInfoArrayV['contractedMonthlySalary'];
		$existPayChangeR = $salaryInfoArrayV['payChangeReason'];
		$modBy = $salaryInfoArrayV['modifiedBy'];
		if(!empty($salaryInfoArrayV['modifiedDate']))
		{
			$modDate = date('m/d/Y',strtotime($salaryInfoArrayV['modifiedDate']));
		}
		else
		{
			$modDate = '';	
		}
		
		if(!empty($salaryInfoArrayV['compEntryDate']))
		{
			$compensationDateEntry = date('m/d/Y',strtotime($salaryInfoArrayV['compEntryDate']));
		}
		else
		{
			$compensationDateEntry = '';	
		}
		
		
		// row starts here
		if($restrict==1)
		{   
			if($salaryInfoArrayV['isFinalized']!='Y')
			{
			
				$data[$salaryInfoArrayK]['salaryInfoStDate'] = "<a href='#' onclick='modalWindow(\"edit\",\"".$employeeID."\",\"".$topLevelHeading."\",\"".$salaryInfoStDate."\",\"".$lastRecord."\",\"".$emppayrollLocation."\",\"".$employData[0][hireDate]."\",\"".$employData[0][termDate]."\");  return false;'>(Edit)<span style='text-decoration:none;'>&nbsp;&nbsp;</span>".$salaryInfoStDate."</a>";
			}
			else
			{
				$data[$salaryInfoArrayK]['salaryInfoStDate'] = '<a href="#" class="jQuerytoolTipDiv">'.$salaryInfoStDate.'</a>';
			}
		} 
		else 
		{
			$data[$salaryInfoArrayK]['salaryInfoStDate'] = $salaryInfoStDate;	
		}
			
			
			 
		if(!empty($compensationDateEntry)) 
		{ 
			$data[$salaryInfoArrayK]['compensationDateEntry'] = $compensationDateEntry;
		} 
		else
		{ 
			$data[$salaryInfoArrayK]['compensationDateEntry'] = '&nbsp;'; 
		}
		
             
		if(!empty($salInfoBaseWage)) 
		{
			$data[$salaryInfoArrayK]['salInfoBaseWage'] = ($salInfoBaseWage=='1.00') ? 'compensation rate not displayed ': $salInfoBaseWage;
		} 
		else 
		{ 
			$data[$salaryInfoArrayK]['salInfoBaseWage'] = '&nbsp;';
		}
             
             
          
		if($salInfoPayTye==1)
		{
			$data[$salaryInfoArrayK]['salInfoPayTye'] = 'Salary (Exempt)';
		} 
		else 
		{
			$data[$salaryInfoArrayK]['salInfoPayTye'] = 'Hourly ( Non-Exempt)';
		}
                      
            
            
		if($payrollLocation == "384"  || $payrollLocation == "75" || $payrollLocation == "72"  || $payrollLocation == "802") 
		{
			$data[$salaryInfoArrayK]['payrollLocation'] = (!empty($contractedMonthlySalary)) ? $contractedMonthlySalary : '&nbsp;';
		
		}
		
		
            
        if(!empty($existPayChangeR))
		{
			 $data[$salaryInfoArrayK]['existPayChangeR'] = $existPayChangeR;
		}
		else
		{ 
			$data[$salaryInfoArrayK]['existPayChangeR'] = '&nbsp;';
		}
            
           
		if(!empty($modBy)) 
		{
			$data[$salaryInfoArrayK]['modBy'] = $modBy;
		}
		else
		{ 
			$data[$salaryInfoArrayK]['modBy'] = '&nbsp;';
		}
            
        if(!empty($modDate))
		{
			$data[$salaryInfoArrayK]['modDate'] =  $modDate;
		}
		else
		{
			$data[$salaryInfoArrayK]['modDate'] =  '&nbsp;';
		}
		
		
		$restrict++; 
	}
}
else 
{
	$noData = "No records in the history";
}
echo $tableObj->showTable($data, count($data));
//echo $noData;
echo $htmlTagObj->closeTag('div');


echo $htmlTagObj->openTag('div','class="window" id="dialogMain"');
echo '<a href="#"class="close"  style="float:right; border:0px; margin:-10px;"  /> 
	<img src="../../../Include/images/round_red_close_button.jpg"  border="0" width="20px" height="20px" /></a> <br /><br />';	
echo $htmlTagObj->openTag('div','id="replace"');
echo $htmlTagObj->closeTag('div');
echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->openTag('div','id="mask"');
echo $htmlTagObj->closeTag('div');

?>

<script type="text/javascript">
	
			
function modalWindow(type, employeeID, topLevelHeading, salaryInfoStDate , lastRecord, emppayrollLocation, empHireDate, empTermDate)
{
	/* Modal window properties */
	var id = '#dialogMain';
	//$(id).css('width', '600');
	//$(id).css('height', '350');
	
	//Get the screen height and width
	var maskHeight = $(document).height();
	var maskWidth = $(window).width();
			
	//Set heigth and width to mask to fill up the whole screen
	$('#mask').css({'width':maskWidth,'height':maskHeight});
	
	//transition effect		
	$('#mask').fadeIn(1000);	
	$('#mask').fadeTo("slow",0.8);	
	
	//Get the window height and width
	var winH = $(window).height();
	var winW = $(window).width();
	  
	//Set the popup window to center
	$(id).css('top',  winH/2-$(id).height()/2);
	$(id).css('left', winW/2-$(id).width()/2);
	
	//transition effect
	$(id).fadeIn(2000); 
	/* End of modal window properties. */
	
	
	//htmlData('includes/empSalaryAdd.php', tosend , 'replace');
	
	// Ajax Call ---------------------------
	alert(type);
	if(type=='add')
	{
		$.post("includes/empSalaryAdd_RDS.php",   
			{ 
				employeeID:employeeID,
				topLevelHeading:topLevelHeading,
				emppayrollLocation:emppayrollLocation,
				empHireDate:empHireDate,
				empTermDate:empTermDate	
			},   
			function(data)
			{
				$('#replace').html(data);
			} 
		
		);
	}
	else
	{
		$.post("includes/empSalaryEdit_RDS.php",   
			{ 
				employeeID:employeeID,
				topLevelHeading:topLevelHeading,
				salaryInfoStDate:salaryInfoStDate,
				lastRecord:lastRecord,
				emppayrollLocation:emppayrollLocation,
				empHireDate:empHireDate,
				empTermDate:empTermDate	
			},   
			function(data)
			{
				$('#replace').html(data);
			} 
		
		);
		
	}
	
	
	
	
}
$(document).ready(function() {	
   
   //select all the a tag with name equal to modal
	//if close button is clicked
	$('.window .close').click(function (e) {
		//Cancel the link behavior
		e.preventDefault();
		$('#mask').hide();
		$('.window').hide();
		$('#replace').html("&nbsp;"); /* THIS WAS ADDED BCZ. TO INITIALIZE THE DOM AGAIN */ 
	});		
		
	//if mask is clicked
	$('#mask').click(function () {
		$(this).hide();
		$('.window').hide();
	});	
	
	//wrote by Juan
	$('#WIDE_LOGO #menu a[name=ToggleLegend]').click(function(e) {
		//Cancel the link behavior
		e.preventDefault();			
		$('#legend').toggle("normal");
	});	
	
});

function closeWindow()
{
	
		//Cancel the link behavior
		
		$('#mask').hide();
		$('.window').hide();
		$('#replace').html("&nbsp;"); /* THIS WAS ADDED BCZ. TO INITIALIZE THE DOM AGAIN */ 
	
}

function checkForCorrectDate(selDate, selLoc, loadDiv)
{
	
	//alert(selDate);
	//alert(selLoc);
	//document.getElementById('btnSalary').disabled=true;
	
	//htmlEffDateCheck('checkSalaryEffectiveDate.php','selDate='+selDate+'&selLoc='+selLoc,'EFFDATEDIV');
	//$('#EFFDATEDIV').html(data) = '<img src="../Include/images/progress.gif">';
	$('#'+loadDiv).show();
	$.post("checkSalaryEffectiveDate.php",   
			{ 
				selDate:selDate,
				selLoc:selLoc
			},   
			function(data)
			{
				//$('#EFFDATEDIV').html(data);
				//alert(data);
				if(data!='true')
				{
					document.getElementById('hdnAjaxVar').value=2;
					$('#'+loadDiv).hide();
					
					alert('The effective date entered here is limited to the current pay period and future dates only.');
					return false;
				}
				else
				{
					$('#'+loadDiv).hide();
					document.getElementById('hdnAjaxVar').value=1;
					document.getElementById('txtCompEntryDate').value = document.getElementById('startDate').value;
					return false;
				}
			} 
		
		);
	

	//return false;
}



</script>

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