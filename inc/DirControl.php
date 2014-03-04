<?php
class DirControl {
    
    private $path, $file;
    
    public function __construct(){
        $this->path['dir'] = '/test/';
        $this->path['include'] = "../inc/";
        $this->path['style'] = "../style/".$style."/";
        $this->path['css'] = $path['style']."_css/";
        $this->path['js'] = $path['style']."_js/";
        $this->path['style_index'] = $path['style']."index.html";
        $this->path['images'] = $path['include']."images/"; 
        $this->path['plugins'] = "../plugins/"; 
        $this->path['pages'] = "../pages/"; 
        $this->path['panels'] = "../panels/"; 
        $this->file['functions'] = $path['include']."functions.php";
        $this->file['auth'] = $path['include']."auth.php";
        $this->file['init'] = $path['include']."init.php";
        $this->path['lang'] = $path['include']."language/";
        $this->file['mysql'] = $path['include']."mysql.php";
    }
    
    public function getPath($pathname)
    {
        return $this->path[$pathname];
    }
    
    public function getFile($filename)
    {
        return $this->file[$filename];
    }
}
