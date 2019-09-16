<?php
include_once($_SERVER['DOCUMENT_ROOT']."/Include/logADPPayrollChangesClass.inc.php");
$employeeeMaintenanceObj = new ADPPayroll();

$location = $_REQUEST['location'];
$prevState = $_REQUEST['prevState'];

$selectedState = $employeeeMaintenanceObj->getLocationBasedState($location);
echo '<input type="hidden" name="locationState" id="locationState" value="'.$selectedState.'">';
$employeeeMaintenanceObj->gethiddenValues('locationState', stripslashes($prevState) ,'results', 'ctlEmployees', 'location' , 'ctlEmployees#location');

?>