<?php

class Render
{

    public static function navigation_back_from($id)
    {
        $sm = new SiteManager('*', 'filter:visibility');
        if ($site = $sm->get_site_by_search('subfrom', $sm->filter_id($id))) {
            $id = $site->id;
        }
        $sites = $sm->get_backside_list_from($id);
        $menu = '<ul>';
        if ($sites) {
            foreach ($sites as $site) {
                $menu .= '<li><a href="'. $site->get_url() . '" >' . $site->title . '</a>' . self::get_menu($site) . '</li>';
            }
            $menu .= '</ul>';
            return $menu;
        }
        return $id;
    }

    private static function get_menu($site)
    {
        $menu = '';
        if (isset($site->subsites)) {
            $menu .= '<ul>';
            foreach ($site->subsites as $subsite) {
                $menu .= '<li><a href="'. $subsite->get_url() . '">' . $subsite->title . '</a>' . self::get_menu($subsite) . '</li>';
            }
            $menu .= '</ul>';
        }
        return $menu;
    }
}