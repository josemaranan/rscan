<?php

echo $htmlTagObj->openTag('div', 'class="push"');
echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->openTag('div', 'class="footer"');
echo $htmlTagObj->openTag('p', 'class=""');
//echo 'If you have any questions please <u>contact us.</u>  <br /> <u>Privacy Information</u> | <u>Legal Statement</u> | <u>Product Legal Disclaimers</u> | <u>Member Disclosure</u> <br /> &#169; 2014 '.$footerCompanyName.' All rights reserved.';

echo ' &#169; 2014 '.$footerCompanyName.' All rights reserved.';
echo $htmlTagObj->closeTag('p');
echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->closeTag('body');
echo $htmlTagObj->closeTag('html');

echo $htmlTagObj->openTag('div', ' id="mask" style="position:absolute; width:100%;"');
echo $htmlTagObj->closeTag('div');

?>