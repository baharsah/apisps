<?php


Class PaymentModel Extends CI_Model {


    public function checkAdminPrice($code){
        $this->db->select("*");
        $this->db->where("title" , $code);
        $this->db->where('hascustomprice' , 1);
        $q = $this->db->get('tbladmin');
        return $q->row();
    }
}