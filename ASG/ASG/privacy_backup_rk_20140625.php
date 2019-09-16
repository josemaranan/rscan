<?php
 
$pageTitle = "ASG - PRIVACY POLICY ";

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
echo 'PRIVACY POLICY';
echo $htmlTagObj->closeTag('p');

echo $htmlTagObj->openTag('div', 'class="emptyDiv" style="height:10px"');
echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->openTag('p', 'class="contentHead"');
echo 'Arch Solutions Group (LLC), Arch Solutions Insurance Agency (LLC), and its subsidiaries and affiliates (each an "Arch Company", "Arch Companies", "we" or "us") is committed to protecting the privacy of the individuals we encounter in conducting our business.  "Personal Information" is information that identifies and relates to you or other individuals (such as your dependents).  This Privacy Policy describes how we handle Personal Information that we collect through: ';
echo $htmlTagObj->closeTag('p');

echo $htmlTagObj->openTag('div', 'class="emptyDiv" style="height:20px"');
echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->openTag('ul', 'class="contentHead"');

	echo $htmlTagObj->openTag('li', 'class="contentHead"');
	echo 'this website ("the Site") by means of the software applications made available by us for use on or through computers and mobile devices (the "Apps"), through our social media pages and related Apps; and';
	echo $htmlTagObj->closeTag('li');
	
	echo $htmlTagObj->openTag('li', 'class="contentHead"');
	echo 'other means (for example, from your application and claim forms, telephone calls, e-mails and other communications with us, as well as from medical professionals, witnesses or other third parties involved in our business dealings with you).';
	echo $htmlTagObj->closeTag('li');

echo $htmlTagObj->closeTag('ul');

echo $htmlTagObj->openTag('div', 'class="emptyDiv" style="height:20px"');
echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->openTag('p', 'class="contentHead"');
echo 'By using the Arch site, you signify your acceptance of this Privacy Policy.';
echo $htmlTagObj->closeTag('p');

echo $htmlTagObj->openTag('div', 'class="emptyDiv" style="height:20px"');
echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->openTag('p', 'class="contentHead"');
echo 'Please note: This Privacy Policy is supplemented by Privacy Notices tailored to our specific relationships with you, including Privacy Notices that are sent to individuals as required under applicable laws and regulations.';
echo $htmlTagObj->closeTag('p');

echo $htmlTagObj->openTag('div', 'class="emptyDiv" style="height:20px"');
echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->openTag('ol', 'class="olMainHead" type="1"');

	echo $htmlTagObj->openTag('li', '');
	echo '<p class="contentNormalBlueHead">Who To Contact About Your Personal Information</p>';
	echo '<p class="contentText">If you have any questions about our use of your Personal Information you can e-mail bsergeant@asgyes.com.</p>';
	echo $htmlTagObj->closeTag('li');
	
	echo $htmlTagObj->openTag('li', '');
	echo '<span class="contentNormalBlueHead">Personal Information That We Collect</span>';
	echo '<p class="contentText">Depending on your relationship with us (for example, as a consumer policyholder; insured person benefiting under another policyholder\'s policy, or claimant; witness; commercial broker or appointed representative; or other person relating to our business), Personal Information collected about you and your dependents may include: </p><br />';
	echo $htmlTagObj->openTag('ul', 'type="disc" class="ulMainHead"');
		echo $htmlTagObj->openTag('li', '');
		echo '<span class="contentHead">General identification and contact information</span><br />';
		echo '<span class="contentText">Your name; address; e-mail and telephone details; gender; marital status; family status; date of birth; passwords on our systems; educational background; physical attributes; activity records, such as driving records; photos; employment history, skills and experience; professional licenses and affiliations; relationship to the policyholder, insured or claimant; and date and cause of death, injury or disability.</span>';
		echo $htmlTagObj->closeTag('li');
		
		echo $htmlTagObj->openTag('li', '');
		echo '<span class="contentHead">Identification numbers issued by government bodies or agencies </span><br />';
		echo '<span class="contentText">Social Security or national insurance number; passport number; tax identification number; military identification number; or driver\'s or other license number.</span>';
		echo $htmlTagObj->closeTag('li');
		
		echo $htmlTagObj->openTag('li', '');
		echo '<span class="contentHead">Medical condition and health status</span><br />';
		echo '<span class="contentText">Current or former physical or mental or medical condition; health status; injury or disability information; medical procedures performed; personal habits (for example, smoking or consumption of alcohol); prescription information; and medical history.</span>';
		echo $htmlTagObj->closeTag('li');
		
		echo $htmlTagObj->openTag('li', '');
		echo '<span class="contentHead">Other sensitive information</span><br />';
		echo '<span class="contentText">In certain cases, we may receive sensitive information about you for example, if you apply for insurance through a third party marketing partner that is a professional, trade, political, religious or community organization.  In addition, we may obtain information about your criminal record or civil litigation history in the process of preventing, detecting and investigating fraud.  We may also obtain sensitive information if you voluntarily provide it to us (for example, if you express preferences regarding medical treatment based on your religious beliefs).</span>';
		echo $htmlTagObj->closeTag('li');
		
		echo $htmlTagObj->openTag('li', '');
		echo '<span class="contentHead">Telephone recordings</span><br />';
		echo '<span class="contentText">Recordings of telephone calls to our representatives and call centers.</span>';
		echo $htmlTagObj->closeTag('li');
		
		echo $htmlTagObj->openTag('li', '');
		echo '<span class="contentHead">Information to investigate or prevent crime, including fraud and money laundering </span><br />';
		echo '<span class="contentText">For example, insurers commonly share information about their previous dealings with policyholders and claimants for this purpose.</span>';
		echo $htmlTagObj->closeTag('li');
		
		echo $htmlTagObj->openTag('li', '');
		echo '<span class="contentHead">Information enabling us to provide products and services</span><br />';
		echo '<span class="contentText">Location and identification of property insured (for example, property address, vehicle license plate or identification number); travel plans; age categories of individuals you wish to insure; policy and claim numbers; coverage/peril details; cause of loss; prior accident or loss history; your status as director or partner, or other ownership or management interest in an organization; and other insurance you hold.</span>';
		echo $htmlTagObj->closeTag('li');
		
		echo $htmlTagObj->openTag('li', '');
		echo '<span class="contentHead">Social media account and information from Apps</span><br />';
		echo '<span class="contentText">We may receive certain Personal Information about you when you use our Apps or Social Media Pages, including your social media account ID and profile picture.  If you elect to connect any of your other social media accounts to your account(s) on the Arch site, Personal Information from your other social media account(s) will be shared with us, which may include Personal Information that is part of you profile relating to those accounts or your friends\' profiles.</span>';
		echo $htmlTagObj->closeTag('li');
		
	echo $htmlTagObj->closeTag('ul');
	echo $htmlTagObj->closeTag('li');
	
	echo $htmlTagObj->openTag('li', '');
	echo '<p class="contentNormalBlueHead">How We Use Personal Information </p>';
	echo '<p class="contentHead">We use this Personal Information to: </p>';
	echo $htmlTagObj->openTag('ul', 'type="disc" class="ulMainHead"');
		echo $htmlTagObj->openTag('li', '');
		echo '<span class="contentText">Communicate with you and others as part of our business.</span>';
		echo $htmlTagObj->closeTag('li');
		
		echo $htmlTagObj->openTag('li', '');
		echo '<span class="contentText">Send you important information regarding changes to our policies, other terms and conditions, our ARCH services and other administrative information.</span>';
		echo $htmlTagObj->closeTag('li');
		
		echo $htmlTagObj->openTag('li', '');
		echo '<span class="contentText">Make decisions about whether to provide insurance and assistance services, and other products and services which we offer, and provide such products and services, including claim assessment, processing and settlement; and, where applicable, manage claim disputes.</span>';
		echo $htmlTagObj->closeTag('li');
		
		echo $htmlTagObj->openTag('li', '');
		echo '<span class="contentText">Provide improved quality, training and security (for example, with respect to recorded or monitored phone calls to our contact numbers).</span>';
		echo $htmlTagObj->closeTag('li');
		
		echo $htmlTagObj->openTag('li', '');
		echo '<span class="contentText">Carry out market research and analysis, including satisfaction surveys.</span>';
		echo $htmlTagObj->closeTag('li');
		
		echo $htmlTagObj->openTag('li', '');
		echo '<span class="contentText">Provide marketing information to you (including information about other products and services offered by selected third-party partners) in accordance with preferences you have expressed.</span>';
		echo $htmlTagObj->closeTag('li');
		
		echo $htmlTagObj->openTag('li', '');
		echo '<span class="contentText">Personalize your experience when using our Arch services by presenting information and advertisements tailored to you.</span>';
		echo $htmlTagObj->closeTag('li');
		
		echo $htmlTagObj->openTag('li', '');
		echo '<span class="contentText">Identify you to anyone to whom you send messages through any of the Arch services.</span>';
		echo $htmlTagObj->closeTag('li');
		
		echo $htmlTagObj->openTag('li', '');
		echo '<span class="contentText">Allow you to participate in contests, prize draws and similar promotions, and to administer these activities. Some of these activities have additional terms and conditions, which could contain additional information about how we use and disclose your Personal Information, so we suggest that you read these carefully.</span>';
		echo $htmlTagObj->closeTag('li');
		
		echo $htmlTagObj->openTag('li', '');
		echo '<span class="contentText">Facilitate social sharing functionality.</span>';
		echo $htmlTagObj->closeTag('li');
		
		echo $htmlTagObj->openTag('li', '');
		echo '<span class="contentText">Manage our infrastructure and business operations, and comply with internal policies and procedures, including those relating to auditing; finance and accounting; billing and collections; IT systems; data and website hosting; business continuity; and records, document and print management.</span>';
		echo $htmlTagObj->closeTag('li');
		
		echo $htmlTagObj->openTag('li', '');
		echo '<span class="contentText">Resolve complaints, and handle requests for data access or correction.</span>';
		echo $htmlTagObj->closeTag('li');
		
		echo $htmlTagObj->openTag('li', '');
		echo '<span class="contentText">Comply with applicable laws and regulatory obligations (including laws outside your country of residence), such as those relating to anti-money laundering, sanctions and anti-terrorism; comply with legal process; and respond to requests from public and governmental authorities (including those outside your country of residence).</span>';
		echo $htmlTagObj->closeTag('li');
		
		echo $htmlTagObj->openTag('li', '');
		echo '<span class="contentText">Establish and defend legal rights; protect our operations or those of any of our group companies or business partners, our rights, privacy, safety or property, and/or that of our group companies, you or others; and pursue available remedies or limit our damages.</span>';
		echo $htmlTagObj->closeTag('li');
		
	echo $htmlTagObj->closeTag('ul');
	echo $htmlTagObj->closeTag('li');
	
	echo $htmlTagObj->openTag('li', '');
	echo '<p class="contentNormalBlueHead">International Transfer of Personal Information </p>';
	echo '<p class="contentText">Due to the global nature of our business, for the purposes set out above we may transfer Personal Information to parties located in other countries.  For example, we may transfer Personal Information in order to process international travel insurance claims and provide emergency medical assistance services when you are abroad. We may transfer information internationally to our group companies, service providers, business partners and governmental or public authorities.  By providing Personal Information and other information through the Arch services, and/or by sending a communication to an "Arch.com" e-mail address, you understand and consent to the collection, use, processing disclosure and transfer of such information in the United States and other countries or territories, which may not offer the same level of data protection as the country where you reside, in accordance with the terms of this Privacy Policy. Further, please note that any communication you send to an "Arch.com" e-mail address will be routed through the United States.</p><br />';
	echo $htmlTagObj->closeTag('li');
	
	echo $htmlTagObj->openTag('li', '');
	echo '<p class="contentNormalBlueHead">Sharing of Personal Information </p>';
	echo '<p class="contentText">Arch may make Personal Information available to: </p><br />';
	echo $htmlTagObj->openTag('ul', 'type="disc" class="ulMainHead"');
		echo $htmlTagObj->openTag('li', '');
		echo '<span class="contentHead">Our group companies</span><br />';
		echo '<span class="contentText">Other Arch group companies may have access to and use of Personal Information in connection with the conduct of our business where appropriate.</span>';
		echo $htmlTagObj->closeTag('li');
		
		echo $htmlTagObj->openTag('li', '');
		echo '<span class="contentHead">Other insurance and distribution parties </span><br />';
		echo '<span class="contentText">In the course of marketing and providing insurance and processing claims, Arch may make Personal Information available to third parties such as other insurers; reinsurers; insurance and reinsurance brokers and other intermediaries and agents; appointed representatives; distributors; affinity marketing partners; and financial institutions, securities firms and other business partners.</span>';
		echo $htmlTagObj->closeTag('li');
		
		echo $htmlTagObj->openTag('li', '');
		echo '<span class="contentHead">Our service providers</span><br />';
		echo '<span class="contentText">External third-party service providers, such as medical professionals, accountants, actuaries, auditors, experts, lawyers and other outside professional advisors; travel and medical assistance providers; call center service providers; IT systems, support and hosting service providers; printing, advertising, marketing and market research and analysis service providers; banks and financial institutions that service our accounts; third-party claim administrators; document and records management providers; claim investigators and adjusters; construction consultants; engineers; examiners; jury consultants; translators; and similar third-party vendors and outsourced service providers that assist us in carrying out business activities.</span>';
		echo $htmlTagObj->closeTag('li');
		
		echo $htmlTagObj->openTag('li', '');
		echo '<span class="contentHead">Recipients of your social sharing activity</span><br />';
		echo '<span class="contentText">Your friends associated with your social media account(s), other website users and your social media account provider(s), in connection with your social sharing activity, such as if you connect another social media account to your ARCH services account or log into your ARCH services account from another social media account. By connecting your ARCH services account and your other social media account you authorize us to share information with your social media account provider and you understand that the use of the information we share will be governed by that other social media website\'s privacy policy. If you do not want your Personal Information shared with other users or with your other social media account provider(s), please do not connect other social media accounts with your ARCH services account and do not participate in social sharing using our ARCH services.</span>';
		echo $htmlTagObj->closeTag('li');
		
		echo $htmlTagObj->openTag('li', '');
		echo '<span class="contentHead">Governmental authorities and third parties involved in court action </span><br />';
		echo '<span class="contentText">Arch may also share Personal Information with governmental or other public authorities (including, but not limited to, workers\' compensation boards, courts, law enforcement, tax authorities and criminal investigations agencies); and third-party civil legal process participants and their accountants, auditors, lawyers and other advisors and representatives as we believe to be necessary or appropriate: (a) to comply with applicable law, including laws outside your country of residence; (b) to comply with legal process; (c) to respond to requests from public and government authorities including public and government authorities outside your country of residence; (d) to enforce our terms and conditions; (e) to protect our operations or those of any of our group companies; (f) to protect our rights, privacy, safety or property, and/or that of our group companies, you or others; and (g) to allow us to pursue available remedies or limit our damages.</span>';
		echo $htmlTagObj->closeTag('li');
		
	echo $htmlTagObj->closeTag('ul');
	echo '<p class="contentText">Personal Information may also be shared by you, on message boards, chat, profile pages and blogs, and other services to which you are able to post information and materials (including, without limitation, our Social Media Pages and Apps). Please note that any information you post or disclose through these services will become public information, and may be available to visitors who access the ARCH services and to the general public. We urge you to be very careful when deciding to disclose your Personal Information, or any other information, on the ARCH services.</p>';
	echo $htmlTagObj->closeTag('li');
	
	echo $htmlTagObj->openTag('li', '');
	echo '<p class="contentNormalBlueHead">Security </p>';
	echo '<p class="contentText">Arch will take appropriate technical, physical, legal and organizational measures, which are consistent with applicable privacy and data security laws.  Unfortunately, no data transmission over the Internet or data storage system can be guaranteed to be 100% secure.  If you have reason to believe that your interaction with us is no longer secure (for example, if you feel that the security of any Personal Information you might have with us has been compromised), please immediately notify us.  (See the "Who to Contact About Your Personal Information" section above.)</p><br />';
	echo '<p class="contentText">When Arch provides Personal Information to a service provider, the service provider will be selected carefully and required to use appropriate measures to protect the confidentiality and security of the Personal Information.</p><br />';
	echo '<p class="contentText">If we believe the security of your Personal Information in our possession or control may have been compromised, we may seek to notify you of that development. If a notification is appropriate, we would endeavor to do so as promptly as possible under the circumstances, and, to the extent we have your e-mail address, we may notify you by e-mail.</p><br />';
	echo $htmlTagObj->closeTag('li');
	
	echo $htmlTagObj->openTag('li', '');
	echo '<p class="contentNormalBlueHead">Personal Information of Other Individuals</p>';
	echo '<p class="contentText">If you provide Personal Information to Arch regarding other individuals, you agree: (a) to inform the individual about the content of this Privacy Policy, and any other applicable Arch Privacy Notice provided to you; and (b) to obtain any legally-required consent of Personal Information about the individual in accordance with this Privacy Policy and other Privacy Notice. </p><br />';
	echo $htmlTagObj->closeTag('li');
	
	echo $htmlTagObj->openTag('li', '');
	echo '<p class="contentNormalBlueHead">Marketing Preferences</p>';
	echo '<p class="contentText">If you no longer want to receive marketing-related e-mails from Arch on a going-forward basis, you may opt out of receiving these marketing-related emails by clicking on the link to "unsubscribe" provided in each e-mail or by contacting us at the above addresses. We will endeavor to comply with your opt-out request(s) within a reasonable time period. Please note that if you opt out as described above, we will not be able to remove your Personal Information from the databases of third parties with whom we have already shared your Personal Information (i.e., to those to whom we have already provided your Personal Information as of the date on which we respond to your opt-out request).  Please also note that if you do opt out of receiving marketing communications from us, we may still send you other important administrative communications from which you cannot opt out.</p><br />';
	echo $htmlTagObj->closeTag('li');
	
	echo $htmlTagObj->openTag('li', '');
	echo '<p class="contentNormalBlueHead">Questions and Concerns</p>';
	echo '<p class="contentText">Please contact us as set out in the "Who to Contact About Your Personal Information" section above with any such requests or if you have any questions or concerns about how we process Personal Information. </p><br />';
	echo $htmlTagObj->closeTag('li');
	
	echo $htmlTagObj->openTag('li', '');
	echo '<p class="contentNormalBlueHead">Other Information We Collect Through our Arch services</p>';
	echo '<p class="contentText">"Other Information" is any information that does not reveal your specific identity, such as:</p><br />';
	echo $htmlTagObj->openTag('ul', 'type="disc" class="ulMainHead"');
		echo $htmlTagObj->openTag('li', '');
		echo '<span class="contentText">Browser and device information;</span>';
		echo $htmlTagObj->closeTag('li');
		
		echo $htmlTagObj->openTag('li', '');
		echo '<span class="contentText">App usage data;</span>';
		echo $htmlTagObj->closeTag('li');
		
		echo $htmlTagObj->openTag('li', '');
		echo '<span class="contentText">Information collected through cookies, pixel tags and other technologies;</span>';
		echo $htmlTagObj->closeTag('li');
		
		echo $htmlTagObj->openTag('li', '');
		echo '<span class="contentText">Demographic information and other information provided by you; and</span>';
		echo $htmlTagObj->closeTag('li');
		
		echo $htmlTagObj->openTag('li', '');
		echo '<span class="contentText">Aggregated information  </span>';
		echo $htmlTagObj->closeTag('li');
	echo $htmlTagObj->closeTag('ul');
	
	echo '<p class="contentHead">How We Collect Other Information </p><br />';
	echo '<p class="contentText">We and our third-party service providers may collect Other Information in a variety of ways, including: </p><br />';
	echo $htmlTagObj->openTag('ul', 'type="disc" class="ulMainHead"');
		echo $htmlTagObj->openTag('li', '');
		echo '<span class="contentHead">Through your browser or device:  </span>';
		echo '<span class="contentText">Certain information is collected by most websites or automatically through your device, such as your IP address (i.e., your computer\'s address on the internet), screen resolution, operating system type (Windows or Mac) and version, device manufacturer and model, language, internet browser type and version, time of the visit, page(s) visited and the name and version of the Arch services (such as the App) you are using.  We use this information to ensure that the Arch services function properly.</span>';
		echo $htmlTagObj->closeTag('li');
		
		echo $htmlTagObj->openTag('li', '');
		echo '<span class="contentHead">Through your use of the App:</span>';
		echo '<span class="contentText">When you download and use the App, we and our service providers may track and collect App usage data, such as the date and time the App on your device accesses our servers and what information and files have been downloaded to the App based on your device number.</span>';
		echo $htmlTagObj->closeTag('li');
		
		echo $htmlTagObj->openTag('li', '');
		echo '<span class="contentHead">Using cookies:</span>';
		echo '<p class="contentText">Cookies are pieces of information stored directly on the computer you are using. Cookies allow us to recognize your computer and to collect information such as internet browser type, time spent on the Arch services, pages visited, language preferences, country website preference. We may use the information for security purposes, to facilitate navigation, to display information more effectively, to personalize your experience while using the Arch services, or to gather statistical information about the usage of the Arch services. Cookies further allow us to present to you the advertisements or offers that are most likely to appeal to you. We may also use cookies to track your responses to our advertisements and we may use cookies or other files to track your use of other websites. </p>';
		echo '<p class="contentText">One of the advertisement companies that we may use in the future is Google, Inc., trading as DoubleClick. To opt out from the DoubleClick advertisement cookie please visit: <a href="http://www.doubleclick.com/privacy/index.aspx" target="blank" class="textLink">www.doubleclick.com/privacy/index.aspx</a>. You can refuse to accept other cookies we use by adjusting your browser settings. However, if you do not accept these cookies, you may experience some inconvenience in your use of the Site and some online products.</p>';
		echo $htmlTagObj->closeTag('li');
		
		echo $htmlTagObj->openTag('li', '');
		echo '<span class="contentHead">Using pixel tags, web beacons, clear GIFs or other similar technologies:</span>';
		echo '<p class="contentText">These may be used in connection with some Arch services and HTML-formatted e-mail messages to, among other things, track the actions of users of the Arch services and e-mail recipients, measure the success of our marketing campaigns and compile statistics about usage of the Arch services and response rates. </p><br />';
		echo '<p class="contentText">We use Google\'s analytics service, which uses cookies and web beacons to help us understand more about how our website is used by consumers so we can continue to improve it. Google does not have the right to use the information we provide to them beyond what is necessary to assist us. </p>';
		echo $htmlTagObj->closeTag('li');
		
		echo $htmlTagObj->openTag('li', '');
		echo '<span class="contentHead">Using your Physical Location:</span>';
		echo '<span class="contentText">We may collect the physical location of your device by, for example, using satellite, cell phone tower or Wi-Fi signals. We may use your device\'s physical location to provide you with personalized location-based services and content. We may also share your device\'s physical location, combined with information about what advertisements you viewed and other information we collect, with our marketing partners to enable them to provide you with more personalized content and to study the effectiveness of advertising campaigns. In some instances, you may be permitted to allow or deny such uses and/or sharing of your device\'s location, but if you choose to deny such uses and/or sharing, we and/or our marketing partners may not be able to provide you with the applicable personalized services and content.</span>';
		echo $htmlTagObj->closeTag('li');
		
		echo $htmlTagObj->openTag('li', '');
		echo '<span class="contentHead">Using information provided by you:</span>';
		echo '<span class="contentText">Some information (for example, your location or preferred means of communication) is collected when you voluntarily provide it. Unless combined with Personal Information, this information does not personally identify you.</span>';
		echo $htmlTagObj->closeTag('li');
		
		echo $htmlTagObj->openTag('li', '');
		echo '<span class="contentHead">By aggregating information: </span>';
		echo '<span class="contentText">We may aggregate and use certain information (for example, we may aggregate information to calculate the percentage of our users who have a particular telephone area code). </span>';
		echo $htmlTagObj->closeTag('li');
	echo $htmlTagObj->closeTag('ul');
	
	echo '<p class="contentText">Please note that we may use and disclose Other Information for any purpose, except where we are required to do otherwise under applicable law.  If we are required to treat Other Information as Personal Information under applicable law, then, in addition to the uses listed in the " Other Information We Collect " section above, we may use and disclose Other Information for all the purposes for which we use and disclose Personal Information.</p>';
	echo $htmlTagObj->closeTag('li');
	
	echo $htmlTagObj->openTag('li', '');
	echo '<p class="contentNormalBlueHead">Third Party Privacy Practices</p>';
	echo '<p class="contentText">This Privacy Policy does not address, and we are not responsible for, the privacy, information or other practices of any third parties, including any third party operating any site or service to which the Arch services link.  The inclusion of a link on the Arch services does not imply endorsement of the linked site or service by us or by our group companies. </p><br />';
	echo '<p class="contentText">Please note that we are not responsible for the collection, usage and disclosure policies and practices (including the data security practices) of other organizations, such as Facebook, Apple, Google, Microsoft, RIM or any other software application developer or provider, social media platform, operating system or wireless service provider, or device manufacturer, including any Personal Information you disclose to other organizations through or in connection with the Apps or our Social Media Pages.  </p><br />';
	echo $htmlTagObj->closeTag('li');
	
	echo $htmlTagObj->openTag('li', '');
	echo '<p class="contentNormalBlueHead">Use of Services by Minors </p>';
	echo '<p class="contentText">The Arch services are not directed to individuals under the age of eighteen (18), and we request that these individuals do not provide Personal Information through the Arch services. </p><br />';
	echo $htmlTagObj->closeTag('li');
	
	echo $htmlTagObj->openTag('li', '');
	echo '<p class="contentNormalBlueHead">Changes to This Privacy Policy </p>';
	echo '<p class="contentText">We review this Privacy Policy regularly and reserve the right to make changes at any time to take account of changes in our business and legal requirements.  We will place updates on our website.  </p><br />';
	echo $htmlTagObj->closeTag('li');
	

echo $htmlTagObj->closeTag('ol');

echo $htmlTagObj->closeTag('div');

include 'footer.php';
?>