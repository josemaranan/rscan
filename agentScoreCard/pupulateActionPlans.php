<?php

include_once($_SERVER['DOCUMENT_ROOT']."/agentScoreCard/includes/agentScoreCard.class.inc.php");
$agentScoreObj2 = new agentScoreCard();
$methodID = $_REQUEST[methodID];
$actionPlans = $agentScoreObj2->getActionPlans($methodID);




?>	
<br>	
<table border="0">
<tr>
<td width="20%" style="text-align:left">
Action Plan :
</td>
<td>
  <select name="ddlActionPlans" id="ddlActionPlans" onChange="return addActionPlan();">
      <option value="" selected="selected">Please Choose</option>
    <?
		foreach($actionPlans as $actionArrayK=>$actionArrayV)
		{
			print "<option value='$actionArrayV[actionPlanID]' ";		
			print "> $actionArrayV[description]</option>\n";
		}
		
	 
?>
</select>
</td>
</tr>

</table>
<?php $agentScoreObj2->closeConn();?>