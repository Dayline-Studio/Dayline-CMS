<?php

class Template
{

    public $dir;
    private $relPath;
    public $name;
    private $cssFolder = '_css/';

    public function __construct($dir, $relPath, $name)
    {
        $this->dir = $this->getCorrectDir($dir);
        $this->name = $name;
        $this->relPath = $this->getCorrectDir($relPath);
    }

    public function getFile($path) {
        $fileName = $this->dir.$path.'.html';
        if (file_exists($fileName)) {
            return file_get_contents($fileName);
        } return '';
    }

    public function getStylesheets() {
        $cssFiles = $this->getFileListFrom($this->cssFolder);
        $generatedLinks = '';
        foreach($cssFiles as $cssFile) {
            $generatedLinks .= '<link href="'.$this->getRelPath($cssFile).'" rel="stylesheet" type="text/css"/>';
        }
        return $generatedLinks;
    }

    public function getFileListFrom($path) {
        $handle = opendir($this->dir.$path);
        $files = [];
        while ($folder = readdir($handle)) {
            if (!$this->isExcluded($folder)) {
                $files[] = $this->dir.$path.$folder;
            }
        }
        closedir($handle);

        return $files;
    }

    private function getCorrectDir($dir) {
        if (substr($dir, -1, 1) != '/') {
            return $dir.'/';
        } else {
            return $dir;
        }
    }

    private function isExcluded($file) {
        if ($file != '.' && $file != '..') {
            return false;
        }
        return true;
    }

    private function getRelPath($path) {
        $paths = explode($this->relPath, $path);
        return $this->relPath.$paths[1];
    }

} 