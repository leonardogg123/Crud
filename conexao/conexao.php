<?php
class Database{
    private $host = "localhost"; // nome do servidor do mysql local
    private $db_name = "zoologico"; //nome do nosso banco de dados
    private $username = "root";//usuario padrao do xampp
    private $senha="";//senha padro do xampp
    private $conn;
    
public function getConnection(){
    $this->conn = null;
    try{
        $this->conn = new PDO("mysql:host=".$this->host.";dbname=".$this->db_name,$this->username,$this->senha);
        $this->conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    }catch(PDOException $e){
        echo "Erro na conexão: ". $e->getMessage();
    }
    return $this->conn;
}
}
?>