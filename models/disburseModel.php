<?php 
    include 'app/connection.php';
    include_once 'config/autoload.php';

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

        public function getDataInvoices() {
            $connection = database();
            $sql = 'SELECT * FROM Invoice';
            $hasil = $connection->query($sql);

            closeDb($connection);
            return $hasil;
        }

        public function getInvoice($invoice) {
            $connection = database();
            $sql = 'SELECT bankCode, accountNumber, amount, remark  FROM Invoice WHERE invoiceId = "'.$invoice.'"';
            $hasil = $connection->query($sql);

            closeDb($connection);
            return $hasil;
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

        public function getDataInvoice() {
            $message = "";
            $hasil = $this->getDataInvoices();

            if($hasil->num_rows > 0){
                $dataInvoices = array();
                while($row = $hasil->fetch_assoc()){
                    $data['invoice'] = $row['invoiceId'];
                    $data['bankCode'] = $row['bankCode'];
                    $data['accountName'] = $row['accountName'];
                    $data['amount'] = $row['amount'];
                    $data['transactionDate'] = $row['createdAt'];
                    $data['status'] = $row['status'];

                    array_push($dataInvoices, $data);
                }
                $message = 'Berhasil mengambil data invoice';
            }else{
                $message = 'Anda belum memiliki transaksi';
            }

            $response = array(
                "statusCode" => "00",
                "message" => $message
            );
            
            $response['data'] = $dataInvoices;
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

        public function modelDisbursement($invoice) {
            $message = "";
            $checkInvoice = $this->getInvoice($invoice);
            $url = env('BASE_URL_DISBURSEMENT').''.env('ENDPOINT_DISBURSE');
            
            if($checkInvoice->num_rows > 0){
                while($row = $checkInvoice->fetch_assoc()){
                    $data = array (
                        'account_number' => $row['accountNumber'],
                        'bank_code' => $row['bankCode'],
                        'amount' => $row['amount'],
                        'remark' => $row['remark']
                    );

                    $header = array (
                        'Content-Type' => 'application/x-www-form-urlencoded'
                    );

                    $response = $this->HttpRequest($url, $data, $header, 'POST');
                    $responseBody = json_decode($response);
                    
                    $message = "Berhasil mengirim data ke Flip";   
                }
            }else{
                $message = "Data invoice tidak ditemukan";
            }

            $response = array(
                "statusCode" => "00",
                "message" => $message
            );
        
            return $response;
        }

        public function HttpRequest($url, $data, $content, $method) {
            $curlRequest = curl_init($url); 
            curl_setopt($curlRequest, CURLOPT_TIMEOUT, 30);
            curl_setopt($curlRequest, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            curl_setopt($curlRequest, CURLOPT_CUSTOMREQUEST, $method);
            curl_setopt($curlRequest, CURLOPT_POSTFIELDS, $data);
            curl_setopt($curlRequest, CURLOPT_HTTPHEADER, $content);
            curl_setopt($curlRequest, CURLOPT_USERPWD, env('HEADER_AUTH_FLIP').":");  

            $response = curl_exec($curlRequest);
            curl_close($curlRequest);

            return $response;
        }
    }
?>