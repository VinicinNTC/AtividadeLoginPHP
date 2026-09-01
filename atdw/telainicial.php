<?php
$servidor = "localhost";
$usuario  = "root";
$senha    = "usbw"; 
$banco    = "produtosz"; 

$conexao = new mysqli($servidor, $usuario, $senha, $banco);

if ($conexao->connect_error) {
    die("Falha na conexão: " . $conexao->connect_error);
}

// Filtro de busca
$busca = isset($_GET['busca']) ? $conexao->real_escape_string($_GET['busca']) : '';

if (!empty($busca)) {
    $sql = "SELECT id_produtos, nome, categoria, valor_compra, valor_venda, qtd_estoque 
            FROM produtos 
            WHERE nome LIKE '%$busca%' OR categoria LIKE '%$busca%'";
} else {
    $sql = "SELECT id_produtos, nome, categoria, valor_compra, valor_venda, qtd_estoque FROM produtos";
}

$resultado = $conexao->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estoque 2 Irmãos</title>
    
    <link rel="stylesheet" href="estilo.css">
</head>
<body>

    <h1>🐱 Estoque 2 Irmãos 🐱</h1>

    <h1>
        <a href="ajuda.html" style="text-decoration: none;
     font-size: 20px; background-color: #6c5ce7; color: white; padding: 4px 10px; border-radius: 50%; font-weight: bold; vertical-align: middle; margin-left: 10px;" title="Como funciona o sistema?">?</a>
     Precisa de ajuda?<a href="ajuda.html" style="text-decoration: none;
     font-size: 20px; background-color: #6c5ce7; color: white; padding: 4px 10px; border-radius: 50%; font-weight: bold; vertical-align: middle; margin-left: 10px;" title="Como funciona o sistema?">?</a>
    </h1>

    <div class="card-container">
        <div class="card-title">Cadastro de Produtos 👍</div>


        <!-- FORMULÁRIO PRINCIPAL -->
        <form method="POST">
            <div class="form-grid">
                <div class="form-group">
                    <label>Nome do Produto:</label>
                    <input type="text" name="nome" placeholder='"goiaba"' required>
                </div>

                <div class="form-group">
                    <label>Categoria:</label>
                    <select name="categoria" required>
                        <option value="" disabled selected>Selecione uma categoria...</option>
                        <option value="Informática">Informática</option>
                        <option value="Alimentos">Alimentos</option>
                        <option value="Eletrônicos">Eletrônicos</option>
                        <option value="Carros">Carros</option>
                        <option value="Videogames">Videogames</option>
                        <option value="Outros">Outros</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Valor Compra:</label>
                    <input type="number" step="0.01" name="valor_compra" value="0.00" required>
                </div>

                <div class="form-group">
                    <label>Valor Venda:</label>
                    <input type="number" step="0.01" name="valor_venda" value="0.00" required>
                </div>
            </div>

            <div class="form-group" style="width: 23%;">
                <label>Qtd. Estoque / Venda:</label>
                <input type="number" name="qtd_estoque" value="1" min="1" required>
            </div>

            <div class="btn-group">
                <!-- FORMACTION ROTA PARA OS DOIS SCRIPTS PHP SEPARADOS -->
                <button type="submit" formaction="produtos.php" class="btn">Cadastrar produto</button>
                <button type="submit" formaction="vender_produto.php" class="btn">Vender produto</button>
            </div>
        </form>

        <hr>

        <!-- CAMPO DE BUSCA -->
        <form method="GET" action="telainicial.php" class="form-busca">
            <div style="display: flex; gap: 10px; margin-bottom: 15px;">
                <input 
                    type="text" 
                    name="busca" 
                    placeholder="Pesquisar por nome ou categoria..." 
                    value="<?= isset($_GET['busca']) ? htmlspecialchars($_GET['busca']) : '' ?>"
                    style="flex: 1; padding: 8px; border-radius: 4px; border: 1px solid #ccc;"
                >
                <button type="submit" class="btn">Buscar</button>
                
                <?php if (!empty($_GET['busca'])): ?>
                    <a href="telainicial.php" style="color: #ff6b6b; align-self: center; text-decoration: none; font-weight: bold;">Limpar</a>
                <?php endif; ?>
            </div>
        </form>

        <div class="card-title">🤯 Estoque</div>

        <!-- TABELA -->
        <table class="tabela-estoque">
            <thead>
                <tr>
                    <th>Descrição</th>
                    <th>Categoria</th>
                    <th>Venda</th>
                    <th>Lucro Unit.</th>
                    <th>Estoque</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($resultado && $resultado->num_rows > 0): ?>
                <!-- CALCULO DE COMOOO PEGAR O LUCRO DAS LINHAS DA TABELA !
                 ENTENDA: {Lucro Unitário} = {Valor de Venda} - \{Valor de Compra}
                 E ASSIM ELE ENTREGA NA PROPRIA TABELA O LUCRO, sem alterar na tabela do banco de dados isso, bonzao demaiz.-->
                    <?php while ($linha = $resultado->fetch_assoc()): ?>
                        <?php $lucro = $linha['valor_venda'] - $linha['valor_compra']; ?>
                        <tr>
                            <td><?= htmlspecialchars($linha['nome']) ?></td>
                            <td><?= htmlspecialchars($linha['categoria']) ?></td>
                            <td>R$ <?= number_format($linha['valor_venda'], 2, ',', '.') ?></td>
                            <td>R$ <?= number_format($lucro, 2, ',', '.') ?></td>
                            <td><?= $linha['qtd_estoque'] ?></td>
                            <td>
                                
                                <a href="excluir_produto.php?id=<?= $linha['id_produtos'] ?>" 
                                   class="btn-excluir" 
                                   onclick="return confirm('Deseja excluir este item?')">X</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align: center; color: #ccc;">Nenhum produto cadastrado.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</body>
</html>