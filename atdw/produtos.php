<?php
$servidor="localhost";
$usuario="root";
$senha="usbw";
$banco="produtosz";

$conexao = new mysqli($servidor, $usuario, $senha, $banco);

if($conexao->connect_error){
    die("Falha na conexão! ". $conexao->connect_error);
}

if($_SERVER["REQUEST_METHOD"] == POST){

    $nome_produto = $_POST['nome_produto'];
    $categoria = $_POST['categoria'];
    $valor_compra = $_POST['valor_compra'];
    $valor_venda = $_POST['valor_venda'];
    $qtd_estoque = $_POST['qtd_estoque'];

    $sql = "INSERT INTO Produtos (nome, categoria, valor_compra, valor_venda, qtd_estoque) 
            VALUES('$nome_produto', '$categoria', '$valor_compra','$valor_venda','$qtd_estoque')";
    
    if ($conexao->query($sql) === TRUE) {
        echo "<script>alert('Produto cadastrado com sucesso!'); window.location.href='index.html';</script>";
    } else {
        echo "Erro ao cadastrar: " . $conexao->error;
    }
}
$conexao->close();
?>