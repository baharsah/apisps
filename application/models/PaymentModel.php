<?php


Class PaymentModel Extends CI_Model {

    private function randToIDReport($rand){

        $this->db->select('idtblreport as idreport');
        $this->db->where('rand' , $rand);
        $query = $this->db->get("tblreport");

        $row = $query->row();

        return (int) $row->idreport ; 

    }

    public function publishReport($tipebayar , $user , $idproduct , $depositawal , $depositakhir , $hargaproduk , $biayaadmin , $namaproduct , $rand = NULL , $kodeadmin = NULL){
        if($tipebayar == 1){
        $data =  array(
            "idproduct" => $idproduct , 
            'depositAwal' => $depositawal , 
            'depositAkhir' => $depositakhir,
            'user' => $user,
            'hargaProduk' => $hargaproduk,
            'biayaAdmin' => $biayaadmin,
            'namaProduk' => $namaproduct,
        );
        $this->db->insert('tblreport', $data);
    }else{
        $data =  array(
            "idproduct" => NULL , 
            'depositAwal' => $depositawal , 
            'depositAkhir' => $depositakhir,
            'user' => $user,
            'hargaProduk' => $hargaproduk,
            'biayaAdmin' => $biayaadmin,
            'namaProduk' => $namaproduct,
            'rand' => $rand
        );
        $this->db->insert('tblreport', $data);
        $data2 = array (

            'tbladmin_title' => $kodeadmin,
            'tblreport_idtblreport' => $this->randToIDReport($rand),
            'identifier' => $namaproduct." ".$kodeadmin,
            'nama' => $namaproduct

        );

        $this->db->insert('tblcustomproduct', $data2);


    }
        // While Publishing report, this function is decrease deposit automatically

        $this->decreaseSaldo($user , $depositakhir);

        // return

        return array("status" => "success" , "data" => $data , "sisaSaldo"=> $this->checkSaldo($user));


    }

    private function decreaseSaldo($user , $saldoakhir){

        $this->db->set('deposit', $saldoakhir, FALSE);
        $this->db->where('iduser', $user);
        $this->db->update('tbldeposit');

    }


    public function checkSaldo($id){
        $this->db->select('deposit');
        $this->db->where('iduser'  , $id);
        $q = $this->db->get('tbldeposit');
        return $q->row() ; 
    }


    public function checkAdminPrice($code , $set){
        $this->db->select("*");
        $this->db->where("title" , $code);
        $this->db->where('hascustomprice' , $set);
        $q = $this->db->get('tbladmin');
        return $q->row();
    }

    public function generateReport($user){
        // Buat report utama
        $this->db->select("*");
        $this->db->where('user' , $user);
        $q = $this->db->get('tblreport');
        return $q->result();
    }

    public function showReadablePricingProducts(){
        $this->db->select("nama , harga");
        $q = $this->db->get('tbproduct');
        return $q->result();
    }
    public function showProduct($id){
        $this->db->select("*");
        $this->db->where('idtblproduct' , $id);
        $q = $this->db->get('tbproduct');
        return $q->row();
    }

    public function callCustomProduct($id){
        $this->db->select("*");
        $this->db->where('tblreport_idtblreport' , $id);
        $q = $this->db->get('tblcustomproduct');
        return $q->row();
    }
}