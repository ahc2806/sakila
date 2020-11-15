<?php
    require_once './CategoriaBL.php';

    class CategoriaService {
        private $categoriaDTO;
        private $categoriaBL;

        public function __construct() {
            $this->categoriaDTO = new CategoriaDTO();
            $this->categoriaBL = new CategoriaBL();
        }

        public function create($nombre) {
            $this->categoriaDTO->nombre = $nombre;

            if($this->categoriaBL->create($this->categoriaDTO) > 0)
                echo json_encode($this ->categoriaDTO, JSON_PRETTY_PRINT);
            else
                echo json_encode(array());  
        }

        public function read($id) {
            $this->categoriaDTO = $this->categoriaBL->read($id);
            echo json_encode($this->categoriaDTO, JSON_PRETTY_PRINT);
        }

        public function update($id, $nombre) {
            $this->categoriaDTO->id = $id;
            $this->categoriaDTO->nombre = $nombre;

            if($this->categoriaBL->update($this->categoriaDTO) > 0)
                echo json_encode($this->categoriaDTO, JSON_PRETTY_PRINT);
            else
                echo json_encode(array());
        }

        public function delete($id) {
            $this->categoriaDTO->id = $id;

            if($this->categoriaBL->delete($this->categoriaDTO->id) > 0)
                echo json_encode($this->categoriaDTO, JSON_PRETTY_PRINT);
            else
                echo json_encode(array());
        }
    }

    $categoriaService = new CategoriaService();

    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET': {
                if(empty($_GET['param'])) {
                    $categoriaService->read(0);
                } else {
                    if(is_numeric($_GET['param'])){
                        $categoriaService->read($_GET['param']);
                    } else {
                        $categoriaDTO = new CategoriaDTO();
                        $categoriaDTO->response = array('CODE' => 'Error', 'TEXT' => 'El parametro debe ser numerico');
                        echo json_encode($categoriaDTO->response, JSON_PRETTY_PRINT);
                    }
                }
            break;
        }
        case 'POST': {
            $data = json_decode(file_get_contents('php://input'), true);
            if(!isset($data['nombre']) && empty($data['nombre'])) {
                $categoriaDTO = new CategoriaDTO();
                $categoriaDTO->response = array('CODE' => 'Error', 'TEXT' => 'Hay valores vacios');
                echo json_encode($categoriaDTO->response, JSON_PRETTY_PRINT);
            } else {
                $categoriaService->create($data['nombre']);
            }
            break;
        }
        case 'PUT': {
            $data = json_decode(file_get_contents('php://input'), true);
            if((!isset($data['nombre']) && empty($data['nombre'])) && (!isset($data['id']) && empty($data['id']))) {
                $categoriaDTO = new CategoriaDTO();
                $categoriaDTO->response = array('CODE' => 'Error', 'TEXT' => 'Hay valores vacios');
                echo json_encode($categoriaDTO->response, JSON_PRETTY_PRINT);
            } else {
                $categoriaService->update($data['id'], $data['nombre']);
            }
            break;
        }
        case 'DELETE': {
            $data = json_decode(file_get_contents('php://input'), true);
            if(!isset($data['id']) && empty($data['id'])) {
                $categoriaDTO = new CategoriaDTO();
                $categoriaDTO->response = array('CODE' => 'Error', 'TEXT' => 'No se ha especificado el id');
                echo json_encode($categoriaDTO->response, JSON_PRETTY_PRINT);
            } else {
                $categoriaService->delete($data['id']);
            }
            break;
        }
        default: {
            $categoriaDTO = new CategoriaDTO();
            $categoriaDTO->response = array('CODE' => 'Error', 'TEXT' => 'Peticion incorrecta');
            echo json_encode($categoriaDTO->response, JSON_PRETTY_PRINT);
        }
    }
?>