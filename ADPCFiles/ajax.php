<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/RNetIncludes/class1/common.config.inc.php');
$task = $_POST['task'];
include_once($_SERVER['DOCUMENT_ROOT'] . '/ADPCFiles/controller.php');
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