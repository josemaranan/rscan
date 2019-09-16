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


$htmlTextElement->type 			= 'radio';
$htmlTextElement->name 			= 'rdoFormType';
$htmlTextElement->id 			= 'position';
$htmlTextElement->value			= 'positionForm';
$htmlTextElement->onClick		= 'showForm(this.value)';
$htmlTextElement->checked		= 'checked';
$searchTypeField1				= $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();	

$htmlTextElement->type 			= 'radio';
$htmlTextElement->name 			= 'rdoFormType';
$htmlTextElement->id 			= 'workflow';
$htmlTextElement->value			= 'workflowForm';
$htmlTextElement->onClick		= 'showForm(this.value)';
$searchTypeField2				= $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();


$commonListBox->name 			= 'ddlbussinessFunction'; 
$commonListBox->id 				= 'ddlbussinessFunction';
$commonListBox->loader 			= TRUE;
$commonListBox->loaderID 		= 'ddlbussinessFunctionLoader';
$commonListBox->onChange 		= "return popuateDepartmentsAndPostionsByBusinessFunction(this.value , 'ddlDepartments@@ddlPositions' , 'ddlDepartmentLoader@@ddlPositionsLoader' , 'loadDepartments@@loadPositions'); return false;";
$busnessFunDdl 					= $commonListBox->AddRow('', 'Please choose');	
$busnessFunDdl 					= $commonListBox->AddRow('Corporate', 'Corporate');
$busnessFunDdl 					= $commonListBox->AddRow('Field Staff', 'Site');	
$busnessFunDdl 					= $commonListBox->display();	 
$commonListBox->resetProperties();

$commonListBox->name 			= 'ddlDepartments';
$commonListBox->id 				= 'ddlDepartments';
$commonListBox->loader 			= TRUE;
$commonListBox->loaderID 		= 'ddlDepartmentLoader';
$commonListBox->sqlQry 			= "EXEC Rnet.dbo.[RNet_spGetDepartments] '%'";
$commonListBox->selectedItem 	= '';
$commonListBox->optionKey 		= 'departmentCode';
$commonListBox->optionVal 		= 'Department';
$commonListBox->onChange 		= "return populatePositionsByDepartment(this.value , 'ddlPositions' , 'ddlPositionsLoader' , 'loadPositions'); return false;";
$departmentsDdl					= $commonListBox->AddRow('', 'Please choose');
$departmentsDdl					= $commonListBox->display();
$commonListBox->resetProperties();

$commonListBox->name 			= 'ddlPositions[]';
$commonListBox->id 				= 'ddlPositions';
$commonListBox->multiple		= 'multiple';
$commonListBox->size			= '5';
$commonListBox->loader 			= TRUE;
$commonListBox->loaderID 		= 'ddlPositionsLoader';
$commonListBox->sqlQry 			= "EXEC Rnet.dbo.[rnet_spGetPositions] '%','%', ''";
$commonListBox->selectedItem 	= '';
$commonListBox->optionKey 		= 'positionID';
$commonListBox->optionVal 		= 'position';
$positionsDdl 					= $commonListBox->AddRow('', 'Please choose');
$positionsDdl					= $commonListBox->display();
$commonListBox->resetProperties();

$htmlButtonElement->id 			= 'Submit';
$htmlButtonElement->name		= 'Submit';
$htmlButtonElement->value 		= 'Generate Report';
$htmlButtonElement->Class 		= 'WSGInputButton';
$htmlButtonElement->type 		= 'button';
$htmlButtonElement->onclick     = 'return loadData("positionsearch"); return false;';
$sbmButton 						= $htmlButtonElement->simpleButton();
$sbmButton						= $htmlTagObj->openTag('td','colspan="2"').$sbmButton.$htmlTagObj->closeTag('td');
$htmlButtonElement->resetProperties();

$businessField		= $htmlTextElement->addLabel($busnessFunDdl, 'Business Function:', '#ff0000',TRUE); 
$departmentField	= $htmlTextElement->addLabel($departmentsDdl, 'Department:', '#ff0000',TRUE);
$positionField		= $htmlTextElement->addLabel($positionsDdl, 'Position:', '#ff0000',TRUE);

$tableObj->tableId = 'searchTable';
$tableObj->tableClass = 'searchtab';
$tableObj->maxCol = 3;

$tableObj->searchFields['business'] 	= $businessField; //ddlContainers
$tableObj->searchFields['department']	= $departmentField; 
$tableObj->searchFields['position'] 	= $positionField; 
$tableObj->searchFields['button'] 		= $sbmButton;

echo $htmlTagObj->openTag('div', 'class="outer" id="middleTitle1"  ');

echo $htmlTagObj->openTag('div', 'class="outer" id="emptyDiv"');
echo $htmlTagObj->closeTag('div');
echo $htmlTagObj->openTag('div', 'class="outer" id="emptyDiv"');
echo $htmlTagObj->closeTag('div');
echo $htmlTagObj->openTag('div', 'class="outer" id="emptyDiv"');
echo $htmlTagObj->closeTag('div');

echo $searchTypeField1.'By Position &nbsp;'.$searchTypeField2.'By Workflow';

echo $htmlTagObj->openTag('div', 'id="positionForm"');

$htmlForm->action 	= '#';
$htmlForm->name 	= 'form_data';
$htmlForm->id 		= 'searchFormWF';

$htmlForm->fieldSet = TRUE;
echo $htmlForm->startForm();
echo $htmlForm->addLegend('Position Search');
echo $tableObj->searchFormTableComponent();
echo $htmlTagObj->closeTag('fieldset');
echo $htmlForm->endForm();
echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->openTag('div', 'id="workflowForm" style="display:none;"');
echo 'workflow';
echo $htmlTagObj->closeTag('div');


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

function loadData(tasktype)
{
	$("#report_content").html(loader+'Please wait...');
	var params = $('#searchFormWF').serialize();
	$.ajax({
			 type:'POST',
			 url:host+'/admin/populateDivsUsingAjax.php',
			 data:'task='+tasktype+'&'+params,
			 async:true,
			 success:function(data){
				 $("#report_content").html(data);				 
			 }		 
	  });
}

function openDialog(task,taskName)
{
	//alert(task);
	
	var titleDialog = (taskName == 'new') ? 'Creating new Work Flow:' : 'Editing \"'+taskName+'\" Work Flow:';
	$('#showDialog').dialog({										   
		height: 'auto',
		width:1000,										   
		modal:true,
		position:'center center',
		title: titleDialog
	}).html(loader).load('manageWorkFlowsDialog.php?task='+task);
}

function closePopup()
{
	$('#showDialog').dialog('close');
}
function popuateWorkFlowIcons(containerId)
{
	//alert(containerId);
	$("#workFlowDiv").html(loader);
	$.post(
		   "manageWorkFlowsAjax.php?task=popuateWorkFlowIcons",
		   {containerID: containerId},
		   function(data)
		   {
			  $("#workFlowDiv").html(data);
		   }
   );
}

function showForm(type)
{
	$('#positionForm, #workflowForm').hide();
	$('#'+type).show();
}

function popuateDepartmentsAndPostionsByBusinessFunction(businF, toWichDiv, loaderID , relatedTask, i)
{		
	var toWhichIds  = toWichDiv.split('@@');
	var toloaderIDs	= loaderID.split('@@');
	var relatedTasks= relatedTask.split('@@');		
	
	if(i==null)
		i=0;
	
	var toWhichDivParent = $("#"+toWhichIds[i]).parent().get(0).id;
	var host = getHostAddress();
	
	$('#'+toloaderIDs[i]).show();	
	
	$.ajax({
			 type:'POST',
			 url:host+'/admin/populateDivsUsingAjax.php',
			 data:'bussinessFun='+businF+'&task='+relatedTasks[i],
			 async:true,
			 success:function(msg){
				 //alert(msg);
				 $('#'+toloaderIDs[i]).hide();
				 $("#"+toWhichDivParent).html('');
				 $("#"+toWhichDivParent).append(msg);
				 
				 i++;
				 if(toWhichIds[i]!=null)
				 {
					 popuateDepartmentsAndPostionsByBusinessFunction(businF, toWichDiv, loaderID , relatedTask, i)
				 }
				 
			 },
			error:function(){
			 alert('Error !');
			}			 
	  });
} 

function populatePositionsByDepartment(department, toWichDiv, loaderID , relatedTask)
{			
	var toWhichDivParent = $("#"+toWichDiv).parent().get(0).id;
	var businF = $('#ddlbussinessFunction').val();
	var host = getHostAddress();
	
	$('#'+loaderID).show();	
	
	$.ajax({
			 type:'POST',
			 url:host+'/admin/populateDivsUsingAjax.php',
			 data:'bussinessFun='+businF+'&department='+department+'&task='+relatedTask,
			 async:true,
			 success:function(msg){
				 //alert(msg);
				 $('#'+loaderID).hide();
				 $("#"+toWhichDivParent).html('');
				 $("#"+toWhichDivParent).append(msg);								 
			 },
			error:function(){
			 alert('Error !');
			}			 
	  });
}
</script>
<?php
echo $htmlTagObj->closeTag('body');
echo $htmlTagObj->closeTag('html');
?>