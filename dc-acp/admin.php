<?php
// Include CMS System
/**--**/
include "../dc-inc/base.php";
//------------------------------------------------
// Site Informations
/**--**/
$meta['title'] = "ACP";
//------------------------------------------------

$error = "";
$disp = "";
$subsite = array();

if (!permTo("menu_acp")) {
    $error = msg(_no_permissions);
}

if (isset($_GET['acp'])) {
    $acp = $_GET['acp'];
} else {
    $acp = "";
}

global $path;

$items = opendir(Config::$path['acp'].'module');
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
    $disp = show("acp/welcome").' <a href="/login?do=logout">Logout</a>';
}

if ($error == "") {
    $submenu = "";
    $path['acp_settings'] = 'settings/' . $acp . '.xml';
    if (file_exists($path['acp_settings'])) {
        $acp_settings = new SimpleXMLElement($path['acp_settings'],0,true);
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
    $vc = new VersionControl();
    $case = array("menu" => $item_stack, "content" => show($disp, array('where' => $acp)), "submenu" => $submenu);
    Disp::$content = show("acp/menu", array_merge($case, $vc->get_vars()));
    Disp::addMeta($meta);
    Disp::render();
} else {
    Disp::$content = $error;
    Disp::addMeta($meta);
    Disp::render();
}
