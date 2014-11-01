<?php

class StorageController
{

    private $templates = [];
    private $templatePath = 'templates/';
    private $includePath = 'include/';

    private $defaultTemplate;

    private $storagePath;
    private $storagePathRel;

    /**
     * with end slash
     * @param $path
     * @param $relPath
     */
    public function __construct($path, $relPath)
    {
        $this->storagePathRel = $relPath;
        $this->storagePath = $this->getCorrectDir($path);
        $this->searchTemplates();
        $this->defaultTemplate = new Template(Config::$path['template_default_abs'], Config::$path['template_default'], 'default');
    }

    public function searchTemplates()
    {
        $handle = opendir($this->storagePath . $this->templatePath);
        while ($folder = readdir($handle)) {
            if (!$this->isExcluded($folder)) {
                $this->templates[] = new Template($this->storagePath . $this->templatePath . $folder, $this->storagePathRel, $folder);
            }
        }
        closedir($handle);
    }

    public function isTemplateAvailable()
    {
        if (sizeof($this->templates) == 0) {
            return false;
        }
        return true;
    }

    /**
     * @param $name
     * @return Template|null
     */
    public function getTemplate($name)
    {
        foreach ($this->templates as $template) {
            if ($template->name == $name) {
                return $template;
            }
        }
        return NULL;
    }

    public function getTemplateFileSecure($file)
    {
        return $this->getTemplateFile(Config::$settings->style, $file);
    }

    /**
     * @param $templateName
     * @param $file
     * @return string
     */
    public function getTemplateFile($templateName, $file)
    {
        if ($template = $this->getTemplate($templateName)) {
            if ($file = $template->getFile($file)) {
                return $file;
            } else if ($file = $this->defaultTemplate->getFile($file)) {
                return $file;
            }
        }
        return '';
    }

    private function getCorrectDir($dir)
    {
        if (substr($dir, -1, 1) != '/') {
            return $dir . '/';
        } else {
            return $dir;
        }
    }

    private function isExcluded($file)
    {
        if ($file != '.' && $file != '..') {
            return false;
        }
        return true;
    }

}