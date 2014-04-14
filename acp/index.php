<?php
// Include CMS System
/**--**/ include "../inc/base.php";
//------------------------------------------------
// Site Informations
/**--**/  $meta['title'] = "ACP";
//------------------------------------------------
if (!permTo("menu_acp")) { $error = msg(_no_permissions); }

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
    while ($item = readdir($items)) 
    {
        if ($item != ".." && $item != ".") 
        {
            $filename = substr($item,0,-4);
            $item_stack .= '<li><a class="buttonStyle" href="index.php?acp='.$filename.'">[s_'.$filename.']</a></li>';
            if ($filename == $acp) {
                $file_exist = true;
            }    
        }
    } 
    closedir($items);

if ($file_exist)
{
    include 'module/'.$acp.".php";
}
else
{
    $disp = show("acp/welcome");
}

if ($error == "") {
    $submenu = "";
    $path['acp_settings'] = 'settings/'.$acp.'.xml';
    if (file_exists($path['acp_settings']))
    {
        $acp_settings =  new SimpleXMLElement(file_get_contents($path['acp_settings']));
        if (isset($acp_settings->subsites[0]))
        {  
            $li = "";
            foreach ($acp_settings->subsites[0] as $sub)
            {
                $li .= '<li><a class="buttonStyle" href="?acp='.$_GET['acp'].'&AMP;action='.$sub.'" >[s_'.$sub.']</a></li>';
            }
            $submenu = show('acp/acp_horiz_list', array('li' => $li));
        }
    }
    init(show("acp/menu", array("menu" => $item_stack, "content" => $disp, "submenu" => $submenu)),$meta);
} else {
    init($error,$meta);
}
