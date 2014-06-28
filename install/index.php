<?php

// Include CMS System
/**--**/
include "../inc/base.php";
//------------------------------------------------
require_once('mysql.php');
$meta['title'] = 'Installer';

$_POST['salt'] = randomstring(32);

$files = array();
//$files[] = array('../inc/config.php', 777);
$files[] = array('../content/upload', 777);
$files[] = array('../inc/_cache', 777);

if (!isset($_GET['action'])) {
    $action = "";
} else {
    $action = $_GET['action'];
}

$te = new TemplateEngine();
$te->set_dir('html/');

switch ($action) {
    default :
        header('Location: ?action=agb');
        break;
    case 'agb':
        $te->setHtml('agb.html');
        $disp = $te->render();
        break;
    case 'permissions':
        $te->setHtml('permissions.html');
        $permissions_no_error = true;
        $case['permission_table'] = '';
        $c = array();
        foreach ($files as $path) {
            if ($path[1] != substr(sprintf('%o', fileperms($path[0])), -3)) {
                $class = 'danger';
                $permissions_no_error = false;
            } else {
                $class = 'success';
            }
            $c[] = array(
                'file' => $path[0],
                'is_file' => is_file($path[1]) ? 'Datei' : 'Ordner',
                'permission_num' => $path[1],
                'class' => $class
            );

        }
        $te->addArr('permission_table', $c);
        if ($permissions_no_error) {
            $te->add_var('submit', file_get_contents("html/permissions_true.html"));
        } else {
            $te->add_var('submit', '');
        }
        $disp = $te->render();
        break;
    case 'mysql_connection':
        $disp = file_get_contents("html/install.html");
        break;
    case 'check_input':
        $action = 'mysql_connection';
        if ($dblink = mysqli_connect($_POST['sql_host'], $_POST['sql_user'], $_POST['sql_pass'], $_POST['sql_db'])) {
            foreach ($tables as $table => $sql) {
                if (mysqli_query($dblink, 'DROP TABLE ' . $table)) {
                    $disp .= "Datenbank " . $table . " wurde gelöscht, weil sie bereits vorhanden war<br>";
                }
                if (mysqli_query($dblink, 'CREATE TABLE ' . $table . ' (' . $sql . ') ')) {
                    $disp .= "Datenbank " . $table . " wurde erfolgreich erstellt <br>";
                } else {
                    $disp .= "Erstellen der Datenbank " . $table . " fehlgeschlagen.<br>";
                }
            }
            if (!isset(
                $_POST['slq_host']) || !isset($_POST['sql_user']) || !isset($_POST['sql_pass']) || !isset($_POST['sql_db'])
            ) {
                $disp .= "Bitte alle Felder ausfüllen!<br>";
            }
        } else echo 'Nein';
        $disp .= file_get_contents("html/check_input.html");
        break;
    case 'create_config':
        $te->setHtml('config.html');
        $content = show(file_get_contents('config.clear'), $_POST);
        $config_file = fopen('config.php', 'r+');
        rewind($config_file);
        fwrite($config_file, $content);
        fclose($config_file);
        $disp = $te->render();
        break;

    case 'create_user':
        //$new_user = new User($user);
        break;
}

$te_m = new TemplateEngine();
$te_m->set_dir('html/');
$te_m->setHtml('index.html');

$config = @simplexml_load_file("config.xml");

$te_m->add_var('version',$config->version);
$te_m->add_var('released',321432);
$te_m->add_var('build',0000.5);

$navi_lang = array(
    'agb' => 'Lizensbedingungen',
    'permissions' => 'Dateirechte',
    'mysql_connection' => 'Datenbank verbindung',
    'create_config' => 'Erstellen der Config'
);

foreach ($navi_lang as $sitename => $language) {
    if ($sitename == $action) {
        $active = "active";
        $meta['title'] = $language;
    } else {
        $active = "";
    }
    $n[] = array(
        'text' => $language,
        'active' => $active
    );
}
$te_m->addArr('navi_menu', $n);

Disp::addMeta($meta);
Disp::$content = $disp;
Disp::renderMinStyle($te_m->render());