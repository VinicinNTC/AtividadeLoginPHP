<?php
$servidor = "localhost";
$usuario  = "root";
$senha    = "usbw"; 
$banco    = "produtosz"; 

$conexao = new mysqli($servidor, $usuario, $senha, $banco);

if ($conexao->connect_error) {
    die("Falha na conexão: " . $conexao->connect_error);
}

// Recebe usando a chave 'nome'
$nome         = $_POST['nome']; 
$categoria    = $_POST['categoria'];
$valor_compra = $_POST['valor_compra'];
$valor_venda  = $_POST['valor_venda'];
$qtd_estoque  = (int)$_POST['qtd_estoque'];

$sql = "INSERT INTO produtos (nome, categoria, valor_compra, valor_venda, qtd_estoque) 
        VALUES ('$nome', '$categoria', '$valor_compra', '$valor_venda', '$qtd_estoque')";

if ($conexao->query($sql) === TRUE) {
    header("Location: telainicial.php");
    exit();
} else {
    echo "Erro ao cadastrar: " . $conexao->error;
}

$conexao->close();
?>