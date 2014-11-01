<?php
// Include CMS System
include "../dc-inc/base.php";
//------------------------------------------------
function backSideFix() {

}
Auth::backSideFix();

if ($_REQUEST['script'] == 'js') {
    $myJavaScriptLoader = new ScriptLoader($GLOBALS['base_dir'].'/dc-inc/framework/js', true, 'js');
    header("Content-type: application/x-javascript");
    header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time() + 66600));
    echo $myJavaScriptLoader->getScript(false);
} else {
    $myCssScriptLoader = new ScriptLoader($GLOBALS['base_dir'].'/dc-inc/framework/css', true, 'css');
   // header("Content-type: application/x-javascript");
    echo $myCssScriptLoader->getScript(false);
}