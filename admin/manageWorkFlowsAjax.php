<?php


include_once($_SERVER['DOCUMENT_ROOT'] . '/RNetIncludes/class1/common.config.inc.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/RNetIncludes/class/ReportTable.inc.php');
include_once($_SERVER["DOCUMENT_ROOT"]."/Include/tempTableStructure.inc.php"); 

$RDSObj = new RDSData(MSSQL_HOST, MSSQL_USERNAME, MSSQL_PASSWORD, MSSQL_DB, MSSQL_TYPE, MSSQL_PORT, '');


$task = $_REQUEST['task'];  
$parameters = $rnetAllObj->storeValues($_POST);
		
switch($task)
{
	case 'loadWorkFlows':
		
		$headerObj->jsSource = $jsFilesAjax;
		$jsFiles = $headerObj->getJsSourceFiles();
		echo $jsFiles;
		
		$containerID = '';
		$workFlowIconID = $workFlowName = '';
		if(isset($_REQUEST['ddlContainers']))
		{
			$containerID	= $_REQUEST['ddlContainers'];
		}
		if(isset($_REQUEST['ddlWorkFlows']))
		{
			$workFlowIconID	= $_REQUEST['ddlWorkFlows'];
		}
		if(isset($_REQUEST['txtWorkFlowName']))
		{
			$workFlowName	= $_REQUEST['txtWorkFlowName'];
		}
		/*$sqlQuery = "
					SELECT 
						a.*,
						b.description
					FROM 
						rnet.dbo.ctlWorkflows a (nolock)
					LEFT JOIN 
						rnet.dbo.ctlWorkflowicons b (nolock)
					ON 
						a.workflowIconID = b.workflowIconID"; */
		/*$sqlQuery = "
					SELECT 
						a.*,
						b.description,
						b.containerID
					FROM 
						rnet.dbo.ctlWorkflows a (nolock)
					LEFT JOIN 
						rnet.dbo.ctlWorkflowicons b (nolock)
					ON 
						a.workflowIconID = b.workflowIconID
				where ('".$containerID."'='' or b.containerID = '".$containerID."') and ('".$workFlowIconID."' = '' or a.workflowIconID = '".$workFlowIconID."') 
					and ('".$workFlowName."' = '' or a.workflowName = '".$workFlowName."')	 "; 	*/
					
					
		$sqlQuery = " EXEC Rnet.dbo.[report_spGetManageWorkflows] '".$containerID."', '".$workFlowIconID."' , '".$workFlowName."' "; 				
	

		$resultsSet = $RDSObj->execute($sqlQuery);
		//echo $sqlQuery; exit();
	
		$numRows = $RDSObj->getNumRows($resultsSet);
		if ($numRows >= 1)
		{
			$mainArray = $RDSObj->bindingInToArray($resultsSet);
		}
		
		//print_r($mainArray); exit;
	
		$Table=new ReportTable();
		$Table->Width="98%";
		
		$Col=& $Table->AddColumn("Column0");
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
		$Col=& $Table->AddColumn("Column13");
		$Col=& $Table->AddColumn("Column14");
		$Col=& $Table->AddColumn("Column15");
		$Col=& $Table->AddColumn("Column16");
		$Col=& $Table->AddColumn("Column17");
		$Col=& $Table->AddColumn("Column18");
		
		/*Array        (            [workflowID] => 2            [workflowIconID] => 1
            [workflowName] => AE View Invoices(GE,GE Canada) 
            [US_description] => View Invoices(GE,GE Canada) 
            [US_workflowURL] => /Users/GRP/AE/CreateBill_GE.php
            [US_active] => Y            [MX_description] => 
            [MX_workflowURL] =>             [MX_active] => Y
            [PH_description] =>             [PH_workflowURL] => 
            [PH_active] => Y            [CR_description] => 
            [CR_workflowURL] =>             [CR_active] => Y
            [IN_description] =>             [IN_workflowURL] => 
            [IN_active] => Y            [description] => My Profile        )
		*/
		
		$Row=& $Table->AddHeader();
		$Row->Cells["Column0"]->Value="Action";
		$Row->Cells["Column0"]->locked	= true;
		$Row->Cells["Column1"]->Value="Workflow Icon";
		$Row->Cells["Column1"]->locked	= true;
		$Row->Cells["Column2"]->Value="Work Flow Name";
		$Row->Cells["Column2"]->locked	= true;
		$Row->Cells["Column3"]->Value="US Description";
		$Row->Cells["Column4"]->Value="US Work flow URL";
		$Row->Cells["Column5"]->Value="US Active";
		$Row->Cells["Column6"]->Value="MX Description";
		$Row->Cells["Column7"]->Value="MX Work flow URL";
		$Row->Cells["Column8"]->Value="MX Active";
		$Row->Cells["Column9"]->Value="PH Description";
		$Row->Cells["Column10"]->Value="PH Work flow URL";
		$Row->Cells["Column11"]->Value="PH Active";
		$Row->Cells["Column12"]->Value="CR Description";
		$Row->Cells["Column13"]->Value="CR Work flow URL";
		$Row->Cells["Column14"]->Value="CR Active";
		$Row->Cells["Column15"]->Value="IN Description";
		$Row->Cells["Column16"]->Value="IN Work flow URL";
		$Row->Cells["Column17"]->Value="IN Active";
		$Row->Cells["Column18"]->Value="Long Description";
		
		foreach($mainArray as $mainArrayK=>$mainArrayV)
		{
				$Row=& $Table->AddRow();
				$autoId = $mainArrayV['workflowID'];
				$workflowIconID = $mainArrayV['workflowIconID'];
				$workflowName = $mainArrayV['workflowName'];
				
				$assignPositions = $htmlTagObj->anchorTag("javascript:;", "Assign Position", "onclick='openPositionDialog(\"".$autoId."\",\"".$workflowName."\",\"".$workflowIconID."\");'");
				
				$Row->Cells["Column0"]->Value = "<a href='#' onclick='openDialog(\"".$autoId."\",\"".$workflowName."\");'>EDIT</a> | ".$assignPositions;
				$Row->Cells["Column0"]->locked= true; 
				$Row->Cells["Column1"]->Value = $mainArrayV['description'];
				$Row->Cells["Column1"]->locked= true; 
				$Row->Cells["Column2"]->Value = $mainArrayV['workflowName'];
				$Row->Cells["Column2"]->locked= true; 
				$Row->Cells["Column3"]->Value = $mainArrayV['US_description'];
				$Row->Cells["Column4"]->Value = urldecode($mainArrayV['US_workflowURL']);
				$Row->Cells["Column5"]->Value = ($mainArrayV['US_active'] == 'Y') ? 'YES' : 'NO';
				$Row->Cells["Column6"]->Value = $mainArrayV['MX_description'];
				$Row->Cells["Column7"]->Value = urldecode($mainArrayV['MX_workflowURL']);
				$Row->Cells["Column8"]->Value = ($mainArrayV['MX_active'] == 'Y') ? 'YES' : 'NO';
				$Row->Cells["Column9"]->Value = $mainArrayV['PH_description'];
				$Row->Cells["Column10"]->Value = urldecode($mainArrayV['PH_workflowURL']);
				$Row->Cells["Column11"]->Value = ($mainArrayV['PH_active'] == 'Y') ? 'YES' : 'NO';
				$Row->Cells["Column12"]->Value = $mainArrayV['CR_description'];
				$Row->Cells["Column13"]->Value = urldecode($mainArrayV['CR_workflowURL']);
				$Row->Cells["Column14"]->Value = ($mainArrayV['CR_active'] == 'Y') ? 'YES' : 'NO';
				$Row->Cells["Column15"]->Value = $mainArrayV['IN_description'];
				$Row->Cells["Column16"]->Value = urldecode($mainArrayV['IN_workflowURL']);
				$Row->Cells["Column17"]->Value = ($mainArrayV['IN_active'] == 'Y') ? 'YES' : 'NO';
				$Row->Cells["Column18"]->Value = $mainArrayV['longDescription'];
				
		}
		$footerInfo = $rnetAllObj->getTableGridFooterInfo($numRows);
		$Table->Display();
		echo $footerInfo;
		break;		
	
	case 'popuateWorkFlowIcons':
		
		$commonListBox->name = 'ddlWorkFlows';
		$commonListBox->id = 'ddlWorkFlows';
		if( isset($parameters[containerID]) && $parameters[containerID] != '')
		{
			$wfQuery = "SELECT * FROM rnet..ctlWorkflowicons (NOLOCK) where containerID = ".$parameters[containerID]." ORDER BY description ";
		}
		else
		{
			$wfQuery = "SELECT * FROM rnet..ctlWorkflowicons (NOLOCK) ORDER BY description";
		}
		$commonListBox->sqlQry = $wfQuery;
		$commonListBox->selectedItem = '';
		$commonListBox->optionKey = 'workflowIconID';
		$commonListBox->optionVal = 'description';
		$commonListBox->AddRow('', 'Please choose');
		$ddlWorkFlows = $commonListBox->display();
		$commonListBox->resetProperties();
		
		echo $ddlWorkFlows;
		break;
	case 'popuatePositions':
		$departmentId = '';
		if( isset($parameters[deptId]) && $parameters[deptId] != '')
		{
			$departmentId = $parameters[deptId];
		}
		$commonListBox->name = 'ddlPositions[]';
		$commonListBox->id = 'ddlPositions';
		$wfQuery = "EXEC Rnet.dbo.[report_spGetPositionList] '','','".$departmentId."','Y' ";
		$commonListBox->sqlQry = $wfQuery;
		$commonListBox->multiple = 'Multiple';
		$commonListBox->optionKey = 'positionID';
		$commonListBox->optionVal = 'position';
		$commonListBox->loader = true;
		$commonListBox->loaderID  = 'positionDiv';
		$commonListBox->AddRow('', 'Please choose');
		$ddlPositions = $commonListBox->display();
		$commonListBox->resetProperties();
		
		echo $ddlPositions;
		break;
	case 'addOrEditWorkFlow':
		//print_r($parameters);
		/*																															
		 ddlContainers=1
		 &ddlWorkFlows=1
		 &txtWorkFlowName=Deliverable+Manager+
		 &txtUsDescription=Deliverable+Manager
		 &txtUsWorkFlowURL=%2FDeliverableManager%2FDMmain.php		 
		 &chkUsActive=Y
		 &txtMxDescription=ree
		 &txtMxWorkFlowURL=
		 &chkMxActive=Y
		 &txtPhDescription=
		 &txtPhWorkFlowURL=
		 &chkPhActive=Y
		 &txtCrDescription=&
		 txtCrWorkFlowURL=
		 &chkCrActive=Y
		 &txtInDescription=
		 &txtInWorkFlowURL=
		 &chkInActive=Y
		 &hdnTask=9) ADD 
		 
		 						*/
		// echo $parameters[hdnTask];
		$usActive = 'Y';
		$mxActive = 'Y';
		$phActive = 'Y';
		$crActive = 'Y';
		$inActive = 'Y';
		
		if(!isset($parameters[chkUsActive]))
		{
			$usActive = 'N';
		}
		if(!isset($parameters[chkMxActive]))
		{
			$mxActive = 'N';
		}
		if(!isset($parameters[chkPhActive]))
		{
			$phActive = 'N';
		}
		if(!isset($parameters[chkCrActive]))
		{
			$crActive = 'N';
		}
		if(!isset($parameters[chkInActive]))
		{
			$inActive = 'N';
		}

		 if($parameters[hdnTask] == 'ADD')
		 {
			 //echo 'insert...';
			 $insertQuery = "insert into rnet.dbo.ctlWorkflows
								(workflowIconID,
								 workflowName,
								 US_description,
								 US_workflowURL,
								 US_active,
								 MX_description,
								 MX_workflowURL,
								 MX_active,
								 PH_description,
								 PH_workflowURL,
								 PH_active,
								 CR_description,
								 CR_workflowURL,
								 CR_active,
								 IN_description,
								 IN_workflowURL,
								 IN_active,
								 longDescription)
							values 
								(
								 ".$parameters[ddlWorkFlows].",
								 '".$parameters[txtWorkFlowName]."',
								 
								 '".$parameters[txtUsDescription]."',
								 '".$parameters[txtUsWorkFlowURL]."',
								 '".$usActive."',
								 
								 '".$parameters[txtMxDescription]."',
								 '".$parameters[txtMxWorkFlowURL]."',
								 '".$mxActive."',
								 
								 '".$parameters[txtPhDescription]."',
								 '".$parameters[txtPhWorkFlowURL]."',
								 '".$phActive."',
								 
								 '".$parameters[txtCrDescription]."',
								 '".$parameters[txtCrWorkFlowURL]."',
								 '".$crActive."',
								 
								 '".$parameters[txtInDescription]."',
								 '".$parameters[txtInWorkFlowURL]."',
								 '".$inActive."',
								 '".$parameters[txaLnDescription]."'
								 )";
								
				//echo $insertQuery;
				$isExecuted = $RDSObj->execute($insertQuery);
		 }
		 else
		 {
			// echo 'update';
			 $updateQuery = "update 
			 					 rnet.dbo.ctlWorkflows 
			 				set
								 workflowIconID = ".$parameters[ddlWorkFlows].",
								 workflowName = '".$parameters[txtWorkFlowName]."',								 
								 US_description = '".$parameters[txtUsDescription]."',
								 US_workflowURL = '".$parameters[txtUsWorkFlowURL]."',
								 US_active = '".$usActive."',								 
								 MX_description = '".$parameters[txtMxDescription]."',
								 MX_workflowURL = '".$parameters[txtMxWorkFlowURL]."',
								 MX_active = '".$mxActive."',								 
								 PH_description = '".$parameters[txtPhDescription]."',
								 PH_workflowURL = '".$parameters[txtPhWorkFlowURL]."',
								 PH_active = '".$phActive."',								 
								 CR_description = '".$parameters[txtCrDescription]."',
								 CR_workflowURL = '".$parameters[txtCrWorkFlowURL]."',
								 CR_active = '".$crActive."',								 
								 IN_description = '".$parameters[txtInDescription]."',
								 IN_workflowURL = '".$parameters[txtInWorkFlowURL]."',
								 IN_active = '".$inActive."',
								 longDescription = '".$parameters[txaLnDescription]."'
							where 
								workflowID = ".$parameters[hdnTask];
								
				//echo $updateQuery; 
				$isExecuted = $RDSObj->execute($updateQuery);
		 }
		 
		 echo ($isExecuted) ? 'true' : 'false';
		break;
	
	case 'assignPositionToWorkflow':
		//echo '<pre>'; print_r($_REQUEST); exit;
		//$commonListBox->name = 'ddlWorkFlows';
//		$commonListBox->id = 'ddlWorkFlows';
//		$wfQuery = " SELECT * FROM rnet..ctlWorkflowicons (NOLOCK)  order by [description] ";
//		$commonListBox->sqlQry = $wfQuery;
//		$commonListBox->selectedItem = $_REQUEST['workflowId'];
//		$commonListBox->optionKey = 'workflowIconID';
//		$commonListBox->optionVal = 'description';
//		$commonListBox->AddRow('', 'Please choose');
//		$ddlWorkFlows = $commonListBox->display();
//		$commonListBox->resetProperties();				
		
		$commonListBox->name	= 'ddlDepartment';
		$commonListBox->id 		= 'ddlDepartment';
		//$wfQuery = " SELECT distinct [departmentName] as department, departmentCode  FROM Results.dbo.ctlDepartments (NOLOCK) WHERE [departmentName] IS NOT NULL ORDER BY departmentName ";
		$wfQuery = "EXEC Rnet.dbo.[rnet_spGetActiveDepartments] ";
		$commonListBox->sqlQry 	= $wfQuery;
		$commonListBox->selectedItem = $mainArray[workflowIconID];
		$commonListBox->optionKey = 'departmentCode';
		$commonListBox->optionVal = 'department';
		$commonListBox->AddRow('', 'Please choose');
		$commonListBox->onChange = "return getPositions(this.value); return false;";
		$ddlDepartment = $commonListBox->display();
		$commonListBox->resetProperties();
		
		
		$commonListBox->name = 'ddlPositions[]';
		$commonListBox->id = 'ddlPositions';
		$wfQuery = "EXEC Rnet.dbo.[report_spGetPositionList] '','','','Y' ";
		$commonListBox->sqlQry = $wfQuery;
		$commonListBox->multiple = 'Multiple';
		$commonListBox->optionKey = 'positionID';
		$commonListBox->optionVal = 'position';
		$commonListBox->loader = true;
		$commonListBox->loaderID  = 'positionDiv';
		$ddlPositions = $commonListBox->display();
		$commonListBox->resetProperties();
		
		$htmlTextElement->name 	= 'hdnWorkflowId';
		$htmlTextElement->id	= 'hdnWorkflowId';
		$htmlTextElement->value = $_REQUEST['workflowId'];
		$htmlTextElement->type 	= 'hidden';
		$hdnWorkflowId 			= $htmlTextElement->renderHtml();
		$htmlTextElement->resetProperties();
		
		$htmlTextElement->name 	= 'hdnWorkflowIconId';
		$htmlTextElement->id	= 'hdnWorkflowIconId';
		$htmlTextElement->value = $_REQUEST['workflowIconID'];
		$htmlTextElement->type 	= 'hidden';
		$hdnWorkflowIconId 			= $htmlTextElement->renderHtml();
		$htmlTextElement->resetProperties();		
		
		$htmlCustomButtonElement 				= new HtmlCustomButtonElement('button');
		$htmlCustomButtonElement->id            = 'btnAddorEdit'; 
		$htmlCustomButtonElement->name          = 'btnAddorEdit';
		$htmlCustomButtonElement->value         = 'Search'; 
		$htmlCustomButtonElement->style         = 'float:left; margin-right: 5px;';
		//$htmlCustomButtonElement->onclick       = 'return submitData(this.value); return false;';
		$htmlCustomButtonElement->onclick       = 'return searchPositions(); return false;';
		$btnAddorEdit = $htmlCustomButtonElement->renderHtml();	
		$htmlCustomButtonElement->resetProperties();
		
		$htmlCustomButtonElement->id            = 'btnCancel'; 
		$htmlCustomButtonElement->name          = 'btnCancel'; 
		$htmlCustomButtonElement->value         = 'Cancel'; 
		$htmlCustomButtonElement->style         = 'float:left;'; 
		$htmlCustomButtonElement->type          = 'button'; 
		$htmlCustomButtonElement->onclick       = 'return closePopup(); return false;'; 
		$btnAddorEdit .= $htmlCustomButtonElement->renderHtml();	
		$htmlCustomButtonElement->resetProperties();
		
		
		
		//$lblddlWorkFlows	= $htmlTextElement->addLabel($ddlWorkFlows, 'WorkFlows:', '#ff0000',TRUE); 
		$lblddlDepartment	= $htmlTextElement->addLabel($ddlDepartment.$hdnWorkflowId.$hdnWorkflowIconId, 'Department:', '#ff0000',TRUE);
		$lblddlPositions	= $htmlTextElement->addLabel($ddlPositions, 'Positions:', '#ff0000',TRUE);
		$btnAddorEdit	= $htmlTextElement->addLabel($btnAddorEdit, '', '', FALSE);
		
		
		
		$tableObj->tableId = 'searchTable';
		$tableObj->tableClass = 'searchtab';
		$tableObj->maxCol = 3;
		
		//$tableObj->searchFields['lblddlWorkFlows'] = $lblddlWorkFlows; //ddlContainers
		$tableObj->searchFields['lblddlDepartment'] = $lblddlDepartment; 
		$tableObj->searchFields['lblddlPositions'] = $lblddlPositions; 
		$tableObj->searchFields['btnAddorEdit'] = $btnAddorEdit; 
		
		//echo $htmlTagObj->openTag('div', 'class="outer" id="emptyDiv"');
		//echo $htmlTagObj->closeTag('div');
		//echo $htmlTagObj->openTag('div', 'class="outer" id="emptyDiv"');
		//echo $htmlTagObj->closeTag('div');
		
		echo $htmlTagObj->openTag('div', 'id="formDiv" class="outerModal"');
		//$htmlForm->action = 'assignPositionstoWidgets_configure.php';
		$htmlForm->action = '#';
		$htmlForm->name = 'form_data';
		$htmlForm->id = 'searchPositionForm';
		
		$htmlForm->fieldSet = TRUE;
		echo $htmlForm->startForm();
		echo $htmlForm->addLegend('Assign Position(s) To Workflow');
		echo $tableObj->searchFormTableComponent();
		echo $htmlTagObj->openTag('div','align="center"');
		//echo '<br/>'.$btnAddorEdit.$btnCancel; //.addslashes($mainArray[businessFunction]);;
		echo $htmlTagObj->closeTag('div');
		
		echo $htmlTagObj->closeTag('fieldset');
		echo $htmlForm->endForm();
		
		echo $htmlTagObj->closeTag('div');
		
		echo $htmlTagObj->openTag('div', 'id="showContent" align="center" style="width : auto;"');
		echo $htmlTagObj->closeTag('div');

		break;
	case 'viewPositionsList':
		//print_r($_POST); exit;
		$ddlWorkFlows 		= 	'';
		$ddlDepartment 		= 	'';
		$hdnValues			=	'';
		$ddlPositions   	= '';
		if(isset($_REQUEST['hdnWorkflowIconId']))
		{
			$ddlWorkFlows = $_REQUEST['hdnWorkflowIconId'];		
		}
		if(isset($_REQUEST['hdnWorkflowId']))
		{
			$hdnWorkflowId = $_REQUEST['hdnWorkflowId'];		
		}
		if(isset($_REQUEST['ddlDepartment']))
		{
			$ddlDepartment = $_REQUEST['ddlDepartment'];		
		}
		if(isset($_REQUEST['ddlPositions']))
		{
			$ddlPositions = $_REQUEST['ddlPositions'];		
		}
		
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
							Rnet.dbo.ctlWorkFlows (nolock) where workFlowIconID = ".$ddlWorkFlows." and workflowID = ".$hdnWorkflowId."
						ORDER BY
							workFlowName ";
			//echo $sqlQuery; exit;
			
			$resultsSet = $RDSObj->execute($sqlQuery);
			$numRows = $RDSObj->getNumRows($resultsSet);
			if ($numRows >= 1)
			{
				while($spquey = mssql_fetch_assoc($resultsSet))
				{
					$workFlowArray[] = 	$spquey['workFlowID'].'||'.$spquey['workFlowName'];
					$workFlowIDString .= $spquey['workFlowID'].',';
				}
			
			}	
			$workFlowIDString = substr($workFlowIDString,0,-1);
		}
		
		$sqlQuery = " SELECT 
							US_active, 
							MX_active,
							PH_active,
							CR_active,
							IN_active
						FROM 
							Rnet.dbo.ctlWorkFlows (nolock) where workflowID = ".$hdnWorkflowId."
						ORDER BY
							workFlowName ";
			//echo $sqlQuery; exit;
			
		$resultsSet = $RDSObj->execute($sqlQuery);
		$numRows = $RDSObj->getNumRows($resultsSet);
		if ($numRows >= 1)
		{
			$countryStatusArray = $RDSObj->bindingInToArray($resultsSet);
		}	
		$nonActiveCountries = "'Dominican Republic','Panama'";
	
		if($countryStatusArray[0]['US_active'] != 'Y')
		{
			$nonActiveCountries .= ", 'United States of America'";
		}
		if($countryStatusArray[0]['MX_active'] != 'Y')
		{
			$nonActiveCountries .= ", 'Mexico'";
		}
		if($countryStatusArray[0]['PH_active'] != 'Y')
		{
			$nonActiveCountries .= ",'Philippines'";
		}
		if($countryStatusArray[0]['CR_active'] != 'Y')
		{
			$nonActiveCountries .= ",'Costa Rica'";
		}
		if($countryStatusArray[0]['IN_active'] != 'Y')
		{
			$nonActiveCountries .= ",'India'";
		}

		
		unset($sqlQuery);
		unset($resultsSet);
		unset($numRows);
		unset($positionArray);
		unset($positionIDString);
		
		//if(empty($_REQUEST['ddlPositions']))
		if($ddlPositions == '')
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
								b.departmentCode = '".$ddlDepartment."'  
						AND
								a.Active = 'Y' 
						ORDER BY [description] ";
			}
			else
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
								a.Active = 'Y' 
						ORDER BY [description] ";
			}
			
			$resultsSet = $RDSObj->execute($sqlQuery);
			$numRows = $RDSObj->getNumRows($resultsSet);
			if ($numRows >= 1)
			{
				while($spqueyPos = mssql_fetch_assoc($resultsSet))
				{
					$positionArray[] = 	$spqueyPos['positionID'].'||'.$spqueyPos['description'];
					$positionIDString .= $spqueyPos['positionID'].',';
				}
			}
			$positionIDString = substr($positionIDString,0,-1);
		
		}
		else
		{
			foreach($_REQUEST['ddlPositions'] as $poKey=>$poVal)
			{
				if($poVal!='')
				{
					$positionArray[] = 	$poVal.'||'.$PositionArray[$poVal];
					$positionIDString .= $poVal.',';	
				}
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
								workFlowID ,
								country
						FROM 
								Rnet.dbo.prmWorkFlowPositions (nolock) 
						WHERE 
								positionID IN (".$positionIDString.") 
							AND
								workFlowID IN (".$workFlowIDString.") ";
			
			$resultsSet = $RDSObj->execute($sqlQuery);
			$numRows = $RDSObj->getNumRows($resultsSet);
			if ($numRows >= 1)
			{
				while($spqueyPosEx = mssql_fetch_assoc($resultsSet))
				{
					if(!in_array($spqueyPosEx['positionID'].'||'.$spqueyPosEx['workFlowID'], $existingArray))
					{
						$existingArray[] = 	$spqueyPosEx['positionID'].'||'.$spqueyPosEx['workFlowID'];
					}
					if(!in_array($spqueyPosEx['country'], $existingCountryArray))
					{
						$existingCountryArray[$spqueyPosEx['positionID']][] = $spqueyPosEx['country'];
					}
				}
			}
		
		}
		
		//Country listing
		$sqlQuery = " SELECT DISTINCT country FROM results..ctlLocations (NOLOCK) WHERE country NOT IN (".$nonActiveCountries.") AND country IS NOT NULL";
		$resultsSet = $RDSObj->execute($sqlQuery);
		$numRows = $RDSObj->getNumRows($resultsSet);
		if ($numRows >= 1)
		{
			while($countries = mssql_fetch_assoc($resultsSet))
			{
				$countriesArr[$countries['country']] = $countries['country'];
			}
		}
		
		//print_r($positionArray); echo '==='; print_r($workFlowArray); exit;
		//echo '<pre>'; print_r($existingCountryArray);exit;
		unset($countPosition);
		$countPosition = count($positionArray);
		
		unset($countWorkFlow);
		$countWorkFlow = count($workFlowArray);			
		
		$btnSubmit = '<input type="button" name="submit" value="Save" onClick="return chkAtLeastOneSelect(); return false;" />';	
		
		//echo $htmlTagObj->openTag('div', 'class="outer" id="emptyDiv"');
		//echo $htmlTagObj->closeTag('div');
		//echo $htmlTagObj->openTag('div', 'class="outer" id="emptyDiv"');
		//echo $htmlTagObj->closeTag('div');
		
		$Table=new ReportTable();
		$Table->Width="98%";
		
		$htmlForm->action = 'assignPositionstoWidgets_process.php';
		$htmlForm->name = 'configureForm';
		$htmlForm->id = 'configureForm';
		echo $htmlForm->startForm();
		
		$Col=& $Table->AddColumn("Column0");
		$Col=& $Table->AddColumn("Column1");
		$Col=& $Table->AddColumn("Column2");
		
		$selectedPositions = '';
		
		foreach($positionArray AS $id => $val)
		{
			$positionData = explode('||', $val);
			$workflowData = explode('||', $workFlowArray[0]);
			
			$Row=& $Table->AddRow();			
			$Row->Cells["Column0"]->Value= $positionData[1];
			
			
			$htmlTextElement->type	= 'hidden';
			$htmlTextElement->name	= 'hdnVal[]';
			$htmlTextElement->value	= $positionData[0].'||'.$workflowData[0];
			$hdnBoxString =  $htmlTextElement->renderHtml();
			$htmlTextElement->resetProperties();
						
			$htmlTextElement->type	= 'checkbox';
			$htmlTextElement->name	= 'ckhBVox[]';
			$htmlTextElement->id	= 'ckhBVox'.$positionData[0].$workflowData[0];
			$htmlTextElement->value	= $positionData[0].'||'.$workflowData[0];
			$htmlTextElement->onClick	= 'enableCountryDDL('.$positionData[0].','.$workflowData[0].')';
			if(in_array($positionData[0].'||'.$workflowData[0], $existingArray))
			{
				$htmlTextElement->checked	= 'checked';
				$selectedPositions .= $positionData[0].'||'.$workflowData[0].'@@@';
			}
			$checkBoxString =  $htmlTextElement->renderHtml();
			$htmlTextElement->resetProperties();
			$Row->Cells["Column1"]->Value= $checkBoxString . $hdnBoxString;
			
			
			$commonListBox->name 			= 'workFlowCountry'.$positionData[0].'[]';
			$commonListBox->id 				= 'workFlowCountry'.$positionData[0];
			$commonListBox->customArray 	= $countriesArr;
			$commonListBox->selectedItem 	= $existingCountryArray[$positionData[0]];
			$commonListBox->multiple 		= 'multiple';
			$commonListBox->optionKey 		= 'country';
			if($existingCountryArray[$positionData[0]] == '')
			{
				$commonListBox->disabled	= 'disabled';
			}
			
			/*
			$commonListBox->optionVal 		= 'country';
			$ddlCountries		 			= $commonListBox->AddRow('', 'Please choose');
			$ddlCountries		 			= $commonListBox->convertArrayToDropDown();
			$commonListBox->resetProperties();
			$Row->Cells["Column2"]->Value= $ddlCountries;
			*/
			
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
		
		$htmlTextElement->type	= 'hidden';
		$htmlTextElement->name	= 'hdnSelectedPositions';
		$htmlTextElement->id	= 'hdnSelectedPositions';
		$htmlTextElement->value	= $selectedPositions;
		$hdnValues .= $htmlTextElement->renderHtml();
		$htmlTextElement->resetProperties();	
		
		echo $htmlTagObj->openTag('div', 'class="" id="emptyDiv"');
		echo $btnSubmit;
		echo $htmlTagObj->closeTag('div');
		
		
		echo $hdnValues;
		echo $htmlForm->endForm();
		

		
		
		
		
		
		
		
		
		
		break;
	case 'configurePositions':
		//echo '<pre>'; print_r($_REQUEST); exit;
		$ddlWorkFlows 		= 	'';
		$ddlDepartment 		= 	'';
		$hdnValues			=	'';
		if(isset($_REQUEST['hdnddlWorkFlows']))
		{
			$ddlWorkFlows = $_REQUEST['hdnddlWorkFlows'];		
		}				
		if(isset($_REQUEST['hdnddlDepartment']))
		{
			$ddlDepartment = $_REQUEST['hdnddlDepartment'];		
		}
		
		if(isset($_REQUEST['hdnSelectedPositions']))
		{
			$hdnSelectedPositions = $_REQUEST['hdnSelectedPositions'];		
		}
		
		
		//echo '<pre>'; print_r($workFlowCountry);exit;
		unset($allPositionString);
		unset($allWorkFlowString);
		//print_r($_REQUEST[hdnVal]); exit;
		foreach($_REQUEST[hdnVal] as $key=>$value)
		{
			unset($valueArray);
			$valueArray = explode('||' , $value);
			if(!empty($valueArray[0]))
			{
				$allPositionString .= trim($valueArray[0]).',';
			}
			
			if(!empty($valueArray[1]))
			{
				$allWorkFlowString .= trim($valueArray[1]).',';
			}
		}
		$allPositionString = substr($allPositionString,0,-1);
		$allWorkFlowString = substr($allWorkFlowString,0,-1);
		
			
		unset($sqlQuery);
		unset($resultsSet);
		
		//track workflow position change logs
		
		$existingArray   = array();
		$loggedPositions = array();
		if($hdnSelectedPositions != '')
		{
			$prevSelectedPositions = explode('@@@', $hdnSelectedPositions);	
			if(!empty($prevSelectedPositions) )
			{							
				foreach($prevSelectedPositions as $prevKey => $prevVal)
				{
					if(!empty($_REQUEST[ckhBVox]))
					{
						if(!in_array($prevVal, $_REQUEST[ckhBVox]))
						{
							$loggedPositions[] = $prevVal;
						}
					}
					else
					{
						$loggedPositions[] = $prevVal;
					}
				}
			}
		}
		
		if(!empty($loggedPositions))
		{
			foreach($loggedPositions as $key => $val)
			{
				if($val != '')
				{
					$positionWorkflowValues = explode('||', $val);
					$sqlQuery = "insert into rnet..logManageWorkflowPositions (workflowID, positionID, modifiedBy, modifiedDate) VALUES ('".$positionWorkflowValues[1]."', '".$positionWorkflowValues[0]."', '".$RDSObj->UserDetails->User."', getdate())";
					$RDSObj->execute($sqlQuery);
				}
			}
		}
		//end
		
		unset($sqlQuery);
		unset($resultsSet);
		
		$sqlQuery  = " DELETE 
							FROM Rnet.dbo.prmWorkFlowPositions 
					   WHERE 
							positionID IN (".$allPositionString.") 
						AND
							workFlowID IN (".$allWorkFlowString.") ";
				
		$resultsSet = $RDSObj->execute($sqlQuery);
		
		unset($sqlQuery);
		unset($resultsSet);
		
		foreach($_REQUEST[ckhBVox] as $keyEx=>$ValueEx)
		{
			unset($vArray);
			if(!empty($ValueEx))
			{								
				
				$vArray = explode('||' , $ValueEx);
				$countryVal = $_REQUEST['workFlowCountry' . $vArray[0]];
				if(!empty($countryVal))
				{
					foreach($countryVal AS $id => $val)
					{
						$sqlQuery .= " INSERT INTO Rnet.dbo.prmWorkFlowPositions
										VALUES (
													'".trim($vArray[1])."',
													'".trim($vArray[0])."',
													'".trim($val)."'
												)";
					}
				}
				else
				{
					//$vArray = explode('||' , $ValueEx);
					$sqlQuery .= " INSERT INTO Rnet.dbo.prmWorkFlowPositions
									VALUES (
												'".trim($vArray[1])."',
												'".trim($vArray[0])."',
												'".trim($val)."'
											)";
				}
			}
		}
		
		$resultsSet = $RDSObj->execute($sqlQuery);		
		echo $resultsSet;
		break;
	default:
		echo 'Default error: ';
}
		

?>