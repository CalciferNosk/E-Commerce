<?php defined ('BASEPATH') OR exit('No direct script access allowed');

class ItemModel extends CI_Model{

    public function __construct()
    {
        // parent::__construct();
        // $this->set_max_concat_str = "SET SESSION group_concat_max_len = 18446744073709551615;";

        
    }

   public function getItem($ids = null){
        $where = $ids == null ? '' : 'Where id in ('. implode(",",$ids) .')';
        $sql = "select * from tblItem {$where}";

        return  $this->db->query($sql)->result_array();
   }
   public function getLastItemId(){
        $sql = "select * from tblItem order by id desc limit 1";

        return $this->db->query($sql)->row();
   }
   private function getPrivate(){


   }
}