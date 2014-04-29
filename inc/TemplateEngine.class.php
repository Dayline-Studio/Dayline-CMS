<?php
class TemplateEngine {

    private $html;
    private $replace_content = array();

    public function render() {

        preg_match_all('/\[(.*)\|(.*)\|(.*)\]/',$this->html,$result);
        $result = $this->switchArrayPositions($result);

        foreach ($result as $foreach) {
            $add = '';
            foreach($this->replace_content[$foreach[2]] as $zeile) {
                $out = NULL;
                foreach ($zeile as $name => $value){
                    if ($out === NULL) {
                        $out = $foreach[3];
                    }
                    $out = str_replace('{'.$name.'}', $value, $out);
                }
                $add .= $out;
            }
            $this->html = str_replace($foreach[0],$add,$this->html);
        }
    }

    public function setHtml($html) {
        $this->html = $html;
    }
    
    public function getHtml() {
        return $this->html;
    }

    public function addReplace($arr) {
        $this->replace_content = array_merge($this->replace_content, $arr);
    }
    
    public function addArr($varname, $arr) {
        $this->replace_content = array_merge($this->replace_content, array($varname => $arr));
    }

    private function switchArrayPositions($arr) {
        foreach($arr as $value) {
            foreach ($value as $num => $var) {
                $new[$num][] = $value[$num];
            }
        }
        return $new;
    }
} 
