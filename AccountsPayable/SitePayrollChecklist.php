<?php
	//include config file  
	$pageTitle = "Location Payroll Checklist";

	include_once($_SERVER['DOCUMENT_ROOT'] . '/RNetIncludes/class1/includeClassFiles.php');
	
	$pageHyperlinks = array('Results Main Page'=>'Clients/Results/index.php');
	$headerObj->loadPageLinks($htmlTagObj , $pageHyperlinks);
	//  Main Content	
	echo $htmlTagObj->openTag('div', 'id="loadTable", style="margin-top:50px;"');	
	echo $htmlTagObj->closeTag('div');
	// end main content.
	
	// dialog box
	echo $htmlTagObj->openTag('div', 'id="showDialog" style="display:none;"');
	echo $htmlTagObj->openTag('div', 'id="showSubDialog"');
	echo $htmlTagObj->closeTag('div');	
	//include('OwnershipMarginConfiguration_Dialog.php');
	echo $htmlTagObj->closeTag('div');
	// end dialog box
	
	echo $htmlTagObj->closeTag('body');
	echo $htmlTagObj->closeTag('html');
?>
<script type="text/javascript">
		loader = "<img src='../../../Include/images/progress.gif' />";
		whrLoc = '';       
        $(document).ready(function ()
		{
				loadTable();
				
		});
		function loadTable()
		{	
			
			var pars = {task: 'loadContent'};
			
			$("#loadTable").html("<div align='center'>"+loader+"<br/>Please wait, Loading data...</div>");
			
			$.post(
				   "SitePayrollChecklist_Retrieve.php", 
				   pars, 
				   function(res)
				   {
					    //alert(res);
						$("#loadTable").html(res);
						$( "DIV.scrollingdatagrid " ).scroll(function(e) {										 
							 $("DIV.scrollingdatagrid TABLE THEAD TR").css({
							  top : $('DIV.scrollingdatagrid').scrollTop()
							});																	
						});
						
				   }
			);
			
		}
		function editPayRoll(loc,desc)
		{
			//alert(loc);
			
			whrLoc = loc;
			openDialog(desc);
			$("#showSubDialog").html("<div align='center'>"+loader+"<br/>Please wait, Loading data...</div>");
			$('#showSubDialog').load('SitePayrollChecklist_Dialog.php?location='+loc);
		}
		function openDialog(tit)
		{
			clearDialog();
			$('#showDialog').dialog({										   
				height:'auto',
				width:830,										   
				modal:true,
				position:'center top',
				title: tit+' Payroll:'
			});//.html('test data.'+task); 
			
		}
		
		function clearDialog()
		{
			$('#showDialog input[type="text"]').val('');
			$('#showDialog select').each(function()
			{
				$(this).val();
			});
            //$("#showDialog").find("input:radio").prop("checked", false).end().buttonset("refresh");
			$("#showSubDialog").html("");
		}
		function closeDialog()
		{
			$('#showDialog').dialog('close');
		}
</script>