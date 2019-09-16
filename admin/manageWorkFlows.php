<?php

/**
 * @description : Manage work flows (Admin Panel )
 * @author : BhanuPrakash
 * @date : 07/07/2014
 * */

include_once($_SERVER['DOCUMENT_ROOT'] . '/RNetIncludes/class1/includeClassFiles.php');

//  page titles
$pageHyperlinks = array('Results Main Page'=>'Clients/Results/index.php');
$headerObj->loadPageLinks($htmlTagObj , $pageHyperlinks);


$commonListBox->name = 'ddlContainers';
$commonListBox->id = 'ddlContainers';
$commonListBox->sqlQry = " SELECT * FROM rnet..ctlWorkFlowContainer (NOLOCK) ORDER BY description ";
$commonListBox->selectedItem = $mainArray[containerID];
$commonListBox->onChange = "return popuateWorkFlowIcons(this.value); return false;";
$commonListBox->optionKey = 'containerID';
$commonListBox->optionVal = 'description';
$commonListBox->AddRow('', 'Please choose');
$ddlContainers = $commonListBox->display();
$commonListBox->resetProperties();

$commonListBox->name = 'ddlWorkFlows';
$commonListBox->id 	= 'ddlWorkFlows';
if($mainArray[containerID] != '')
{
	$wfQuery = "SELECT * FROM rnet..ctlWorkflowicons (NOLOCK) where containerID = ".$mainArray[containerID]." ORDER BY description ";
}
else
{
	$wfQuery = "SELECT * FROM rnet..ctlWorkflowicons (NOLOCK) ORDER BY description ";
}
$commonListBox->sqlQry = $wfQuery;
$commonListBox->selectedItem = $mainArray[workflowIconID];
$commonListBox->optionKey = 'workflowIconID';
$commonListBox->optionVal = 'description';
$commonListBox->AddRow('', 'Please choose');
$ddlWorkFlows = $commonListBox->display();
$commonListBox->resetProperties();

$htmlTextElement->name = 'txtWorkFlowName';
$htmlTextElement->id = 'txtWorkFlowName';
$htmlTextElement->value = $mainArray[workflowName];
$htmlTextElement->type = 'text';
$htmlTextElement->size = $txtBoxSize;
$txtWorkFlowName = $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();


$htmlCustomButtonElement 				= new HtmlCustomButtonElement('button');
$htmlCustomButtonElement->id            = 'btnAddorEdit'; 
$htmlCustomButtonElement->name          = 'btnAddorEdit';
$htmlCustomButtonElement->value         = 'Search'; 
$htmlCustomButtonElement->style         = 'float:left; margin-right: 5px;';
$htmlCustomButtonElement->onclick       = 'return loadData(); return false;';
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

$containerField		= $htmlTextElement->addLabel($ddlContainers, 'Container:', '#ff0000',FALSE); 
$workflowField		= $htmlTextElement->addLabel('<div id="workFlowDiv">'.$ddlWorkFlows.'</div>', 'WorkFlow:', '#ff0000',FALSE);
$workflowNameField	= $htmlTextElement->addLabel($txtWorkFlowName, 'Workflow Name:', '#ff0000',FALSE);

$tableObj->tableId = 'searchTable';
$tableObj->tableClass = 'searchtab';
$tableObj->maxCol = 3;

$tableObj->searchFields['container'] 	= $containerField; //ddlContainers
$tableObj->searchFields['workflow']	 	= $workflowField; 
$tableObj->searchFields['workflowName'] = $workflowNameField; 

echo $htmlTagObj->openTag('div', 'class="outer" id="middleTitle1"  ');

echo $htmlTagObj->openTag('div', 'id="emptyDiv"');
echo $htmlTagObj->closeTag('div');
echo $htmlTagObj->openTag('div', 'id="emptyDiv"');
echo $htmlTagObj->closeTag('div');
echo $htmlTagObj->openTag('div', 'id="emptyDiv"');
echo $htmlTagObj->closeTag('div');

$htmlForm->action 	= '#';
$htmlForm->name 	= 'form_data';
$htmlForm->id 		= 'searchFormWF';

$htmlForm->fieldSet = TRUE;
echo $htmlForm->startForm();
echo $htmlForm->addLegend('Workflow Search');
echo $tableObj->searchFormTableComponent();
echo $htmlTagObj->openTag('div','align="center"');
echo $btnAddorEdit.$btnCancel; 
echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->closeTag('fieldset');
echo $htmlForm->endForm();
echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->openTag('div', 'class="outer" id="workflowBtn"  style="padding-left: 0px;"'); 
echo $htmlTagObj->anchorTag('#','Create New Work Flow', 'class="blue_button" onclick="openDialog(\'ADD\',\'new\'); return false;"');
echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->openTag('div', 'id="report_content"');	
echo $htmlTagObj->closeTag('div');
// end main content.

// dialog box
echo $htmlTagObj->openTag('div', 'id="showDialog" style="display:none;"');
echo $htmlTagObj->closeTag('div');
// end dialog box
?>

<script language="javascript" type="text/javascript">

var loader = "<img src='../Include/images/progress.gif' />";

$(function ()
{
	//loadData();	
});
//makeItDynamic();
function loadData()
{
	$("#report_content").html(loader+'Please wait...');
	var params = $('#searchFormWF').serialize();
	$.post(
		   "manageWorkFlowsAjax.php?task=loadWorkFlows&"+params,
		   {},
		   function(data)
		   {
			  $("#report_content").html(data);
			  $( "DIV.scrollingdatagrid " ).scroll(function(e) {																				  						 
									  				$("DIV.scrollingdatagrid TABLE THEAD TR").css({
														  top : $('DIV.scrollingdatagrid').scrollTop()
													});											
			 });
			  
		   }
   );
}

function openDialog(task,taskName)
{
	//alert(task);
	
	var titleDialog = (taskName == 'new') ? 'Creating new Work Flow:' : 'Editing \"'+taskName+'\" Work Flow:';
	$('#showDialog').dialog({										   
		height: 'auto',
		width:1200,										   
		modal:true,
		position:'center center',
		title: titleDialog
	}).html(loader).load('manageWorkFlowsDialog.php?task='+task);
}

function closePopup()
{
	$('#showDialog').dialog('close');
}
function popuateWorkFlowIcons(containerId, parentDiv)
{
	var parentDivId = '';
	if(parentDiv!=null && parentDiv!='')
	{
		parentDivId = '#'+parentDiv;
	}

	$("#workFlowDiv").html(loader);
	$.post(
		   "manageWorkFlowsAjax.php?task=popuateWorkFlowIcons",
		   {containerID: containerId},
		   function(data)
		   {
			  $(parentDivId+" #workFlowDiv").html(data);
		   }
   );
}

function getPositions(deptId)
{
	$("#positionDiv").show();
	$.post(
		   "manageWorkFlowsAjax.php?task=popuatePositions",
		   {'deptId': deptId},
		   function(data)
		   {
			  $("#positionDiv").parent().html(data);
		   }
   );
}
var dialogHeight = $(document).height() - 40;
function openPositionDialog(workflowId,workflowName, workflowIconID)
{
	var titleDialog = 'Assign positions to \"'+workflowName+'\" Work Flow:';
	$('#showDialog').dialog({										   
		height: dialogHeight,
		width:1000,										   
		modal:true,
		position:'center center',
		title: titleDialog,
		open: function (event, ui) {
			$('#showDialog').css('overflow', 'hidden'); //this line does the actual hiding
		  }

	}).html(loader);
	
	$.post(
		   "manageWorkFlowsAjax.php",
		   {'task': 'assignPositionToWorkflow', 'workflowName': workflowName, 'workflowId':workflowId, 'workflowIconID':workflowIconID },
		   function(data)
		   {
			  $("#showDialog").html(data);			 
		   }
   );	
}

//function submitData()

function searchPositions()
{
	//var workFlowName = document.getElementById('ddlWorkFlows').value;
	//var workFlowId	 	= document.getElementById('hdnWorkflowId').value;
	var departmentName 	= document.getElementById('ddlDepartment').value;	
	var position 	   	= document.getElementById('ddlPositions').value;
	
	if(departmentName=='' && position=='')
	{
		alert('Please select either department or position');
		return false;
	}
	//if(position=='')
	//{
	//	alert('Please select atleast one position');
	//	return false;
	//}

	var showContentHeight = $('#showDialog').height()-100;
	var showContentWidth = $('#showDialog').width()-40;
	$("#showContent").html(loader+'Please wait...');
	$.ajax({
		type: 'post',
		url: 'manageWorkFlowsAjax.php',
		dataType: 'html',
		//contentType: "text/plain",
		async:true,
		data: 'task=viewPositionsList&'+$("#searchPositionForm").serialize(),
		success: function (html) {
			$("#showContent").html(html);
			
			$("#showContent").css({
												  'height' : showContentHeight+50
											});

			$("#showContent #configureForm #scrollingdatagrid").css({
												  'overflow' : auto
											});
			
		}
	});
	
	makeItDynamicModalWindow(showContentWidth,showContentHeight,'showContent #configureForm');
}
function chkAtLeastOneSelect()
{
	var sFlag = true;
	var checkLen = $( "input:checked" ).length;
	if(checkLen==0)
	{	
		alert('Please check at least one checkbox');
		return false;
	}

	var allVals = [];
	var positionID = '';
     //$(':checkbox').each(function() {
	 $('input[type=checkbox]:checked').each(function(){
       //allVals.push($(this).val());
	   positionID = $(this).val().split('||');

	   if($('#workFlowCountry'+positionID[0]).val() == '' || $('#workFlowCountry'+positionID[0]).val() == null)
	   {
			alert('Select atleast one country for position');
			$('#workFlowCountry'+positionID[0]).focus();
			sFlag = false;
			return false;
	   }
	  
     });
	 
	 if(sFlag == true)
	 {
		$.ajax({
			type: 'post',
			url: 'manageWorkFlowsAjax.php',
			dataType: 'html',
			async:true,
			data: 'task=configurePositions&'+$("#configureForm").serialize(),
			success: function (html) {
				if(html == 1)
				{
					alert('Position assigned successfully');
					$('#showDialog').dialog('close');
				}
				else
				{
					alert(html);				
				}
			}
		});  
	 }
	 else
	 {
		return false;	
	}

}

function enableCountryDDL(positionID, workFlowID)
{
	//if()

	if($('#ckhBVox'+positionID+workFlowID).is(':checked') == true)
	{
		$('#workFlowCountry'+positionID).removeAttr('disabled');
	}
	else
	{
		$('#workFlowCountry'+positionID).attr('disabled','disabled');
	}
}
</script>
<?php
echo $htmlTagObj->closeTag('body');
echo $htmlTagObj->closeTag('html');
?>