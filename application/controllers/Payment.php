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
            array('nama' => 'Akmal Ilham Ardiansyah' , 'harga' => 89000),
            array('nama' => 'Mikata Akari' , 'harga' => 79000),
            array('nama' => 'Eiris Arkani Arfan Perkasa' , 'harga' => 193000),


        );
        return $a[rand(0,(count($a)-1))]  ;
    }

    public function topup(){
        header("Content-Type: application/json");
        $this->load->model('paymentmodel');
        // Cek Saldo
         $this->sal = $this->paymentmodel->checkSaldo((int) $this->input->post('user'))->deposit;
         //cek produk
         $this->product = $this->paymentmodel->showProduct((int) $this->input->post('product_code'));
         // Check biaya admin 
         $this->admin = $this->paymentmodel->checkAdminPrice($this->paymentmodel->showProduct((int) $this->input->post('product_code'))->idadmin , 0);
         //tambah report
        echo json_encode($this->paymentmodel->publishReport(1, (int) $this->input->post('user') , $this->product->idtblproduct , $this->sal , (int)($this->sal - ((int) $this->product->harga + (int) $this->admin->harga))  , $this->product->harga , $this->admin->harga , (string) $this->product->nama." ".(string) $this->input->post('payment_code') ));



    }

    public function payment(){

        header("Content-Type: application/json");
        $this->load->model('paymentmodel');
        // Cek Saldo
         $this->sal = $this->paymentmodel->checkSaldo((int) $this->input->post('user'))->deposit;
         //cek produk
         $this->product = $this->paymentmodel->showProduct((int) $this->input->post('product_code'));
         // Check biaya admin 
         $this->admin = $this->paymentmodel->checkAdminPrice($this->paymentmodel->showProduct((int) $this->input->post('product_code'))->idadmin , 0);
         //tambah report
        echo json_encode($this->paymentmodel->publishReport(1, (int) $this->input->post('user') , $this->product->idtblproduct , $this->sal , (int)($this->sal - ((int) $this->product->harga + (int) $this->admin->harga))  , $this->product->harga , $this->admin->harga , (string) $this->product->nama." ".(string) $this->input->post('payment_code') , rand(1,99999) , $this->admin->title));

    }



    public function checkstandard(){
        header("Content-Type: application/json");
        $k ;
        $this->load->model('paymentmodel');
        $k['productCode'] = (int) $this->input->post('product_code');
        $k['productAdmin'] = $this->paymentmodel->showProduct($k['productCode'])->idadmin;
        $t = $this->paymentmodel->checkAdminPrice($k['productAdmin'] , 0);
        if( is_null($t)){
            $k["code"] = 404 ;
            $k['message'] = "Produk ini memiliki harga absolut. Silahkan gunakan fitur Pembayaran Tagihan atau hubungi <call> untuk informasi lebih lanjut";
        }else{
        $k['adminPrice'] = (int) $t->harga ;
        $k['daprice'] = $this->paymentmodel->showProduct($k['productCode']) ; 
        $k['totalprice'] = $this->paymentmodel->showProduct($k['productCode'])->harga + (int) $t->harga ;
    }
    echo json_encode($k)  ;


    }

    public function checkcustom(){
        header("Content-Type: application/json");
        $k ;
        $this->load->model('paymentmodel');
        $k['paymentCode'] = (int) $this->input->post('payment_code');
        $k['productAdmin'] = $this->input->post('admin_code');
        $t = $this->paymentmodel->checkAdminPrice($k['productAdmin'] ,1);
        if( is_null($t)){
            $k["code"] = 404 ;
            $k['message'] = "Produk ini memiliki harga absolut. Silahkan gunakan fitur Topup atau hubungi <call> untuk informasi lebih lanjut";
        }else{
        $k['adminPrice'] = (int) $t->harga ;
        $k['daprice'] = $this->dummydata($k['paymentCode']) ; 
        $k['totalprice'] = $this->dummydata($k['paymentCode'])['harga'] + (int) $t->harga ;
    }
    echo json_encode($k)  ;


    }

    public function report(){
        header("Content-Type: application/json");
        $this->load->model('paymentmodel');
        foreach($this->paymentmodel->generateReport($this->input->post('user')) as $r){
            if(is_null($r->idproduct)){
                $r->detailTambahan = $this->paymentmodel->callCustomProduct($r->idtblreport);
               }else{
                $r->detailProduk = $this->paymentmodel->showProduct($r->idproduct);

               }
            $t[]  = $r;
            
        }
        echo json_encode($t);


    }
}