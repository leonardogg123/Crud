<?php
require_once('classes/Crud.php');
require_once('conexao/conexao.php');

//estabelecendo uma conexão com o banco de dados, e depois criando uma instância de uma classe chamada "Crud";
$database = new Database();// Isso cria um "gerenciador de conexão" que nos permite se comunicar com um banco de dados.
$db = $database->getConnection(); // É como abrir uma linha telefônica para conversar com o banco de dados.
$crud = new Crud($db);//criando uma ferramenta que nos ajuda a realizar ações no banco de dados, como adicionar, ler, atualizar ou excluir informações


if(isset($_GET['action'])){
    switch($_GET['action']){ //switch  avalia o valor de $_GET['action'] para determinar qual caso deve ser executado.
        case 'create': // Se $_GET['action'] for igual a 'create', então chama a função $crud->create($_POST);. Isso provavelmente cria um novo registro no banco de dados com os dados que foram enviados via POST (geralmente a partir de um formulário HTML).
            $crud->create($_POST);
            $rows = $crud->read();
            break;

            case 'read': // Se $_GET['action'] for igual a 'read', então chama a função $crud->read();. Isso provavelmente lê informações do banco de dados.
                $rows = $crud->read();
                break;

            //case update Se $_GET['action'] for igual a 'update', verifica se $_POST['id'] está definido (o ID do registro a ser atualizado). Se estiver definido, chama $crud->update($_POST); para atualizar o registro no banco de dados com os dados enviados via POST. Em seguida, lê as informações atualizadas do banco de dados.

            case 'update' :
                if(isset($_POST['id'])){
                    $crud->update($_POST);
                }
                $rows = $crud->read();
                break;

            //case delete Se $_GET['action'] for igual a 'delete', chama $crud->delete($_GET['id']); para excluir o registro com o ID especificado na URL. Em seguida, lê as informações atualizadas do banco de dados.
            case 'delete':
                $crud->delete($_GET['id']);
                $rows = $crud->read();
                break;

            default: //Se $_GET['action'] não corresponder a nenhum dos casos anteriores, ele cai no bloco padrão e executa $crud->read(); para ler informações do banco de dados.
            $rows = $crud->read();
            break;
    }
}else{
        $rows = $crud->read(); //se caso der errado ele chama o read para ler o banco dados
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>crud</title>
</head>
<body>
  <style>
        form{
            max-width:500px;
            margin: 0 auto;
        }
         label{
            display: flex;
            margin-top:10px
         }
         input[type=text]{
            width:100%;
            padding: 12px 20px;
            margin: 8px 0;
            display:inline-block;
            border: 1px solid #ccc;
            border-radius:4px;
            box-sizing:border-box;
         }
         input[type=submit]{
            background-color:#4caf50;
            color:white;
            padding:12px 20px;
            border:none;
            border-radius:4px;
            cursor:pointer;
            float:right;
         }
         input[type=submit]:hover{
            background-color:#45a049;
         }
         table{
            border-collapse:collapse;
            width:100%;
            font-family:Arial, sans-serif;
            font-size:14px;
            color:#333;
         }
         th, td{
            text-align:left;
            padding:8px;
            border: 1px solid #ddd;
         }
        th{
           background-color:#f2f2f2;
           font-weight:bold; 
        }
        a{
            display:inline-block;
            padding:4px 8px;
            background-color: #007bff;
            color:#fff;
            text-decoration:none;
            border-radius:4px;
        }
        a:hover{
            background-color:#0069d9;
        }

        a.delete{
            background-color: #dc3545;
        }
        a.delete:hover{
            background-color:#c82333;
        }
    </style>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reescrita do Crud</title>
</head>
<body>
    <?php //isset Verifica se o parâmetro 'action' foi passado na URL.
    if(isset($_GET['action']) && $_GET['action'] == 'update' && isset($_GET['id'])){
        $id = $_GET['id']; //obtem o id e armazena a variavel id
        $result = $crud->readOne($id); // Chama uma função chamada readOne($id) provavelmente definida na classe $crud. Essa função provavelmente consulta o banco de dados em busca de um registro com o ID especificado e retorna as informações desse registro.

        if(!$result){
            echo "Registro não encontrado.";
            exit();
        }//Verifica se o resultado da consulta ($result) é falso, o que significa que o registro com o ID especificado não foi encontrado no banco de dados


        //quando encontrado ele armazenado separadamente modelo,marca etc
        $especie = $result['especie'];
        $comportamento = $result['comportamento'];
        $locomocao = $result['locomocao'];
        $sexo = $result['sexo'];
        $ducha = $result['ducha'];

        //obs:Em resumo, esse trecho de código PHP é responsável por verificar se um registro específico deve ser atualizado (com base no valor 'update' passado na URL) e, se sim, obter os dados desse registro do banco de dados para uso posterior na página. Se o registro não for encontrado, ele exibe uma mensagem de erro e encerra o script.
        ?>
        <!--esse formulario é usado para editar informações de um registro específico no banco de dados. -->
        
        <form acion="?action=update" method="POST"><!-- Este é o elemento de formulário. Ele define que, quando o formulário for enviado, ele enviará os dados para a mesma página com o parâmetro action definido como 'update' na URL. Isso provavelmente indica que o formulário será usado para atualizar um registro existente.-->
        <input type="hidden" name="id" value="<?php echo $id ?>"><!--Este é um campo de entrada oculto (hidden input) que contém o ID do registro que está sendo atualizado. O valor desse campo é definido como o valor da variável $id que foi obtida anteriormente. Isso permite que o sistema saiba qual registro deve ser atualizado no banco de dados. -->
            <label for="especie"> Especie</label><!--Isso cria um rótulo para o campo de entrada seguinte. O for atributo corresponde ao id do campo de entrada associado.-->
            <input type="text" name="especie" value="<?php echo $especie ?>"><!--Este é um campo de entrada de texto que permite editar o valor do campo "modelo". O valor desse campo é definido como o valor da variável $modelo, que contém o valor atual do campo no registro do banco de dados.-->

            <label for="comportamento"> Comportamento </label>
            <input type="text" name="comportamento" value="<?php echo $comportamento ?>">

            <label for="locomocao"> Placa </label>
            <input type="text" name="locomocao" value="<?php echo $locomocao ?>">

            <label for="sexo"> Cor </label>
            <input type="text" name="sexo" value="<?php echo $sexo ?>">

            <label for="ducha"> Ano </label>
            <input type="text" name="ducha" value="<?php echo $ducha ?>">

            <input type="submit" value="Atualizar" name="enviar" onclick="return confirm('Certeza que deseja atualizar?')">
            <!--Este é um botão de envio do formulário. Quando clicado, ele envia os dados do formulário para a mesma página com a ação de 'update'. O onclick atributo exibe uma confirmação antes de enviar os dados, para garantir que o usuário realmente deseja atualizar o registro.-->
        
        </form>

        <?php
        
    }else{
        ?>

<form action="?action=create" method="POST"><!--Ele define que, quando o formulário for enviado, ele enviará os dados para a mesma página com o parâmetro action definido como 'create-->

<label for="">Especie</label>
 <input type="text" name="especie"><!--deixa tu escrever-->

<label for="">Comportamento</label>
<input type="text" name="comportamento">

<label for="">Locomoção</label>
 <input type="text" name="locomocao">

<label for="">Sexo</label>
 <input type="text" name="sexo">

<label for="">Ducha</label>
 <input type="text" name="ducha">

  <input type="submit" value="cadastrar" name="enviar">
  
</form>
<?php
}

?>

<!--tabela para aparecer lá-->
<table>
          <tr>
            <td>id</td>
            <td>especie</td>
            <td>comportamento</td>
            <td>locomocao</td>
            <td>sexo</td>
            <td>ducha</td>
            <td>ações</td>
        </tr>
<?php
    if($rows->rowCount() == 0){
        echo "<tr>";
        echo "<td colspan='7'>Nenhum dado encontrado</td>";
        echo "</tr>";
    }else{
        while($row = $rows->fetch(PDO::FETCH_ASSOC)){
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['especie'] . "</td>";
            echo "<td>" . $row['comportamento'] . "</td>";
            echo "<td>" . $row['locomocao'] . "</td>";
            echo "<td>" . $row['sexo'] . "</td>";
            echo "<td>" . $row['ducha'] . "</td>";
            echo "<td>";
            echo "<a href='?action=update&id=" . $row['id'] . "'>Update</a>";
            echo "<a href='?action=delete&id=" . $row['id'] . "' onclick='return confirm(\"Tem certeza que quer apagar esse registro?\")' class='delete'>Delete</a>";
            echo "</td>";
            echo "</tr>";
        }
    }
    //se não for igual a 0 ele aparece uma tabela pois achou o dado
?>

    
    </table>

</body>
</html>