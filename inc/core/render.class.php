<?php
class Render {



    public static function navigation_back_from($id) {

        $sm = new SiteManager('*');
        $sites = $sm->get_backside_list_from($id);
        $menu = '<ul>';
        foreach ($sites as $site) {
            $menu .= '<li>'.$site->title.self::get_menu($site).'</li>';
        }
        $menu .= '</ul>';
        return $menu;
    }

    private static function get_menu($site) {
        $menu = '';
        if (isset($site->subsites)) {
            $menu .= '<ul>';
            foreach($site->subsites as $subsite) {
                $menu .= '<li>'.$subsite->title.self::get_menu($subsite).'</li>';
            }
            $menu .= '</ul>';
        }
        return $menu;
    }
}