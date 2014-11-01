<?php

class Search extends ModuleModel
{
    public $tag_stats;
    private $available_tasks = array('search_engine'), $results = [];

    protected function render()
    {
        if (isset($_POST['module_task'])) {
            $task = $_POST['module_task'];
            if (in_array($task, $this->available_tasks)) {
                return $this->$task();
            }
        } else {
            return $this->render_results();
        }
    }

    protected function render_admin()
    {

    }

    private function search_engine()
    {
        function mark($str, $tag)
        {
            return str_ireplace($tag, '<span style="background-color:yellow">' . strtoupper($tag) . '</span>', $str);
        }

        function convert_result($str_a, $tag, $str_b)
        {
            return substr($str_a, -100) . $tag . substr($str_b, 0, 100);
        }

        $tag = $_POST['tags'];
        unset($_POST);
        $tags = explode(' ', $tag);
        $sm = new SiteController('*', 'filter:visibility');
        foreach ($tags as $tag) {
            $tag = preg_replace('/[^a-zA-Z0-9]/', '', $tag);
            foreach ($sm->sites as $site) {
                $haystack = strip_tags($site->modules_render(1));
                if (!empty($tag) && strpos(strtolower($haystack), strtolower($tag))) {
                    $this->found($site, mark($haystack, $tag));
                }
            }
            if (array_key_exists($tag, $this->tag_stats)) {
                $this->tag_stats->$tag = (int)$this->tag_stats->$tag + 1;
            } else {
                $this->tag_stats->$tag = 1;
            }
            $this->update();
        }
        return $this->render_results();
    }

    private function render_results()
    {
        $te = new TemplateEngine('site/modules/search_show');
        $te->addArr('results', $this->results);
        return $te->render();
    }

    private function found($site, $str)
    {
        $this->results[] = array('title' => $site->title, 'id' => $site->get_site_id(), 'found' => $str);
    }
}