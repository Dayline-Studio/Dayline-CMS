<?php
//------------------------------------------------
include "../dc-inc/base.php";
//------------------------------------------------

$content = $_SESSION['print_content'];

echo show("print/print", array("content" => $content));