<?php defined('BASEPATH') or exit('No direct script access allowed');

class VideoModel extends CI_Model
{

    public function __construct()
    {
        // parent::__construct();
        // $this->set_max_concat_str = "SET SESSION group_concat_max_len = 18446744073709551615;";

    }
    function getVideo($User = null)
    {

        $where = $User == null ? '' : 'wHERE UploadedBy =' . $User;
        $sql = $this->db->query("SELECT 
                            *
                        FROM
                            uploadedvideo
                        {$where}");
        return $sql;
    }
    function getVideoById($id)
    {
        $sql = $this->db->query("SELECT 
                                    *
                                FROM
                                    uploadedvideo
                               WHERE 
                                id = {$id} ");
                return $sql;
    }
}
