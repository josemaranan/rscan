<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/RNetIncludes/class1/common.config.inc.php');

$RDSObj = new RDSData(MSSQL_HOST, MSSQL_USERNAME, MSSQL_PASSWORD, MSSQL_DB, MSSQL_TYPE, MSSQL_PORT, '');

$dialogTask = $_REQUEST['task'];
//echo $dialogTask.' Test text...'; exit;


unset($mainArray);
if( $dialogTask != 'ADD')
{
	$sqlQuery = "SELECT 
						a.*,
						b.description,
						b.containerID
					FROM 
						rnet.dbo.ctlWorkflows a (nolock)
					LEFT JOIN 
						rnet.dbo.ctlWorkflowicons b (nolock)
					ON 
						a.workflowIconID = b.workflowIconID
				 	where 
						workflowID = ".$dialogTask;
	$resultsSet = $RDSObj->execute($sqlQuery);
	$numRows = $RDSObj->getNumRows($resultsSet);
	if ($numRows >= 1)
	{
		//$mainArray = $RDSObj->bindingInToArray($resultsSet);
		$mainArray = mssql_fetch_assoc($resultsSet);
		//print_r($mainArray); exit();  
		/*11
		Array(    [0] => Array        (            [workflowID] => 11
            [workflowIconID] => 1            [workflowName] => Utilities
            [US_description] => Utilities            [US_workflowURL] => 
            [US_active] => Y            [MX_description] => 
            [MX_workflowURL] =>             [MX_active] => Y
            [PH_description] =>             [PH_workflowURL] => 
            [PH_active] => Y            [CR_description] => 
            [CR_workflowURL] =>             [CR_active] => Y
            [IN_description] =>             [IN_workflowURL] => 
            [IN_active] => Y        ))*/
	}
	
}


// table div
$txtBoxSize = '30';
echo $htmlTagObj->openTag('div', 'style="padding:10px 0px 0px 5px;overflow:auto;"');

$htmlTextElement->name = 'hdnTask';
$htmlTextElement->id = 'hdnTask';
$htmlTextElement->value = $dialogTask;
$htmlTextElement->type = 'hidden';
$hdnTask = $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();

$commonListBox->name = 'ddlContainers';
$commonListBox->id = 'ddlContainers';
$commonListBox->sqlQry = " SELECT * FROM rnet..ctlWorkFlowContainer (NOLOCK)";
$commonListBox->selectedItem = $mainArray[containerID];
$commonListBox->onChange = "return popuateWorkFlowIcons(this.value, 'showDialog'); return false;";
$commonListBox->optionKey = 'containerID';
$commonListBox->optionVal = 'description';
$commonListBox->AddRow('', 'Please choose');
$ddlContainers = $commonListBox->display();
$commonListBox->resetProperties();

$commonListBox->name = 'ddlWorkFlows';
$commonListBox->id = 'ddlWorkFlows';
if($mainArray[containerID] != '')
{
	$wfQuery = "SELECT * FROM rnet..ctlWorkflowicons (NOLOCK) where containerID = ".$mainArray[containerID];
}
else
{
	$wfQuery = "SELECT * FROM rnet..ctlWorkflowicons (NOLOCK)";
}
$commonListBox->sqlQry = $wfQuery;
$commonListBox->selectedItem = $mainArray[workflowIconID];
$commonListBox->optionKey = 'workflowIconID';
$commonListBox->optionVal = 'description';
$commonListBox->AddRow('', 'Please choose');
$ddlWorkFlows = $commonListBox->display();
$commonListBox->resetProperties();


$htmlCustomButtonElement = new HtmlCustomButtonElement('button');
$htmlCustomButtonElement->id            = 'btnAddorEdit'; 
$htmlCustomButtonElement->name          = 'btnAddorEdit';
$htmlCustomButtonElement->value         = ($dialogTask == 'ADD') ? 'Add' : 'Edit'; 
$htmlCustomButtonElement->style         = 'float:left; margin-right: 5px;';
$htmlCustomButtonElement->onclick          = 'return submitData(this.value); return false;';
$btnAddorEdit = $htmlCustomButtonElement->renderHtml();	
$htmlCustomButtonElement->resetProperties();

$htmlCustomButtonElement->id            = 'btnCancel'; 
$htmlCustomButtonElement->name          = 'btnCancel'; 
$htmlCustomButtonElement->value         = 'Cancel'; 
$htmlCustomButtonElement->style         = 'float:left;'; 
$htmlCustomButtonElement->type          = 'button'; 
$htmlCustomButtonElement->onclick          = 'return closePopup(); return false;'; 
$btnCancel = $htmlCustomButtonElement->renderHtml(); 
$htmlCustomButtonElement->resetProperties();

$htmlTextElement->name = 'chkUsActive';
$htmlTextElement->id = 'chkUsActive';
$htmlTextElement->value = 'Y';
$htmlTextElement->type = 'checkbox';
$htmlTextElement->checked = ($mainArray[US_active] == 'Y') ? 'checked' : '';
$chkUsActive = $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();

$htmlTextElement->name = 'txtWorkFlowName';
$htmlTextElement->id = 'txtWorkFlowName';
$htmlTextElement->value = $mainArray[workflowName];
$htmlTextElement->type = 'text';
$htmlTextElement->size = $txtBoxSize;
$txtWorkFlowName = $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();

/*
$htmlTextElement->name = 'txtDescription';
$htmlTextElement->id = 'txtDescription';
$htmlTextElement->value = $mainArray[description];
$htmlTextElement->type = 'text';
$htmlTextElement->size = $txtBoxSize;
$txtDescription = $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();*/

$htmlTextElement->name = 'txtUsDescription';
$htmlTextElement->id = 'txtUsDescription';
$htmlTextElement->value = $mainArray[US_description];
$htmlTextElement->type = 'text';
$htmlTextElement->size = $txtBoxSize;
$htmlTextElement->style = 'width:220px';
$txtUsDescription = $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();

$htmlTextElement->name = 'txtUsWorkFlowURL';
$htmlTextElement->id = 'txtUsWorkFlowURL';
$htmlTextElement->value = $mainArray[US_workflowURL];
$htmlTextElement->type = 'text';
$htmlTextElement->size = $txtBoxSize;
$htmlTextElement->style = 'width:330px';
$txtUsWorkFlowURL = $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();


//-----
$htmlTextElement->name = 'chkMxActive';
$htmlTextElement->id = 'chkMxActive';
$htmlTextElement->value = 'Y';
$htmlTextElement->type = 'checkbox';
$htmlTextElement->checked = ($mainArray[MX_active] == 'Y') ? 'checked' : '';
$htmlTextElement->onkeypress = 'return storeCountryFieldValues("mx")';
$chkMxActive = $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();

$htmlTextElement->name = 'txtMxWorkFlowURL';
$htmlTextElement->id = 'txtMxWorkFlowURL';
$htmlTextElement->value = $mainArray[MX_workflowURL];
$htmlTextElement->type = 'text';
$htmlTextElement->size = $txtBoxSize;
$htmlTextElement->style = 'width:330px';
$txtMxWorkFlowURL = $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();

$htmlTextElement->name = 'txtMxDescription';
$htmlTextElement->id = 'txtMxDescription';
$htmlTextElement->value = $mainArray[MX_description];
$htmlTextElement->type = 'text';
$htmlTextElement->size = $txtBoxSize;
$htmlTextElement->style = 'width:220px';
$txtMxDescription = $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();

$lblddlContainers	= $htmlTextElement->addLabel($ddlContainers, 'Container:', '#ff0000',TRUE); 
$lbltxtWorkFlowName	= $htmlTextElement->addLabel($txtWorkFlowName, 'Work Flow Name:', '#ff0000',TRUE);
$lblddlWorkFlows	= $htmlTextElement->addLabel('<div id="workFlowDiv">'.$ddlWorkFlows.'</div>', 'Workflow:', '#ff0000',TRUE);  

$lbltxtUsDescription	= $htmlTextElement->addLabel($txtUsDescription, 'US Description:', '#ff0000',TRUE);
$lbltxtUsWorkFlowURL = $htmlTextElement->addLabel($txtUsWorkFlowURL, 'US Work Flow URL:', '#ff0000',TRUE);
$lblchkUsActive = $htmlTextElement->addLabel($chkUsActive, 'US Active:', '#ff0000',false);

$lbltxtMxDescription	= $htmlTextElement->addLabel($txtMxDescription, 'MX Description:', '#ff0000',TRUE);
$lbltxtMxWorkFlowURL = $htmlTextElement->addLabel($txtMxWorkFlowURL, 'MX Work Flow URL:', '#ff0000',TRUE);
$lblchkMxActive = $htmlTextElement->addLabel($chkMxActive, 'MX Active:', '#ff0000',false);

//-----
$htmlTextElement->name = 'chkPhActive';
$htmlTextElement->id = 'chkPhActive';
$htmlTextElement->value = 'Y';
$htmlTextElement->type = 'checkbox';
$htmlTextElement->checked = ($mainArray[PH_active] == 'Y') ? 'checked' : '';
$chkPhActive = $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();

$htmlTextElement->name = 'txtPhWorkFlowURL';
$htmlTextElement->id = 'txtPhWorkFlowURL';
$htmlTextElement->value = $mainArray[PH_workflowURL];
$htmlTextElement->type = 'text';
$htmlTextElement->size = $txtBoxSize;
$htmlTextElement->style = 'width:330px';
$txtPhWorkFlowURL = $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();

$htmlTextElement->name = 'txtPhDescription';
$htmlTextElement->id = 'txtPhDescription';
$htmlTextElement->value = $mainArray[PH_description];
$htmlTextElement->type = 'text';
$htmlTextElement->size = $txtBoxSize;
$htmlTextElement->style = 'width:220px';
$txtPhDescription = $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();

$lbltxtPhDescription	= $htmlTextElement->addLabel($txtPhDescription, 'PH Description:', '#ff0000',TRUE);
$lbltxtPhWorkFlowURL = $htmlTextElement->addLabel($txtPhWorkFlowURL, 'PH Work Flow URL:', '#ff0000',TRUE);
$lblchkPhActive = $htmlTextElement->addLabel($chkPhActive, 'PH Active:', '#ff0000',false);

//-----
$htmlTextElement->name = 'chkCrActive';
$htmlTextElement->id = 'chkCrActive';
$htmlTextElement->value = 'Y';
$htmlTextElement->type = 'checkbox';
$htmlTextElement->checked = ($mainArray[CR_active] == 'Y') ? 'checked' : '';
$chkCrActive = $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();

$htmlTextElement->name = 'txtCrWorkFlowURL';
$htmlTextElement->id = 'txtCrWorkFlowURL';
$htmlTextElement->value = $mainArray[CR_workflowURL];
$htmlTextElement->type = 'text';
$htmlTextElement->size = $txtBoxSize;
$htmlTextElement->style = 'width:330px';
$txtCrWorkFlowURL = $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();

$htmlTextElement->name = 'txtCrDescription';
$htmlTextElement->id = 'txtCrDescription';
$htmlTextElement->value = $mainArray[CR_description];
$htmlTextElement->type = 'text';
$htmlTextElement->size = $txtBoxSize;
$htmlTextElement->style = 'width:220px';
$txtCrDescription = $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();

$lbltxtCrDescription	= $htmlTextElement->addLabel($txtCrDescription, 'CR Description:', '#ff0000',TRUE);
$lbltxtCrWorkFlowURL = $htmlTextElement->addLabel($txtCrWorkFlowURL, 'CR Work Flow URL:', '#ff0000',TRUE);
$lblchkCrActive = $htmlTextElement->addLabel($chkCrActive, 'CR Active:', '#ff0000',false);

//-----
$htmlTextElement->name = 'chkInActive';
$htmlTextElement->id = 'chkInActive';
$htmlTextElement->value = 'Y';
$htmlTextElement->type = 'checkbox';
$htmlTextElement->checked = ($mainArray[IN_active] == 'Y') ? 'checked' : '';
$chkInActive = $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();

$htmlTextElement->name = 'txtInWorkFlowURL';
$htmlTextElement->id = 'txtInWorkFlowURL';
$htmlTextElement->value = $mainArray[IN_workflowURL];
$htmlTextElement->type = 'text';
$htmlTextElement->size = $txtBoxSize;
$htmlTextElement->style = 'width:330px';
$txtInWorkFlowURL = $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();

$htmlTextElement->name = 'txtInDescription';
$htmlTextElement->id = 'txtInDescription';
$htmlTextElement->value = $mainArray[IN_description];
$htmlTextElement->type = 'text';
$htmlTextElement->size = $txtBoxSize;
$htmlTextElement->style = 'width:220px';
$txtInDescription = $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();

$lbltxtInDescription	= $htmlTextElement->addLabel($txtInDescription, 'IN Description:', '#ff0000',TRUE);
$lbltxtInWorkFlowURL = $htmlTextElement->addLabel($txtInWorkFlowURL, 'IN Work Flow URL:', '#ff0000',TRUE);
$lblchkInActive = $htmlTextElement->addLabel($chkInActive, 'IN Active:', '#ff0000',false);

//$longDescription	= $htmlTagObj->openTag('td', 'colspan="6"').$htmlTagObj->textAreatag(' id="txaLnDescription" name="txaLnDescription" width="80" rows="8" ', $mainArray[longDescription]).$htmlTagObj->closeTag('td');
$longDescription	= $htmlTagObj->textAreatag(' id="txaLnDescription" name="txaLnDescription" cols="80" rows="5" ', $mainArray[longDescription]);
$htmlTextElement->colspan = 5;
$longDescription = $htmlTextElement->addLabel($longDescription, 'Long Description:', '#ff0000',false);

$tableObj->tableId = 'searchTable';
$tableObj->tableClass = 'searchtab';
$tableObj->maxCol = 3;

$tableObj->trIds[0] = 'container';
$tableObj->searchFields['lblddlContainers'] = $lblddlContainers; //ddlContainers
$tableObj->searchFields['lblddlWorkFlows'] = $lblddlWorkFlows; 
$tableObj->searchFields['lbltxtWorkFlowName'] = $lbltxtWorkFlowName;

$tableObj->trIds[1] = 'us';
$tableObj->searchFields['lbltxtUsDescription'] = $lbltxtUsDescription;
$tableObj->searchFields['lbltxtUsWorkFlowURL'] = $lbltxtUsWorkFlowURL;
$tableObj->searchFields['lblchkUsActive'] = $lblchkUsActive;

$tableObj->trIds[2] = 'mx';
$tableObj->searchFields['lbltxtMxDescription'] = $lbltxtMxDescription;
$tableObj->searchFields['lbltxtMxWorkFlowURL'] = $lbltxtMxWorkFlowURL;
$tableObj->searchFields['lblchkMxActive'] = $lblchkMxActive;

$tableObj->trIds[3] = 'ph';
$tableObj->searchFields['lbltxtPhDescription'] = $lbltxtPhDescription;
$tableObj->searchFields['lbltxtPhWorkFlowURL'] = $lbltxtPhWorkFlowURL;
$tableObj->searchFields['lblchkPhActive'] = $lblchkPhActive;

$tableObj->trIds[4] = 'cr';
$tableObj->searchFields['lbltxtCrDescription'] = $lbltxtCrDescription;
$tableObj->searchFields['lbltxtCrWorkFlowURL'] = $lbltxtCrWorkFlowURL;
$tableObj->searchFields['lblchkCrActive'] = $lblchkCrActive;

$tableObj->trIds[5] = 'in';
$tableObj->searchFields['lbltxtInDescription'] = $lbltxtInDescription;
$tableObj->searchFields['lbltxtInWorkFlowURL'] = $lbltxtInWorkFlowURL;
$tableObj->searchFields['lblchkInActive'] = $lblchkInActive;

$tableObj->searchFields['longDescription'] = $longDescription;


$htmlForm->action = '#';
$htmlForm->name = 'dialog_data';
$htmlForm->id = 'dialog_data';
echo $htmlForm->startForm();
echo $tableObj->searchFormTableComponent();
echo $htmlTagObj->openTag('div','align="center"');
echo '<br/>'.$btnAddorEdit.$btnCancel; //.addslashes($mainArray[businessFunction]);;
echo $htmlTagObj->closeTag('div');
echo $hdnTask;
echo $htmlForm->endForm();

// to show the process image
echo $htmlTagObj->openTag('div', 'id="showSubDialog" align="center" style="width : 500px;"');
echo $htmlTagObj->closeTag('div');
// end 

echo $htmlTagObj->closeTag('div');

?>

<script language="javascript" type="text/javascript">

var errMsg = '<span style="color: red;">* Required fields should not be empty.</span>';

function getCount(obj, flag)
{
	
	var fieldID = $(obj).attr('id');
	var fieldType = $(obj).attr('type');
	var FieldValue = $(obj).val();
	
	if(fieldType=='checkbox')
	{
		if(!($("#"+fieldID).is(':checked')))
		{
			flag++;
		}
	}
	else
	{
		if(FieldValue == '' )
		{
			flag++;
		}
	}
	return flag;
}

function vefrifyRowData(countryCode)
{
	var returnValue = true;
	if($('#txt'+countryCode+'Description').val()=='')
	{
		alert('Please enter '+countryCode.toUpperCase()+' description');
		returnValue = false;
	}
	if($('#txt'+countryCode+'WorkFlowURL').val()=='')
	{
		alert('Please enter '+countryCode.toUpperCase()+' workflow URL');
		returnValue = false;
	}	
	return returnValue;
}

function submitData(isAdd)
{
	//alert(isAdd);
	//alert(submitInputs); return false;
	//$("#showSubDialog").html(loader);  
	var isSubmit = true;
	
	var usFlag = 0;
	var mxFlag = 0;
	var phFlag = 0;
	var crFlag = 0;
	var inFlag = 0;
	
	var cFlag = 0;
	$("#dialog_data tr#us input, tr#mx input, tr#ph input, tr#cr input, tr#in input").each(function()
	{
		cFlag = getCount(this, cFlag);				
	});		
	
	var isRowSelecttion = true;
	if(cFlag == 15)
	{
		//alert('Please fill atleast one country details');
		//return false;
		isRowSelecttion = false;
	}
	else 
	{
		$("#dialog_data tr#us input").each(function()
		{
			usFlag = getCount(this, usFlag);		
		});		

		$("#dialog_data tr#mx input").each(function()
		{
			mxFlag = getCount(this, mxFlag);											  
		});
			
		$("#dialog_data tr#ph input").each(function()
		{
			phFlag = getCount(this, phFlag);       								  
		});
			
		$("#dialog_data tr#cr input").each(function()
		{
			crFlag = getCount(this, crFlag);        									  
		});
			
		$("#dialog_data tr#in input").each(function()
		{
			inFlag = getCount(this, inFlag);       								  
		});
	//alert(usFlag+'=='+mxFlag+'=='+phFlag+'=='+crFlag+'=='+inFlag)	
		if(usFlag!=3 && usFlag>0)
		{
			returnValue = vefrifyRowData('Us');
			if(!returnValue)
				return false;
		}
		if(mxFlag!=3 && mxFlag>0)
		{
			returnValue = vefrifyRowData('Mx');
			if(!returnValue)
				return false;
		}
		if(phFlag!=3 && phFlag>0)
		{
			returnValue = vefrifyRowData('Ph');
			if(!returnValue)
				return false;
		}
		if(crFlag!=3 && crFlag>0)
		{
			returnValue = vefrifyRowData('Cr');
			if(!returnValue)
				return false;
		}
		if(inFlag!=3 && inFlag>0)
		{
			returnValue = vefrifyRowData('In');
			if(!returnValue)
				return false;
		}				
	}
	
	
	if($('#container #ddlContainers').val()=='')
	{
		alert('Please select container');
		return false;
	}
	else if($('#container  #ddlWorkFlows').val()=='')
	{
		alert('Please select workflow');
		return false;
	}
	else if($('#container #txtWorkFlowName').val()=='')
	{
		alert('Please enter workflow name');
		return false;
	}
	else if(!isRowSelecttion)
	{
		alert('Please fill atleast one country details');
		return false;
	}
	else
	{		
		$("#showSubDialog").html(loader);
		var submitInputs = $("#dialog_data").serialize();
		
		$.post(
			   "manageWorkFlowsAjax.php?task=addOrEditWorkFlow",
			   submitInputs,
			   function(data)
			   {
				  $("#showSubDialog").html(data);
				  if(data)
				  {
					  alert(isAdd+'ed successfully.');
					  loadData();
					  closePopup();
				  }
				  else
				  {
					  alert('Error in insert/update.');
				  }
			   }
		);
	}
	
}

function popuateWorkFlowIcons(containerId, parentDiv)
{
	var parentDivId = '';
	if(parentDiv!=null && parentDiv!='')
	{
		parentDivId = '#'+parentDiv;
	}

	$(parentDivId+" #workFlowDiv").html(loader);
	$.post(
		   "manageWorkFlowsAjax.php?task=popuateWorkFlowIcons",
		   {containerID: containerId},
		   function(data)
		   {
			  $(parentDivId+" #workFlowDiv").html(data);
		   }
   );
}
/*
function blinkMessage()
{
	//$("#showSubDialog").toggle();
	//$('#showSubDialog').filter(":visible").animate({ width: "toggle" });
}
*/


</script>
