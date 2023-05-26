<?php

//always use these constants to store the current date & time.
date_default_timezone_set('Asia/Kolkata');

// App name
define("APP_NAME", "99 Magic Players");

define("CURRENTTIME", date('Y-m-d H:i:s'));
define("CURRENTDATE", date('Y-m-d'));

//to be used when you need the URL path:
$serverPath = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]/99_magic_players/";

define("LOCAL_IMAGE_PATH", $serverPath);
//if you want to add a folder name for temporary purpose then you can do this: define("LOCAL_IMAGE_PATH", $serverPath.'folderName');   concat the name
define("SERVERPATHS", $serverPath);

//project root folder path
$document_root = $_SERVER['DOCUMENT_ROOT'].'/99_magic_players/';
define("DOCUMENT_ROOT", $document_root);

// API authentication setting
define("AUTHENTICATE_API", TRUE);
define("APP_API_KEY", "sXZ7tdYP7hy2qZKD9cL");
define("APP_API_KEY_REQUIRED_TEXT", "API Key required");
define("APP_API_KEY_INVALID_TEXT", "Invalid API Key");

//Admin email address
define("ADMINEMAIL","tester.studyleagueit@gmail.com");

//Membership Details
define("MEMBERSHIP_AMOUNT", 99);
define("MEMBERSHIP_VALIDITY", 1);
define("MEMBERSHIP_VALIDITY_TYPE", "Year");

//CGST percentage
define("CGST_PERCENTAGE", 9); // in percent

//SGST percentage
define("SGST_PERCENTAGE", 9); // in percent

//to be used only when the project has mail sending facility otherwise delete it
define("HOST","mail.studyleagueit.com");
define("PORT",25);
define("USERNAME","testing@studyleagueit.com");
define("PASSWORD","123456789");
define("NOREPLY","testing@studyleagueit.com");

//SMS Pack credential
define("SMSAPIKEY","35FF9A76EA45A1");
define("SENDERID","SLITSS");
define("ROUTEID","7");
define("ENTITYID","1201161001935042878");
define("CAMPAIGN_ID","10423");
define("template_id","1207161767824281970");
// define("template_id","1207162460085800305");
define("smsKeyHash","1qhzQMoAmvE");

?>
