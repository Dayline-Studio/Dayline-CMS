<?php

class PageNavigation extends MainModule
{

    protected function render()
    {
        if (!isset($_GET['show'])) {
            return 'Reload required';
        }
        $te = new TemplateEngine('site/modules/pagenavigation_show');
        $sm = new SiteManager('*');

        $this_id = $sm->filter_id($_GET['show']);
        $sites = $sm->get_subsites_from($sm->sites[$this_id]->subfrom);

        for ($i = 0; $i < sizeof($sites); $i++) {
            if ($sites[$i]->id == $this_id) {
                $case['disabled_last'] = array_key_exists($i - 1, $sites) ? '' : 'disabled';
                $case['disabled_next'] = array_key_exists($i + 1, $sites) ? '' : 'disabled';
                $case['id_last'] = array_key_exists($i - 1, $sites) ? $sites[$i - 1]->get_site_id() : $sites[$i]->get_site_id() . '#';
                $case['id_next'] = array_key_exists($i + 1, $sites) ? $sites[$i + 1]->get_site_id() : $sites[$i]->get_site_id() . '#';
                break;
            }
        }
        $te->add_vars($case);
        return $te->render();
    }

    protected function render_admin()
    {
        return '';
    }
}