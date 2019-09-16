<?php

session_start();

include_once($_SERVER['DOCUMENT_ROOT'] . '/RNetIncludes/class1/common.config.inc.php');

include_once($_SERVER['DOCUMENT_ROOT'] . '/ADPCFiles/global.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/ADPCFiles/model.php');


class Controller extends GlobalVariable
{
	public $tabNames = array(
			1=>"View Missing Information",
			2=>"Generate ADPC FILE"//,3=>"View Historical Data Changes"	
	);	
	public $selectedTab;
	public $objTable;
	public $objTableRow;
	public $tabSerial;
	public $employeeID;
	
	public function __construct($selectedTab=NULL)
	{
		parent::__construct();
		$this->selectedTab = $selectedTab;
	}
	
	public function displayTabs()
	{	
		foreach($this->tabNames as $k => $v)
		{
			echo $this->htmlTagObj->openTag('div','class="eachDivLink" id="leftColumnDiv'.$k.'" onclick="loadPageContent('.$k.');"');
			echo $v;
			echo $this->htmlTagObj->closeTag('div');
		}
	}
	
	public function displaySearchForm()
	{
		include('searchFormTemplate.php');
		
	}
	
	public function setTablegridLayout()
	{
		echo $this->htmlTagObj->openTag('div', 'id="tableData" style="border:1px solid #7ac143;margin-top:1px;"');
		echo $this->htmlTagObj->closeTag('div');
		if($this->selectedTab == 2)
		{
			echo $this->htmlTagObj->openTag('div', 'id="buttonsSection" class="minusDiv" style="border:1px solid #7ac143;margin-top:1px;height:30px;padding-top:5px;"');
			echo $this->htmlTagObj->closeTag('div');
		}
		echo $this->htmlTagObj->openTag('div', 'id="notifySection" class="minusDiv" style="margin-top:1px;height:20px;padding:5px;"');
		echo $this->htmlTagObj->openTag('div', 'style="background-color:red;height:10px;width:15px;float:left;"');
		echo $this->htmlTagObj->closeTag('div');
		echo $this->htmlTagObj->openTag('div', 'style="float:left;margin-left:5px;"');
		echo 'Missing Information';
		echo $this->htmlTagObj->closeTag('div');
		echo $this->htmlTagObj->closeTag('div');
	}
	
	public function callWebservice($inputs)
	{
		$generatedBy = $_SESSION['FirstName'].' '.$_SESSION['LastName'];
		$isHistoricalDataModified = ($inputs['isHistoricalDataModified'] == 'N') ? 'N' : 'Y';
		
		$host = 'DEV';
		if($_SERVER['HTTP_HOST']=="rnetv3.resultstel.com")
		{
			$host = 'LIVE';
		}
		$employeeIDs = $inputs['employeeIDs'];
		$filesToGenerate = $inputs['type'];		
		$params = array( "host" => trim($host), "filesToGenerate" => trim($filesToGenerate), "employeeIDs" => trim($employeeIDs),
				"isHistoricalDataModified" => trim($isHistoricalDataModified), "generatedBy" => trim($generatedBy)
		);
		$option=array('trace'=>1);		
		//$client = new soapclient("http://10.102.64.172/GenerateADPCFile/generateADPCFile.asmx?WSDL",$option);
		$client = new soapclient("http://10.102.64.172/GenerateADPCFilefromrnet/GenerateADPCFile.asmx?WSDL",$option);
		$results = $client->start($params);
		$returnValue = $results->startResult;
		if(trim($returnValue) == 'success')
		{
			echo 'The ADPC file has been generated and sent to the employees below:<br/><br/>';
			$table = $this->getADPCEmailAddresses();
			echo $table;
		}
		else
		{
			echo $returnValue;
		}
		
	}
	
	public function getADPCEmailAddresses()
	{
		$objModel = new Model();
		$mainArray = $objModel->getADPCEmailAddresses();
		$returnString = '<table class="defaultTable" cellspacing=0><tr><th>First Name</th><th>Last Name</th><th>Email</th></tr>';
		foreach($mainArray as $idx => $row)
		{
			$returnString .= '<tr><td>'.$row['firstName'].'</td><td>'.$row['lastName'].'</td><td>'.$row['emailAddress'].'</td></tr>';
		}
		$returnString .= '</table>';
		return $returnString;
	}
	
	public function displayButtons($selectedTab)
	{
		$echoString = '';
		if($selectedTab == 2)
		{
			$onClick 	= 'onclick="submitData(\'NEWHIRES\');"';
			$echoString .= $this->setButtonElement($onClick,'Generate New Hires File');
			$onClick 	= 'onclick="submitData(\'CHANGES\');"';
			$echoString .= '&nbsp;'.$this->setButtonElement($onClick,'Generate Changes File');
			$onClick 	= 'onclick="submitData(\'BOTH\');"';
			$echoString .= '&nbsp;'.$this->setButtonElement($onClick,'Generate New Hires & Changes File ');
		}
		echo $echoString;
	}
	
	public function setTableHeaders($headers)
	{
		include_once($_SERVER['DOCUMENT_ROOT'] . '/RNetIncludes/class/ReportTable.inc.php');
		
		$this->objTable=new ReportTable();
		$this->objTable->Width="98%";
		
		for($i=0;$i<count($headers);$i++)
		{
			$Col=& $this->objTable->AddColumn("Column".$i);
		}		
		$this->objTableRow = & $this->objTable->AddHeader();
		$temp = 0;
		foreach($headers as $index => $colName)
		{
			if($temp <= 3)
			{
				$this->objTableRow->Cells["Column".$temp]->locked= true;
			}
			$this->objTableRow->Cells["Column".$temp]->Value = $colName;
			$temp++;
		}
	}
	
	public function setTableBody($mainArray,$headers)
	{
		foreach($mainArray as $mainArrayK=>$mainArrayV)
		{
			$this->objTableRow = & $this->objTable->AddRow();
			$temp = 0;
			foreach($headers as $index => $colName)
			{
				if($temp <= 3)
				{
					$this->objTableRow->Cells["Column".$temp]->locked= true;
				}
				if($index == 'checkBox')
				{
					$this->objTableRow->Cells["Column".$temp]->Value = 
					'<input type="checkbox" name="chkEmployees[]" fn="<tr><td>'.$mainArrayV['employeeID'].'</td><td>'.$mainArrayV['firstName'].' '.$mainArrayV['lastName'].'</td></tr>" isExistInADP="'.$mainArrayV['isExistInADP'].'" value="'.$mainArrayV['employeeID'].'" id="chk'.$mainArrayV['employeeID'].'" />';
				}
				else
				{
					$emptyCell = '';
					$cellValue = trim($mainArrayV[$index]);
					
					if($this->tabSerial == '2' || $this->tabSerial == '1')
					{
						$emptyCell = '<div class="missedColumn" eid="'.$mainArrayV['employeeID'].'" >&nbsp;</div>';
						if($index == 'SSN' && $cellValue != '')
						{
							$cellValue = 'XXXXXX'.substr($cellValue, -4);
						}
						if($index == 'basewage' && $cellValue != '')
						{
							$cellValue = 'XXXX';
						}
						
					}
					$value = ($cellValue == '') ? $emptyCell : $cellValue;
					$this->objTableRow->Cells["Column".$temp]->Value = $value;
				}
		
				$temp++;
			}
		}
	}
	
	public function displayTablegrid($inputs)
	{
		$this->tabSerial = $inputs['hdnTabID'];
		$objModel = new Model();
		$mainArray = $objModel->getEmployees($inputs);
		
		$this->headerObj->jsSource = $this->jsFilesAjax;
		$jsFiles = $this->headerObj->getJsSourceFiles();
		echo $jsFiles;
		
		$headers = $this->setTableColumns();
		$this->setTableHeaders($headers);
		$this->setTableBody($mainArray,$headers);
		$this->objTable->Display();		
		
	}
	
	public function setTextElement($name,$id,$value,$isHdn=NULL)
	{		
		$this->htmlTextElement->name 		= $name;
		$this->htmlTextElement->id 			= $id;
		$this->htmlTextElement->value 		= $value;
		if($isHdn == 'Y')
		{
			$this->htmlTextElement->type 	= 'hidden';
		}
		$returnString						= $this->htmlTextElement->renderHtml();
		$this->htmlTextElement->resetProperties();
		return $returnString;
	}
	
	public function setButtonElement($onclick,$btnValue)
	{
		$returnString = $this->htmlTagObj->openTag('button',$onclick);
		$returnString .= $btnValue;
		$returnString .= $this->htmlTagObj->closeTag('button');
		return $returnString;
	}
	
	public function setTableColumns()
	{
		$ifCheckbox = array('checkBox'=>'');
		$columns = array('employeeID'=>'EmployeeID','firstName'=>'First Name','lastName'=>'Last Name','street1'=>'Street 1','city'=>'City','state'=>'State','zip'=>'ZIP',
				'SSN'=>'SSN','ethnicGroup'=>'ethnicGroup','gender'=>'Gender','adpBusinessTitle'=>'ADP Business Title','dob'=>'DOB','hireDate'=>'Hire Date',
				'payrollLocationDescription'=>'Payroll Location','adpJobCode'=>'ADP Job Code','employmentStatus'=>'Status',
				'adpReportingLocation'=>'ADP Reporting Location','company'=>'Company','paygroup'=>'Pay Group','employeeType'=>'Employee Type','basewage'=>'Base Wage','isExistInADP'=>'Exists in ADP?', 'day1PresentDate'=>'Day 1 Present Date'
		);
		
		if($this->tabSerial == 3)
		{
			$headers = array('checkBox'=>'','employeeID'=>'EmployeeID','firstName'=>'First Name','lastName'=>'Last Name','location'=>'Location');
		}
		else
		{
			if($this->tabSerial == 2)
			{
				$headers = $ifCheckbox + $columns;
			}
			else 
			{
				$headers = $columns;
			}
			
			
		}
		return $headers;
	}
	
}



$task = $_POST['task'];
$objController = new Controller($task);

switch($task)
{
	case '1':
	case '2':
		$objController->displaySearchForm();
		$objController->setTablegridLayout();
		break;

	case '3':
		$objController->setTablegridLayout();
		break;

	case 'generateGrid':
		$objController->displayTablegrid($_POST);
		break;

	case 'generateButtons':
		$selectedTab = $_POST['selectedTab'];
		$objController->displayButtons($selectedTab);
		break;

	case 'submitData':
		$objController->callWebservice($_POST);
		break;

	case 'loadTabs':
		$objController->displayTabs();
		break;

	default:
		echo 'Default Error:';
		break;

}
?>