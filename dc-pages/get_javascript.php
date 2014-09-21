<?php
// Include CMS System
include "../dc-inc/base.php";
//------------------------------------------------

backSideFix();


$myJavaScriptLoader = new JavaScriptLoader($GLOBALS['base_dir'].'/dc-inc/framework/js', true);
header("Content-type: application/x-javascript");
echo $myJavaScriptLoader->getScript(true);
