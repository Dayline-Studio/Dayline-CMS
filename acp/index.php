<?php
// Include CMS System
/**--**/
include "../inc/base.php";
//------------------------------------------------
// Site Informations
/**--**/
$meta['title'] = "ACP";
//------------------------------------------------
if (!permTo("menu_acp")) {
    $error = msg(_no_permissions);
}

$error = "";
$disp = "";
$subsite = array();

if (isset($_GET['acp'])) {
    $acp = $_GET['acp'];
} else {
    $acp = "";
}

global $path;

$items = opendir('../acp/module');
$item_stack = "";
$file_exist = false;
while ($item = readdir($items)) {
    if ($item != ".." && $item != ".") {
        $filename = substr($item, 0, -4);
        $active = $filename == $acp ? ' active' : '';
        $item_stack .= '<a class="btn btn-default' . $active . '" href="?acp=' . $filename . '">{s_' . $filename . '}</a>';
        if ($filename == $acp) {
            $file_exist = true;
        }
    }
}
closedir($items);

if ($file_exist) {
    include 'module/' . $acp . ".php";
} else {
    $disp = show("acp/welcome");
}

if ($error == "") {
    $submenu = "";
    $path['acp_settings'] = 'settings/' . $acp . '.xml';
    if (file_exists($path['acp_settings'])) {
        $acp_settings = new SimpleXMLElement(file_get_contents($path['acp_settings']));
        if (isset($acp_settings->subsites[0])) {
            $li = "";
            foreach ($acp_settings->subsites[0] as $sub) {
                $active = '';
                if ($sub == $action) {
                    $active = ' active';
                }
                $li .= '<a class = "btn btn-default' . $active . '" href="?acp=' . $_GET['acp'] . '&AMP;action=' . $sub . '" >{s_' . $sub . '}</a>';
            }
            $submenu = $li;
        }
    }
    Disp::$content = show("acp/menu", array("menu" => $item_stack, "content" => $disp, "submenu" => $submenu));
    Disp::addMeta($meta);
    Disp::render();
} else {
    init($error, $meta);
}
