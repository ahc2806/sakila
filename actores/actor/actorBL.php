<?php
    require('../dto/actorDTO.php');
    require('../connection.php');
    
    class ActorBL {
        private $conn;
        
        public function __construct() {
            $this -> conn = new Connection(); 
        }

        public function create($actorDTO) {
            $this -> conn -> OpenConnection();
            $connsql = $this -> conn -> getConnection();
            $lastInsertId = 0;
            
            try{
                if($connsql) {
                    $connsql -> beginTransaction();
                    $sqlStatment = $connsql -> prepare(
                        "INSERT INTO actor VALUES(
                            default,
                            :first_name,
                            :last_name,
                            current_timestamp
                        )"
                    );

                    $sqlStatment -> bindParam(':first_name', $actorDTO->nombre);
                    $sqlStatment -> bindParam(':last_name', $actorDTO->apellidos);
                    $sqlStatment -> execute();

                    $lastInsertId = $connsql -> lastInsertId();
                    $connsql -> commit();
                }
            } catch(PDOException $e) {
                $connsql -> rollBack();
                $lastInsertId = 0;
            }
            return $lastInsertId;
        }

        public function read($id) {
            $this -> conn -> OpenConnection();
            $connsql = $this -> conn -> getConnection();
            $arrayActor = new ArrayObject();
            $sqlquery = "SELECT * FROM actor";

            if($id > 0)
                $sqlquery = "SELECT * FROM actor WHERE actor_id = {$id}";
            try {
                if($connsql) {
                    foreach($connsql -> query($sqlquery) as $row) {
                        $actorDTO = new ActorDTO();
                        $actorDTO -> id = $row['actor_id'];
                        $actorDTO -> nombre = $row['first_name'];
                        $actorDTO -> apellidos = $row['last_name'];
                        $arrayActor -> append($actorDTO);
                    }
                }
            } catch(PDOException $e) {
                $arrayActor = Array();
            }
            return $arrayActor;
        }

        public function update($actorDTO) {
            $this -> conn -> OpenConnection();
            $connsql = $this -> conn -> getConnection();
            
            try{
                if($connsql) {
                    $connsql -> beginTransaction();
                    $sqlStatment = $connsql -> prepare(
                        "UPDATE actor SET 
                            first_name = :first_name,
                            last_name = :last_name
                        WHERE actor_id = :id"
                    );

                    $sqlStatment -> bindParam(':id', $actorDTO->id);
                    $sqlStatment -> bindParam(':first_name', $actorDTO->nombre);
                    $sqlStatment -> bindParam(':last_name', $actorDTO->apellidos);
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
            $this -> conn -> OpenConnection();
            $connsql = $this -> conn -> getConnection();
            
            try{
                if($connsql) {
                    $connsql -> beginTransaction();
                    $sqlStatment = $connsql -> prepare(
                        "DELETE FROM actor WHERE actor_id = {$id}"
                    );
                    $sqlStatment -> execute();
                    $connsql -> commit();
                    return $id;
                }
            } catch(PDOException $e) {
                $connsql -> rollBack();
                return 0;
            }
        }
    }
?>