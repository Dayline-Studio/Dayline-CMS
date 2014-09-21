<?php

class SiteNavigator extends MainModule
{
    public $kind = 'list';
    private $kinds = array('list','line', 'line-bootstrape', 'sitemap');

    protected function render()
    {
     switch($this->kind) {
         case 'list':
             if ($cSite = SiteManager::get_current_site()) {
                 $sm = new SiteManager('*', 'filter:visibility');
                 $sites = $sm->get_backside_list_from($cSite->id);
                 return $this->render_navi($sites);
             }
             break;
         case 'line-bootstrape':
             if ($cSite = SiteManager::get_current_site()) {
                 $sm = new SiteManager('*', 'filter:visibility');

                return $this->generate_bootstrape_line($cSite, $sm, true);
             }
             break;
         case 'line':
             if ($cSite = SiteManager::get_current_site()) {
                 $sm = new SiteManager('*', 'filter:visibility');

                return $this->generate_line($cSite, $sm);
             }
             break;
         case 'sitemap':
             if ($cSite = SiteManager::get_current_site()) {
                 $sm = new SiteManager('*', 'filter:visibility');
                return $this->generate_sitemap($sm->get_subsites_from(0));
             }
             break;
     }
        return '';
    }

    private function generate_sitemap($sites) {
        $add = '';
        foreach ($sites as $site) {
            $title = $site->get_link();
            $add .= "<li>$title</li>";
            if (!empty($site->subsites)) {
                $add .= $this->generate_sitemap($site->subsites);
            }
        }
        return "<ul>$add</ul>";
    }

    private function generate_line($site, $sm, $active = false) {
        if ($site->subfrom != 0) {
            return $this->generate_line($sm->sites[$site->subfrom],$sm).' >> '.$site->get_link();
        } else {
            return $site->get_link();
        }
    }

    private function generate_bootstrape_line($site, $sm, $active = false) {
        return (!$site->subfrom ? "<ol class=\"breadcrumb\">" : $this->generate_bootstrape_line($sm->sites[$site->subfrom],$sm)) ."<li ".($active ? 'class="active"' : '').">".($active ? $site->title : $site->get_link())."</li>".($active ? '</ol>' : '');
    }

    private function render_navi($arr)
    {
        $ret = '<ul>';
        foreach ($arr as $site) {
            $add = "";
            if (isset($site->subsites)) $add = $this->render_navi($site->subsites);
            $ret .= '<li><a href="' . $site->get_url() . '">' . $site->title . '</a>' . $add . '</li>';
        }
        return $ret . '</ul>';
    }

    protected function render_admin()
    {
        $te = new TemplateEngine('site/modules/sitenavigator_admin');
        $kinds = [];
        foreach ($this->kinds as $kind) {
            $kinds[] = array('kind' => $kind);
        }
        $te->addArr('kinds', $kinds);
        return $te->render();
    }
}