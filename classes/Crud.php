<?php

include_once('conexao/conexao.php');

$db = new Database();

class Crud{ // classe "Crud" Esta classe é usada para executar operações de banco de dados na tabela "carros". As operações geralmente incluem criar, ler, atualizar e excluir registros na tabela.
    private $conn;// Esta é uma variável de instância privada que armazena o objeto de conexão com o banco de dados. Ela é usada para executar consultas SQL no banco de dados.
    private $table_name = "pets"; //Esta é uma variável de instância privada que armazena o nome da tabela no banco de dados com a qual a classe "Crud" irá interagir. Neste caso, é a tabela "carros".

    public function __construct($db){
        $this->conn = $db;
    } // Este é o construtor da classe "Crud". Ele recebe um objeto de conexão com o banco de dados como parâmetro e armazena esse objeto na variável $conn da classe.

    //função para (C)riar meu registros

    public function create($postValues){
        $especie = $postValues['especie'];//Este é um método chamado create na classe "Crud" que é responsável por criar um novo registro na tabela "carros" do banco de dados com base nos valores passados no array $postValues
        $comportamento = $postValues['comportamento'];// Estas linhas estão extraindo os valores das chaves do array $postValues e armazenando-os em variáveis locais. Os valores são provavelmente aqueles que foram enviados através de um formulário HTML usando o método POST.
        $locomocao = $postValues['locomocao'];
        $sexo = $postValues['sexo'];
        $ducha = $postValues['ducha'];

    $query = "INSERT INTO ". $this->table_name . " (especie, comportamento, locomocao, sexo, ducha) VALUES (?,?,?,?,?)"; // cria uma consulta SQL de inserção (INSERT) que insere um novo registro na tabela especificada ($this->table_name, que é "carros") com os valores das variáveis $modelo, $marca, $placa, $cor e $ano. Os ? são marcadores de posição para os valores que serão vinculados posteriormente.
    $stmt = $this->conn->prepare($query);//Aqui, a consulta SQL é preparada usando o objeto de conexão $this->conn. Isso permite que você vincule valores aos marcadores de posição antes de executar a consulta.
    $stmt->bindParam(1,$especie);//vinculam os valores das variáveis às posições dos marcadores de posição na consulta SQL preparada. O número entre parênteses indica qual marcador de posição está sendo vinculado ao valor.
    $stmt->bindParam(2,$comportamento);
    $stmt->bindParam(3,$locomocao);
    $stmt->bindParam(4,$sexo);
    $stmt->bindParam(5,$ducha);

    $rows = $this->read();//pode ser usado para verificar se um registro com os mesmos valores já existe antes de criar um novo.
    if($stmt->execute()){  //Se a execução for bem-sucedida, exibe um alerta dizendo "Cadastro Ok!" e redireciona para a página de leitura (?action=read) após a inserção bem-sucedida. Em seguida, retorna true para indicar que o cadastro foi feito com sucesso.
        print "<script>alert('Cadastro Ok!')</script>";
        print "<script> location.href='?action=read'; </script>";
        return true;
    }else{//deu errado a inserção de registro
        return false;
    }
}

//função para ler os registros

public function read(){
    $query = "SELECT * FROM ". $this->table_name; //selecionar todos os registros (*) da tabela especificada ($this->table_name, que é "carros"). 
    $stmt = $this->conn->prepare($query); //Isso permite que a consulta seja executada de forma segura e eficiente, evitando ataques de SQL injection.
    $stmt->execute(); //Ela obtém todos os registros da tabela "carros".
    return $stmt; // A função retorna o resultado da execução da consulta
}

//funcao atualizar registros
public function update($postValues){
    $id = $postValues['id']; //Estas linhas estão extraindo os valores das chaves do array $postValues e armazenando-os em variáveis locais
    $especie = $postValues['especie'];
    $comportamento = $postValues['comportamento'];
    $locomocao = $postValues['locomocao'];
    $sexo = $postValues['sexo'];
    $ducha = $postValues['ducha'];

    if(empty($id) || empty($especie) || empty($comportamento) || empty($locomocao) || empty($sexo) || empty($ducha)){ //Esta condição verifica se algum dos campos necessários (ID, modelo, marca, placa, cor ou ano) está vazio. Se algum deles estiver vazio, a função retorna false, indicando que a atualização não pode ser realizada devido à falta de dados essenciais.
        return false;
    }

    $query = "UPDATE ". $this->table_name . " SET especie = ?, comportamento = ?, locomocao = ?, sexo = ?, ducha = ? WHERE id = ?"; //atualização (UPDATE) que define os novos valores para as colunas "modelo", "marca", "placa", "cor" e "ano" com base no valor da coluna "id". 
    $stmt = $this->conn->prepare($query);//objeto de conexão $this->conn. Isso permite que a consulta seja executada de forma segura e eficiente.
    $stmt->bindParam(1,$especie);//vinculam os valores das variáveis locais aos marcadores de posição na consulta SQL preparada. O número entre parênteses indica qual marcador de posição está sendo vinculado ao valor.
    $stmt->bindParam(2,$comportamento);
    $stmt->bindParam(3,$locomocao);
    $stmt->bindParam(4,$sexo);
    $stmt->bindParam(5,$ducha);
    $stmt->bindParam(6,$id);
    if($stmt->execute()){//Esta parte do código executa a consulta SQL preparada. Se a execução for bem-sucedida, a função retorna true, indicando que a atualização foi concluída com sucesso. Caso contrário, retorna false, indicando que a atualização falhou.
        return true;
    }else{
        return false;
    }

    //funcao para pegar os registros do banco e inserir no formulario
}
    public function readOne($id){
        $query = "SELECT * FROM ". $this->table_name . " WHERE id = ?";//seleciona todos os campos (*) da tabela especificada ($this->table_name, que é "carros") onde o valor da coluna "id" é igual ao valor fornecido como parâmetro ($id). O ? é um marcador de posição para o valor do ID.
        $stmt = $this->conn->prepare($query);//Aqui, a consulta SQL é preparada usando o objeto de conexão $this->conn. Isso permite que a consulta seja executada de forma segura e eficiente, evitando ataques de SQL injection.
        $stmt->bindParam(1, $id);//vincula o valor da variável $id ao marcador de posição ? na consulta SQL preparada. O número 1 indica qual marcador de posição está sendo vinculado ao valor.
        $stmt->execute();// Ela busca o registro na tabela "carros" onde o ID corresponde ao valor fornecido.
        return $stmt->fetch(PDO::FETCH_ASSOC);//Após a execução da consulta, esta linha obtém o primeiro (e, em geral, o único) registro que corresponde ao critério da consulta usando fetch(PDO::FETCH_ASSOC). O resultado é retornado como um array associativo contendo os dados do registro encontrado.
    }

    //funcao para apagar os registros 
    public function delete($id){//deleta tudo kk e ainda pergunta se quer deletar mesmo
        $query = "DELETE FROM ". $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1,$id);
        if($stmt->execute()){
                return true;
        }else{
            return false;
        }
    }

}


?>