<?php defined ('BASEPATH') OR exit('No direct script access allowed');

class GlobalModel extends CI_Model{

    public function __construct()
    {
        // parent::__construct();
        // $this->set_max_concat_str = "SET SESSION group_concat_max_len = 18446744073709551615;";

        
    }

    public function get_content()
    {
        #query here
        $data = [];
        return $data;
    }

    public function checkByUserName($Username){
        $sql = "SELECT *, count(*) as `usercount` from tblUser where UserName = '{$Username}'";
        $result = $this->db->query($sql)->row();
    
        return $result->usercount > 0 ? $result:0;
    }

}