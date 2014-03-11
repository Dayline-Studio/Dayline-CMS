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
            display($init);
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
            $url .= "?s=$s&amp;d=$d&amp;r=$r";
            if ( $img ) 
            {
                    $url = '<img src="' . $url . '" />';
            }	
            return $url;
    }

    function msg($msg, $kind = 'stock')
    {
            global $path;
            switch ($kind)
            {
                default:
                    $file = show("msg/msg_stock");
                    
            }
           
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

  function check_email_address($str_email_address) {
    if('' != $str_email_address && !((eregi("^[_\.0-9a-z-]+@([0-9a-z-]+\.)+[a-z]{2,6}$",$str_email_address)))) {
      return false;
    } else {
      return true;
    }
  }
  
  function getCommentBox($site)
  {
      $comment_parts = "";
      $comments = $db("select * from comments where site = ".$site);
      while ($comment = $comments)
      {
          $comment_parts = getComment($comment);
      }
      return $comment_parts.dispCommentInput();
  }
  
  function getComment($comment)
  {
    $com_disp = "";
    $date = ((time()-$comment['date'])/60);
    if ($date*60 < 60){
        $out = (int)($date*60) . " sec ago";
    }
    else if ($date < 60) {
        $out = (int)$date." min ago";
    }
    else if ($date > 59 && $date/60 < 24){
        $out = "vor ".(int)($date/60)."h at ". date("h:i A",$comment['date']);
    }
    else if ($date/60 > 23 && $date/60/24 < 4) {
        $out = (int)($date/60/24)." day(s) ago at ". date("h:i A",$comment['date']);
    }
    else {
        $out = date("F j, g:i a", $comment['date']);
    }

    $user = db("select email from users where id = ".$comment['userid'],'object');
    $gravatar = get_gravatar($user->email, 52, false);
    $com_disp .= show("ucp/comment", array("user" => $comment['name'],
                                            "gravatar" => $gravatar,
                                            "content" => $comment['content'],
                                            "date" => $out));

    
    return $com_disp;
    }
    
  
  function getComments($site = 0, $subsite = 0)
  {
    $comments = db("SELECT * FROM comments WHERE site = ".$site." AND subsite = ".$subsite);
    
    $output ='';
    while ($comment = mysqli_fetch_assoc($comments))
    {
        $output .= getComment($comment);
    }
    return $output;
  }
  
  function dispCommentInput($site = 0, $subsite = 0)
  {
     $gravatar = get_gravatar($_SESSION['email'], 52, false);
     return show("ucp/comment_input", 
          array(
               "gravatar" => $gravatar,
               "user" => $_SESSION['name'],
               "site" => $site,
               "subsite" => $subsite,
                 )); 
  }