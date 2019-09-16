<?php
/**
 *
 **/
include_once($_SERVER['DOCUMENT_ROOT'] . '/ASG/ASG/class/ParentListBox.php');

class CommonListBox extends ParentListBox
{
	public $User;
	public $optionKey;
	public $optionVal;
	public $name;
	public $size;
	public $multiple;
	public $customArray  = array(); // Convert to Key Value pairs in drop down
	

	public function __construct()
	{
		
		//$this->User=$User;
		parent::ParentListBox();
	}
	/**
	 *@description:This function used to get the location ,description for displaying locations list box
	 **/
	public function Display()
	{
		$dbObj = new ClassQuery();
		unset($sqlQry);
		unset($rstQry);
		unset($rowQry);
			
		//$sqlQry ='EXEC Rnet.dbo.[rnet_spGetLocations] "'.$this->spParam.'" ';
		//echo $this->sqlQry;
		$rstQry = $dbObj->ExecuteQuery($this->sqlQry);
		while($row=mssql_fetch_assoc($rstQry))
		{
			$this->AddRow($row[$this->optionKey], $row[$this->optionVal]);
		}
		return parent::Display();
	}
	
	public function convertArrayToDropDown()
	{
		
		foreach($this->customArray as $customArrayKey=>$customArrayVal)
		{
			$this->AddRow($customArrayKey, $customArrayVal);
		}
		return parent::Display();
		
	}
	public function resetProperties()
	{
			foreach($this as $key=>$value)
			{
				unset($this->$key);		
			}
	}
	
	
}

?>