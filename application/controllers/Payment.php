<?php


Class Payment Extends CI_Controller {

    /*
    *      Contoh data dummy *
    *      Data dummy yang mempresentasikan data yang di request dari server payment
    */
    
    private function dummydata($codepayment){
        $a = array(
            array("nama" => "Aeri Clarissa Watson" , "harga" => 245300),
            array('nama' => "Ibnu Abdul Muttaqien" , "harga" => 300000),
            array("nama" => "Aziz Albantani" , "harga" => 90000),
            array('nama' => 'Akmal Ilham Ardiansyah' , 'harga' => 89000)
        );
        return $a[rand(0,(count($a)-1))]  ;
    }



    public function check(){
        header("Content-Type: application/json");
        $k ;
        $this->load->model('paymentmodel');
        $k['paymentCode'] = (int) $this->input->post('payment_code');
        $k['productAdmin'] = $this->input->post('admin_code');
        $t = $this->paymentmodel->checkAdminPrice($k['productAdmin']);
         $k['adminPrice'] = (int) $t->harga ; 
        $k['daprice'] = $this->dummydata($k['paymentCode']) ; 
        echo json_encode($k)  ;

    }
}