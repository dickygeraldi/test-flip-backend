<?php

    class DisburseController {
        private $model;

        function __construct($data) {
            $this->model = new $data;
        }

        public function validasiJson($dataJson) {
            $response = array();
            if($dataJson->{'accountNumber'} != '' || $dataJson->{'accountName'} != '' || $dataJson->{'amount'} != '' || $dataJson->{'bankCode'} != ''){
                if(strlen($dataJson->{'accountNumber'}) < 11){
                    true;
                }else{
                    $response['accountNumber'] = [
                        'message' => "Data account number tidak boleh lebih dari 10",
                        'field' => 'AccountNumber'
                    ];
                }
                
                if(strlen($dataJson->{'accountName'}) < 51){
                    true;
                }else{
                    $response['accountName'] = [
                        'message' => "Data account name tidak boleh lebih dari 50",
                        'field' => 'AccountName'
                    ];
                }

                if($dataJson->{'amount'} <= 100000 && $dataJson->{'amount'} >= 5000000){
                    $response['amount'] = [
                        'message' => "Data amount tidak boleh lebih dari 5 juta dan kurang dari 100 ribu",
                        'field' => 'amount'
                    ];
                }else{
                    true;
                }

                if(strlen($dataJson->{'bankCode'}) < 4){
                    true;
                }else{
                    $response['bankCode'] = [
                        'message' => "Data bank code tidak boleh lebih dari 3",
                        'field' => 'bankCode'
                    ];
                }
            }else{
                return $response = "Semua data harus diisi";
            }

            return $response;
        }

        public function productBank() {
            $dataBank = $this->model->getDataBank();
            header('Content-Type: application/json');

            return json_encode($dataBank);
        }

        public function getData() {
            $dataTransaksi = $this->model->getDataInvoice();
            header('Content-Type: application/json');

            return json_encode($dataTransaksi);
        }

        public function inquiryDisburse() {
            header('Content-Type: application/json');

            $jsonReq = file_get_contents('php://input');
            $jsonObj = json_decode($jsonReq);

            if(empty($this->validasiJson($jsonObj))){
                $dataInquiry = $this->model->modelDisburseInquiry($jsonObj);
                return json_encode($dataInquiry); 
            }else{
                return json_encode($this->validasiJson($jsonObj));
            }   
        }

        public function Disbursement() {
            header('Content-Type: application/json');
            $message = "";
            $jsonReq = file_get_contents('php://input');
            $jsonObj = json_decode($jsonReq);

            if($jsonObj->{'invoiceNumber'} != '') {
                $hasil = $this->model->modelDisbursement($jsonObj->{'invoiceNumber'});
                return json_encode($hasil);
            } else {
                $message = "Invoice number wajib diisi";
                return json_encode(array(
                    'status' => '422',
                    'message' => $message
                ));
            }
        }

        public function checkDetailInvoice() {
            header('Content-Type: application/json');
            $jsonReq = file_get_contents('php://input');
            $jsonObj = json_decode($jsonReq);

            if($jsonObj->{'invoiceNumber'} != '') {
                $hasil = $this->model->checkDetailInvoice($jsonObj->{'invoiceNumber'});
                return json_encode($hasil);
            } else {
                $message = "Invoice number wajib diisi";
                return json_encode(array(
                    'status' => '422',
                    'message' => $message
                ));
            }
        }
    }
?>