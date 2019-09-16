<?php
include_once($_SERVER['DOCUMENT_ROOT']."/agentScoreCard/includes/agentScoreCard.class.inc.php");
$agentScoreObj2 = new agentScoreCard();

$actionPlans = $agentScoreObj2->getActionPlans();

?>	
<br>	
<table border="0">
<tr>
<th style="text-align:left; background-color:#4F81BD; color:#FFF; font-weight:normal; font-size:12px;">Action Plan</th>
<td>
  <select name="ddlActionPlans" id="ddlActionPlans" onChange="return addActionPlan();">
      <option value="" selected="selected">Please Choose</option>
    <?
		foreach($actionPlans as $actionArrayK=>$actionArrayV)
		{
			//actionPlanID
			print "<option value='$actionArrayV[description]' ";		
			print "> $actionArrayV[description]</option>\n";
		}
		
	 
?>
</select>
</td>
</tr>

</table>


<script language="javascript" type="text/javascript">
function addActionPlan()
{
	if(document.getElementById('ddlActionPlans').value!= '')
	{
		
		//$('#txtActionPlan').val($("#ddlActionPlans :selected").text());
		var actionPlan = document.getElementById('ddlActionPlans').value;
		window.opener.document.getElementById('txtActionPlan').value = actionPlan;
		window.close()
		
		window.close()
		return(false)
		
	
	}
	
	
}
</script>

<?php $agentScoreObj2->closeConn();?>