<?php
class Render {

    public static function navigation_back_from($id) {
        $sm = new SiteManager('*');
        if($site = $sm->get_site_by_search('subfrom', $sm->filter_id($id))) {
            $id = $site->id;
        }
        $sites = $sm->get_backside_list_from($id);
        $menu = '<ul>';
        foreach ($sites as $site) {
            $menu .= '<li><a href="../pages/site?show='.$site->get_site_id().'" />'.$site->title.self::get_menu($site).'</a></li>';
        }
        $menu .= '</ul>';
        return $menu;
    }

    private static function get_menu($site) {
        $menu = '';
        if (isset($site->subsites)) {
            $menu .= '<ul>';
            foreach($site->subsites as $subsite) {
                $menu .= '<li><a href="../pages/site?show='.$subsite->get_site_id().'" />'.$subsite->title.self::get_menu($subsite).'</a></li>';
            }
            $menu .= '</ul>';
        }
        return $menu;
    }
}