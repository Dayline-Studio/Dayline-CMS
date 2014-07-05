<?php

// Include CMS System
/**--**/
include "../inc/base.php";
//------------------------------------------------
require_once('mysql.php');
$meta['title'] = 'Installer';
Debug::log('test');
$_POST['salt'] = randomstring(32); 

$files = array();
$files[] = array('../inc/config.php', 777);
$files[] = array('../content/upload', 777);
$files[] = array('../inc/_cache', 777);

if (!isset($_GET['action'])) {
    $action = "";
} else {
    $action = $_GET['action'];
}

$config = @simplexml_load_file("config.xml");

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
        if (Db::connect($_POST['sql_host'], $_POST['sql_db'], $_POST['sql_user'], $_POST['sql_pass'])) {
            foreach ($tables as $table => $sql) {
                if (Db::drop($table)) {
                    $disp .= "Datenbank " . $table . " wurde gelöscht, weil sie bereits vorhanden war<br>";
                }
                if (Db::nrquery('CREATE TABLE ' . $table . ' (' . $sql . ') ')) {
                    $disp .= "Datenbank " . $table . " wurde erfolgreich erstellt <br>";
                } else {
                    $disp .= "Erstellen der Datenbank " . $table . " fehlgeschlagen.<br>";
                }
            }
			$_SESSION['admin'] = true;
			$se['website_title'] = 'Titel deiner Website';
			$se['publisher'] = 'Default-Publisher';
			$se['copyright'] = 'Copyright '.date('Y');
			$se['language'] = 'de';
			$se['style'] = 'default';
			$se['link_facebook'] = 'https://github.com/Dayline-Studio/CMS';
			$se['link_twitter'] = 'https://github.com/Dayline-Studio/CMS';
			$se['link_youtube'] = 'https://github.com/Dayline-Studio/CMS';
			$se['link_google'] = 'https://github.com/Dayline-Studio/CMS';
			$se['force_domain'] = 0;
			$se['domain'] = '';
			$se['force_https'] = 0;
			$se['version'] = $config->version;
			Db::insert('settings', $se);
			$me = array(
				'title' => 'ACP',
				'subfrom' => 0,
				'link' => '../acp/',
				'part' => 2,
				'position' => 0);
			Db::insert('menu', $me);
			Db::nrquery("INSERT INTO groups (`id`, `groupid`, `site_edit`, `create_site`, `menu_acp`, `comment`, `create_news`, `delete_site`, `reset_counter`, `delete_news`, `update_socialnetwork`, `mail_abo`, `msg_send`, `delete_group`, `create_menu`, `delete_menu`, `update_menu`, `fm_access`) VALUES ('1', 'Admin', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1'), ('2', 'Guest', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0');");
			
			$content = show(file_get_contents('config.clear'), $_POST);
			$config_file = fopen('../inc/config.php', 'r+');
			rewind($config_file);
			fwrite($config_file, $content);
			fclose($config_file);
			} else $disp = 'Verbindung konnte nicht hergestellt werden!<br>';
			$disp .= file_get_contents("html/check_input.html");
        break;
}

$te_m = new TemplateEngine();
$te_m->set_dir('html/');
$te_m->setHtml('index.html');

$te_m->add_var('version',(String)$config->version);
$te_m->add_var('released',(String)$config->release);
$te_m->add_var('build', (String)$config->build);

$navi_lang = array(
    'agb' => 'Lizensbedingungen',
    'permissions' => 'Dateirechte',
    'mysql_connection' => 'Datenbank verbindung',
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
        'active' => $active,
		'link' => "?action=$sitename"
    );
}
$te_m->addArr('navi_menu', $n);

Disp::addMeta($meta);
Disp::$content = $disp;
Disp::renderMinStyle($te_m->render());