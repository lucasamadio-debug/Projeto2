<?php
require_once "include/funcoes.php";

$cardapio_completo = buscarTodosProdutos($conn);

function filtrarPorCategoria(array $secoes, $categoriaSelecionada): array {
    if (empty($categoriaSelecionada) || $categoriaSelecionada === 'TODOS') {
        return $secoes;
    }
    
    $resultado = [];
    foreach ($secoes as $secao) {
        if (strtoupper($secao['secao_titulo']) === strtoupper($categoriaSelecionada)) {
            $resultado[] = $secao;
        }
    }
    return $resultado;
}

$categoriaAtual = $_GET['categoria'] ?? 'TODOS';
$cardapioParaExibir = filtrarPorCategoria($cardapio_completo, $categoriaAtual);

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="imagens/logo1.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/cardapio.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cabin:wght@400..700&family=Inter:wght@100..900&family=Oswald:wght@200..700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>Thiago Lanches - Cardápio</title>
</head>

<body>

<div class="container menu-conteudo my-5">

    <!-- MENU DE FILTRO POR CATEGORIAS -->
    <div class="d-flex flex-wrap gap-2 mb-4 align-items-center">
        <a href="?param=cardapio&categoria=TODOS" 
           class="btn <?php echo ($categoriaAtual === 'TODOS') ? 'btn-primary text-white' : 'btn-outline-secondary bg-white'; ?>" 
           style="border-radius: 12px; font-family: 'Oswald', sans-serif; <?php echo ($categoriaAtual === 'TODOS') ? 'background-color: #ff6600; border-color: #ff6600;' : 'color: #333;'; ?>">
           🍔 Todos
        </a>

        <a href="?param=cardapio&categoria=PRENSADOS" 
           class="btn <?php echo ($categoriaAtual === 'PRENSADOS') ? 'btn-primary text-white' : 'btn-outline-secondary bg-white'; ?>" 
           style="border-radius: 12px; font-family: 'Oswald', sans-serif; <?php echo ($categoriaAtual === 'PRENSADOS') ? 'background-color: #ff6600; border-color: #ff6600;' : 'color: #333;'; ?>">
           🥖 Prensados
        </a>

        <a href="?param=cardapio&categoria=HOT DOGS" 
           class="btn <?php echo ($categoriaAtual === 'HOT DOGS') ? 'btn-primary text-white' : 'btn-outline-secondary bg-white'; ?>" 
           style="border-radius: 12px; font-family: 'Oswald', sans-serif; <?php echo ($categoriaAtual === 'HOT DOGS') ? 'background-color: #ff6600; border-color: #ff6600;' : 'color: #333;'; ?>">
           🌭 Hot Dogs
        </a>

        <a href="?param=cardapio&categoria=LANCHES GOURMET" 
           class="btn <?php echo ($categoriaAtual === 'LANCHES GOURMET') ? 'btn-primary text-white' : 'btn-outline-secondary bg-white'; ?>" 
           style="border-radius: 12px; font-family: 'Oswald', sans-serif; <?php echo ($categoriaAtual === 'LANCHES GOURMET') ? 'background-color: #ff6600; border-color: #ff6600;' : 'color: #333;'; ?>">
           ✨ Lanches Gourmet
        </a>
        
    
    </div>

    <!-- DADOS DO CARDÁPIO PUXADOS DO BANCO -->
    <?php foreach ($cardapioParaExibir as $secao): ?>
        <div class="menu-secao mb-5">
            <div class="secao-header d-flex align-items-center mb-4">
                <span class="secao-icon"><?php echo $secao['secao_icon']; ?></span>
                <h2 class="secao-title ms-3"><?php echo $secao['secao_titulo']; ?></h2>
            </div>

            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-4">
                <?php foreach ($secao['lanches'] as $lanche): ?>
                    <div class="col">
                        <div class="card card-menu h-100 shadow-sm border-0 position-relative">
                            <div class="card-body d-flex flex-column justify-content-between p-4">

                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="lanche-num"><?php echo $lanche['num']; ?></span>
                                    <?php if (isset($lanche['popular']) && ($lanche['popular'] === true || $lanche['popular'] == 1)): ?>
                                        <span class="badge badge-popular">POPULAR</span>
                                    <?php endif; ?>
                                </div>

                                <div class="mb-4">
                                    <h4 class="lanche-nome"><?php echo $lanche['nome']; ?></h4>
                                    <p class="lanche-ingredientes card-text"><?php echo $lanche['ingredientes']; ?></p>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mt-auto pt-2 border-top">
                                    <span class="lanche-preco">R$ <?php echo number_format($lanche['preco'], 2, ',', '.'); ?></span>
                                    <a href="https://wa.me/5544998340641?text=Olá, gostaria de pedir <?php echo urlencode($lanche['nome']); ?>"
                                       target="_blank" class="seta-link text-decoration-none">
                                        <i class="fa-solid fa-arrow-right"></i>
                                    </a>
                                </div>

                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>

</div>

</body>
</html>