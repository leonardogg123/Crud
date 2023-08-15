<?php

include_once('conexao/conexao.php');

$db = new Database();

class Crud{
    private $conn;
    private $table_name = "carros";

    public function __construct($db){
        $this->conn = $db;
    }

    //função para (C)riar meu registros

public function create($postValues){
    $modelo = $postValues['modelo'];
    $marca = $postValues['marca'];
    $placa = $postValues['placa'];
    $cor = $postValues['cor'];
    $ano = $postValues['ano'];

    $query = "INSERT INTO ". $this->table_name . " (modelo, marca, placa, cor, ano) VALUES (?,?,?,?,?)";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(1,$modelo);
    $stmt->bindParam(2,$marca);
    $stmt->bindParam(3,$placa);
    $stmt->bindParam(4,$cor);
    $stmt->bindParam(5,$ano);

    $rows = $this->read();
    if($stmt->execute()){
        print "<script>alert('Cadastro Ok!')</script>";
        print "<script> location.href='?action=read'; </script>";
        return true;
    }else{
        return false;
    }
}

//função para ler os registros

public function read(){
    $query = "SELECT * FROM ". $this->table_name;
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt;
}

}


?>