<?php
//ini_set('display_errors','1');
include_once($_SERVER['DOCUMENT_ROOT']."/agentScoreCard/includes/agentScoreCard.class.inc.php");
$agentScoreObj = new agentScoreCard();

include_once($_SERVER['DOCUMENT_ROOT']."/Include/HTMLContent.class.inc.php");
$htmlObject = new HTMLClass();

include_once($_SERVER["DOCUMENT_ROOT"]."/Include/tempTableStructure.inc.php");




////////GETTING EXISTING ACTIVE SESSIOSN DETAILS///////////



////////GETTING EXISTING ACTIVE SESSIOSN DETAILS///////////









/* Step 2 */
// Load html header content.
$htmlObject->htmlMetaTagsTitle('Coaching');


$cssJsArray = array('CSS'=>array('readiNetAll.css','agentScore.css','modalwindowzindex.css'), 'JS'=>array('agentScoreCard/jquery.min.js', 'agentScoreCard/coaching_new.js', 'innerDynamicWH.js'));
$htmlObject->loadCSSJsFiles($cssJsArray);

/* Step 3 */
// Load body tag and left menu.
// Don't pass any thing if dont want left menu.

//$htmlObject->loadBodyTag('leftMenu','','style="bgcolor:red"');

$htmlObject->loadBodyTag('leftMenu');
$seconds = 5;

/* Step - 4 Load header part */
// Send object of DB class.
//$pageHyperlinks = array('Main Menu'=>'Clients/Results/index.php', 'Back'=>'agentScoreCard/viewMyScoreCard.php');
$pageHyperlinks = array('Main Menu'=>'Clients/Results/index.php', 'Back'=>'agentScoreCard/viewMyScoreCard.php');

$htmlObject->htmlHeadPart($agentScoreObj->UserDetails->User, $pageHyperlinks);

/* Variable declariations */
$loggedInemployeeID = $agentScoreObj->UserDetails->User;
$requestedDate = date('m/d/Y');
$todayDate = date('m/d/Y');
$yesterDayDate = date ('m/d/Y', strtotime('-1 day', strtotime($todayDate)));

?>
        
<form id="form1" name="form1" method="POST" action="coachLoginAuthentication.php" autocomplete="off">
<div id="report_content" > 
<table width="100%">
<tr>
<td>
<br /><br /><br />
</td>
</tr>
<td>
<td style="text-align:center">
<fieldset style="border:3px solid #000; width:25%;">
<legend class="test"><strong><font color="#104BD9">Coach Login</font></strong></legend>
<table  border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td class="leftSideCo">User Name</td>
    <td style="text-align:left;"><input name="account" type="text" id="account" size="20"  /></td>
  </tr>
  <tr>
    <td class="leftSideCo">Password</td>
    <td style="text-align:left;"><input name="password" type="password" id="password" size="21" /> </td>
  </tr>


  <tr>
    <td colspan="2" style="text-align:center;"><div id="sbtLogin"><input type="image" name="Submit" value="Submit" src="/Include/images/home-enter-button.png" onclick="return validate(); return false;"/></div></td>
  </tr>
</table>
</fieldset>
</td>
</tr>
</table>
</div>
</form>
<?
if($_REQUEST[results] == 'both')
{
?>	
	
    <script type="text/javascript">
        alert('Agent and coach should not be same');
        </script>
    
<?
}
?>

<script type="text/javascript" language="javascript">

function validate() 
{
		var mainFlag = true;
		
		
		if (document.form1.account.value == "") 
		{
			alert('User name is a required field.\n'); 
			mainFlag = false;
			document.form1.account.focus();
			return false; 
		}
		if (document.form1.password.value == "") 
		{ 
			alert('Password is a required field.\n');
			mainFlag = false;
			document.form1.password.focus();
			return false; 
		}
		
		if(mainFlag)
		{
			document.getElementById("sbtLogin").style.display = "none";
			//document.forms['form1'].submit();	
			return true;
		}
		else
		{
			document.getElementById("sbtLogin").style.display = "block";
			return false;
		}	
	
}

//makeItDynamic();
</script>
<?php $agentScoreObj->closeConn(); ?>