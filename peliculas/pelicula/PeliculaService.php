<?php
    require_once './PeliculaBL.php';

    class PeliculaService {
        private $peliculaDTO;
        private $peliculaBL;

        public function __construct() {
            $this->peliculaDTO = new PeliculaDTO();
            $this->peliculaBL = new PeliculaBL();
        }

        public function read() {
            $array = $this->peliculaBL->read();
            echo json_encode($array, JSON_PRETTY_PRINT);
        }

        public function readById($id) {
            $this->peliculaDTO = $this->peliculaBL->readById($id);
            echo json_encode($this->peliculaDTO, JSON_PRETTY_PRINT);
        }
    }

    $peliculaService = new PeliculaService();

    if($_SERVER['REQUEST_METHOD'] == 'GET') {
        if(empty($_GET['param'])) {
            $peliculaService->read();
        } else {
            if(is_numeric($_GET['param'])){
                $peliculaService->readById($_GET['param']);
            } else {
                $peliculaDTO = new PeliculaDTO();
                $peliculaDTO->response = array('CODE' => 'Error', 'TEXT' => 'El parametro debe ser numerico');
                echo json_encode($peliculaDTO->response, JSON_PRETTY_PRINT);
            }
        }
    } else {
        $peliculaDTO = new PeliculaDTO();
        $peliculaDTO->response = array('CODE' => 'Error', 'Request' => 'Peticion Incorrecta');
        echo json_encode($peliculaDTO->response, JSON_PRETTY_PRINT);
    }
?>