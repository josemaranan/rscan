<?php

/**
 *@description:Include class files, CONST  which can be used globally, error log file, CSS ,JS files
 */
  	
	include_once($_SERVER['DOCUMENT_ROOT'] . '/ASG/ASG/class/HtmlTagElement.php');
	include_once($_SERVER['DOCUMENT_ROOT'] . '/ASG/ASG/class/CommonListBox.php');
	include_once($_SERVER['DOCUMENT_ROOT'] . '/ASG/ASG/class/HtmlCustomButtonElement.php');
	include_once($_SERVER['DOCUMENT_ROOT'] . '/ASG/ASG/class/HtmlTextElement.php');
		
	$htmlTagObj = new HtmlTagElement();
	$commonListBox = new CommonListBox();
	$htmlButtonElement = new HtmlCustomButtonElement('submit');
	$htmlTextElement = new HtmlTextElement();
		
/**
 * DEFINE CONST here
 * 1 = devlopement mode
 * 0 = production mode
 */
define('DEBUG_MODE', 0);
/**
 * error log file path
 */
ini_set('display_errors', 0);
/*
if(DEBUG_MODE === 1)
{
	ini_set('ERROR_REPORTING', E_ALL);
	ini_set('log_errors', 1);
	ini_set('error_log', ERROR_LOG_PATH);
} 
else 
{
	ini_set('ERROR_REPORTING', 0);
	ini_set('log_errors', 0);
	ini_set('error_log', 0);
}
*/
	
?>




