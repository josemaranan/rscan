<?php

//$employeeeMaintenanceObj->setUSLocations();
unset($sqlMainQry);
unset($rstMainQry);

$employeeeMaintenanceObj->setADPClients();
$clientsArray = $employeeeMaintenanceObj->getADPClients();


echo $htmlTagObj->openTag('div','id="topHeading" class="outer"');
echo $topLevelHeading;
echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->openTag('div','id="businessRuleHeading" class="outer"');
echo 'Upload Photo';
echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->openTag('div','class="outer" id="emptyDiv"');
echo $htmlTagObj->closeTag('div');

$employeeeMaintenanceObj->getTopLevelEmployeeInfo(); 

echo $htmlTagObj->openTag('div','id="singlePixelBorder" class="outer"');
echo $htmlTagObj->openTag('div','id="topHeading" class="outer"');
echo 'Upload Photo';
echo $htmlTagObj->closeTag('div');
echo $htmlTagObj->closeTag('div');

// form starts here

$htmlForm->action = 'empUploadPhoto_process_RDS.php';
$htmlForm->name = 'empUploadPhoto';
$htmlForm->id = 'searchForm';
$htmlForm->method = 'post';
$htmlForm->encType = 'multipart/form-data';
echo $htmlForm->startForm();

?>

<!-- <form action="empUploadPhoto_process.php" name="empUploadPhoto" id="searchForm" method="post" enctype="multipart/form-data"> -->

<?php
$fileHtml = '<input type="file" name="empPhoto" id="empPhoto">';  // (JPG or GIF or PNG Files only...)
$lblFile	= $htmlTextElement->addLabel($fileHtml, 'Upload Photo :', '#ff0000',true);
$lblInfo	= $htmlTextElement->addLabel('(JPG or GIF or PNG Files only)', '', '','');



$tableObj->tableId = 'adpsearchTable';
$tableObj->maxCol = 1;
$tableObj->border = 0;
$tableObj->cellSpacing = 2;
$tableObj->setTableClass('');
$tableObj->setTableAttr();

$htmlTextElement->type = 'hidden';
$htmlTextElement->name = 'hdnEmployeeID';
$htmlTextElement->id = 'hdnEmployeeID';
$htmlTextElement->value = $employeeID;
echo $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();

//button Search 
$htmlButtonElement->type = 'submit';
$htmlButtonElement->name = 'Submit';
$htmlButtonElement->value = 'Save';
$htmlButtonElement->Class = 'WSGInputButton';
$htmlButtonElement->style = 'text-align: center;';
$htmlButtonElement->onclick = 'return validateUploadPhoto();';
$htmlButtonElement->colspan = '2';
$btnSave = $htmlButtonElement->renderHtml();

 
$tableObj->searchFields['lblFile'] = $lblFile; 
$tableObj->searchFields['lblInfo'] = $lblInfo; // lblInfo
$tableObj->searchFields['btnSave'] = $btnSave;

echo $tableObj->searchFormTableComponent();
echo $htmlForm->endForm();


?>
