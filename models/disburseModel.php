<?php 
    include 'app/connection.php';

    class DisburseModel {
        function __construct(){
            
        }

        public function generateInvoice($length) {
            $strResult = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

            return substr(str_shuffle($strResult), 0, $length);
        }
        
        public function getBankData() {
            $connection = database();
            $sql = 'SELECT * FROM Bank';
            $hasil = $connection->query($sql);

            closeDb($connection);
            return $hasil;
        }

        public function getDataDisburse() {
            $connection = database();
            
        }

        public function checkDataBank($bankCode) {
            $connection = database();
            $sql = 'SELECT * FROM Bank WHERE bankCode = "'.$bankCode.'"';
            $hasil = $connection->query($sql);

            if($hasil->num_rows > 0){
                return true;
            }else{
                return false;
            }
            closeDb($connection);
        }

        public function insertDataInvoice($data) {
            $connection = database();
            $sql = "INSERT INTO Invoice (
                invoiceId, bankCode, accountNumber, accountName, amount, remark, status, createdAt
            ) VALUES (
                '".$data["invoice"]."', '".$data["bankCode"]."', '".$data["accountNumber"]."', '".$data["accountName"]."', '".$data["amount"]."', '".$data["remark"]."', '".$data["status"]."', '".$data["createdAt"]."'
            )";

            if($connection->query($sql) === true) {
                return true;
            }else{
                return "Error: ". $connection->error;
            }

            closeDb($connection);
        }

        public function getDataBank() {
            $message = "";
            $hasil = $this->getBankData();
            
            if($hasil->num_rows > 0){
                $dataBank = array();
                while($row = $hasil->fetch_assoc()) {
                    $data['bankCode'] = $row['bankCode'];
                    $data['bankName'] = $row['bankName'];
                    array_push($dataBank, $data);                   
                }
                $message = "Berhasil mengambil data bank";
            }else{
                $message = "Data bank tidak tersedia";
            }

            $response = array(
                "statusCode" => "00",
                "message" => $message
            );
            
            $response['data'] = $dataBank;
            return $response;
        }

        public function modelDisburseInquiry($jsonData) {

            $hasil = $this->checkDataBank($jsonData->{'bankCode'});
            $dataInquiry = array();
            $message = "";

            if($hasil === true){
                $data['invoice'] = $this->generateInvoice(6);
                $data['status'] = "PENDING";
                $data['createdAt'] = date("Y-m-d h:i:sa");;
                $data['bankCode'] = $jsonData->{'bankCode'};
                $data['accountName'] = $jsonData->{'accountName'};
                $data['accountNumber'] = $jsonData->{'accountNumber'};
                $data['amount'] = $jsonData->{'amount'};
                $data['remark'] = $jsonData->{'remark'};

                $queryHasil = $this->insertDataInvoice($data);
                if($queryHasil === true) {
                    array_push($dataInquiry, $data);
                }else{
                    $message = $queryHasil;
                }

            }else{
                $message = "Kode bank ".$jsonData->{'bankCode'}.' tidak ditemukan';
            }

            $response = array(
                "statusCode" => "00",
                "message" => $message
            );
            
            $response['data'] = $dataInquiry;
            return $response;
        }
    }
?>