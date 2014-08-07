<?php

class SiteManager
{

    public $sites;
    private static $active_site;

    public static function get_current_site() {
        $show = isset($_REQUEST['show']) ? $_REQUEST['show'] : NULL;
        if ($show) {
            $id = self::filter_id($show);
            $sm = new self($id);
            self::$active_site = $sm->get_first_site();
        } return self::$active_site;
    }

    public function __construct()
    {
        $filter = '';
        $ids = func_get_args();
        if (stristr($ids[sizeof($ids)-1],'filter')) {
            $filter = explode(':',$ids[sizeof($ids)-1])[1];
            unset($ids[sizeof($ids)-1]);
        }
        if ($ids[0] !== 0) {
            switch ($filter) {
                case 'visibility':
                    $add = ' WHERE public = 1 ';
                    break;
                default:
                    $add = '';
            }
            if ($ids[0] === '*') {
                $sql = "select * from sites $add ORDER BY position";
            } else {
                $arr = [];
                foreach ($ids as $id) {
                    $id = self::filter_id($id);
                    $arr[] = "id = '$id'";
                }
                $sql = "select * from sites where (" . implode(' OR ', $arr) . ") ORDER BY position DESC";
            }
            $result = Db::npquery($sql, PDO::FETCH_ASSOC);
            foreach ($result as $site) {
                $this->sites[$site['id']] = new Site($site);
            }
        }
    }

    public static function filter_id($str)
    {
        $tags = explode('-', $str);
        $id = $tags[sizeof($tags)-1];
        if (!is_numeric($id)) {
            foreach ($tags as $tag) {
                if(is_numeric($tag)) {
                    return $tag;
                }
            }
        } return $id;
    }

    public function wipe()
    {
        unset($sites);
        $this->sites = NULL;
    }

    public function get_first_site()
    {
        return sizeof($this->sites)>0 ? reset($this->sites) : false;
    }

    public function create_site($data)
    {
        $up = array(
            'title' => $data['title'],
            'keywords' => $data['keywords'],
            'userid' => $_SESSION['userid'],
            'description' => $data['description'],
            'subfrom' => $data['subfrom'],
            'position' => 0,
            'date' => time(),
            'show_lastedit' => isset($data['show_lastedit']) ? 1 : 0,
            'show_author' => isset($data['show_author']) ? 1 : 0,
            'show_print' => isset($data['show_print']) ? 1 : 0,
            'show_headline' => isset($data['show_headline']) ? 1 : 0
        );
        Db::insert('sites', $up);
    }

    public function get_site_by_search($keys, $search, $accuracy = 1)
    {
        if (!is_array($keys)) {
            $keys = array($keys);
        }
        foreach ($keys as $key) {
            foreach ($this->sites as $site) {
                switch ($accuracy) {
                    case 0:
                        if (strpos(strtolower($site->$key), strtolower($search))) {
                            return $site;
                        }
                        break;
                    case 1:
                        if (strtolower($site->$key) == strtolower($search)) {
                            return $site;
                        }
                }
            }
        }
        return false;
    }

    public function get_backside_list_from($id)
    {
        $id = self::filter_id($id);
        if (isset($this->sites[$id])) {
            return $this->get_site_list_from($this->sites[$id]);
        }
        return FALSE;
    }

    private function get_site_list_from($s_site)
    {
        $z = array($s_site);
        foreach ($this->sites as $site) {
            if ($site->subfrom == $s_site->subfrom && $s_site != $site) {
                $z[] = $site;
            }
        }
        if ($s_site->subfrom != 0) {
            $oversite = $this->sites[$s_site->subfrom];
            $oversite->subsites = $z;
            return $this->get_site_list_from($oversite);
        }
        return $z;
    }

    public function get_subsites_from($id)
    {
        $ret = array();
        foreach ($this->sites as $site) {
            if ($site->subfrom == $id) {
                if ($subsites = $this->get_subsites_from($site->id))
                    $site->subsites = $subsites;
                $ret[] = $site;
            }
        }
        return $ret;
    }
}