<?php

class ScriptLoader
{

    public $libPath;


    public $cacheEnabled = false;
    public $files = [], $highPriorityFiles = [];

    /**
     * @param $path - without final slash
     * @param $subfolders
     * @param $extension
     */
    public function __construct($path, $subfolders, $extension)
    {
        $this->extension = $extension;
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
                    if (is_file($dir . '/' . $file) && substr($file, (strlen($this->extension)+1)*-1) == '.'.$this->extension) {
                        $done = false;
                        foreach ($this->priorityList as $key => $value) {
                            if ($file == $value.'.'.$this->extension ) {
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
        $cacheKey = md5($this->libPath.($minify?"1":"0").$this->extension);
        if ($minify) {
            $script = __c("files")->get($cacheKey);
            if ($script == null || !$this->cacheEnabled) {
                $script = JSMin::minify($this->getContentFromAll());
                __c("files")->set($cacheKey, $script, 600);
            }
            return $script;
        }
        $script = __c("files")->get($cacheKey);
        if ($script == null || !$this->cacheEnabled) {
            $script = $this->getContentFromAll();
            __c("files")->set($cacheKey, $script, 600);
        }
        return $script;
    }

}