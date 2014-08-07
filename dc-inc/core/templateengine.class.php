<?php

class TemplateEngine
{

    private $html;
    private $replace_content = array();
    private $single_replace = array();
    private $template_dir;
    public $results;

    public function __construct($content = '')
    {
        $this->setHtml($content);
    }

    /**
     * @param String $dir Pfad mit endslash
     */
    public function set_dir($dir)
    {
        $this->template_dir = $dir;
    }

    public function render()
    {
        $this->html = $this->renderContent($this->html);
        return $this->html;
    }

    private function renderContent($content)
    {
        //$content = preg_replace("/\s+/", " ", $content);
        preg_match_all('/\[(.+?)\|(.+?)\|(.+?|(?R))\]/s', $content, $result,PREG_SET_ORDER);
        $this->results = $result;
        foreach ($result as $foreach) {
            $add = '';
            switch ($foreach[1]) {
                case 'array':
                    if (isset($this->replace_content[$foreach[2]])) {
                        foreach ($this->replace_content[$foreach[2]] as $zeile) {
                            $out = NULL;
                            foreach ($zeile as $name => $value) {
                                if ($out === NULL) {
                                    $out = $foreach[3];
                                }
                                if (is_string($value) | is_int($value) | is_double($value)) {
                                    $out = str_replace('{' . $name . '}', $value, $out);
                                }
                            }
                            $add .= $out;
                        }
                    } else {
                       // die('Template-Engine->Error: Variable ' . $foreach[2] . ' not defined');
                    }
                    break;
                case 'include':
                    $file = Config::$path['template'] . $foreach[2] . $foreach[3];
                    if (file_exists($file)) {
                        $add = $this->renderContent(file_get_contents($file));
                    } else {
                        trigger_error('Template-Engine->Error: include file not found->' . $file);
                    }
                    break;
                case 'if':
                    //[if|var|html<else>html2]
                    break;
            }
            $content = str_replace($foreach[0], $add, $content);
        }
        return show($content, $this->single_replace);
    }

    public function setHtml($html)
    {
        $this->html = show($html);
    }

    public function getHtml()
    {
        return $this->html;
    }

    public function add_var($tag, $value)
    {
        $this->single_replace = array_merge($this->single_replace, array($tag => $value));
    }

    public function add_vars($case)
    {
        $this->single_replace = array_merge($this->single_replace, (array)$case);
    }

    public function addReplace($arr)
    {
        $this->replace_content = array_merge($this->replace_content, (array)$arr);
    }

    public function addArr($varname, $arr)
    {
        $this->replace_content = array_merge($this->replace_content, array($varname => $arr));
    }

    private function switchArrayPositions($arr)
    {
        $new = array();
        foreach ($arr as $value) {
            foreach ($value as $num => $var) {
                $new[$num][] = $value[$num];
            }
        }
        return $new;
    }
} 