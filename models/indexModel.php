<?php 

    class IndexModel {
        function __construct(){
        
        }

        public function index() {
            $jsonResponse = [
                ["statusCode" => "00", "message" => "Index File"],
                ];
            return $jsonResponse;
        }
    }
?>