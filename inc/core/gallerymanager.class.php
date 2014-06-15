<?php
class GalleryManager {

    public $gallery;
    private $gallery_path;

    public function __construct($ids) {
        $this->load_gallery($ids);
    }

    public function load_gallery($ids) {
        if ($ids !== 0) {
            if ($ids === '*') {
                $sql = "select * from gallery";
            } else {
                foreach ($ids as $id) {
                    $arr[] = "id = '$id'";
                }
                $sql = "select * from gallery where (". implode(' OR ', $arr).')';
            }
            $result = Db::npquery($sql,PDO::FETCH_OBJ);
            foreach ($result as $album) {
                $this->gallery[$album->id] = new Album($album, Config::$path['gallery']);
            }
        }
    }

    public function get_first_album() {
        return reset($this->gallery);
    }

    public function get_subalbum_from($id) {
        $ret = false;
        foreach ($this->gallery as $album) {
            if ($album->subfrom == $id) {
                if ($subalbum = $this->get_subalbum_from($album->id))
                    $album->subalbum= $subalbum;
                $ret[] = $album;
            }
        }
        return $ret;
    }

}