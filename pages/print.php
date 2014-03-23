<?php
//------------------------------------------------
/**--**/ include "../inc/base.php";
//------------------------------------------------

$content = $_SESSION['print_content'];
$title = $_SESSION['print_title'];
    
echo show("print/print", array("title" => $title, "content" => $content ));