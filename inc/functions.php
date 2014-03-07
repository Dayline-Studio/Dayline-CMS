<?php

    require_once 'cachesystem/fastcache.php';
    phpFastCache::$storage = "auto";
    phpFastCache::setup("path", "/home/webspace/ws31/inc/_cache");
        
    function init($content = "", $meta = null)
    {
            global $path, $language; 
            $init = show(loadingPanels(),array("content" => $content));
            $settings = mysqli_fetch_object(db("Select * from settings where id = 1"));
            $init = show($init,array("title" =>                     $meta['title'],
                                     "language_content" =>          $language,
                                     "author" =>                    $meta['author'],
                                     "publisher" =>	            $settings->publisher,
                                     "copyright" =>                 $settings->copyright,
                                     "keywords" =>                  $meta['keywords'],
                                     "description" =>               $meta['description'],
                                     "language" =>                  $settings->language,
                                     "css" =>                       $path['css'],
                                     "style" =>                     $path['style'],
                                     "js" =>                        $path['js']));
            $pageswitcher = show("../pageswitcher");
            display($pageswitcher.$init);
    }

    function display($content)
    {
            echo $content;
    }
    function loadingPanels()
    {
            //Global Path Variable
            global $path;
            //Loading panel Folder
            $panels = opendir($path['panels']);
            //Get the Main File for this Page (index.html)
            $output = getFile($path['style_index']);

            //Loading the panels
            while ($panel = readdir($panels)) 
            {
                    //Loading panel functions
                    if ($panel != ".." && $panel != ".") 
                    {
                            //Import panel function
                            includeFile($path['panels'].$panel);
                            //Remove .php (-4 chars)
                            $panel = substr($panel,0,-4);
                            //Replace the Tag with the returning content from the panel
                            if (function_exists($panel)){
                                $output = show($output, array( $panel => $panel() ));
                            }
                    }
            } 
            closedir($panels);

            return $output;
    }

    //Dateipfad und Tags[array] werden übergeben
    function show($file_content = "", $tags = array(null => null))
    {
            global $path;

            //Tags werden gesplittet einzeln durchgeführt

    if(file_exists($path['style']."/".$file_content.".html"))
            {
                    $file_content = getFile($path['style']."/".$file_content.".html");		
            }
            foreach($tags as $name => $value)
              {
                     //Tags der Datei werden ersetzt durch funktionen und Sprachelemente
                     $file_content = str_replace('['.$name.']', $value, $file_content);
              }
            //Datei wird fertig generiert zurück gegeben.
            return $file_content;
    }



    function randomstring($length = 6) 
    {
            $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
            srand((double)microtime()*1000000);
            $i = 0;
            while ($i < $length) 
            {
                    $num = rand() % strlen($chars);
                    $tmp = substr($chars, $num, 1);
                    $pass = $pass . $tmp;
                    $i++;
            }
            return $pass;
    }

    function customHasher($pw, $salt, $rounds)
    {
            $hash = $pw;
            for ($i = 0; $i < $rounds; $i++)
            {
                    if (!($i%3.5)){
                        $hash = sha1($hash.$salt);
                    }
                    else {
                        $hash = md5($salt.$hash);
                    }
            }
            sha1($hash); 
            return $hash;
    }

    function sqlString($param) {
            return (NULL === $param ? "NULL" : '"'.mysql_real_escape_string($param).'"');
    }

    function sqlInt($param) {
            return (NULL === $param ? "NULL" : intVal ($param));
    }

    function get_gravatar( $email, $s = 80, $img = false) 
    {
            $d = 'wavatar';
            $r = 'g';
            $url = 'http://www.gravatar.com/avatar/';
            $url .= md5( strtolower( trim( $email ) ) );
            $url .= "?s=$s&d=$d&r=$r";
            if ( $img ) 
            {
                    $url = '<img src="' . $url . '" />';
            }	
            return $url;
    }

    function msg($id, $kind = 'stock')
    {
            global $path;
            switch ($kind)
            {
                default:
                    $file = show("msg/msg_stock");
                    
            }
            
            $msg = constant("_".$id);
            $msg = show ($file, array(	"msg" => $msg,
                                        "link" => $_SESSION['last_site']));
            return $msg;
    }

    function getNews($groupid = 0)
    {
            $news_posts = db("Select * From news where public_show = 1 AND grp = ".$groupid);
            $list_news = "";
            while ($get_news = mysqli_fetch_assoc($news_posts))
            {
                    $list_news .= show("news/post",array(	"news_headline"	=>	$get_news['title'],
                                                                                            "news_date"		=> 	date("m.d.y",$get_news['date']),
                                                                                            "news_content"	=>	$get_news['post']
                                                                                    ));
            }
            if ($list_news == ""){
                $list_news = _news_not_found;
            }
            return $list_news;
    }

    function permTo($permission)
    {
            return	mysqli_fetch_object(db("SELECT ".$permission." From groups WHERE id = ".sqlInt($_SESSION['group_main_id'])))->$permission;
    }

    function con($txt) {
    $txt = stripslashes($txt);
    $txt = str_replace("& ","&amp; ",$txt);
    $txt = str_replace("[","&#91;",$txt);
    $txt = str_replace("]","&#93;",$txt);
    $txt = str_replace("\"","&#34;",$txt);
    $txt = str_replace("<","&#60;",$txt);
    $txt = str_replace(">","&#62;",$txt);
    $txt = str_replace("(", "&#40;", $txt);
    $txt = str_replace("'", "&lsquo;", $txt);
    $txt = str_replace("(", "&#40;", $txt);
    return str_replace(")", "&#41;", $txt);
}