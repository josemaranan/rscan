<?php

class Model extends RDSData
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function getEmployees($inputs)
	{
		// Array(    [ddlLocations] => 357    [hdnTabID] => 1[txtEmployeeID] => 2134   [txtFirstName] =>     [txtLastName] =>     [task] => generateGrid)
		$txtEmployeeID = ($inputs['txtEmployeeID'] != '') ? $inputs['txtEmployeeID'] : '%';
		$ddlLocations = ($inputs['ddlLocations'] != '') ? $inputs['ddlLocations'] : '%';
		
		if($inputs['hdnTabID'] == 3)
		{
			$query = $this->execute("select TOP(100) * from results..ctlEmployees (nolock)");
			$mainArray = $this->bindingInToArray($query);
			return $mainArray;
			exit;
		}
		$query = $this->execute("EXEC Rnet.dbo.[rnet_spGetMissingADPInformation] '".$txtEmployeeID."','".$ddlLocations."'");
		$mainArray = $this->bindingInToArray($query);
		return $mainArray;
	}
	
	public function getADPCEmailAddresses()
	{
		
		$query = $this->execute("SELECT 
									a.firstName,
									a.lastName,
									a.emailAddress
								FROM
									Results.dbo.ctlEmployees a WITH (NOLOCK)
								JOIN
									RNet.dbo.ctlADPCEmailAddresses b WITH (NOLOCK)
								ON
									a.employeeID = b.employeeID
								WHERE
									a.emailAddress IS NOT NULL");
		$mainArray = $this->bindingInToArray($query);
		return $mainArray;
	}
	
}

?>