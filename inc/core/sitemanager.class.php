<?php
class SiteManager {

    public $sites;

    public function __construct() {
        $ids = func_get_args();
        if ($ids[0] !== 0) {
            if ($ids[0] === '*') {
                $sql = "select * from sites ORDER BY position";
            } else {
                foreach ($ids as $id) {
                    $this->filter_id($id);
                    $arr[] = "id = '$id'";
                }
                $sql = "select * from sites where (". implode(' OR ', $arr).') ORDER BY position DESC';
            }
            $result = Db::npquery($sql,PDO::FETCH_ASSOC);
            foreach ($result as $site) {
                $this->sites[$site['id']] = new Site($site);
            }
        }
    }

    public function filter_id($str) {
        $tags = explode('-',$str);
        return $tags[0];
    }

    public function wipe() {
        unset($sites);
        $this->sites = NULL;
    }

    public function get_first_site() {
        return reset($this->sites);
    }

    public function create_site($data) {
        $up = array(
            'title' => $data['title'],
            'content' => $data['content'],
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

    public function get_site_by_search($key, $search) {
        foreach ($this->sites as $site) {
            if (strtolower($site->$key) == strtolower ($search)) {
                return $site;
            }
        }
        return false;
    }

    public function get_backside_list_from($id) {
        $id = $this->filter_id($id);
        if (isset($this->sites[$id])) {
            return $this->get_site_list_from($this->sites[$id]);
        } return FALSE;
    }

    private function get_site_list_from($s_site) {
        $z = array($s_site);
        foreach ($this->sites as $site) {
            if($site->subfrom == $s_site->subfrom && $s_site != $site) {
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

    public function get_subsites_from($id) {
        $ret = false;
        foreach ($this->sites as $site) {
            if ($site->subfrom == $id) {
                if ($subsites = $this->get_subsites_from($site->id))
                    $site->subsites= $subsites;
                $ret[] = $site;
            }
        }
        return $ret;
    }
}