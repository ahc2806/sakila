<?php
    require_once '../dto/CategoriaDTO.php';
    require_once '../Connection.php';

    class CategoriaBL {
        private $connection;

        public function __construct(){
            $this->connection = new Connection();
        }

        public function create($categoriaDTO) {
            $this->connection->OpenConnection();
            $connsql = $this->connection->getConnection();
            $lastInsertId = 0;
            
            try{
                if($connsql) {
                    $connsql->beginTransaction();
                    $sqlStatment = $connsql->prepare(
                        "INSERT INTO category VALUES(
                            default,
                            :name,
                            current_timestamp
                        )"
                    );

                    $sqlStatment->bindParam(':name', $categoriaDTO->nombre);
                    $sqlStatment->execute();

                    $lastInsertId = $connsql->lastInsertId();
                    $connsql->commit();
                }
            } catch(PDOException $e) {
                $connsql -> rollBack();
                $lastInsertId = 0;
            }
            return $lastInsertId;
        }

        public function read($id) {
            $this->connection->OpenConnection();
            $connsql = $this->connection->getConnection();
            $arrayCategoria = new ArrayObject();
            $sqlquery = "SELECT * FROM category";

            if($id > 0)
                $sqlquery = "SELECT * FROM category WHERE category_id = {$id}";

            try {
                if($connsql) {
                    foreach($connsql->query($sqlquery) as $row) {
                        $categoriaDTO = new CategoriaDTO();
                        $categoriaDTO->id = $row['category_id'];
                        $categoriaDTO->nombre = $row['name'];
                        $arrayCategoria->append($categoriaDTO);
                    }
                }
            } catch(PDOException $e) {
                $arrayCategoria = Array();
            }
            return $arrayCategoria;
        }

        public function update($categoriaDTO) {
            $this->connection->OpenConnection();
            $connsql = $this->connection->getConnection();
            
            try{
                if($connsql) {
                    $connsql->beginTransaction();
                    $sqlStatment = $connsql->prepare(
                        "UPDATE category SET 
                            name = :name
                        WHERE category_id = :id"
                    );

                    $sqlStatment -> bindParam(':id', $categoriaDTO->id);
                    $sqlStatment -> bindParam(':name', $categoriaDTO->nombre);
                    $sqlStatment -> execute();

                    $connsql -> commit();
                    return true;
                }
            } catch(PDOException $e) {
                $connsql -> rollBack();
                return false;
            }
        }

        public function delete($id) {
            $this->connection->OpenConnection();
            $connsql = $this->connection->getConnection();
            
            try{
                if($connsql) {
                    $connsql->beginTransaction();
                    $sqlStatment = $connsql->prepare(
                        "DELETE FROM category 
                        WHERE category_id = {$id}"
                    );
                    $sqlStatment->execute();
                    $connsql->commit();
                    return $id;
                }
            } catch(PDOException $e) {
                $connsql -> rollBack();
                return 0;
            }
        }
    }
?>