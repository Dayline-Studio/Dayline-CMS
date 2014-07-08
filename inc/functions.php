<?php

phpFastCache::$storage = "auto";
phpFastCache::setup("path", Config::$path['cache']);

function show($file_content = "", $tags = array(null => null))
{
    if (file_exists(Config::$path['template'] . "/" . $file_content . ".html")) {
        $file_content = file_get_contents(Config::$path['template'] . "/" . $file_content . ".html");
    } else if (file_exists("../templates/default/" . $file_content . ".html")) {
        $file_content = file_get_contents("../templates/default/" . $file_content . ".html");
    } else if (file_exists($file_content)) {
        $file_content = file_get_contents($file_content);
    }
    foreach ($tags as $name => $value) {
        if (!is_array($value) && !is_object($value)) {
            $file_content = str_replace('{' . $name . '}', $value, $file_content);
        }
    }
    return preg_replace("/\s+/", " ", $file_content);
}

function get_template_dir_from($path) {
    if(file_exists(Config::$path['template'] . "/" . $path)) {
        return Config::$path['template'] . "/" . $path;
    } else if (file_exists("../templates/default/" . "/" . $path)) {
        return "../templates/default/" . "/" . $path;
    } else if (file_exists($path)) {
        return $path;
    } else {
        Debug::log('Template file not found -> '. $path);
        return "";
    }
}

function getUserInformations($userid, $informations)
{
    return Db::npquery("SELECT $informations FROM users WHERE id = $userid LIMIT 1", PDO::FETCH_OBJ);
}

function randomstring($length = 6)
{
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
    srand((double)microtime() * 1000000);
    $i = 0;
    $tmp = "";
    while ($i < $length) {
        $num = rand() % strlen($chars);
        $tmp .= substr($chars, $num, 1);
        $i++;
    }
    return $tmp;
}

function custom_verify($pw, $pw2) {
    global $config;
    return password_verify($pw.$config['salt'],$pw2);
}

function customHasher($pw)
{
    global $config;
    return password_hash($pw.$config['salt'],PASSWORD_BCRYPT, array('cost' => 12));
}

function sqlString($param)
{
    return (NULL === $param ? "NULL" : "'" . mysql_real_escape_string($param) . "'");
}

function sqlStringCon($param)
{
    return (NULL === $param ? "NULL" : "'" . mysql_real_escape_string(con($param)) . "'");
}

function sqlInt($param)
{
    return (NULL === $param ? "NULL" : intVal($param));
}

function get_gravatar($email, $s = 80, $img = false)
{
    $d = 'wavatar';
    $r = 'g';
    $url = 'https://www.gravatar.com/avatar/';
    $url .= md5(strtolower(trim($email)));
    $url .= "?s=$s&amp;d=$d&amp;r=$r";
    if ($img) {
        $url = '<img src="' . $url . '" />';
    }
    return $url;
}

function msg($msg, $kind = 'stock')
{
    global $meta;
    switch ($kind) {
        default:
            $file = show("msg/msg_stock");
    }

    $meta['title'] = $msg;
    $msg = show($file, array("msg" => $msg,
        "link" => $_SESSION['last_site']));
    backSideFix();
    return $msg;
}

function getNews($groupid = 0)
{
    $news_posts = db("Select * From news where public_show = 1 AND grp = " . $groupid . " ORDER BY date DESC");
    $list_news = "";
    while ($get_news = _assoc($news_posts)) {
        $list_news .= show("news/post", array(
            "news_headline" => $get_news['title'],
            "news_date" => date("F j, Y, G:i", $get_news['date']),
            "news_content" => $get_news['content'],
            "id" => $get_news['id'],
            "post_comment" => '<a href="../pages/news.php?id=' . $get_news['id'] . '#comments">Kommentare: ' . db("SELECT count(id) as counted FROM comments where site = 2 AND subsite = " . $get_news['id'], 'object')->counted . '</a>'
        ));
    }
    if ($list_news == "") {
        $list_news = _news_not_found;
    }
    return show("news/layout_posts", array("posts" => $list_news));
}

function permTo($permission)
{
    if (isset($_SESSION['group_main_id'])) {
        $perm = Db::query("SELECT " . $permission . " From groups WHERE id = :id LIMIT 1", array('id' => $_SESSION['group_main_id']), PDO::FETCH_OBJ);
        if (isset($perm->$permission)) {
            return $perm->$permission;
        }
    }
    return 0;
}

function con($txt)
{
    $txt = stripslashes($txt);
    $txt = str_replace("& ", "&amp; ", $txt);
    $txt = str_replace("[", "&#91;", $txt);
    $txt = str_replace("]", "&#93;", $txt);
    $txt = str_replace("\"", "&#34;", $txt);
    $txt = str_replace("<", "&#60;", $txt);
    $txt = str_replace(">", "&#62;", $txt);
    $txt = str_replace("(", "&#40;", $txt);
    $txt = str_replace("'", "&lsquo;", $txt);
    $txt = str_replace("(", "&#40;", $txt);
    return str_replace(")", "&#41;", $txt);
}

function check_email_address($str_email_address)
{
    if ('' != $str_email_address && !((preg_match("/[_\.0-9a-z-]+@([0-9a-z-]+\.)+[a-z]{2,6}/i", $str_email_address)))) {
        return false;
    } else {
        return true;
    }
}

function updateRSS()
{
    global $path;

    $xml = new DOMDocument('1.0', 'UTF-8');
    $xml->formatOutput = true;

    $roo = $xml->createElement('rss');
    $roo->setAttribute('version', '2.0');
    $xml->appendChild($roo);
    $cha = $xml->createElement('channel');
    $roo->appendChild($cha);
    $new = $xml->createElement('title', 'CMS - News');
    $cha->appendChild($new);
    $new = $xml->createElement('description', 'D4ho.de - CMS');
    $cha->appendChild($new);
    $bld = $xml->createElement('image');
    $cha->appendChild($bld);
    $bld->appendChild($xml->createElement('url', "http://dummyimage.com/120x61"));

    $qry = Db::npquery('SELECT * FROM news WHERE grp = 2 AND public_show = 1 ORDER BY date DESC');
    foreach ($qry as $rss_feed) ;
    {
        $new = $xml->createElement('item');
        $cha->appendChild($new);
        $rss['title'] = $rss_feed['title'];
        $image = '&lt;img style="border: 0px none; margin: 0px; padding: 0px;" align="right" alt="" width="60" height="60" src="' . $rss_feed['main_image'] . '" &gt;';
        $rss['description'] = $image . $rss_feed['description'];
        $rss['language'] = Config::$settings->lang;
        $rss['link'] = "http://cms.d4ho.de/pages/news.php?id=" . $rss_feed['id'];
        $rss['pubDate'] = date("D, j M Y H:i:s ", $rss_feed['date']);
        $hea = $xml->createElement('image');
        $new->appendChild($hea);
        $img = $xml->createElement('url', $rss_feed['main_image']);
        $hea->appendChild($img);

        foreach ($rss as $tag => $value) {
            $hea = $xml->createElement($tag, utf8_encode($value));
            $new->appendChild($hea);
        }
    }

    if ($xml->save($path['rss'] . 'public-news.xml')) {
        return true;
    }
    return false;
}

function convertMatch($matches)
{
    $new = array();
    foreach ($matches as $value) {
        if (defined("_" . $value)) {
            $new["s_" . $value] = constant("_" . $value);
        } else {
            $new["s_" . $value] = "STRING_NOT_FOUND_" . strtoupper($value);
        }
    }
    return $new;
}

function searchBetween($start_tag, $String, $end_tag)
{
    if (preg_match_all('/' . preg_quote($start_tag) . '(.*?)' . preg_quote($end_tag) . '/s', $String, $matches)) {
        return $matches[1];
    }
    return array();
}

function backSideFix()
{
    if (isset($_SESSION['last_site'])) {
        $_SESSION['current_site'] = $_SESSION['last_site'];
    }
}

function tagConverter($tags)
{
    $tags = str_replace("/", ",", $tags);
    $tags = str_replace(" , ", ",", $tags);
    $tags = str_replace(" ,", ",", $tags);
    $tags = str_replace(", ", ",", $tags);
    return explode(",", $tags);
}

function sendmail($content, $subject, $receiver)
{
    $mail = new PHPMailer();
    $mail->isSendmail();

    $mail->SetFrom('admin@' . $_SERVER['HTTP_HOST'], 'mailFrom->' . $_SERVER['HTTP_HOST']);

    $mail->CharSet = "utf-8";
    $mail->Subject = $subject;

    $mail->msgHTML(
        show(
            'mail/layout',
            array(
                'title' => $subject,
                'content' => $content,
                'date' => date('l jS \am F Y H:i:s', time())
            )
        )
    );
    $mail->addAddress($receiver);
    if ($mail->send()) {
        return true;
    }
    return false;
}

function convertDateOutput($datein)
{
    $date = ((time() - $datein) / 60);
    if ($date * 60 < 60) {
        return (int)($date * 60) . " sec ago";
    } else if ($date < 60) {
        return (int)$date . " min ago";
    } else if ($date > 59 && $date / 60 < 24) {
        return "vor " . (int)($date / 60) . "h at " . date("h:i A", $datein);
    } else if ($date / 60 > 23 && $date / 60 / 24 < 4) {
        return (int)($date / 60 / 24) . " day(s) ago at " . date("h:i A", $datein);
    } else {
        return date("F j, g:i a", $datein);
    }
}

function goBack()
{
    header('Location: ' . $_SESSION['last_site']);
    exit();
}

function goToWithMsg($url, $msg, $type = 'info')
{
    new Notification($msg, $type);
    if ($url == 'back') {
        goBack();
    } else {
        header('Location: ' . $url);
        exit();
    }
}

function con_to_lang($str)
{
    return '{s_' . $str . '}';
}

function s_decode($str)
{
    $ret = new ArrayObject();
    $vars = explode(';', $str);
    foreach ($vars as $sstr) {
        $ex = explode('=', $sstr);
        $ret->$ex[0] = $ex[1];
    }
    return $ret;
}

function s_encode($str)
{
    $con = array();
    foreach ($str as $key => $value) {
        $con[] = "$key=$value";
    }
    return implode(';', $con);
}

function get_public_properties($object)
{
    $result = get_object_vars($object);
    if ($result === NULL or $result === FALSE) {
        throw new UnexpectedValueException("Given $object parameter is not an object.");
    }
    return $result;
}

function get_options($arr)
{
    $ret = "";
    foreach ($arr as $array) {
        $ret .= '<option value="' . $array['value'] . '">' . $array['title'] . '</option>';
    }
    return $ret;
}

function get_editor($content = '')
{
    return show('allround/input_editor', array('content' => $content));
}

function sendMessage($sender, $receiver, $content, $title, $email = "")
{
    sendmail($content, $title, getUserInformations($receiver, 'email')->email);
    $in = array(
        'sender_id' => $sender,
        'receiver_id' => $receiver,
        'email' => $email,
        'date' => time(),
        'content' => $content,
        'title' => $title
    );
    return Db::insert('messages', $in);
}