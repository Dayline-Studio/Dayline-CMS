<?php

class NewsPost extends ModuleModel
{
    public $site_id, $date_created;

    protected function on_create()
    {
        $this->site_id = 0;
        $this->site_id = SiteController::filter_id($this->position);
        $this->date_created = time();
        //todo: rss load
    }

    protected function render()
    {
        return '';
    }

    protected function render_admin()
    {
        return 'Dieses Modul ist unsichtbar und markiert diese Seite als News Post!';
    }

    protected function on_construct() {
        $sm = new SiteController($this->site_id);
        $this->site = $sm->get_first_site();
    }

    private $site;

    public function get_content($limit = 0)
    {
        return strip_tags($this->site->modules_render(1));
    }

    public function get_title() {
        return $this->site->title;
    }

    public function get_date($output = false) {
        if ($output) {
            return date("F j, Y, G:i", $this->date_created);
        }
        return $this->date_created;
    }

    public function get_url() {
        return $this->site->get_url();
    }
}