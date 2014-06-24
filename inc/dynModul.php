<?php
require_once 'base.php';

backSideFix();

if (isset($_GET['modulname'])) {
    $panelPath = $path['dyn_panels'] . "dyn_" . $_GET['modulname'] . ".php";
    $panelName = $_GET['modulname'];

    if (file_exists($panelPath)) {
        include($panelPath);
    } else echo "fail2";
} else echo "fail1";