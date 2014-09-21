<?php

class JavaScriptLoader
{

    public $libPath;

    public $files = [], $highPriorityFiles = [];

    /**
     * @param $path - without final slash
     * @param $subfolders
     */
    public function __construct($path, $subfolders)
    {
        $this->libPath = $path;
        if (file_exists($this->libPath.'/priority.json')) {
            $this->priorityList = json_decode(file_get_contents($this->libPath.'/priority.json'))->list;
        }
        $this->readFiles($this->libPath);
    }

    public function readFiles($dir)
    {
        if (is_dir($dir)) {
            if ($dh = opendir($dir)) {
                while (($file = readdir($dh)) !== false) {
                    if (is_file($dir . '/' . $file) && substr($file, -3) == '.js') {
                        $done = false;
                        foreach ($this->priorityList as $key => $value) {
                            if ($file == $value.'.js') {
                                $this->highPriorityFiles[$key] = $dir . '/' . $file;
                                $done = true;
                            }
                        }
                        if (!$done) $this->files[] = $dir . '/' . $file;
                    } else if (is_dir($dir . '/' . $file) && $file != '.' && $file != '..') {
                        $this->readFiles($dir . '/' . $file);
                    }
                }
                closedir($dh);
            }
        }
    }

    private function getContentFromAll()
    {
        $bigFile = '';
        foreach ($this->highPriorityFiles as $file) {
            $bigFile .= file_get_contents($file);
        }
        foreach ($this->files as $file) {
            $bigFile .= file_get_contents($file);
        }
        return $bigFile;
    }

    public function getScript($minify)
    {


        if ($minify) {
            $script = __c("files")->get('FrameworkjsMin');
            if ($script == null) {
                $script = JSMin::minify($this->getContentFromAll());
                __c("files")->set('FrameworkjsMin', $script, 600);
            }
            return $script;
        }
        $script = __c("files")->get('Frameworkjs');
        if ($script == null) {
            $script = $this->getContentFromAll();
            __c("files")->set('Frameworkjs', $script, 600);
        }
        return $script;
    }

}