<?php
//$employeeeMaintenanceObj->setUSLocations();
unset($sqlMainQry);
unset($rstMainQry);

$employeeeMaintenanceObj->setADPClients();
$clientsArray = $employeeeMaintenanceObj->getADPClients();


echo $htmlTagObj->openTag('div','id="topHeading" class="outer"');
echo $topLevelHeading;
echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->openTag('div','id="businessRuleHeading" class="outer"');
echo 'Employee Compansation Information';
echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->openTag('div','class="outer" id="emptyDiv"');
echo $htmlTagObj->closeTag('div');

$employeeeMaintenanceObj->getTopLevelEmployeeInfo(); 

echo $htmlTagObj->openTag('div','id="singlePixelBorder" class="outer"');

echo $htmlTagObj->openTag('div','id="topHeading" class="outer"');
echo 'Modify Contractor';
echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->openTag('div','class="noteClass" style="font-size: 12px;"');
echo 'Setting this employee as a contractor will remove them from payroll file generation.  This employee should not receive bonuses, incentives, or deductions 	through RNet.  The default effective date will be the hire date of the employee.  If this is an existing employee moving to a contractor status, please change the effective date accordingly.';
echo $htmlTagObj->closeTag('div');
echo $htmlTagObj->closeTag('div');

//  $fileHtml = '<input type="file" name="empPhoto" id="empPhoto">';  // (JPG or GIF or PNG Files only...)

$htmlTextElement->type = 'text';
$htmlTextElement->name = 'txtCompany';
$htmlTextElement->id = 'txtCompany';
$htmlTextElement->value = '';
$txtCompany = $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();

$htmlTextElement->type = 'text';
$htmlTextElement->name = 'txtEffectiveDate';
$htmlTextElement->id = 'txtEffectiveDate';
$htmlTextElement->value = ''; //$hireDate;
//$htmlTextElement->readonly = 'true';
$htmlTextElement->accesskey = 'true';
$txtEffectiveDate = $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();


//button Search 
$htmlCustomButtonElement = new HtmlCustomButtonElement('button');

$htmlCustomButtonElement->type = 'button';
$htmlCustomButtonElement->style = 'float:left';
$htmlCustomButtonElement->value = 'Save';
$htmlCustomButtonElement->onclick = 'return modifyContractor();';
$btnSave = $htmlCustomButtonElement->renderHtml();
$htmlCustomButtonElement->resetProperties();

$htmlCustomButtonElement->type = 'button';
$htmlCustomButtonElement->style = 'float:left; margin-left:5px;';
$htmlCustomButtonElement->value = 'Cancel';
$htmlCustomButtonElement->name = 'btnCubmit';
$htmlCustomButtonElement->onclick = "return cancelContractor();";
$btnSave .= $htmlCustomButtonElement->renderHtml();
$htmlCustomButtonElement->resetProperties();



$lblCmp	= $htmlTextElement->addLabel($txtCompany, 'Company :', '#ff0000',true);
$lblED	= $htmlTextElement->addLabel($txtEffectiveDate, 'Effective Date :', '#ff0000',true);
$lblbtnSave	= $htmlTextElement->addLabel($btnSave, '', '','');




$tableObj->tableId = 'adpsearchTable';
$tableObj->maxCol = 1;
$tableObj->border = 0;
$tableObj->cellSpacing = 2;
$tableObj->setTableClass('');
$tableObj->setTableAttr();


 
$tableObj->searchFields['lblCmp'] = $lblCmp; 
$tableObj->searchFields['lblED'] = $lblED; // lblInfo
$tableObj->searchFields['btnSave'] = $lblbtnSave;

echo '<br/>'.$tableObj->searchFormTableComponent();

//echo  '<br/>'.$btnSave.'&nbsp;'.$btnCancel;


?>

<script type="text/javascript">
	
var hostUrl = '<?php echo "https://".$_SERVER["HTTP_HOST"] ?>';
$( "#txtEffectiveDate" ).datepicker({
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
       
function modifyContractor()
{
	var empid = '<?php echo $employeeID; ?>';
	var cmp = $("#txtCompany").val();
	var edate = $("#txtEffectiveDate").val();
	//alert(empid+cmp+edate);
	if(cmp != '' && edate != '')
	{
		$.post("includes/empModifyContractor_Process.php", {eid: empid, company: cmp, edate: edate},function(res)
		{
			if(res.success == 'true')
			{
				alert(res.msg);
				$("#txtCompany").val('');
			}
			else
			{
				alert('Please try later.');
			}
		},"json");
	}
	else
	{
		alert('Please enter company and Date.');
	}
}

function cancelContractor()
{
	$("#txtCompany").val('');
	$("#txtEffectiveDate").val('');
}
</script>
