<?php
include_once($_SERVER['DOCUMENT_ROOT']."/agentScoreCard/includes/agentScoreCard.class.inc.php");
$agentScoreObj = new agentScoreCard();
$width = '900';
$height = '500';
$title = '';

$width = $_REQUEST['dwidth'];
$height = $_REQUEST['dheight'];

$title = $_REQUEST['dtitle'];

?>
<style type="text/css">
.ColumnHeader1 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 13px;
	font-weight: bolder;
	color: #000;
	background-color:#FFF;
	padding: 4px;
}
</style>

<div id = "dialogTitle" style="background:#1266B1; color:#FFF; font-size:11px; font-weight:bold; padding:5px;" class="outer"><?php echo $title;?></div>

<div style="margin:0px; padding:0px; float:right; padding-right:10px;" id="testDiv" class="outer">
<a href="#" onclick="return closeMask(); return false;">Close</a>
</div>

<br clear="all" />
<div style="margin:0px; padding:0px; height:<?php echo $height;?>px; width:<?php echo $width;?>px; overflow-Y:auto; overflow-x:hidden">

<table width="100%" border="1" cellpadding="0" bgcolor="#FFFFFF" cellspacing="0" class="report table-autosort table-stripeclass:alternate"> 

<thead>
<tr>
    <th class="ColumnHeader1">LOB Name</th>
    <th class="ColumnHeader1">Training Type</th>
    <th class="ColumnHeader1">Training Hours</th>
    <th class="ColumnHeader1">Training <br />Start Date</th>
    <th class="ColumnHeader1">Training <br />End Date</th>
    <th class="ColumnHeader1">Training <br />Professional</th>
    <th class="ColumnHeader1">Training Notes</th>

    
</tr>
</thead>
<tbody>
      
    
    
</tbody>


</table>

</div>