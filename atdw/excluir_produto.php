<?php
$conexao = new mysqli("localhost", "root", "usbw", "produtosz");

if ($conexao->connect_error) {
    die("Falha na conexão: " . $conexao->connect_error);
}

if (isset($_GET['id'])) {
    $id = $conexao->real_escape_string($_GET['id']);
    
    // Deleta o registro pelo ID do produto
    $sql = "DELETE FROM produtos WHERE id_produtos = '$id'";
    $conexao->query($sql);
}

$conexao->close();
header("Location: telainicial.php");
exit();
?>