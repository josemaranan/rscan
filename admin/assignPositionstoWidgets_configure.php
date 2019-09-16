<?php

/**
 * @description : Configure position to workflow widgets
 * @author : Vasudev
 * @date : 08/14/2014
 * */
 
include_once($_SERVER['DOCUMENT_ROOT'] . '/RNetIncludes/class1/includeClassFiles.php');
$RDSObj = new RDSData(MSSQL_HOST, MSSQL_USERNAME, MSSQL_PASSWORD, MSSQL_DB, MSSQL_TYPE, MSSQL_PORT, '');


include_once($_SERVER['DOCUMENT_ROOT'] . '/RNetIncludes/class1/ReportTable.inc.php');
$htmlCustomButtonElement = new HtmlCustomButtonElement();
  
$pageHyperlinks = array('Back'=>'/admin/assignPositionstoWidgets_search.php');
$headerObj->loadPageLinks($htmlTagObj , $pageHyperlinks);

$ddlWorkFlows 		= 	'';
$ddlDepartment 		= 	'';
$hdnValues			=	'';
if(isset($_REQUEST['ddlWorkFlows']))
{
	$ddlWorkFlows = $_REQUEST['ddlWorkFlows'];		
}


if(isset($_REQUEST['ddlDepartment']))
{
	$ddlDepartment = $_REQUEST['ddlDepartment'];		
}

if(isset($_REQUEST['ddlPositions']))
{
	$ddlPositions = $_REQUEST['ddlPositions'];		
}


//$ddlWorkFlows = 6;
//$ddlDepartment = 'Human Resources';


/* Positon Array */
unset($sqlQuery);
unset($resultsSet);
unset($numRows);
unset($PositionArray);


$sqlQuery = " SELECT positionID , position FROM Results.dbo.ctlPositions (NOLOCK) WHERE active = 'Y' ORDER BY positionID ";
$resultsSet = $RDSObj->execute($sqlQuery);
$numRows = $RDSObj->getNumRows($resultsSet);
if ($numRows >= 1)
{
	while($spqueyZ = mssql_fetch_assoc($resultsSet))
	{
		$PositionArray[$spqueyZ['positionID']]  = $spqueyZ['position'];
	}
}


/* Positon Array */

if(!empty($ddlWorkFlows))
{
	unset($sqlQuery);
	unset($resultsSet);
	unset($numRows);
	unset($workFlowArray);
	unset($workFlowIDString);
	
	$sqlQuery = " SELECT 
					workFlowID , 
					US_description as workFlowName 
				FROM 
					Rnet.dbo.ctlWorkFlows (nolock) where workFlowIconID = ".$ddlWorkFlows." 
				ORDER BY
					workFlowName ";
	//echo $sqlQuery;
	
	$resultsSet = $RDSObj->execute($sqlQuery);
	$numRows = $RDSObj->getNumRows($resultsSet);
	if ($numRows >= 1)
	{
		//$workFlowArray = $RDSObj->bindingInToArray($resultsSet);
		
		while($spquey = mssql_fetch_assoc($resultsSet))
		{
			$workFlowArray[] = 	$spquey['workFlowID'].'||'.$spquey['workFlowName'];
			$workFlowIDString .= $spquey['workFlowID'].',';
		}
	
	}
	
	$workFlowIDString = substr($workFlowIDString,0,-1);
}

unset($sqlQuery);
unset($resultsSet);
unset($numRows);
unset($positionArray);
unset($positionIDString);

if(empty($_REQUEST['ddlPositions']))
{
	if(!empty($ddlDepartment))
	{
		$sqlQuery = " SELECT 
						a.positionID ,
						a.[description] 
				FROM 
						Results.dbo.ctlPositions a WITH (nolock) 
				JOIN
						Results.dbo.ctlDepartments b WITH (NOLOCK)
				ON
						a.departmentCode = b.departmentCode
				WHERE 
						b.departmentName = '".$ddlDepartment."'  
				AND
						a.Active = 'Y' 
				ORDER BY [description] ";
		
		$resultsSet = $RDSObj->execute($sqlQuery);
		$numRows = $RDSObj->getNumRows($resultsSet);
		if ($numRows >= 1)
		{
			//$positionArray = $RDSObj->bindingInToArray($resultsSet);
			while($spqueyPos = mssql_fetch_assoc($resultsSet))
			{
				$positionArray[] = 	$spqueyPos['positionID'].'||'.$spqueyPos['description'];
				$positionIDString .= $spqueyPos['positionID'].',';
			}
		}
		$positionIDString = substr($positionIDString,0,-1);
	} //if(!empty($ddlDepartment))
}
else
{
	foreach($_REQUEST['ddlPositions'] as $poKey=>$poVal)
	{
			$positionArray[] = 	$poVal.'||'.$PositionArray[$poVal];
			$positionIDString .= $poVal.',';	
	}
			$positionIDString = substr($positionIDString,0,-1);
}

unset($sqlQuery);
unset($resultsSet);
unset($numRows);
unset($existingArray);

if(!empty($positionIDString) && !empty($workFlowIDString))
{
	$sqlQuery = " SELECT 
						positionID ,
						workFlowID 
				FROM 
						Rnet.dbo.prmWorkflowPositions (nolock) 
				WHERE 
						positionID IN (".$positionIDString.") 
					AND
						workFlowID IN (".$workFlowIDString.") ";
	
	$resultsSet = $RDSObj->execute($sqlQuery);
	$numRows = $RDSObj->getNumRows($resultsSet);
	if ($numRows >= 1)
	{
		//$positionArray = $RDSObj->bindingInToArray($resultsSet);
		while($spqueyPosEx = mssql_fetch_assoc($resultsSet))
		{
			$existingArray[] = 	$spqueyPosEx['positionID'].'||'.$spqueyPosEx['workFlowID'];
		}
	}

}  // if(!empty($positionIDString) && !empty($workFlowIDString))

/*
echo '<pre>';
print_r($existingArray);
echo '</pre>';


echo '<pre>';
print_r($workFlowArray);
echo '</pre>';*/



unset($countPosition);
$countPosition = count($positionArray);


unset($countWorkFlow);
$countWorkFlow = count($workFlowArray);



$htmlCustomButtonElement->type 		= 'submit';
$htmlCustomButtonElement->style 	= 'float:left';
$htmlCustomButtonElement->value 	= 'Save';
$htmlCustomButtonElement->onclick 	= 'return chkAtLeastOneSelect(); return false;';
$htmlCustomButtonElement->name 		= 'submit';
$btnSubmit = $htmlCustomButtonElement->renderHtml();
	

echo $htmlTagObj->openTag('div', 'class="outer" id="emptyDiv"');
echo $htmlTagObj->closeTag('div');
echo $htmlTagObj->openTag('div', 'class="outer" id="emptyDiv"');
echo $htmlTagObj->closeTag('div');
echo $htmlTagObj->openTag('div', 'class="outer" id="emptyDiv"');
echo $htmlTagObj->closeTag('div');
echo $htmlTagObj->openTag('div', 'class="outer" id="emptyDiv"');
echo $htmlTagObj->closeTag('div');
echo $htmlTagObj->openTag('div', 'class="outer" id="emptyDiv"');
echo $htmlTagObj->closeTag('div');
echo $htmlTagObj->openTag('div', 'class="outer" id="emptyDiv"');
echo $htmlTagObj->closeTag('div');


$Table=new ReportTable();
$Table->Width="98%";

$Col=& $Table->AddColumn("Column0");	 

for($i=0 ; $i<$countPosition; $i++)
{
	$Col=& $Table->AddColumn($i);	
}
$Row=& $Table->AddHeader();

$Row->Cells["Column0"]->Value= "&nbsp;&nbsp;&nbsp;"; 
$Row->Cells["Column0"]->locked	= true; 
for($i=0 ; $i<$countPosition; $i++)
{
	unset($pQs);
	$pQs = explode('||', $positionArray[$i]);
	$Row->Cells[$i]->Value= wordwrap($pQs[1],11,"<br>\n");
}

if($countWorkFlow==0)
{
	header('Location: assignPositionstoWidgets_search.php');
	exit;
}

echo $htmlTagObj->openTag('div', 'id="report_content"');

$htmlForm->action = 'assignPositionstoWidgets_process.php';
$htmlForm->name = 'configureForm';
$htmlForm->id = 'searchForm';
echo $htmlForm->startForm();

for($k=0 ; $k<$countWorkFlow; $k++)
{
	$Row=& $Table->AddRow();
	unset($wpsQ);
	$wpsQ = explode('||', $workFlowArray[$k]);
	
	$Row->Cells["Column0"]->Value = $wpsQ[1];
	$Row->Cells["Column0"]->locked	= true; 
	for($z=0 ; $z<$countPosition; $z++)
	{
		unset($pQs);
		unset($checkBoxString);
		$pQs = explode('||', $positionArray[$z]);
		
		$htmlTextElement->type	= 'hidden';
		$htmlTextElement->name	= 'hdnVal[]';
		$htmlTextElement->value	= $pQs[0].'||'.$wpsQ[0];
		$hdnBoxString =  $htmlTextElement->renderHtml();
		$htmlTextElement->resetProperties();
		
		
		$htmlTextElement->type	= 'checkbox';
		$htmlTextElement->name	= 'ckhBVox[]';
		$htmlTextElement->id	= 'ckhBVox'.$pQs[0].$wpsQ[0];
		$htmlTextElement->value	= $pQs[0].'||'.$wpsQ[0];
		if(in_array($pQs[0].'||'.$wpsQ[0], $existingArray))
		{
			$htmlTextElement->checked	= 'checked';
		}
		$checkBoxString =  $htmlTextElement->renderHtml();
		$htmlTextElement->resetProperties();
		//$Row->Cells[$z]->Value = $checkBoxString.$hdnBoxString.$pQs[0].'||'.$wpsQ[0];
		$Row->Cells[$z]->Value = $checkBoxString.$hdnBoxString;
		
		
	}
	
}

$Table->Display();


$htmlTextElement->type	= 'hidden';
$htmlTextElement->name	= 'hdnddlWorkFlows';
$htmlTextElement->id	= 'hdnddlWorkFlows';
$htmlTextElement->value	= $ddlWorkFlows;
$hdnValues .= $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();

$htmlTextElement->type	= 'hidden';
$htmlTextElement->name	= 'hdnddlDepartment';
$htmlTextElement->id	= 'hdnddlDepartment';
$htmlTextElement->value	= $ddlDepartment;
$hdnValues .= $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();



echo $htmlTagObj->openTag('div', 'class="outer" id="emptyDiv"');
echo $btnSubmit;
echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->openTag('div', 'class="outer" id="emptyDiv"');
echo $htmlTagObj->closeTag('div');
echo $htmlTagObj->openTag('div', 'class="outer" id="emptyDiv"');
echo $htmlTagObj->closeTag('div');
echo $htmlTagObj->openTag('div', 'class="outer" id="emptyDiv"');
echo $htmlTagObj->closeTag('div');
echo $htmlTagObj->openTag('div', 'class="outer" id="emptyDiv"');
echo $htmlTagObj->closeTag('div');
echo $hdnValues;
echo $htmlForm->endForm();
echo $htmlTagObj->closeTag('div'); // report content

echo $htmlTagObj->closeTag('body');
echo $htmlTagObj->closeTag('html');
?>
<script type="text/javascript">
function chkAtLeastOneSelect()
{
	var sFlag = true;
	var checkLen = $( "input:checked" ).length;
	if(checkLen==0)
	{	
		alert('Please check at least one checkbox');
		return false;
		var sFlag = false;
	}
	
	if(sFlag)
	{
		
		document.configureForm.submit();	
	}
	else
	{
		return false;	
	}
}
</script>