<?php
include('base.php');

backSideFix();

if (isset($_GET['modulname'])) {
    $panelPath = "../content/panelsDyn/dyn_".$_GET['modulname'].".php";
    $panelName = $_GET['modulname'];
    
    if(file_exists($panelPath)) {
        include($panelPath);
    } else echo "fail2";
} else echo "fail1";