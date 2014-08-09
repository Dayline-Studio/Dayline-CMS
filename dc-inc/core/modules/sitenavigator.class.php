<?php
class SiteNavigator extends MainModule
{

    public $kind = 'list';

    protected function render()
    {
        global $show, $meta;
        switch (substr(basename($_SERVER["PHP_SELF"]), 0, -4)) {
            case 'site':
                $sm = new SiteManager($show);
                $content = $this->siteNaviBackward($sm->get_first_site()->id);
                break;
            default:
                $content = '<a href="{pages}' . basename($_SERVER["PHP_SELF"]) . '">' . ucfirst(substr(basename($_SERVER["PHP_SELF"]), 0, -4)) . "</a>";
                break;
            case 'categories':
                $content = '<a href="{pages}' . basename($_SERVER["PHP_SELF"]) . '">' . ucfirst(substr(basename($_SERVER["PHP_SELF"]), 0, -4)) . "</a>";
                break;
        }

        if (isset($meta['page_id']) && $meta['page_id'] == 3) {
            $site_navi = $this->navigation_back_from($show);
        } else {
            $site_navi = "";
        }

        return $site_navi;
    }

    protected function render_admin()
    {

    }

    public function navigation_back_from($id)
    {
        $sm = new SiteManager('*', 'filter:visibility');
        if ($site = $sm->get_site_by_search('subfrom', $sm->filter_id($id))) {
            $id = $site->id;
        }
        $sites = $sm->get_backside_list_from($id);
        $menu = '<ul>';
        if ($sites) {
            foreach ($sites as $site) {
                $menu .= '<li><a href="'. $site->get_url() . '" >' . $site->title . '</a>' . $this->get_menu($site) . '</li>';
            }
            $menu .= '</ul>';
            return $menu;
        }
        return $id;
    }

    private function get_menu($site)
    {
        $menu = '';
        if (isset($site->subsites)) {
            $menu .= '<ul>';
            foreach ($site->subsites as $subsite) {
                $menu .= '<li><a href="'. $subsite->get_url() . '">' . $subsite->title . '</a>' . $this->get_menu($subsite) . '</li>';
            }
            $menu .= '</ul>';
        }
        return $menu;
    }

    private function siteNaviBackward($id)
    {
        $sm = new SiteManager($id);
        $site = $sm->get_first_site();
        $oversite = "";
        if ($site->subfrom != 0) {
            $oversite = $this->siteNaviBackward($site->subfrom) . ' >> ';
        }
        return $oversite . '<a href="' . $site->get_url() . '">' . $site->title . '</a>';
    }
}