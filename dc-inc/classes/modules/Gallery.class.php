<?php

class Gallery extends ModuleModel
{

    protected $javaClass = 'Gallery';

    public $album_path = '';

    protected function render()
    {
        if (!empty($this->album_path)) {
            $te = new TemplateEngine('site/modules/gallery_show');
            $dir = scandir(Config::$path['root_base'] . $this->album_path);
            $images = [];
            foreach ($dir as $image) {
                if ($image != '..' && $image != '.' && is_file(Config::$path['root_base'] . $this->album_path . $image)) {
                    $images[] = array('path' => $this->album_path . $image, 'thumb' => $this->is_thumb($this->album_path . $image));
                }
            }
            $te->addArr('images', $images);
            return $this->set_infos($te->render());
        } else {
            return 'No Folder set';
        }
    }

    private function is_thumb($str)
    {
        return str_replace(Config::$path['upload_rel'], Config::$path['thumbs_rel'], $str);
    }

    protected function render_admin()
    {
        $fm = new FileManager('gallery/');
        $te = new TemplateEngine('filemanager/main');
        $te->add_var('path', $fm->dir->base_path);
        $te->addArr('folders', $fm->get_current_folder()->folders);
        $te->addArr('files', $fm->get_current_folder()->files);
        return $this->set_infos(show('site/modules/gallery_admin', array('filemanager' => $te->render())));
    }
}