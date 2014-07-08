<?php
// Include CMS System
include "../inc/base.php";
//------------------------------------------------
// Site Informations
$meta['title'] = "Test";
$meta['page_id'] = 666;
//------------------------------------------------


$test = '[test1|test1|test1]</br>[test|test|test]';
$te = new TemplateEngine($test);
$te->render();
print_r($te->results);
