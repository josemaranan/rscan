<?php
 
$pageTitle = "ASG - Contact Information";

include 'header.php';

echo $htmlTagObj->openTag('div', 'class="headDiv"');
echo $htmlTagObj->openTag('div', 'class="bodyDiv"');
echo $htmlTagObj->openTag('div', 'class="bodyDivBlue"');
echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->openTag('div', 'class="logoDiv"');
echo $htmlTagObj->imgTag($companyLogo, 'align="center"');
echo $htmlTagObj->closeTag('div');
echo $htmlTagObj->closeTag('div');
echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->openTag('div', 'class="emptyDiv" style="height:30px"');
echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->openTag('div', 'class="contentDiv"');
echo $htmlTagObj->openTag('p', 'class="pageHead"');
echo 'Contact Information';
echo $htmlTagObj->closeTag('p');

echo $htmlTagObj->openTag('div', 'class="emptyDiv" style="height:10px"');
echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->openTag('p', 'class="contentBlueHead"');
echo 'If users have any questions or suggestions regarding our terms and conditions, please contact us at <u><a href="mailto:bsergeant@asgyes.com" class="footerLink">bsergeant@asgyes.com</a>.</u>';
echo $htmlTagObj->closeTag('p');

echo $htmlTagObj->openTag('div', 'class="emptyDiv" style="height:30px"');
echo $htmlTagObj->closeTag('div');
//*/

echo $htmlTagObj->closeTag('div');

include 'footer.php';
?>