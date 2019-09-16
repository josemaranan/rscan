<?php
//ini_set('display_errors','1');
/**
 * @description : ADP-HR-Confirm Day 1 Presence page 
 * @author : BhanuPrakash
 * @date : 04/08/2014
 * */
//session_start();
//include includeCLassFiles which contains header part and class obj instances. file  
//RNetIncludes folder path  

//include_once($_SERVER['DOCUMENT_ROOT'] . '/RNetIncludes/class/includeClassFiles.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/RNetIncludes/class1/includeClassFiles.php');


//  page titles
$pageHyperlinks = array('Main Menu'=>'Clients/Results/index.php');
$headerObj->loadPageLinks($htmlTagObj , $pageHyperlinks);
/*
echo $htmlTagObj->openTag('div', 'class="noteClass" style="margin: 25px 10px 0px 212px; "');
echo '<b>Human Resources Access</b> This screen is used by administrators to confirm day 1 presence on behalf of an employee.';
echo $htmlTagObj->closeTag('div');
/*
echo $htmlTagObj->openTag('div', 'class="boxClass" style="margin: 5px 10px 0px 212px;"');
echo 'This screen is used by administrators to confirm day 1 presence on behalf of an employee.';
echo $htmlTagObj->closeTag('div');*/

$sqlQuery = "SELECT 
					a.location,a.description 
				FROM  
					[ctlLocations] a WITH (NOLOCK) 
				JOIN
					RNet.dbo.ctladpPaygroupLocations b WITH (NOLOCK)
				ON
					a.location = b.location		
				WHERE
					a.State IS NOT NULL AND a.location IN ".$RDSObj->UserDetails->Locations."  
				AND 
					a.active ='Y' AND a.switch ='N' 
				AND
					a.country = 'United States of America' 
				ORDER BY 
					a.description ";
					
$commonListBox->name = 'ddlLocations';
$commonListBox->id = 'ddlLocations';
$commonListBox->sqlQry = $sqlQuery;
$commonListBox->selectedItem = '';
$commonListBox->onChange = "return loadTableContent(this.value); return false;";
$commonListBox->optionKey = 'location';
$commonListBox->optionVal = 'description';
$commonListBox->AddRow('', 'Please choose');
$locationDDL = $commonListBox->display();
$commonListBox->resetProperties();

/* Configure form properties and actions */
$htmlForm->action = '#';
$htmlForm->name = 'form_data';
$htmlForm->id = 'form_data';
$htmlForm->fieldSet = TRUE;
$startForm = $htmlForm->startForm();
$formLegend = $htmlForm->addLegend('Confirm Day 1 Presence');

$lblLocation	= $htmlTextElement->addLabel($locationDDL, 'Location:', '#ff0000','');

$tableObj->tableId = 'searchTable';
$tableObj->tableClass = 'searchtab'; 
$tableObj->setTableWidth('100px'); 
$tableObj->maxCol = 2;
 
$tableObj->searchFields['lblLocation'] = $lblLocation;
$searchForm = $tableObj->searchFormTableComponent();


echo $htmlTagObj->openTag('div', 'id="searchBody" class="outer"');
echo $htmlTagObj->openTag('div', 'id="searchFieldSet"');
echo $startForm;
echo $formLegend;
echo $htmlTagObj->openTag('div', 'class="noteClass" style="color: black;"');
echo '<b>Human Resources Access</b> This screen is used by administrators to confirm day 1 presence on behalf of an employee.';
echo $htmlTagObj->closeTag('div');
echo '<br/>'.$searchForm;	
echo $htmlForm->endForm();
echo $htmlTagObj->closeTag('fieldset');
echo $htmlTagObj->closeTag('div');
echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->openTag('div', 'class="outer" id="emptyDiv"');
echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->openTag('div', 'id="report_content"');	
echo $htmlTagObj->closeTag('div');
// end main content.
?>

<script language="javascript" type="text/javascript">

var host = getHostAddress();
var loader = "<img src='"+host+"/Include/images/progress.gif' />";

$(function()
{
   loadTableContent('');
});
function loadTableContent(location)
{
	//alert(location);
	if(location == '')
	{
		$("#report_content").html('<h3 style="padding:10px;">Please select Location.</h3>');
	}
	else
	{
		$("#report_content").html(loader+'Please wait...');
		$.post(
		   "day1ChangeAjax.php?task=loadActiveNotDay1Presence",
		   {locationID: location},
		   function(data)
		   {
				//alert(data);
				$("#report_content").html(data);
				$("#scrollingdatagrid").css('margin-left','11px');
		   }
		);
	}
}

function validateEmployeeDay1()
{
	var selected = new Array();
	$('#report_content input:checked').each(function() {
		selected.push($(this).attr('name'));
	});
	
	if(selected.length > 0)
	{
		var empList = $('#report_content :input').serialize();
		$("#report_content").html(loader+'Processing your request...');
		$.post(
		   "day1ChangeAjax.php?task=submitActiveNotDay1Presence",
		   empList,
		   function(data)
		   {
				if(data == 1)
				{
					alert('Updated successfully.');
				}
				else
				{
					alert('Error in submit data. Please try later.');
				}
				loadTableContent($("#ddlLocations option:selected").val());
		   }
		);
	}
	else
	{
		alert('Please check at least one employee');	
		return false;		
	}
}
</script>

<?php
echo $htmlTagObj->closeTag('body');
echo $htmlTagObj->closeTag('html');
?>