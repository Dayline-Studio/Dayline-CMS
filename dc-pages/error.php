<?php
include '../dc-inc/base';

$meta['title'] = 'Error';
switch($_REQUEST) {
    case 'sitenotfound':
        $disp = show('error/sitenotfound');
        break;
}

Disp::$content = $disp;
DIsp::addMeta($meta);
Disp::render();