<?php
// Include CMS System
/**--**/ include "../inc/config.php";
//------------------------------------------------
// Site Informations
/**--**/  $meta['title'] = "ACP";
//------------------------------------------------



 
function getAcpMenu() {
    $sites = db("SELECT * FROM menu WHERE part = 3");
}     
