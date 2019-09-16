<?php
//ini_set('display_errors','1');
/**
 *@description : this file act as router between ajax class and php functions
 *@author : Vasudev
 *@since : 10/21/2013
 */
  include_once($_SERVER['DOCUMENT_ROOT'] . '/RNetIncludes/class1/common.config.inc.php');
  $RDSObj = new RDSData(MSSQL_HOST, MSSQL_USERNAME, MSSQL_PASSWORD, MSSQL_DB, MSSQL_TYPE, MSSQL_PORT, '');

  unset($bussinessFun);
  $bussinessFun = '';    
  
  if(isset($_REQUEST['task']))
  {
		$rnetTask = $_REQUEST['task'];
  }	  
  
  switch($rnetTask)
  {
		case 'loadDepartments':
				$bussinessFun 	= '%';
				if(isset($_POST['bussinessFun']) && $_POST['bussinessFun']!='')
				{
					$bussinessFun 	= $_POST['bussinessFun'];
				}
				$query				 		= " EXEC Rnet.dbo.[RNet_spGetDepartments] '".$bussinessFun."' ";
				
				$commonListBox->name 			= 'ddlDepartments';
				$commonListBox->id 				= 'ddlDepartments';
				$commonListBox->loader 			= TRUE;
				$commonListBox->loaderID 		= 'ddlDepartmentLoader';
				$commonListBox->sqlQry 			= $query;
				$commonListBox->selectedItem 	= '';
				$commonListBox->optionKey 		= 'departmentCode';
				$commonListBox->optionVal 		= 'Department';
				$commonListBox->onChange 		= "return populatePositionsByDepartment(this.value , 'ddlPositions' , 'ddlPositionsLoader' , 'loadPositions'); return false;";
				$departmentsDdl					= $commonListBox->AddRow('', 'Please choose');
				$departmentsDdl					= $commonListBox->display();
				$commonListBox->resetProperties();
				echo $departmentsDdl;

			 break;
		
		case 'loadPositions':
			$department		= '';
			$bussinessFun 	= '%';
			if(isset($_POST['department'])) 
			{
				$department 	= $_POST['department'];
			}
			if(isset($_POST['bussinessFun']) && $_POST['bussinessFun']!='') 
			{
				$bussinessFun 	= $_POST['bussinessFun'];
			}			
			$query				 			= "EXEC Rnet.dbo.[rnet_spGetPositions] '".$bussinessFun."', '%', '".$department."' ";
			
			$commonListBox->name 			= 'ddlPositions[]';
			$commonListBox->id 				= 'ddlPositions';
			$commonListBox->multiple		= 'multiple';
			$commonListBox->size			= '5';
			$commonListBox->loader 			= TRUE;
			$commonListBox->loaderID 		= 'ddlPositionsLoader';
			$commonListBox->sqlQry 			= $query;
			$commonListBox->selectedItem 	= '';
			$commonListBox->optionKey 		= 'positionID';
			$commonListBox->optionVal 		= 'position';
			$positionsDdl 					= $commonListBox->AddRow('', 'Please choose');
			$positionsDdl					= $commonListBox->display();
			$commonListBox->resetProperties();
			
			echo $positionsDdl;
			break;
		case 'positionsearch':
			//echo '<pre>'; print_r($_POST); exit;	
 		    include_once($_SERVER['DOCUMENT_ROOT'] . '/RNetIncludes/class1/ReportTable.inc.php');

			$businessFunction 	= $_POST['ddlbussinessFunction'];
			$department			= $_POST['ddlDepartments'];
			$positions			= '%';
			if(isset($_POST['ddlPositions']) && $_POST['ddlPositions']!='')
			{
				$positions = implode(',',$_POST['ddlPositions']);	
			}
			
			//$businessFunction 	= $department	= $positions	= '%';
			if($businessFunction=='')   $businessFunction = '%';

			$sqlQuery		 	= "EXEC Rnet.dbo.[report_spGetWorkflowsPositions] '".$businessFunction."','".$department."','".$positions."'";
				
			$resultsSet 		= $RDSObj->execute($sqlQuery);
			$numRows 			= $RDSObj->getNumRows($resultsSet);
			if ($numRows >= 1)
			{
				$mainArray = $RDSObj->bindingInToArray($resultsSet);
			}
			
			
			$Table=new ReportTable();
			$Table->Width="98%";
			
			$Col=& $Table->AddColumn("Column1");
			$Col=& $Table->AddColumn("Column2");
			$Col=& $Table->AddColumn("Column3");
		
			$Row=& $Table->AddHeader();
			$Row->Cells["Column1"]->Value="Workflow Name";
			$Row->Cells["Column2"]->Value="Description";
			$Row->Cells["Column3"]->Value="workflow URL";		
			
			$positionNext = '';
			foreach($mainArray as $mainArrayK=>$mainArrayV)
			{
				$position	= $mainArrayV['position'];
				if($positionNext!=$position)
				{
					$Row=& $Table->AddRow();
					$Row->Cells['Column1']->ColumnSpan  = 3;
					$Row->Cells['Column1']->Font->Bold  = 'bold';
					$Row->Cells['Column1']->Font->Color = '#ffffff';
					$Row->Cells["Column1"]->BackColor   = '#7AC143';
					$Row->Cells["Column1"]->Value 		= $position;
					$Row->Cells["Column2"]->style		= 'display:none';
					$Row->Cells["Column3"]->style		= 'display:none';
				}
				$positionNext = $position;
				
				//$mainArrayV['workflowName']
				//$view = $htmlTagObj->anchorTag('javascript:;', 'view', 'onclick="openDialog1(\'Workflow Position\',\'/admin/populateDivsUsingAjax.php?task=reportView\', \'80%\', \'600\', \'modalwindow=Y\')"');
				$view = $htmlTagObj->anchorTag('javascript:;', $mainArrayV['workflowName'], 'onclick="openDialog1(\'Workflow Position\',\'/admin/populateDivsUsingAjax.php?task=reportView\', \'80%\', \'600\', \'modalwindow=Y\')"');
				
				$Row=& $Table->AddRow();
				$Row->Cells["Column1"]->Value = $view;
				$Row->Cells["Column2"]->Value = $mainArrayV['description'];
				$Row->Cells["Column3"]->Value = $mainArrayV['US_workflowURL'];				
			}
			$footerInfo = $rnetAllObj->getTableGridFooterInfo($numRows);			
			$Table->Display();			
			echo $footerInfo;
			
			break;
		case 'reportView':
			echo 'Testing';
			break;
  }
  exit();
?>