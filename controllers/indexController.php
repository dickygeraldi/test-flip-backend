<?php
    class IndexController {
        private $model;

        function __construct($data) {
            $this->model = new $data;
        }

        public function Index() {
            return json_encode($this->model->index());
        }

    }
?>