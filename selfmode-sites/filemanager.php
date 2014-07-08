<?php
// Include CMS System
include "../inc/base.php";
//------------------------------------------------
// Site Informations
$meta['title'] = "Test";
$meta['page_id'] = 666;
//------------------------------------------------


$path = isset($_GET['path']) ? $_GET['path'] : '';
$fm = new FileManager($path);
$te = new TemplateEngine('filemanager/main');
$te->add_var('path', $fm->dir->base_path);
$te->addArr('folders', $fm->get_current_folder()->folders);
$te->addArr('files', $fm->get_current_folder()->files);
Disp::$content = $te->render();
Disp::renderMinStyle('allround/index_head_only');