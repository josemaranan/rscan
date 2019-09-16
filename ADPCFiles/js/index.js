// JavaScript Document

makeItDynamic();

var host = getHostAddress();
var loader = "<img src='"+host+"/Include/images/progress.gif' />";
var reProcessingClient = '';

$("#plainImage").remove();
var currentTabID;
var autoTrigger = false;

$(function(){
	responsive();
	loadTabs();
	defaultHomeImage();	
	if($.urlParam('ID') != null && $.urlParam('ID') != 0)
	{
		//loadPageContent(2);
		//autoTrigger = true;
		currentTabID = 2;
		finalSubmission($.urlParam('ID'),'CHANGES');
	}
	
});


function loadTabs()
{
	loaderDialog('open');
	/*$.post(host+"/ADPCFiles/controller.php",{task : 'loadTabs'},function(data)
	{	
		$(".leftColumnDiv").html(data);
		loaderDialog('close');
	});*/
	$.ajax(
			{
				url: "/ADPCFiles/controller.php",
				data: {"task":"loadTabs"},
				method: "POST",
				success: function(result){
					$(".leftColumnDiv").html(result);
					loaderDialog('close');
				}
			});
}

$.urlParam = function(name){
    var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
    if (results==null)
    {
       return null;
    }
    else
    {
       return results[1] || 0;
    }
}

function defaultHomeImage()
{
	//$(".linkWiseDataDiv").html('<img src="images/homeHRIS.png" width="100%" height="100%"/>');
	
	$(".linkWiseDataDiv").html('<img src="images/adpc.png" />');
	$(".linkWiseDataDiv").addClass('linkWiseDataDivHome');
	responsive();
}
function responsive()
{
	var setH = ($(window).height()) - ( ($("#WIDE_LOGO").height()) + ($("#pageHeaderTitle").height()) + 50 );
	$(".mainThreeLayer").css({'height':setH+'px'});
	//innerResponsive();
}


function loadPageContent(tabId)
{
	//defaultHomeImage();
	loaderDialog('open');
	$(".leftColumnDiv .eachDivLink").removeClass('activeDivLink');
	$("#leftColumnDiv"+tabId).addClass('activeDivLink');
	
	currentTabID = tabId; 
	if($( ".linkWiseDataDiv" ).hasClass( "linkWiseDataDivHome" ))
	{
		$(".linkWiseDataDiv").removeClass('linkWiseDataDivHome');
	}
		
	$.post(host+"/ADPCFiles/controller.php",{task : tabId},function(data)
	{	
		$(".linkWiseDataDiv").html(data);
		innerResponsive();
		loaderDialog('close');
		if(tabId == 3)
		{
			generateGrid();
		}
		/*if(autoTrigger == true)
		{
			autoTrigger = false;
			$("#txtEmployeeID").val($.urlParam('ID'));
			generateGrid();
		}*/
	});

}

function innerResponsive()
{
	var getH = $(".linkWiseDataDiv").height();
	var removalH = 0;
	$(".minusDiv").each(function(){
		removalH = removalH + $(this).height();
	});
	var finalH = getH - removalH - 10;
	$("#tableData").css({'height':finalH+'px'});
}

function loadButtons()
{
	$.post("controller.php",{task:'generateButtons',selectedTab:currentTabID},function(data)
	{
		$("#buttonsSection").html(data);
	});
}

function submitData(type)
{
	
	var selectedEmployees = '';
	var restrictedEmployees = '';
	
	var count = $("input[name='chkEmployees[]']:checked").length;
	if(count == 0)
	{
		alert('Please select employee(s).');
		return false;
	}
	$("input[name='chkEmployees[]']:checked").each(function(){
		if(type == 'CHANGES')
		{
			if( $(this).attr('isExistInADP') != 'Y')
			{
				restrictedEmployees += this.fn;
			}
		}
		if(selectedEmployees != '')
		{
			selectedEmployees += ',';
		}
		selectedEmployees += this.value;	
	});
	
	var tempDivID = new Date().getTime();
	var tempDiv = $('body').append('<div id="'+tempDivID+'" style="display:none;"></div>');
	
	if(restrictedEmployees != '' )
	{
		var htmml = 'Below employee(s) were not existed in ADP and file will not generate for these employee(s).<br/>Do you want to continue?<br/><br/><table class="defaultTable" cellspacing=0><tr><th>Employee ID</th><th>Name</th></tr>'+restrictedEmployees+'</table>';
		
		$('#'+tempDivID).dialog({										   
			height	: 'auto',
			width	: 700,										   
			modal	: true,
			position: 'center center',
			title	: 'ADPC File Generation',
			buttons	: { "OK": function(){	$(this).dialog('close'); finalSubmission(selectedEmployees,type); }, "Cancel": function(){ $(this).dialog('close'); } }
		}).html(htmml);
		return false;
	}
	else//alert(selectedEmployees);
	{
		finalSubmission(selectedEmployees,type);
	}
	
}

function finalSubmission(selectedEmployees,type)
{
	var tempDivID = new Date().getTime();
	var tempDiv = $('body').append('<div id="'+tempDivID+'" style="display:none;"></div>');
	
	loaderDialog('open');
	$.post("controller.php",{task:'submitData',selectedTab:currentTabID,employeeIDs:selectedEmployees,type:type,isHistoricalDataModified:'N'},function(data)
	{
		loaderDialog('close');
		//alert(data);
		$('#'+tempDivID).dialog({										   
			height	: 'auto',
			width	: 500,										   
			modal	: true,
			position: 'center center',
			title	: 'ADPC File Generation'
		}).html(data);
	});
}

function generateGrid()
{
	//innerResponsive();
	
	if(currentTabID == 3)
	{
		var inputs = {task:'generateGrid',hdnTabID:currentTabID};
	}
	else
	{
		var isInput = 0;
		$('#adpcSearchForm input[type="text"]').each(function() {
		     if($.trim(this.value).length) { // zero-length string AFTER a trim
		    	 isInput++;
		     }
		});
		if ( $('#ddlLocations option:selected').val() != '') 
		{
			isInput++; //alert(isInput);
		}
	
		if(isInput == 0)
		{
			alert('To serach data atleast one element data required.');
			return false;
		}
		var inputs = $("#adpcSearchForm").serialize()+'&task=generateGrid';
	}
	
	loaderDialog('open');
	$.post("controller.php",inputs,function(data)
	{
		$("#tableData").html(data);
		
		setTimeout(function(){
			var reqHeight = $("#tableData").height();
			$(".scrollingdatagrid").css({'width':'100%','height':reqHeight+'px'});
			$( "#tableData DIV.scrollingdatagrid " ).scroll(function(e) 
			{	
				$("#tableData DIV.scrollingdatagrid TABLE THEAD TR").css({
					top : $('DIV.scrollingdatagrid').scrollTop()
				});	
				
				$("#tableData DIV.scrollingdatagrid TABLE, #tableData DIV.scrollingdatagrid TBODY .locked").css({
                    left : $('DIV.scrollingdatagrid').scrollLeft()
                });
				
				$("#tableData DIV.scrollingdatagrid TABLE .locked, #tableData DIV.scrollingdatagrid TFOOT .locked").css({
                    left : $('DIV.scrollingdatagrid').scrollLeft()
                });
			});
			if(currentTabID == 2)
			{
				loadButtons();
				if( $(".missedColumn").length > 0 )
				{
					$(".missedColumn").each(function(){
						var getEmpId = $(this).attr('eid');
						$("#chk"+getEmpId).attr('disabled','disabled');
					});
				}
				
			}
			loaderDialog('close');			
		},3000);
		
	});
}


function exportLogs()
{
	window.location.href = host+'/ExportToExcel/exportToExcel_Logs.php';
}


function loadSubTables(task,repId)
{
	$("#"+repId).html(loader);
	var cal = selectedLinks['calendar'];
	
	$.post("controller.php",{task : task,date : cal},function(data)
	{
		$("#"+repId).html(data);
	});
}

function setHeightMainContent(tabId)
{
	var totH = $(".middleColumnDiv").height();
	if(tabId == 2 || tabId == 3 || tabId == 4 || tabId == 5)
	{
		totH = totH - ($(".sliderLocationDiv").height() + 15);
	}
	else
	{
		totH = totH - 15;
	}
	$(".linkWiseDataDiv").css({'height':totH+'px'});
}

			
$(window).resize(function()
{
	responsive(); 
});


function loaderDialog(task)
{
	if(task == 'close')
	{
		$('#loaderDialog').dialog('destroy');
	}
	else if(task == 'open')
	{
		$('#loaderDialog').dialog({										   
			height: '80',
			width:'50',
			modal:true,
			my: "center",
			 at: "center",
			 of: window,
			open: function(event,ui)
			{
				$(this).dialog("widget").find(".ui-dialog-titlebar").hide();
				$(this).dialog("widget").find(".ui-dialog-content").css({"min-height":"80px","min-width":"50px"});
			}
		});
	}
}




/* ------- table grid ----------------- */

function generate(height , documentwidth , requiredWidth , eachDivWidth, tableData)
{
	//alert('generate'+tableData);

	var data = '';
	var obj = '';
	//eachDivWidth = eachDivWidth - 3;
	//alert(height + '---' + documentwidth + '---' + requiredWidth + '---' + eachDivWidth);
	if(requiredWidth>documentwidth)
	{
			var divWidth = documentwidth;
	}
	else
	{
			var divWidth = 	requiredWidth;
	}

    data = eval(tableData);
	//alert("length:"+data.length);
	//alert(divWidth);

	obj = { width: divWidth , height: height, flexHeight: false , resizable:false,draggable:false,topVisible : false   };
	
	obj.colModel = [];
	$i = 0;
	$.each(data.columns, function(key, value){
		columnGener = {title: value.colName, width: eachDivWidth, dataType: "string", dataIndx: value.index};
		obj.colModel.push(columnGener);
	});
	
	obj.dataModel = {
        data: data.data,
        location: "local",
        sorting: "local",
        paging: "local",
        curPage: 1,
        rPP: 100,
       // sortIndx: "edit",
        sortDir: "up",
        rPPOptions: [1, 10, 20, 30, 40, 50, 100, 500, 1000]
    };

	if($("#finalTableGridDiv").hasClass( 'pq-grid'))                         //To fix the multiple load without refresh fix
	{
		$( "#finalTableGridDiv" ).pqGrid( "destroy" );
	}

    $("#finalTableGridDiv").pqGrid(obj);
	$("#finalTableGridDiv").pqGrid( "option", "numberCell", false );
	//$("#report_content1").pqGrid( "option", "freezeCols", 2 );
	$("#finalTableGridDiv").pqGrid( {editable:false} );
		
	if(requiredWidth>documentwidth)
	{
		
		var balanceWidth = parseInt(requiredWidth)-parseInt(documentwidth);
		var nextDivs = Math.floor(balanceWidth/eachDivWidth);
		var num_eles = nextDivs+2; // freeze how many
		
		$("#finalTableGridDiv").pqGrid( "option", "scrollModel", {horizontal: true} );
		$("div.pq-scrollbar-horiz").pqScrollBar( "option", "num_eles", num_eles );
	}
	else
	{
		
		$("#finalTableGridDiv").pqGrid( "option", "scrollModel", {horizontal: false} );	
	}
	
	$(".pq-sb-slider").css({'left':'0px'});
	$(".pq-header-outer").css({'left':'8px'});
	

}

function checkWidth(adjustValue, tableData) 
{

	var documentHeight = $(".linkWiseDataDiv").height(); //document.documentElement.clientHeight;  //($(".linkWiseDataDiv").height());
	var documentwidth = $(".linkWiseDataDiv").width(); //document.documentElement.clientWidth;  // ($(".linkWiseDataDiv").width()); 
	var colLen = tableData.columns.length;
	var numCols = colLen;
	var eachDivWidth = 100;
	var requiredWidth = parseInt(numCols)*parseInt(eachDivWidth);
	
	documentwidth = documentwidth-20;
	
	var allDivHeight = 0;
	$(".linkWiseDataDiv .tempCls").each(function(){
				var curOuterHeight = $(this).height();
				//alert(curOuterHeight);
				allDivHeight = allDivHeight + curOuterHeight;
	});
	allDivHeight = allDivHeight+20; // Top section heading as per new layout.
	var requiredheight = parseInt(documentHeight)-parseInt(allDivHeight);
	var requiredheightFinal = requiredheight;

	if(documentwidth>requiredWidth)
	{
		var scrollBarWidth = 17;
		var scrollAdjust = parseFloat(scrollBarWidth)/numCols;
		var eachDivWidth = 	(parseFloat(documentwidth)/numCols)-scrollAdjust;
		var requiredWidth = documentwidth;
	}
	
	generate(requiredheightFinal , documentwidth , requiredWidth , eachDivWidth, tableData);

}
