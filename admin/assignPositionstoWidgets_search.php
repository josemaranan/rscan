<?php

/**
 * @description : search position to workflow widgets
 * @author : Vasudev
 * @date : 08/14/2014
 * */
 
include_once($_SERVER['DOCUMENT_ROOT'] . '/RNetIncludes/class1/includeClassFiles.php');

//  page titles
$pageHyperlinks = array('Results Main Page'=>'Clients/Results/index.php');
$headerObj->loadPageLinks($htmlTagObj , $pageHyperlinks);

echo $htmlTagObj->openTag('div', 'id="report_content"');	


$txtBoxSize = '30';
echo $htmlTagObj->openTag('div', 'style="padding:10px 0px 0px 5px;overflow:auto;"');

$commonListBox->name = 'ddlWorkFlows';
$commonListBox->id = 'ddlWorkFlows';
$wfQuery = " SELECT * FROM rnet..ctlWorkflowicons (NOLOCK)  order by [description] ";
$commonListBox->sqlQry = $wfQuery;
$commonListBox->selectedItem = $mainArray[workflowIconID];
$commonListBox->optionKey = 'workflowIconID';
$commonListBox->optionVal = 'description';
$commonListBox->AddRow('', 'Please choose');
$ddlWorkFlows = $commonListBox->display();
$commonListBox->resetProperties();



$commonListBox->name = 'ddlDepartment';
$commonListBox->id = 'ddlDepartment';
$wfQuery = " SELECT distinct [departmentName] as department  FROM Results.dbo.ctlDepartments (NOLOCK) WHERE [departmentName] IS NOT NULL ORDER BY departmentName ";
$commonListBox->sqlQry = $wfQuery;
$commonListBox->selectedItem = $mainArray[workflowIconID];
$commonListBox->optionKey = 'department';
$commonListBox->optionVal = 'department';
$commonListBox->AddRow('', 'Please choose');
$ddlDepartment = $commonListBox->display();
$commonListBox->resetProperties();


$commonListBox->name = 'ddlPositions[]';
$commonListBox->id = 'ddlPositions';
//$wfQuery = " SELECT positionID , CONVERT(VARCHAR(10), positionID)+'-'+position positionVal FROM Results.dbo.ctlPositions (NOLOCK) WHERE active = 'Y' AND positionID IN (10,314,356,308,321,244,7,304,344,336,303,326,348,147,374,229,364,309,66,373,287) ORDER BY positionID ";
$wfQuery = "EXEC Rnet.dbo.[report_spGetPositionList] '','','','Y' ";
$commonListBox->sqlQry = $wfQuery;
$commonListBox->multiple = 'Multiple';
//$commonListBox->selectedItem = $mainArray[workflowIconID];
$commonListBox->optionKey = 'positionID';
//$commonListBox->optionVal = 'positionVal';
$commonListBox->optionVal = 'position';
//$commonListBox->AddRow('16', 'WebDeveloper');
$ddlPositions = $commonListBox->display();
$commonListBox->resetProperties();


$htmlCustomButtonElement = new HtmlCustomButtonElement('button');
$htmlCustomButtonElement->id            = 'btnAddorEdit'; 
$htmlCustomButtonElement->name          = 'btnAddorEdit';
$htmlCustomButtonElement->value         = 'Search'; 
$htmlCustomButtonElement->style         = 'float:left; margin-right: 5px;';
$htmlCustomButtonElement->onclick       = 'return submitData(this.value); return false;';
$btnAddorEdit = $htmlCustomButtonElement->renderHtml();	
$htmlCustomButtonElement->resetProperties();

$htmlCustomButtonElement->id            = 'btnCancel'; 
$htmlCustomButtonElement->name          = 'btnCancel'; 
$htmlCustomButtonElement->value         = 'Cancel'; 
$htmlCustomButtonElement->style         = 'float:left;'; 
$htmlCustomButtonElement->type          = 'button'; 
$htmlCustomButtonElement->onclick       = 'return closePopup(); return false;'; 
// $btnCancel = $htmlCustomButtonElement->renderHtml(); 
$htmlCustomButtonElement->resetProperties();



$lblddlWorkFlows	= $htmlTextElement->addLabel($ddlWorkFlows, 'WorkFlows:', '#ff0000',TRUE); 
//$lblddlDepartment	= $htmlTextElement->addLabel($ddlDepartment, 'Department:', '#ff0000',TRUE);
$lblddlPositions	= $htmlTextElement->addLabel($ddlPositions, 'Positions:', '#ff0000',TRUE);



$tableObj->tableId = 'searchTable';
$tableObj->tableClass = 'searchtab';
$tableObj->maxCol = 2;

$tableObj->searchFields['lblddlWorkFlows'] = $lblddlWorkFlows; //ddlContainers
//$tableObj->searchFields['lblddlDepartment'] = $lblddlDepartment; 
$tableObj->searchFields['lblddlPositions'] = $lblddlPositions; 

echo $htmlTagObj->openTag('div', 'class="outer" id="emptyDiv"');
echo $htmlTagObj->closeTag('div');
echo $htmlTagObj->openTag('div', 'class="outer" id="emptyDiv"');
echo $htmlTagObj->closeTag('div');

$htmlForm->action = 'assignPositionstoWidgets_configure.php';
$htmlForm->name = 'form_data';
$htmlForm->id = 'searchForm';

$htmlForm->fieldSet = TRUE;
echo $htmlForm->startForm();
echo $htmlForm->addLegend('Training Class Management');
echo $tableObj->searchFormTableComponent();
echo $htmlTagObj->openTag('div','align="center"');
echo '<br/>'.$btnAddorEdit.$btnCancel; //.addslashes($mainArray[businessFunction]);;
echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->closeTag('fieldset');
echo $htmlForm->endForm();

// to show the process image
echo $htmlTagObj->openTag('div', 'id="showSubDialog" align="center" style="width : 500px;"');
echo $htmlTagObj->closeTag('div');
// end 

echo $htmlTagObj->closeTag('div');


echo $htmlTagObj->closeTag('div');
?>
<script type="text/javascript">
function submitData()
{
	var workFlowName = document.getElementById('ddlWorkFlows').value;
	var position 	 = document.getElementById('ddlPositions').value;
	
	if(workFlowName=='')
	{
		alert('Please select workflow');
		return false;
	}
	if(position=='')
	{
		alert('Please select atleast one position');
		return false;
	}
	document.forms['form_data'].submit();	
}

</script>